<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad extends CI_Controller {

	/**
	 * 获取广告,并记录展示日志
	 * @params $area_id	int	广告位ID
	 */
	public function get($area_id=0,$min_pay,$ps_type='web')
	{
		header('Access-Control-Allow-Origin: *');

		// 根据广告位信息获取合适广告内容返回
		if($area_id)
		{
			#$this->load->model('redis_model');
			$area_key = 'area_ads_'.$area_id;
			$area_ads_array = json_decode($this->cache->redis->get($area_key));
			$ad_array = array();
			$ad_id = 0;
			$rs_ad_id = 0;

			if($area_ads_array)
			{
				foreach($area_ads_array as $ad)
				{
					$weigth_array = explode('|',$ad);
					
					if($weigth_array)
					{
						$ad_id = $weigth_array[0];
						
						$af=$this->cache->redis->get('adinfo_'.$ad_id);
						
						if($af)
						{
							$af=json_decode($af,true);

							//消费金额匹配
							if($af['min_pay']>0)
							{
								if($min_pay<$af['min_pay'])
								{
									continue;
								}
							}
							
							//通投广告不能有除了性别通投的标签存在
							if(array_key_exists("scene",$af) && count($af['scene'])>0)
							{
								if(array_key_exists("gender",$af['scene']))
								{
									if(in_array("男",$af['scene']['gender']) && in_array("女",$af['scene']['gender']))
									{
										unset($af['scene']['gender']);
										
										if(count($af['scene'])>0)
										{
											continue;	
										}
									}
									else
									{
										continue;		
									}
								}
							}
							
							if((isset($af['province']) && count($af['province'])>0) || (isset($af['city']) && count($af['city'])>0) || (isset($af['districts']) && count($af['districts'])>0))
							{
								continue;	
							}
							
							// 判断广告是否在缓存中，不存在的广告是下线了或者超额度的广告，不应该再展示
							if($this->cache->redis->get('adinfo_'.$ad_id))
							{
								// 权重规则有金额+场景匹配度+随机 改为只用出价作为权重
								// 价高的一定出现在前面，直到消耗完或达到限额 by shiwei 2016/8/3
								// 出现权重为前一小时收益  by  kexuan  2016/09/15
								//$random_weight = rand(0,5);
								//$total_weight = $weigth_array[1]+$weigth_array[2]+$random_weight;
								//$total_weight = $weigth_array[2];
								$total_weight = $af['revenue'];
								$ad_array[] = array('id'=> $ad_id,'weight' => $total_weight);
							}
						}
					}
				}
				if($ad_array)
				{
					usort($ad_array,function($a, $b){return $a['weight']==$b['weight'] ? 0 : ($a['weight']>$b['weight'] ? -1 : 1);});


					  //若存在第二个广告，且第一和第二排名相同，则随机投出一个
					  if(isset($ad_array[1]) && $ad_array[0]['weight']==$ad_array[1]['weight'])
					  {
						  $rand=rand(1,2);
						  
						  if($rand==1)
						  {
							  $rs_ad_id = $ad_array[0]['id'];	
						  }
						  else
						  {
							  $rs_ad_id = $ad_array[1]['id'];		
						  }
					  }
					  else
					  {
						  $rs_ad_id = $ad_array[0]['id'];	
					  }
				}
			}

			$adinfo = $this->cache->redis->get('adinfo_'.$rs_ad_id);
			if($adinfo)
			{
				$adinfo = json_decode($adinfo,1);
			}
			if(isset($adinfo['content']))
			{
				$min_price = 70;
                if($adinfo['price']<$min_price)
                {
                    //价格小于0.7元不显示广告
                    die;
                }

				$ad_content = $adinfo['content'];
				$ad_content = str_replace('{area_id}',$area_id,$ad_content);
				// 记录展现日志，返回广告
				$this->write_epr_log($rs_ad_id,$area_id);
				if($ps_type=='web')
				{
					print "
						function load_ad$area_id(){
							var o=document.getElementById('stormad_$area_id');
							var p=o.parentNode;
							var e=document.createElement('div');";
					if($adinfo['width']>0 && $adinfo['height']>0)
					{
						print "	e.style='width:".$adinfo['width']."px;height:".$adinfo['height']."px';";
					}
					print "
							e.innerHTML='".$ad_content."';
							p.insertBefore(e,o);
						}
						if(typeof addLoadEvent != 'function'){
							function addLoadEvent(func){
								var oldonload = window.onload;   
								if (typeof window.onload != 'function') {   
									window.onload = func;   
								} else {	 
									window.onload = function() {   
										oldonload();
										func();
									}   
								}
							}
						}
						addLoadEvent(load_ad$area_id);
					";
				}
				else
				{
					$api_content = str_replace('{area_id}',$area_id,$adinfo['api_content']);
					print $api_content;
				}
			}
		}
	}
	
	/**
	*   根据渠道号，获取可用广告位
	*	@params $distributionid	int	渠道号
	**/
	public function get_area_by_distributionid($distributionid,$ad_type='')
	{
		
		$area_channel_key='ad_area_channel_'.$distributionid;
		$area_json=$this->cache->redis->get($area_channel_key);
		
		if($area_json)
		{
			$area_arr=json_decode($area_json,true);

			if($ad_type!='')
			{
				foreach($area_arr as $row)
				{			
					if($ad_type==$row['type'])
					{
						$rs[]=$row['ad_area_id'];	
					}
				}
			}
			else
			{
				foreach($area_arr as  $row)
				{			
					$rs[]=$row['ad_area_id'];	
				}	
			}
			
			if($rs)
			{
				$rs_area_arr=json_encode($rs);
				print $rs_area_arr;
			}
			
		}
	}
	
	/**
	 * 根据标签精准获取广告,并记录展示日志
	 * @params $area_id	int	广告位ID
	 * @params $accurate	int	精准投放数组
	 */
	public function accurate_get($area_id=0,$min_pay,$accurate_arr)
	{
		header('Access-Control-Allow-Origin: *');
		
		$accurate=json_decode(urldecode($accurate_arr),true);
		
		//print_r($accurate);
		//echo $accurate->payamount."<br />";
		foreach($accurate as $key=>$val)
		{
			$ac_arr[$key]=$val;
		}
		
		//print_r($ac_arr);echo "<br />";
		
		// 根据广告位信息获取合适广告内容返回
		if($area_id)
		{
			$area_key = 'area_ads_'.$area_id;
			$area_ads_array = json_decode($this->cache->redis->get($area_key));
			$ad_array = array();
			$ad_id = 0;
			$rs_ad_id = 0;

			if($area_ads_array)
			{
				foreach($area_ads_array as $ad)
				{
					$weigth_array = explode('|',$ad);
					if($weigth_array)
					{
						$ad_id = $weigth_array[0];
						
						//判断广告是否在缓存中，不存在的广告是下线了或者超额度的广告，不应该再展示
						$af=$this->cache->redis->get('adinfo_'.$ad_id);
						if($af)
						{
							$af=json_decode($af,true);

							//初始化权重
							$lbs_weight=0;
							$scene_weight=0;
							$price_weigh=0;
							
							//通透权重
							$lbs_tt=0;
							$scene_tt=0;
							
							//消费金额匹配
							if($min_pay>=$af['min_pay'])
							{
								$price_weigh=1;	
							}
							else
							{
								$price_weigh=0;	
								continue;
							}
							
							//当请求中有标签信息，计算广告标签匹配权重。否则通投获取（此为严谨条件，理论上，走这条接口的。请求中一定带标签）
							if(count($ac_arr)>0)
							{
								//若广告标签数组有标签，计算权重
								if(array_key_exists("scene",$af) && count($af['scene'])>0)
								{
									//分field_label来匹配信息
									//若请求中带性别，则检索广告的性别标签是否符合
									if(array_key_exists("gender",$ac_arr))
									{
										//能匹配带性别且性别相符的，或性别通投的广告
										if((array_key_exists("gender",$af['scene']) && in_array($ac_arr['gender'],$af['scene']['gender'])))
										{
											$scene_weight++;
										}
										else
										{
											//若性别不匹配，则跳过此广告
											$scene_weight=0;
											continue;
										}
										
										//若广告为性别通投，则符合，通投权重加为1，广告符合权重+1
										if(in_array("男",$af['scene']['gender']) && in_array("女",$af['scene']['gender']))
										{
											$scene_tt=1;
											$scene_weight=1;
										}								
									}
									//请求不带性别，只能取性别通投的广告
									else
									{
										//性别通投，通投权重+1，广告符合权重+1
										if(in_array("男",$af['scene']['gender']) && in_array("女",$af['scene']['gender']))
										{
											$scene_tt=1;
											$scene_weight=1;
										}
										else
										{
											$scene_weight=0;
											continue;	
										}
									}				
								}
							
								//匹配省份
								if(array_key_exists("province",$ac_arr))
								{
									//若广告无城市LBS，则为LBS通投广告。通投权重加1
									if(!array_key_exists("province",$af))
									{
										$lbs_tt=1;
										$lbs_weight=1;
									}
									else
									{
										if(in_array($ac_arr['province'],$af['province']))	
										{
											//省份匹配，LBS+1权重；
											$lbs_weight++;

											//获取对应省份的ID
											$province_key_arr=array_keys($af['province'],$ac_arr['province']);
											$province_key=$province_key_arr[0];
											
											//print_r($af['city'][$province_key]);echo "<br />";
												
											//匹配市
											if(array_key_exists("city",$ac_arr))
											{	
												//假如广告的城市LBS为通投。则设置通过，不加权重,否则检验城市和区
												if($af['city'][$province_key]!='unlimited')
												{
													if(in_array($ac_arr['city'],$af['city'][$province_key]))	
													{
														//城市匹配，LBS+1权重；检验区
														$lbs_weight++;

														//获取对应城市ID
														$city_key_arr=array_keys($af['city'][$province_key],$ac_arr['city']);
														$city_key=$city_key_arr[0];
														
														//print_r($af['districts'][$city_key]);
																												
														//匹配区
														if(array_key_exists("districts",$ac_arr))
														{
															//假如广告的区LBS为通投。则设置通过，不加权重,否则检验区
															if($af['districts'][$city_key]!='unlimited')
															{
																if(in_array($ac_arr['districts'],$af['districts'][$city_key]))	
																{
																	//区匹配，LBS+1权重；完成全部LBS匹配
																	$lbs_weight++;
																}
																//不匹配，跳过此广告
																else
																{
																	$lbs_weight=0;
																	continue;	
																}
															}
														}
														else
														{
															//如果请求中不带区标签，广告有，则跳过，无法获取此广告。否则，不跳过，但也不权重
															if($af['districts'][$city_key]!='unlimited')	
															{
																$lbs_weight=0;
																continue;
															}	
														}
														
													}
													//不匹配，跳过此广告
													else
													{
														$lbs_weight=0;
														continue;
													}												
												}
											}
											else
											{
												//如果请求中不带城市标签，广告有，则跳过。无法获取此广告，否则，不跳过。但也不权重
												if($af['city'][$province_key]!='unlimited')	
												{
													$lbs_weight=0;
													continue;
												}	
											}
										}
										else
										{
											$lbs_weight=0;
											continue;	
										}
									}
								}
								else
								{
									//如果请求中不带省份标签，广告有，则跳过。无法获取此广告，否则，不跳过。通投权重加1
									if(array_key_exists("province",$af))
									{
										$lbs_weight=0;
										continue;
									}
									else
									{
										$lbs_tt=1;
										$lbs_weight=1;	
									}
								}
							}
							//请求不带标签。不通过，走通投
							else
							{
								continue;	
							}
							
							 //标签或LBS权重为0，不匹配。跳过此广告。
							if($lbs_weight<=0 || $scene_weight<0)
							{
								continue;	
							}
							
							 //标签和LBS通投权重都为1，为通投广告。跳过此广告。
							if($lbs_tt==1 && $scene_tt==1)
							{
								continue;	
							}

							// 权重规则有金额+场景匹配度+随机 改为只用出价作为权重
							// 价高的一定出现在前面，直到消耗完或达到限额 by shiwei 2016/8/3
							// 出现权重为价格X标签匹配度 by kexuan 2016/8/31
							// 出现权重为前一小时收益  by  kexuan  2016/09/15
							//$random_weight = rand(0,5);
							//$total_weight = $weigth_array[1]+$weigth_array[2]+$random_weight;
							//$total_weight = $weigth_array[2]*($lbs_weight+$scene_weight);
							$total_weight = $af['revenue'];
							$ad_array[] = array('id'=> $ad_id,'weight' => $total_weight);
						}
					}
				}
				//print_r($ad_array);echo $scene_weight."<br />";
				if($ad_array)
				{
					usort($ad_array,function($a, $b){return $a['weight']==$b['weight'] ? 0 : ($a['weight']>$b['weight'] ? -1 : 1);});
					
					//若第一和第二排名相同，则随机投出一个
					if($ad_array[0]['weight']==$ad_array[1]['weight'])
					{
						$rand=rand(1,2);
						
						if($rand==1)
						{
							$rs_ad_id = $ad_array[0]['id'];	
						}
						else
						{
							$rs_ad_id = $ad_array[1]['id'];		
						}
					}
					else
					{
						$rs_ad_id = $ad_array[0]['id'];	
					}
				}
			}

			$adinfo = $this->cache->redis->get('adinfo_'.$rs_ad_id);

			if($adinfo)
			{
				$adinfo = json_decode($adinfo,1);
			}
			if(isset($adinfo['content']))
			{
				// 记录展现日志，返回广告
				$this->write_epr_log($rs_ad_id,$area_id);

				$api_content = str_replace('{area_id}',$area_id,$adinfo['api_content']);
				print $api_content;
			}
		}
	}

	/**
	 * 点击广告处理方法,扣款，记录点击日志,并跳转到广告链接
	 * @params $area_id	int	广告位ID
	 * @params $ad_id	int	广告ID
	 */
	public function click($area_id=0,$ad_id=0,$ses_id)
	{
		#$this->load->model('redis_model');
		$adinfo = $this->cache->redis->get('adinfo_'.$ad_id);
		if($adinfo)
		{
			$adinfo = json_decode($adinfo,1);//json字符串解析为数组
			// 判断是否超限额
			//$cost = $adinfo['price']+$adinfo['scene_price'];
			$cost = $adinfo['price']; // edit by shiwei 费用不扣场景费用 7/29/2016
			$user_key = 'user_'.$adinfo['user_id'];
			$campaign_key = 'campaign_'.$adinfo['campaign_id'];
			$adgroup_key = 'adgroup_'.$adinfo['adgroup_id'];
			$campaign_cost_remain = $this->cache->redis->get($campaign_key);
			$adgroup_cost_remain = $this->cache->redis->get($adgroup_key);
			$user_money_remain = $this->cache->redis->get($user_key);
			if($campaign_cost_remain>0 && $adgroup_cost_remain>0 && $user_money_remain>0)
			{
				if(!$ses_id)
				{
					$ses_id = session_id;
				}
				if($ses_id)
				{
					// 点击扣费过滤，4小时内多次点击不重复扣费
					$user_charge_key = 'uc_'.$area_id.'_'.$ad_id.'_'.$ses_id;
					if( !$this->cache->redis->get($user_charge_key))
					{
						$step_price = 10;//实际扣费价格在当时价格上加1毛
						$min_price = 70;//底价
						$area_price_key = 'area_price_'.$area_id;
						$area_price = $this->cache->redis->get($area_price_key);
						if($area_price && $area_price>0 && $cost > $area_price)
						{
							$new_cost = $area_price + $step_price; //实际扣费价格在当时价格上加1毛
							$cost = $new_cost<$cost ? $new_cost : $cost; //实际扣费不能高于设定价格
						}
						// 当无人竞价（area_price不存在或者小于等于0），或者费用小于最低价格时，扣费价格应设为底价
						if(!$area_price || $area_price<=0 || $cost<$min_price)
						{
							$cost = $min_price;//设置底价
						}
						$this->load->model('ad_model');
						$this->ad_model->charge($adinfo['user_id'],$cost); //实际账户扣费
						$this->cache->redis->save($user_key,$user_money_remain-$cost);//缓存中扣费
						$this->cache->redis->save($campaign_key,$campaign_cost_remain-$cost);//更新推广计划当日限额余额
						$this->cache->redis->save($adgroup_key,$adgroup_cost_remain-$cost);//更新推广组当日限额余额
						$this->cache->redis->save($user_charge_key,1,14400); //4小时 14400秒
					}
					else
					{
					    $cost = 0;//4小时之内多次点击不扣费，日志也记录为不扣费
					}
				}
			}
			else
			{
				// 超限额或者账户无余额，把广告从缓存中删除，执行下线
				$this->cache->redis->delete('adinfo_'.$ad_id);
				$cost = 0; //当已经达到日限额或者已经无费用，不再扣费
			}
			
			$log_data = array('ad_id'=>$ad_id,
							  'area_id'=>$area_id,
							  'cost'=>$cost,
							  'campaign_id'=>$adinfo['campaign_id'],
							  'adgroup_id'=>$adinfo['adgroup_id']);
			$this->_write_click_log($log_data);

			redirect($adinfo['link']);
		}
		//redirect($this->config->item('resource_host'));//广告不存在时跳转到广告平台页面
	}

	/**
	 * 写展现日志
	 */
	private function write_epr_log($ad_id=0,$area_id=0)
	{
		if($ad_id && $area_id)
		{
			$this->load->model('ad_logs_model');
			$data = array(
				'ad_id' => $ad_id,
				'area_id' => $area_id,
				'ip' => $this->input->ip_address(),
				'sid' => session_id(),
				'time' => time(),
			);
			$this->ad_logs_model->insert('epr_logs',$data);
		}
	}

	/**
	 * 写点击日志
	 * @params $data array 日志数据
	 */
	private function _write_click_log($data=array())
	{
		$this->load->model('ad_logs_model');
		$data['ip'] = $this->input->ip_address();
		$data['sid'] = session_id();
		$data['time'] = time();
		$this->ad_logs_model->insert('click_logs',$data);
	}

}
