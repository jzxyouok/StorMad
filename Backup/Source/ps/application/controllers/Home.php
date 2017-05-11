<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!is_cli())
{
	exit('无权访问');
}


class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('home/index');
	}

	/**
	 * 生成统计数据
	 */
	public function create_report()
	{
		print "模拟调试用，生产环境不能执行";
		die;
		$report_time = 3600;
		$time_start = strtotime('2016-6-28 00:00:00');
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
												'impressions' => 0,
												'cost' => 0,
											);
				}
				if(!array_key_exists($log->area_id,$area_report))
				{
					$area_report[$log->area_id] = array(
												'area_id' => $log->area_id,
												'clicks' => 0,
												'impressions' => 0,
												'cost' => 0,
											);
				}
				//统计数据
				if(isset($log->cost))//点击
				{
					$ad_report[$log->ad_id]['clicks']++;
					$ad_report[$log->ad_id]['cost'] += $log->cost;
					$area_report[$log->area_id]['clicks']++;
					$area_report[$log->area_id]['cost'] += $log->cost;
				}
				else
				{
					$ad_report[$log->ad_id]['impressions']++;
					$area_report[$log->area_id]['impressions']++;
				}
			}	

			// 写入数据库
			foreach($ad_report as $report)
			{
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				$this->ad_report_model->create_ad_report($report);
			}
			foreach($area_report as $report)
			{
				$report['start_time'] = $report_start;
				$report['end_time'] = $report_end;
				$this->ad_report_model->create_area_report($report);
			}
			$report_end += $report_time;
		}
	}

	/**
	 * 插入测试数据-展示日志
	 */
	public function add_logs($type='epr')
	{
		print "插入模拟测试数据用，正式环境慎用";
		die;
		$time_pass = 86400;//一天内
		$ad_ids = array(1,2,4,5);
		$area_ids = array(2,3,4,5,8,9);
		$this->load->model('ad_logs_model');
		$this->load->model('ad_model');
		for($i=0;$i<10;$i++)
		{
			if($type=='epr')
			{
				$data = array(
					'ad_id' => $ad_ids[rand(0,3)],
					'area_id' => $area_ids[rand(0,5)],
					'ip' => '127.0.0.1',
					'sid' => 'SID_'.rand(1,100000000000),
					'time' => time() - rand(100,$time_pass),
				);
				$this->ad_logs_model->insert('epr_logs',$data);
			}
			elseif($type=='click')
			{
				$ad_id = $ad_ids[rand(0,3)];
				$adinfo = $this->ad_model->get_adinfo_by_id($ad_id);
				$data = array(
					'ad_id' => $ad_id,
					'campaign_id' => $adinfo['campaign_id'],
					'adgroup_id' => $adinfo['adgroup_id'],
					'area_id' => $area_ids[rand(0,5)],
					'cost' => rand(1,100),
					'ip' => '127.0.0.1',
					'sid' => 'SID_'.rand(1,100000000000),
					'time' => time() - rand(100,$time_pass),
				);
				$this->ad_logs_model->insert('click_logs',$data);
			}
		}
	}
	
	public function test()
	{
		die;
		$this->load->model('ad_model');
		$result = $this->ad_model->get_scene_by_ad_id(44);
		print_r($result);
		die;
		print strtotime(date('Y-m-d H:0:0',strtotime('-1 hours')));
		print "|<br>";
		#print date('Y-m-d H:i:s',strtotime(date('Y-m-d H',strtotime('-1 hours')).':0:0')));
		print "|<br>";
		print strtotime(date('Y-m-d H:i:s'));
		die;
		$this->load->model('ad_model');
		$this->ad_model->charge(1,1234);
		exit;
		$this->load->model('redis_model');
	#	print $this->ad_model->get_random_ad_by_area_id(9);
	#	print_r($this->ad_model->get_scene_by_ad_id(2));
		#$this->ad_model->redis_load_area_ads(2);
		$result = $this->redis_model->redis_load_adinfo(16);
		#print_r($result);
	}
}
