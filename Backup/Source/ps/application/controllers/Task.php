<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!is_cli())
{
    exit('无权访问');
}

/**
 * 定时任务脚本，初始化及维护缓存层数据
 */
class Task extends CI_Controller {

	private $_day_time_out = 86400; //一天的秒数
	private $_minute_time_out = 600; //10分钟过期
	private $_increment_time = 1200; //20分钟，增量更新时检查的这个时间内更新的数据
	private $_load_type = 'increment';

	/**
	 * 初始化数据，把数据初始化到redis中,每天执行一次，凌晨0点
	 * @params $load_type string 数据初始化类型 init:全量数据初始化 increment:增量更新数据
	 */
	public function load($load_type='increment')
	{
		$this->_load_type = $load_type=='increment' ? 'increment' : 'init';
		print date('Y-m-d H:i:s')." $this->_load_type start\n";
		$this->load->model('ad_model');
		$this->load->model('cache_model');
		$this->_load_user();
		$this->_load_campaign();
		$this->_load_adgroup();
		$this->_load_area();
		$this->_load_channel_area();
		$this->_load_adinfo();
		print date('Y-m-d H:i:s')." $this->_load_type done\n";
	}

	/**
	 * 修复统计数据，每小时执行一次
	 * $s_time  string(10)  时间戳 用于恢复数据用
	 */
	public function repair_create_report($s_time="")
	{
		print date('Y-m-d H:i:s')." repair_create_report start\n";
		$report_time = 3600;

		$time_start=strtotime('-2 hours',$s_time);

		$this->load->model('ad_model');
		$this->load->model('ad_logs_model');
		$this->load->model('ad_report_model');
		$report_end = $time_start + $report_time;
		
		

			$report_start = $report_end - $report_time;
			
			//根据时间段取数据
			$epr_logs_data = $this->ad_logs_model->bak_get_logs('epr',$report_start,$report_end);    //汇总曝光表数据
			$click_logs_data = $this->ad_logs_model->bak_get_logs('click',$report_start,$report_end);	//汇总点击表数据
			$logs_data = array_merge($epr_logs_data,$click_logs_data);

/*			echo count($logs_data)."<br />";
			print_r($logs_data);
			die();
			*/
			$ad_report = $area_report = array();
			foreach($logs_data as $log)
			{
				if(!array_key_exists($log->ad_id,$ad_report))
				{
					$adinfo = $this->ad_report_model->get_adinfo($log->ad_id);
					$ad_report[$log->ad_id] = array(
												'campaign_id' => $adinfo['campaign_id'],
												'adgroup_id' => $adinfo['adgroup_id'],
												'ad_id' => $log->ad_id,
												'clicks' => 0,
												'invalid_clicks' => 0,
												'impressions' => 0,
												'cost' => 0,
												'user_id' => $adinfo['user_id']
											);
				}
				if(!array_key_exists($log->area_id,$area_report))
				{
					$area_report[$log->area_id] = array(
												'area_id' => $log->area_id,
												'clicks' => 0,
												'invalid_clicks' => 0,
												'impressions' => 0,
												'cost' => 0,
											);
				}
				//统计数据
				if(isset($log->cost))//点击
				{
					if($log->cost>0)
					{
						$ad_report[$log->ad_id]['clicks']++;
						$area_report[$log->area_id]['clicks']++;
					}
					else
					{
						$ad_report[$log->ad_id]['invalid_clicks']++;
						$area_report[$log->area_id]['invalid_clicks']++;
					}
					$ad_report[$log->ad_id]['cost'] += $log->cost;
					$area_report[$log->area_id]['cost'] += $log->cost;
				}
				else
				{
					$ad_report[$log->ad_id]['impressions']++;
					$area_report[$log->area_id]['impressions']++;
				}
			}

			$user_money_log = array();
			// 写入数据库
			foreach($ad_report as $report)
			{
				if(!array_key_exists($report['user_id'],$user_money_log))
				{
					$user_money_log[$report['user_id']] = array('user_id'=>$report['user_id'],'money'=>0);
				}
				$user_money_log[$report['user_id']]['money'] += $report['cost'];
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				unset($report['user_id']);
				$this->ad_report_model->create_ad_report($report);
			}
			// 每小时记录花费日志
			foreach($user_money_log as $log)
			{
				if($log['money']>0)
				{
					$user_info = $this->ad_model->get_user_by_id($log['user_id']);
					$log['add_time'] = $report_end;
					$log['type'] = 1;
					$log['remain_sum'] = $user_info['money']; //广告点击的花费在点击时已经扣去，此处余额记录统计当时的余额即可
					$log['comment'] = date('Y年m月d日 H时点击花费',$report_start);
					$this->ad_report_model->create_user_money_log($log);
				}
			}

			foreach($area_report as $report)
			{
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				$this->ad_report_model->create_area_report($report);
			}


		print date('Y-m-d H:i:s')." repair_create_report done\n";
	}



	/**
	 * 统计数据，每小时执行一次
	 */
	public function create_report()
	{
		print date('Y-m-d H:i:s')." create_report start\n";
		$report_time = 3600;
		//为了避免数据统计不准确，一次统计过去2个小时的数据，冗余的一个小时可以修复数据
		$time_start = strtotime(date('Y-m-d H:0:0',strtotime('-2 hours')));
		$this->load->model('ad_model');
		$this->load->model('ad_logs_model');
		$this->load->model('ad_report_model');
		$report_end = $time_start + $report_time;
		while($report_end < time())
		{
			$report_start = $report_end - $report_time;
			//根据时间段取数据
			$epr_logs_data = $this->ad_logs_model->get_logs('epr',$report_start,$report_end);
			$click_logs_data = $this->ad_logs_model->get_logs('click',$report_start,$report_end);
			$logs_data = array_merge($epr_logs_data,$click_logs_data);
			$ad_report = $area_report = array();
			foreach($logs_data as $log)
			{
				if(!array_key_exists($log->ad_id,$ad_report))
				{
					$adinfo = $this->ad_report_model->get_adinfo($log->ad_id);
					$ad_report[$log->ad_id] = array(
												'campaign_id' => $adinfo['campaign_id'],
												'adgroup_id' => $adinfo['adgroup_id'],
												'ad_id' => $log->ad_id,
												'clicks' => 0,
												'invalid_clicks' => 0,
												'impressions' => 0,
												'cost' => 0,
												'user_id' => $adinfo['user_id']
											);
				}
				if(!array_key_exists($log->area_id,$area_report))
				{
					$area_report[$log->area_id] = array(
												'area_id' => $log->area_id,
												'clicks' => 0,
												'invalid_clicks' => 0,
												'impressions' => 0,
												'cost' => 0,
											);
				}
				//统计数据
				if(isset($log->cost))//点击
				{
					if($log->cost>0)
					{
						$ad_report[$log->ad_id]['clicks']++;
						$area_report[$log->area_id]['clicks']++;
					}
					else
					{
						$ad_report[$log->ad_id]['invalid_clicks']++;
						$area_report[$log->area_id]['invalid_clicks']++;
					}
					$ad_report[$log->ad_id]['cost'] += $log->cost;
					$area_report[$log->area_id]['cost'] += $log->cost;
				}
				else
				{
					$ad_report[$log->ad_id]['impressions']++;
					$area_report[$log->area_id]['impressions']++;
				}
			}

			$user_money_log = array();
			// 写入数据库
			foreach($ad_report as $report)
			{
				if(!array_key_exists($report['user_id'],$user_money_log))
				{
					$user_money_log[$report['user_id']] = array('user_id'=>$report['user_id'],'money'=>0);
				}
				$user_money_log[$report['user_id']]['money'] += $report['cost'];
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				unset($report['user_id']);
				$this->ad_report_model->create_ad_report($report);
			}
			// 每小时记录花费日志
			foreach($user_money_log as $log)
			{
				if($log['money']>0)
				{
					$user_info = $this->ad_model->get_user_by_id($log['user_id']);
					$log['add_time'] = $report_end;
					$log['type'] = 1;
					$log['remain_sum'] = $user_info['money']; //广告点击的花费在点击时已经扣去，此处余额记录统计当时的余额即可
					$log['comment'] = date('Y年m月d日 H时点击花费',$report_start);
					$this->ad_report_model->create_user_money_log($log);
				}
			}

			foreach($area_report as $report)
			{
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				$this->ad_report_model->create_area_report($report);
			}
			$report_start = $report_end;
			$report_end += $report_time;
		}

		print date('Y-m-d H:i:s')." create_report done\n";
	}
	
	/**
	 * 初始化广告主账户余额
	 */
	private function _load_user()
	{
		$condition = array('status'=>1);
		if($this->_load_type=='increment')
		{
			$condition['update_time'] = time()-$this->_increment_time;
		}
		$user_array = $this->ad_model->get_user_list($condition);
		foreach($user_array as $user)
		{
			$this->cache_model->load_user_money($user['id'], $this->_day_time_out);
		}
	}

	/**
	 * 初始化推广计划数据
	 */
	private function _load_campaign()
	{
		$condition = array('status'=>1);
		if($this->_load_type=='increment')
		{
			$condition['update_time'] = time()-$this->_increment_time;
		}
		$campaign_array = $this->ad_model->get_campaign_list($condition);
		foreach($campaign_array as $campaign)
		{
			$this->cache_model->load_campaign($campaign['id'], $this->_day_time_out);
		}
	}

	/**
	 * 初始化推广组数据
	 */
	private function _load_adgroup()
	{
		$condition = array('status'=>1);
		if($this->_load_type=='increment')
		{
			$condition['update_time'] = time()-$this->_increment_time;
		}
		$adgroup_array = $this->ad_model->get_adgroup_list($condition);
		foreach($adgroup_array as $adgroup)
		{
			$this->cache_model->load_adgroup($adgroup['id'], $this->_day_time_out);
		}
	}

	/**
	 * 初始化广告数据
	 */
	private function _load_adinfo()
	{
		//$condition = array('status'=>2);
		$condition = array();
		if($this->_load_type=='increment')
		{
			$condition['update_time'] = time()-$this->_increment_time;
		}
		$ad_array = $this->ad_model->get_adinfo_list($condition);
		foreach($ad_array as $ad)
		{
			if($ad['status'] == 2)
			{
				$campaign_key = 'campaign_'.$ad['campaign_id'];
				$adgroup_key = 'adgroup_'.$ad['adgroup_id'];
				$campaign_cost_remain = $this->cache->redis->get($campaign_key);
				$adgroup_cost_remain = $this->cache->redis->get($adgroup_key);
				$user = $this->ad_model->get_user_by_id($ad['user_id']);
				$user_money_remain = $user['money'];
				// 只导入当日还有余额的广告,所属广告计划和广告组也没超日限额,并且正在推广期的广告
				$time_now = time();
				if($campaign_cost_remain>0 && $adgroup_cost_remain>0 && $user_money_remain>0 && $ad['start_time']<$time_now && $ad['end_time']>$time_now)
				{
					$this->cache_model->load_adinfo($ad['id'], $this->_day_time_out);
				}
				else
				{
					$this->cache->redis->delete('adinfo_'.$ad['id']);//下线此广告
				}
			}
			else
			{
				$this->cache->redis->delete('adinfo_'.$ad['id']);//下线此广告
			}
		}
	}

	/**
	 * 初始化广告位数据
	 */
	private function _load_area()
	{
		$condition = array('status'=>1);
		//广告位需要每10分钟把启动的全量更新一遍，因为需要更换随机100个广告
	//	if($this->_load_type=='increment')
	//	{
	//		$condition['update_time'] = time()-$this->_increment_time;
	//	}
		$area_array = $this->ad_model->get_area_list($condition);
		foreach($area_array as $area)
		{
			$this->cache_model->load_area_ads($area['id'], $this->_minute_time_out);
			$this->cache_model->load_area_price($area['id'], $area['size_id'], $this->_minute_time_out);
		}
	}

	/**
	 * 初始化渠道广告位数据
	 */
	private function _load_channel_area()
	{
		$condition = array('status'=>1);

		$channel_array = $this->ad_model->get_channel_list($condition);
		
		foreach($channel_array as $channel)
		{
			$this->cache_model->load_channel_area($channel['id'], $channel['distribution_id'], $this->_minute_time_out);
		}
	}

	/**
	 * 写点击花费日志
	 * @params $data array 日志数据
	 */
	private function _write_click_money_log($data=array())
	{
		$this->load->model('ad_logs_model');
		$data['type'] = 1;
		$data['add_time'] = time();
		$this->ad_logs_model->insert('user_money_log',$data);
	}

	
	/** 
	*  每1小时更新一次排名
	**/
	public function calculations_rank()
	{
		print date('Y-m-d H:i:s')." calculations_rank start\n";
		
		$this->load->model('ad_model');
		$condition = array('status'=>2);
		$ad_array = $this->ad_model->get_adinfo_list($condition);
		foreach($ad_array as $ad)
		{
			$campaign_key = 'campaign_'.$ad['campaign_id'];
			$adgroup_key = 'adgroup_'.$ad['adgroup_id'];
			$campaign_cost_remain = $this->cache->redis->get($campaign_key);
			$adgroup_cost_remain = $this->cache->redis->get($adgroup_key);
			$user = $this->ad_model->get_user_by_id($ad['user_id']);
			$user_money_remain = $user['money'];
			// 只导入当日还有余额的广告,所属广告计划和广告组也没超日限额,并且正在推广期的广告
			$time_now = time();
			if($campaign_cost_remain>0 && $adgroup_cost_remain>0 && $user_money_remain>0 && $ad['start_time']<$time_now && $ad['end_time']>$time_now)
			{
				//更新排名
				$this->ad_model->update_ad_weight($ad['id']);
			}
		}	
		
		print date('Y-m-d H:i:s')." calculations_rank done\n";
	}
	
}
