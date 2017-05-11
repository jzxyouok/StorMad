<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adinfo_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取规格
    public function get_size()
    {
        $this->db->select('id, size_name, width, height');
        $query = $this->db->get('ad_size')->result_array();
        
        return $query;
    }
    //按规格类型查找规格
    public function find_type_size($type)
    {
        $this->db->where('type', $type);
        $query = $this->db->get('ad_size');

        return $query->result_array();
    }
    //按规格ID查找规格宽高
    public function find_size($size_id)
    {
        $this->db->select('id, size_name, width, height');
        $this->db->where('id', $size_id);
        $query = $this->db->get('ad_size')->row_array();
    
        return $query;
    }
    //获取推广计划
    public function get_campaign()
    {
        $this->db->select('id, campaign_name');
        $this->db->where('user_id', $this->session->userdata('id'));
        $query = $this->db->get('campaign')->result_array();
    
        return $query;
    }
    public function get_campaign_id($campaign_id, $adgroup_id)
    {
        if($campaign_id)
        {
            $this->db->select('id, campaign_name');
            $this->db->where('id', $campaign_id);
            $query = $this->db->get('campaign')->row_array();
        }
        
        if($adgroup_id)
        {
            $this->db->select('campaign.id,campaign.campaign_name');
            $this->db->from('campaign');
            $this->db->join('adgroup', 'adgroup.campaign_id = campaign.id', 'left');
            $this->db->where('adgroup.id', $adgroup_id);
            $query = $this->db->get()->row_array();
        }
    
        return $query;
    }
    //获取推广组
    public function get_adgroup()
    {
        $this->db->select('id, adgroup_name');
        $this->db->where('user_id', $this->session->userdata('id'));
        $query = $this->db->get('adgroup')->result_array();
    
        return $query;
    }
    public function get_adgroup_id($adgroup_id)
    {
        $this->db->select('id, adgroup_name');
        $this->db->where('id', $adgroup_id);
        $query = $this->db->get('adgroup')->row_array();
    
        return $query;
    }
    //获取用户群
    public function get_customer()
    {
        $this->db->select('id, customer_name');
        $this->db->where('user_id', $this->session->userdata('id'));
        $query = $this->db->get('customer')->result_array();
    
        return $query;
    }
    
    //获取广告资源数据总数
    public function get_adinfo_count($conditions, $user_id)
    {
        if(isset($conditions['adgroup_id']) && $conditions['adgroup_id'])
        {
            $this->db->where('adgroup_id', $conditions['adgroup_id']);
        }
        if(isset($conditions['campaign_id']) && $conditions['campaign_id'])
        {
            $this->db->where('campaign_id', $conditions['campaign_id']);
        }
        if(isset($conditions['title']) && $conditions['title'])
        {
            $this->db->like('title', $conditions['title']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get('adinfo')->num_rows();  
        return $query;
    }
    
    public function get_adinfo($conditions, $user_id)
    {
        $this->db->select('adinfo.id,adinfo.title,adinfo.min_pay,adinfo.weight,adinfo.size_id,adinfo.type,adinfo.comment,adinfo.price,adinfo.adgroup_id,adinfo.campaign_id,adinfo.status,ad_size.size_name,ad_size.width,ad_size.height,campaign.campaign_name,adgroup.adgroup_name,customer.customer_name');
        $this->db->from('adinfo');
        $this->db->join('campaign', 'campaign.id = adinfo.campaign_id', 'left');
        $this->db->join('adgroup', 'adgroup.id = adinfo.adgroup_id', 'left');
        $this->db->join('ad_customers', 'ad_customers.ad_id = adinfo.id', 'left');
        $this->db->join('customer', 'customer.id = ad_customers.customer_id', 'left');
        $this->db->join('ad_size', 'ad_size.id = adinfo.size_id', 'left');
        $this->db->order_by('adinfo.id', 'DESC');
        
        if(isset($conditions['adgroup_id']) && $conditions['adgroup_id'])
        {
            $this->db->where('adinfo.adgroup_id', $conditions['adgroup_id']);
        }
        if(isset($conditions['campaign_id']) && $conditions['campaign_id'])
        {
            $this->db->where('adinfo.campaign_id', $conditions['campaign_id']);
        }
        if(isset($conditions['title']) && $conditions['title'])
        {
            $this->db->like('adinfo.title', $conditions['title']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('adinfo.user_id', $user_id);
        
        $query = $this->db->get()->result_array();
        return $query;
    }
    
    //添加、编辑广告
    public function add_adinfo($adinfo)
    {
        $data = array(
            'title' => $adinfo['title'],
            'size_id' => $adinfo['size_id'],
            'type' => $adinfo['type'],
            'content' => $adinfo['content'],
			'min_pay' => $adinfo['min_pay'],
            'comment' => $adinfo['comment'],
            'link' => trim($adinfo['link'], " "),
            'price' => $adinfo['price']*100,
            'adgroup_id' => $adinfo['select_adgroup'],
            'campaign_id' => $adinfo['select_campaign'],
            'status' => $adinfo['status'],
            'start_time' => strtotime($adinfo['start_time']),
            'end_time' => strtotime($adinfo['end_time']),
            'update_time' => time(),
            'user_id' => $this->session->userdata('id'),
        );
		
	   if(isset($adinfo['area']) && count($adinfo['area'])>0)
	   {
			$data['val_region']=implode("|",$adinfo['area']); 
			$data['show_region']=str_replace("unlimited","不限",$data['val_region']);  
	   }
	   
        if($adinfo['id'])
        {
            $where = array('id' => $adinfo['id']);
            $res = $this->update('adinfo', $data, $where);
            $ad_id = $adinfo['id'];
            
			//先删除此广告下所有定位信息；
			$this->db->where('ad_id',$adinfo['id']);
			$this->db->delete('ad_region');
		
            $this->db->where('ad_id', $ad_id);
            $res = $this->db->delete('ad_customers');
        }else
        {
            $data['add_time'] = time();
            $res = $this->insert('adinfo', $data);
            $ad_id = $this->db->insert_id();
        }
		
		if($ad_id && $adinfo['size_id'])
		{
			//获取广告位列表
			$area_list=$this->get_area_list($adinfo['size_id']);
			
			//初始化排名权重计算
			if($area_list)
			{
				$area_id_arr=array();
			
				foreach($area_list as $aow)
				{
					$area_id_arr[]=$aow['id'];
				}
				
				if($adinfo['select_customer'])
				{
					$ad_customer_id = $adinfo['select_customer'];
				}
				else
				{
					$ad_customer_id = "";	
				}

				if(isset($adinfo['area']) && count($adinfo['area'])>0)
				{
					$ad_lbs = $adinfo['area'];
				}
				else
				{
					$ad_lbs = "";
				}

				$weight=$this->calculate_ad($area_id_arr,$ad_customer_id,$ad_lbs,$adinfo['price']);	

				$weight_where = array('id' => $ad_id);
           		$this->update('adinfo', array('weight'=>$weight), $weight_where);	
			}
		}
		
	   if($adinfo['area'])
	   {
		   if($adinfo['id'])
		   {
				$ad_region_id=$adinfo['id'];
		   }
		   else
		   {
				$ad_region_id= $ad_id;  
		   }
		   
		   //储存地区数组
		    $province_arr=array();
			$city_arr=array();
			$districts_arr=array();
			
			foreach($adinfo['area'] as $k => $area)
			{
				$area_arr=explode("-",$area);				
				
				//初始化数据
				$p_data=array();
				$c_data=array();	
				$d_data=array();
				
				$p_data['ad_id']=$ad_region_id;
				
				//获取省份ID
				$p=$this->get_region_id($area_arr[0],1);
				
				$p_data['region_id']=$p['region_id'];
				$p_data['fid']=0;
				
				//检验城市。
				if($area_arr[1]=='unlimited')
				{
					$p_data['unlimited']=1;	
				}
				else
				{
						
					$c_data['ad_id']=$ad_region_id;
			
					//获取城市ID
					$c=$this->get_region_id($area_arr[1],2,$p['region_id']);
					
					$c_data['region_id']=$c['region_id'];
					$c_data['fid']=$p['region_id'];
					
					//检验区县。
					if($area_arr[2]=='unlimited')
					{
						$c_data['unlimited']=1;	
					}
					else
					{
						$d_data['ad_id']=$ad_region_id;
						$d_data['fid']=$c['region_id'];
						
						$dis_arr=explode(",",$area_arr[2]);
						
						foreach($dis_arr as $dis)
						{
							//获取区县ID
							$d=$this->get_region_id($dis,3,$c['region_id']);
							$d_data['region_id']=$d['region_id'];
							
							//检验是否有重复加入，如果没有。则把该省份加入数据表
							if((isset($districts_arr[$c['region_id']]) && !in_array($dis,$districts_arr[$c['region_id']])) || !isset($districts_arr[$c['region_id']]))
							{
								//写入区县，并加入数组，避免重复加入
								$this->insert('ad_region', $d_data);
								$districts_arr[$c['region_id']][]=$dis;
							}
						}	
					}
				}
				
				//检验是否有重复加入，如果没有。则把该省份加入数据表
				if(!in_array($area_arr[0],$province_arr))
				{
					//写入省份，并加入数组，避免重复加入
					$this->insert('ad_region', $p_data);
					$province_arr[]=$area_arr[0];
				}
				
				//检验是否有重复加入，如果没有。则把该城市加入数据表
				if(!in_array($area_arr[1],$city_arr) && $area_arr[1]!='unlimited')
				{
					//写入城市，并加入数组，避免重复加入
					$this->insert('ad_region', $c_data);
					$city_arr[]=$area_arr[1];
				}
			}   
	   }
       
        if($adinfo['select_customer'])
        {
            $ad_customer = array(
                'customer_id' => $adinfo['select_customer'],
                'ad_id' => $ad_id,
            );
            $this->insert('ad_customers', $ad_customer);
            
            //获取指定用户群下的场景价格
            $this->db->select('scene_id');
            $this->db->where('customer_id', $adinfo['select_customer']);
            $scene_id = $this->db->get('customer_scene')->result_array();
            
            foreach ($scene_id as $k=>$val)
            {
                $this->db->select('fid');
                $this->db->or_where('id', $val['scene_id']);
            }
            $scene_fid = $this->db->get('ad_scene')->result_array();  //搜索标签所属分类
            
            foreach ($scene_fid as $k=>$val)
            {
                $this->db->or_where('id', $val['fid']);
            }
            $scene_class_num = $this->db->get('ad_scene')->num_rows();  //获取指定标签所属分类的数目
            $scene_price = $scene_class_num*10;  //获取指定标签的场景价格
            //更新广告的场景价格
            $ad_data = array(
                'scene_price' => $scene_price,
                'update_time' => time(),
            );
            $adinfo_where = array('id' => $ad_id);
            $adinfo_scene_price = $this->update('adinfo', $ad_data, $adinfo_where);
        }
        
		
		
		
        return $res;
    }
    
    //获取获取要编辑的广告数据
    public function edit_adinfo($id)
	{
	    $this->db->select('adinfo.id,adinfo.title,adinfo.min_pay,adinfo.val_region,adinfo.show_region,adinfo.size_id,adinfo.type,adinfo.content,adinfo.comment,adinfo.link,adinfo.start_time,adinfo.end_time,adinfo.price,adinfo.campaign_id,adinfo.adgroup_id,adinfo.status,campaign.campaign_name,adgroup.adgroup_name,customer.customer_name,ad_customers.customer_id');
	    $this->db->from('adinfo');
	    $this->db->join('campaign', 'campaign.id = adinfo.campaign_id', 'left');
	    $this->db->join('adgroup', 'adgroup.id = adinfo.adgroup_id', 'left');
	    $this->db->join('ad_customers', 'ad_customers.ad_id = adinfo.id', 'left');
	    $this->db->join('customer', 'customer.id = ad_customers.customer_id', 'left');
	    $this->db->where('adinfo.id', $id);
	    
	    $query = $this->db->get()->row_array();
	    return $query;
	}
	
	//查找广告
	public function find_adinfo($id)
	{
	    $this->db->select('title');
	    $this->db->where('id', $id);
	    $query = $this->db->get('adinfo');
	
	    return $query->row_array();
	}
	
    //删除广告
	public function del_adinfo($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('adinfo');
	    
		//删除广告与广告位关系
		$this->db->where('ad_id', $id);
	    $this->db->delete('area_ad');
		
	    $this->db->where('ad_id', $id);
	    $this->db->delete('ad_customers');
	    
	    return $res;
	}
	
	//是否启用广告
	public function use_adinfo($status, $id)
	{
	    $data = array(
	        'status' => $status,
	        'update_time' => time(),
	    );
	    
	    $where = array('id' => $id);
	    $update_status = $this->update('adinfo', $data, $where);
	    
	    $this->db->select('status');
	    $query = $this->db->get_where('adinfo', array('id' => $id))->row_array();
	    
	    return $query;
	}
	
	//获取广告的总数
	public function get_adinfo_num()
	{
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('adinfo')->num_rows();
	
	    return $res;
	}
	
	//修改广告标题
	public function edit_title($id, $title)
	{
	    $data = array(
	        'title' => $title,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('adinfo', $data, $where);
	
	    $this->db->select('title');
	    $res = $this->db->get_where('adinfo', array('id' => $id));
	    return $res->row_array();
	}
	
	//修改广告价格
	public function edit_price($id, $value)
	{		
		//更新排名
		$w_row=$this->get_ad_weight($id);
		$old_weight=$w_row['weight']/$w_row['price'];
		$weight=round($old_weight*$value*100,5);
		
	    $data = array(
	        'price' => $value*100,
	        'update_time' => time(),
			'weight' => $weight
	    );

		
	    $where = array('id' => $id);
	    $this->update('adinfo', $data, $where);
	
	    $this->db->select('price');
	    $res = $this->db->get_where('adinfo', array('id' => $id));
	    return $res->row_array();
	}
	
    /**
     * 根据广告id，获取此广告详细信息
     * @params $id int 广告ID
     */
    public function get_adinfo_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('adinfo');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * 根据广告规格id，获取此广告规则详细信息
     * @params $id int 广告规则ID
     */
    public function get_size_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('ad_size');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

	/**
	**  根据地区名字获取对应ID
	**  $name   string   地区名字数组
	**  $type   int		地区类型：1:省；2:市；3:区县
	**/
	public function get_region_id($name,$type,$parent_id="")
	{
		$this->db->select('region_id,parent_id');
		$this->db->from('region');
		
		if($parent_id!='')
		{
			$this->db->where('parent_id',$parent_id);	
		}
		
		$this->db->where('region_name',$name);
		$this->db->where('region_type',$type);
		
		return $this->db->get()->row_array();
	}
		
		/** 排名权重计算函数
		**  area_id_arr  array  广告位ID数组
		**/
		public function calculate_ad($area_id_arr,$customer_id="",$ad_lbs="",$ppc)
		{
			//初始化数据
			$time=strtotime("-1 hours",time());
			$lbs=array();
			$revenue=0;
			
			//获取用户群下的标签并组装
			if($customer_id!="")
			{
				$ad_scene=$this->get_customer_scene($customer_id);		
			}
			
			//获取广告的LBS并组装
			if($ad_lbs!='' && count($ad_lbs)>0)
			{
				foreach($ad_lbs as $k => $area)
				{
					$area_arr=explode("-",$area);				
					
					//获取省份ID
					$p=$this->get_region_id($area_arr[0],1);
			
					//检验城市。
					if($area_arr[1]=='unlimited')
					{
						$lbs[]=$p['region_id'];
					}
					else
					{	
						//获取城市ID
						$c=$this->get_region_id($area_arr[1],2,$p['region_id']);
						
						//检验区县。
						if($area_arr[2]=='unlimited')
						{
							$lbs[]=$c['region_id'];
						}
						else
						{
							$dis_arr=explode(",",$area_arr[2]);
							
							foreach($dis_arr as $dis)
							{
								//获取区县ID
								$d=$this->get_region_id($dis,3,$c['region_id']);

								$lbs[]=$d['region_id'];
							}	
						}
					}
				}
				
				$lbs=array_unique($lbs);
			}
			
			foreach($area_id_arr as $val)
			{
				$area=$this->get_area_report_info($val,$time);
				
				//初始化数据
				$area_scene_arr=array();
				$same_scene_fid=array();
				$same_scene=array();
				$same_lbs=array();
				$same_scene_num=0;
				$area_scene_num=0;
				$scene_weight=0;
				$lbs_weight=0;
				$cov=1;
					
				//广告位权重计数初始化
				$area_lbs_w=array();
				
				if($area['clicks']>0 && $area['impressions']>0)
				{
					$ctr=round($area['clicks']/$area['impressions'],5);
				}
				else
				{
					$ctr=0;	
				}
				
				//标签检验
				//若广告无标签，则不适合投放，不过，一般最低限度都有男女的标签。此处做的是严谨处理
				if($ad_scene)
				{
					//获得广告位标签
					$area_scene_arr=$this->get_scene_by_area_id($val);
					
					//若广告位无标签，则不适合投放任何广告，不过，一般最低限度都有男女的标签。此处做的是严谨处理
					if($area_scene_arr)
					{
						//广告位标签计数
						foreach($area_scene_arr['fid'] as $aow)
						{
							$area_scene_num +=count($area_scene_arr[$aow['fid']]);	
						}

						//检验标签匹配度，计算权重
						$same_scene_fid=array_intersect($area_scene_arr['fid'],$ad_scene['fid']);
						
						//对比标签类型是否有相同，有则检查标签，否则此广告位不符合。若相同的数量小于广告本身设置的数目。则有一项不符都要跳出
						if(count($same_scene_fid)>=count($ad_scene['fid']))
						{
							foreach($same_scene_fid as $sc_fid)
							{
								$same_scene=count(array_intersect($area_scene_arr[$sc_fid['fid']],$ad_scene[$sc_fid['fid']]));
								
								if($same_scene>0)
								{
									$same_scene_num +=$same_scene;
								}
								else
								{
									$scene_weight=0;
									$same_scene_num =0;
									break;
								}
							}
							
							if($same_scene_num>=$area_scene_num)
							{
								$scene_weight=1;
							}
							else
							{
								$scene_weight =round($same_scene_num/$area_scene_num,5);		
							}
							
						}
						else
						{
							$scene_weight=0;
							continue;	
						}
						
					}
					else
					{
						$scene_weight=0;
						continue;	
					}
				}
				else
				{
					$scene_weight=0;
					continue;	
				}

				//检验LBS
				//若广告为LBS通投，则LBS权重为1。否则检验权重
				if(count($lbs)>0)
				{
					$is_all=$this->get_area_isall($val);
					
					if($is_all['is_all']!=1)
					{
						$area_lbs=$this->get_area_region($val);
						
						if($area_lbs)
						{
							//流量权重计数
							foreach($area_lbs['province'] as $pval)
							{
								if($area_lbs['city'][$pval]=='unlimited')
								{
									$area_lbs_w[]=$pval;	
								}
								else
								{
									foreach($area_lbs['city'][$pval] as $cval)
									{
										if($area_lbs['districts'][$cval]=='unlimited')
										{
											$area_lbs_w[]=$cval;
										}
										else
										{
											foreach($area_lbs['districts'][$cval] as $dval)
											{
												$area_lbs_w[]=$dval;
											}
										}
									}	
								}
							}
							
							$same_lbs=count(array_intersect($area_lbs_w,$lbs));
							
							if($same_lbs>=count($area_lbs_w))
							{
								$lbs_weight=1;	
							}
							else
							{
								$lbs_weight=round($same_lbs/count($area_lbs_w),5);	
							}
						}
						else
						{
							$lbs_weight=0;
							continue;	
						}
					}
					else
					{
						$lbs_weight=1;	
					}
				}
				else
				{
					$lbs_weight=1;	
				}
				
				$cov=round($lbs_weight*$scene_weight,5);

				//计算收益权重
				$revenue +=$ppc*100*$area['impressions']*$cov*$ctr;
			}
			
			return $revenue;
		}
		
		//获取广告位的LBS信息
		public function get_area_region($area_id)
		{
			$result=array();
			
			$this->db->select('area_region.region_id,area_region.unlimited,area_region.fid,region.region_type');
			$this->db->from('area_region');
			$this->db->join('region', 'region.region_id = area_region.region_id', 'left');
			$this->db->where('area_region.area_id', $area_id);
			$arr=$this->db->get()->result_array();
			
			foreach($arr as $ar)
			{
				//省
				if($ar['region_type']==1)
				{
					$result['province'][]=$ar['region_id'];
					
					//若不限城市，则把unlimited写入城市数组
					if($ar['unlimited']==1)
					{
						$result['city'][$ar['region_id']]='unlimited';
					}
				}
				//市
				if($ar['region_type']==2)
				{
					$result['city'][$ar['fid']][]=$ar['region_id'];
					
					//若不限区，则把unlimited写入区数组
					if($ar['unlimited']==1)
					{
						$result['districts'][$ar['region_id']]='unlimited';
					}	
				}
				
				//区
				if($ar['region_type']==3)
				{
					$result['districts'][$ar['fid']][]=$ar['region_id'];	
				}
			}
			
			return $result;
		}
		
		//获取广告位是否全量
		public function get_area_isall($area_id)
		{
			$this->db->select('is_all');
			$this->db->from('ad_area');
			$this->db->where('id', $area_id);
            return $this->db->get()->row_array();
		}
		
		//获取用户群对应标签
		public function get_customer_scene($customer_id)
		{
			$result=array();
			
            $this->db->select('customer_scene.scene_id,ad_scene.fid');
			$this->db->from('customer_scene');
			$this->db->join('ad_scene', 'ad_scene.id = customer_scene.scene_id', 'left');
            $this->db->where('customer_scene.customer_id', $customer_id);
            $scarr=$this->db->get()->result_array();
			
			if($scarr)
			{
				foreach($scarr as $row)
				{
					if(!isset($result['fid']) || !in_array($row['fid'],$result['fid']))
					{
						$result['fid'][]=$row['fid'];
					}
					$result[$row['fid']][]=$row['scene_id'];	
				}	
				
				return $result;					
			}
			else
			{
				return false;
			}
		}
		
		//获取广告位的标签
		public function get_scene_by_area_id($area_id=0)
		{
			$result=array();
			
			$this->db->select('area_scene.scene_id,ad_scene.fid');
			$this->db->from('area_scene');
			$this->db->join('ad_scene', 'ad_scene.id = area_scene.scene_id', 'left');
			$this->db->where('area_scene.area_id', $area_id);
			$scarr=$this->db->get()->result_array();
			
			foreach($scarr as $row)
			{
				if(!isset($result['fid']) || !in_array($row['fid'],$result['fid']))
				{
					$result['fid'][]=$row['fid'];
				}
				$result[$row['fid']][]=$row['scene_id'];	
			}
			
			return $result;
		}
		
		//获取广告位的CTR
		public function get_area_report_info($area_id,$time)
		{
			$this->db->select('sum(clicks) clicks, sum(impressions) impressions');
            $this->db->where(array('area_id' => $area_id, 'start_time <' => $time));
			
			return $this->db->get('area_report')->row_array();
		}
		
		//获取广告合适的广告位
		public function get_area_list($size_id)
		{
			$this->db->select('ad_area.id');
			$this->db->from('ad_area');
			$this->db->join('ad_size', 'ad_size.id = ad_area.size_id', 'left');

			$this->db->where('ad_area.status', 1);
			$this->db->where('ad_size.id', $size_id);
			
			return $this->db->get()->result_array();
		}
		
		//获取广告排名
		public function get_rank($weight,$ad_id,$size_id,$status)
		{
			$rank=0;
			
			$this->db->select('id,weight');
			$this->db->where('status', 2);
			$this->db->where('size_id', $size_id);
        	$arr=$this->db->get('adinfo')->result_array();

			if($arr && $weight>0 && $status==2)
			{
				foreach($arr as $row)
				{
					$rank_arr[$row['id']]=$row['weight'];
				}
			
				arsort($rank_arr);

				foreach($rank_arr as $key=>$val)
				{
					$rank++;
					
					if($key==$ad_id)
					{
						break;	
					}
				}
			}
			else
			{
				$rank=0;	
			}
			
			return $rank;
		}
	
		//获取广告权重
		public function get_ad_weight($ad_id)
		{
			$this->db->select('weight,price');
			$this->db->where('id', $ad_id);
			return $this->db->get('adinfo')->row_array();
		}
}
