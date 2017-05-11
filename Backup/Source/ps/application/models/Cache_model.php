<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ad_model');
	}


	/**
	 * 把广告主账户余额加载进redis
	 * @params $user_id int 广告主用户ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_user_money($user_id=0,$time_out)
	{
		$key = 'user_'.$user_id;
		$campaign = $this->ad_model->get_user_by_id($user_id);
		$money = $campaign['money'];
		$this->cache->redis->save($key,$money,$time_out);
	}

	/**
	 * 把推广计划数据加载进redis
	 * @params $campaign_id int 推广计划ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_campaign($campaign_id=0,$time_out)
	{
		$key = 'campaign_'.$campaign_id;
		$campaign = $this->ad_model->get_campaign_by_id($campaign_id);
		$day_sum = $campaign['day_sum'];
		if($day_sum>0)
		{
			$this->load->model('ad_logs_model');
			$used_sum = $this->ad_logs_model->get_used_sum(array('campaign_id'=>$campaign_id));
			$day_sum = $day_sum - $used_sum;
		}
		else
		{
			// 无日限额，设一个较大数值来实现
			$day_sum = 9000000000;
		}
		$this->cache->redis->save($key,$day_sum,$time_out);
	}

	/**
	 * 把推广组数据加载进redis
	 * @params $adgroup_id int 推广组ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_adgroup($adgroup_id=0,$time_out)
	{
		$key = 'adgroup_'.$adgroup_id;
		$adgroup = $this->ad_model->get_adgroup_by_id($adgroup_id);
		$day_sum = $adgroup['day_sum'];
		if($day_sum>0)
		{
			$this->load->model('ad_logs_model');
			$used_sum = $this->ad_logs_model->get_used_sum(array('adgroup_id'=>$adgroup_id));
			$day_sum = $day_sum - $used_sum;
		}
		else
		{
			// 无日限额，设一个较大数值来实现
			$day_sum = 9000000000;
		}
		$this->cache->redis->save($key,$day_sum,$time_out);
	}
	
	/**
	 * 把渠道广告位信息加载进redis
	 * @params $channel_id int 渠道ID
	 * @params $distribution_id int 渠道号
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_channel_area($channel_id, $distribution_id, $time_out)
	{
		$condition = array('status'=>1);
		$area_arr=array();
		$key = 'ad_area_channel_'.$distribution_id;
		$channel_area_info = $this->ad_model->get_area_by_channel_id($channel_id,$condition);
		if($channel_area_info)
		{	
			$area_str=json_encode($channel_area_info);
			
			$this->cache->redis->save($key,$area_str, $time_out);
		}
		else
		{
			$this->cache->redis->delete($key);	
		}
	}
	
	/**
	 * 把广告信息加载进redis
	 * @params $ad_id int 广告ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_adinfo($ad_id=0,$time_out)
	{
		$key = 'adinfo_'.$ad_id;
		$adinfo = $this->ad_model->get_adinfo_by_id($ad_id);
		if($adinfo['size_id']>0)
		{
			$size_info = $this->ad_model->get_size_by_id($adinfo['size_id']);
			$adinfo['width'] = $size_info['width'];
			$adinfo['height'] = $size_info['height'];
		}
		
		$adinfo['revenue']=$adinfo['weight'];
		
		//获取广告位全部标签
		$ad_scene_array = $this->ad_model->get_scene_by_ad_id($ad_id);		

		if($ad_scene_array)
		{
			foreach($ad_scene_array as $adsc)
			{
				$adinfo['scene'][$adsc['field_label']][]=$adsc['scene_name'];
			}
		}
		
		//获取广告LBS
		$ad_region_array = $this->ad_model->get_region_by_ad_id($ad_id);

		//组装广告LBS信息
		if($ad_region_array)
		{
			foreach($ad_region_array as $adr)
			{
				//省
				if($adr['region_type']==1)
				{
					$adinfo['province'][$adr['region_id']]=$adr['region_name'];
					
					//若不限城市，则把unlimited写入城市数组
					if($adr['unlimited']==1)
					{
						$adinfo['city'][$adr['region_id']]='unlimited';
					}
				}
				//市
				if($adr['region_type']==2)
				{
					$adinfo['city'][$adr['fid']][$adr['region_id']]=$adr['region_name'];
					
					//若不限区，则把unlimited写入区数组
					if($adr['unlimited']==1)
					{
						$adinfo['districts'][$adr['region_id']]='unlimited';
					}	
				}
				
				//区
				if($adr['region_type']==3)
				{
					$adinfo['districts'][$adr['fid']][$adr['region_id']]=$adr['region_name'];	
				}
			}
			
			//echo "ad--".$ad_id."=>province=>";print_r($adinfo['province']);echo "city=>";print_r($adinfo['city']);echo "districts=>";print_r($adinfo['districts']);echo "<br /><br />";
		}
		
		$link = base_url().'ad/click/{area_id}/'.$adinfo['id'];
		$api_content = array(
								'title' => $adinfo['title'],
								'type' => $adinfo['type'],
								'comment' => $adinfo['comment'],
								'content' => $adinfo['type']==1 ? $this->config->item('resource_host').$adinfo['content'] : $adinfo['content'],
								'link' => $link,
							);
		$adinfo['api_content'] = json_encode($api_content);
		if($adinfo['type']==1)
		{
			$adinfo['content'] = '<a href="'.$link.'" target="_blank"><img style="width:auto;height:auto;max-width:100%;max-height:100%;" src="'.$this->config->item('resource_host').$adinfo['content'].'"/></a>';
		}
		else
		{
			$adinfo['content'] = '<a href="'.$link.'" target="_blank">'.$adinfo['content'].'</a>';
		}
		$this->cache->redis->save($key,json_encode($adinfo),$time_out);
	}

	/**
	 * 把广告位合适的广告id加载进redis，加载100个，1分钟更新一次
	 * @params $area_id int 广告位ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_area_ads($area_id=0,$time_out)
	{
		$key = 'area_ads_'.$area_id;
		$area_info = $this->ad_model->get_area_by_id($area_id);
		
		//查找此渠道的广告位与广告关系表，是否有广告匹配
		$ad_area_ads_info = $this->ad_model->get_area_ads_info($area_id);
		
		if($ad_area_ads_info)
		{
			foreach($ad_area_ads_info as $row)
			{
				$ads_id[]=$row['ad_id'];
			}
			
			if($area_info)
			{
				$result = array();
				$sc_id = array();
				$sc_arr = array();
				$ar_province = array();
				$ar_city = array();
				$ar_districts = array();
				
				// 获取广告位的全部标签信息
				$area_scene_array = $this->ad_model->get_scene_by_area_id($area_id);
				
				if($area_scene_array)
				{
					foreach($area_scene_array as $sc)
					{
						$sc_arr[$sc['field_label']][]=$sc['scene_id'];
					}	
				}
				
				//获取广告位LBS
				$area_region_array = $this->ad_model->get_region_by_area_id($area_id);
				
				//组装广告位LBS信息
				if($area_region_array)
				{
					foreach($area_region_array as $ar)
					{
						//省
						if($ar['region_type']==1)
						{
							$ar_province[]=$ar['region_id'];
							
							//若不限城市，则把unlimited写入城市数组
							if($ar['unlimited']==1)
							{
								$ar_city[$ar['region_id']]='unlimited';
							}
						}
						//市
						if($ar['region_type']==2)
						{
							$ar_city[$ar['fid']][]=$ar['region_id'];
							
							//若不限区，则把unlimited写入区数组
							if($ar['unlimited']==1)
							{
								$ar_districts[$ar['region_id']]='unlimited';
							}	
						}
						
						//区
						if($ar['region_type']==3)
						{
							$ar_districts[$ar['fid']][]=$ar['region_id'];	
						}
					}
				}
				
				// 随机获取100个广告
				$condition = array('status'=>2,'type'=>$area_info['type'],'size_id'=>$area_info['size_id'],'orderby'=>'rand()','num'=>100,'ads_id'=>$ads_id);
				$ad_array = $this->ad_model->get_adinfo_list($condition);

				//计算100个广告的匹配权重 权重：场景匹配度高[1-100]，点击金额高[1-100]，{随机权重，随机1到5}
				foreach($ad_array as $ad)
				{
					$ad_scene_array = $this->ad_model->get_scene_by_ad_id($ad['id']);
					$as_arr=array();
					$adr_province = array();
					$adr_city = array();
					$adr_districts = array();
					
					//LBS数组交集初始化
					$province_arr = array();
					$city_arr = array();
					$districts_arr = array();
					
					//初始化权重
					$province_weight=0;
					$city_weight=0;
					$districts_weight=0;
					
					if($ad_scene_array)
					{
						foreach($ad_scene_array as $adsc)
						{
							$as_arr[$adsc['field_label']][]=$adsc['scene_id'];
						}
					}

					//假如渠道是非全量，则要进行广告筛选
					if($area_info['is_all']!=1)
					{
						//获取广告LBS
						$ad_region_array = $this->ad_model->get_region_by_ad_id($ad['id']);

						//组装广告LBS信息
						if($ad_region_array)
						{
							foreach($ad_region_array as $adr)
							{
								//省
								if($adr['region_type']==1)
								{
									$adr_province[]=$adr['region_id'];
									
									//若不限城市，则把unlimited写入城市数组
									if($adr['unlimited']==1)
									{
										$adr_city[$adr['region_id']]='unlimited';
									}
								}
								//市
								if($adr['region_type']==2)
								{
									$adr_city[$adr['fid']][]=$adr['region_id'];
									
									//若不限区，则把unlimited写入区数组
									if($adr['unlimited']==1)
									{
										$adr_districts[$adr['region_id']]='unlimited';
									}	
								}
								
								//区
								if($adr['region_type']==3)
								{
									$adr_districts[$adr['fid']][]=$adr['region_id'];	
								}
							}
						}
						
						//若广告主有标签需求，进行标签检索，否则为标签通投广告
						if(count($as_arr)>0)
						{
							//广告位没标签。则跳过此广加载
							if(count($sc_arr)<=0)
							{
								continue;	
							}
							else
							{
								//分field_label标签来匹配信息
								//匹配性别，,若无性别标签，则为性别通投，性别匹配权重设为1
								if(array_key_exists("gender",$as_arr))	
								{
									//若广告主对男女有需求，但广告位为男女通投，无男女标签，则设置权重为1
									if(!array_key_exists("gender",$sc_arr))	
									{
										$gender_weight=1;	
									}
									else
									{
										$gender_weight = count(array_intersect($as_arr['gender'],$sc_arr['gender']));	
									}
								}
								else
								{
									$gender_weight=1;	
								}
							}
						}
						
						//若广告需求LBS投放,进行LBS检索，否则为ＬＢＳ通投广告
						if(count($ad_region_array)>0)
						{
							//广告位无LBS信息，且为非全量，则直接跳过此广告加载
							if(count($area_region_array)<=0)
							{
								continue;
							}
							else
							{
								//匹配省份,若无省份标签，则为省份通投，省市区匹配权重设为1
								if(count($adr_province)>0)
								{
									if(count($ar_province)>0)	
									{
										//查找广告省份与广告位省份交集。若没有跳出此广告
										$province_arr = array_intersect($adr_province,$ar_province);
										
										//有交集，检索城市
										if(count($province_arr)>0)
										{
											$province_weight=1;
											
											foreach($province_arr as $p_val)
											{
												//假如城市为通投，则设置城市权重为1
												if($adr_city[$p_val]=='unlimited' || $ar_city[$p_val]=='unlimited')
												{
													//城市匹配，此广告已符合。跳出检验
													$city_weight=1;
													$districts_weight=1;
													break;
												}
												else
												{
													//查找广告城市与广告位城市交集。
													$city_arr = array_intersect($adr_city[$p_val],$ar_city[$p_val]);
													
													//有交集，检索区
													if(count($city_arr)>0)
													{
														$city_weight=1;
														
														foreach($city_arr as $c_val)
														{
															//假如区为通投，则设置区权重为1
															if($adr_districts[$c_val]=='unlimited' || $ar_districts[$c_val]=='unlimited')
															{
																//区匹配，此广告已符合，跳出检验
																$districts_weight=1;
																break;
															}
															else
															{
																//查找广告区与广告位区交集。
																$districts_weight = count(array_intersect($adr_districts[$c_val],$ar_districts[$c_val]));
															}												
														}
													}
												}		
											}
										}
										else
										{
											continue;	
										}
									}
									else
									{
										continue;
									}
								}
								else
								{
									$province_weight=1;	
									$city_weight=1;
									$districts_weight=1;
								}
								
								//echo "ad->".$ad['id']."=>".$province_weight."|".$city_weight."|".$districts_weight."|".$gender_weight."<br /><br />";
								if($province_weight<=0 || $city_weight<=0 || $districts_weight<=0 || $gender_weight<=0)
								{
									continue;		
								}	
							}
						}
					}

					$scene_weight = count(array_intersect($area_scene_array,$ad_scene_array)); //场景匹配度
					
					$cost_weight = $ad['price']/10; //点击金额权重
					$result[] = $ad['id'].'|'.$scene_weight.'|'.$cost_weight;
				}
				
				//echo "area_id->".$area_id."=>";print_r($result);echo "<br /><br />";
				$this->cache->redis->save($key,json_encode($result),$time_out);
			}
		}
		else
		{
			$this->cache->redis->delete($key);	
		}
	}


	/**
	 * 把广告位合适的广告第二高价加载进redis，实际扣款以第二高价+0.1元，10分钟更新一次
	 * @params $area_id int 广告位ID
	 * @params $size_id int 规格ID
	 * @params $time_out int 过期时间 单位：秒
	 */
	public function load_area_price($area_id=0,$size_id=0,$time_out)
	{
		$key = 'area_price_'.$area_id;
		
		//查找此渠道的广告位与广告关系表，是否有广告匹配
		$ad_area_ads_info = $this->ad_model->get_area_ads_info($area_id);
		
		if($ad_area_ads_info)
		{
			foreach($ad_area_ads_info as $row)
			{
				$ads_id[]=$row['ad_id'];
			}
			
			$condition = array('status'=>2,'size_id'=>$size_id,'orderby'=>'price','direction'=>'DESC','num'=>2,'ads_id'=>$ads_id);
			$adinfo_list = $this->ad_model->get_adinfo_list($condition);
			$price = 0;//无其他人竞价默认返回价格0

			if(isset($adinfo_list[1]) && isset($adinfo_list[1]['price']))
			{
				if($adinfo_list[1]['price']>$price)
				{
					$price = $adinfo_list[1]['price'];
				}
			}
			$this->cache->redis->save($key,$price,$time_out);
		}
		else
		{
			$this->cache->redis->delete($key);
		}
	}
}
