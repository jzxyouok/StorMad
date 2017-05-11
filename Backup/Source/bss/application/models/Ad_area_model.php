<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_area_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	//获取标签分类
	public function get_class()
	{
	    $query = $this->db->get_where('ad_scene', array('fid' => 0))->result_array();
	    return $query;
	}
	
	//获取标签名
	public function get_scene($fid)
	{
	    $query = $this->db->get_where('ad_scene', array('fid' => $fid))->result_array();
	    return $query;
	}
	
	//获取广告位资源数据总数
	public function get_area_count($conditions)
	{
	   if(isset($conditions['page_size']) && isset($conditions['offset']))
	   {
	      $this->db->limit($conditions['page_size'],$conditions['offset']);
	   }
	    
	   $query = $this->db->get('ad_area')->num_rows();  
	   return $query;
	}
	
	//获取广告资源数据
	public function get_area($conditions)
	{
	   $this->db->select('ad_area.id,ad_area.is_all,ad_area.area_name,ad_area.site_name,ad_area.page_name,ad_area.type,ad_area.status,ad_size.size_name,ad_size.width,ad_size.height');
	   $this->db->from('ad_area');
	   $this->db->join('ad_size', 'ad_size.id = ad_area.size_id', 'left');
	   
	   if(isset($conditions['page_size']) && isset($conditions['offset']))
	   {
	       $this->db->limit($conditions['page_size'],$conditions['offset']);
	   }
	   
	   $this->db->order_by('ad_area.id', 'DESC');
	   $query = $this->db->get()->result_array();  
	   return $query;
	}
	
	//获取规格
	public function get_size()
	{
	   $query = $this->db->get('ad_size')->result_array();  //获取广告资源数据
	   return $query;
	}
	
	//查找广告位
	public function find_area($id)
	{
	    $this->db->select('area_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('ad_area');
	
	    return $query->row_array();
	}
	
	//获取指定广告位
	public function get_one_area($id)
	{
	    $this->db->select('ad_area.id,ad_area.is_all,ad_area.val_region,ad_area.show_region,ad_area.area_name,ad_area.site_name,ad_area.page_name,ad_area.type,ad_area.status,ad_area.size_id,ad_area.comment,ad_size.size_name,ad_size.width,ad_size.height,channel_ad_area.channel_id,channel.distribution_name');
	    $this->db->from('ad_area');
	    $this->db->join('ad_size', 'ad_size.id = ad_area.size_id', 'left');
		$this->db->join('channel_ad_area', 'channel_ad_area.ad_area_id = ad_area.id', 'left');
		$this->db->join('channel', 'channel.id = channel_ad_area.channel_id', 'left');
	    
	    $this->db->where('ad_area.id', $id);
	    $query = $this->db->get()->row_array();
	    return $query;
	}
	public function edit_area_scene($id)
	{
	    $this->db->select('ad_scene.id as scene_id, ad_scene.scene_name');
	    $this->db->from('ad_area');
	    $this->db->join('area_scene', 'area_scene.area_id = ad_area.id', 'left');
	    $this->db->join('ad_scene', 'ad_scene.id = area_scene.scene_id', 'left');
	    $this->db->where('ad_area.id', $id);
	
	    $res = $this->db->get()->result_array();
	
	    return $res;
	}
	
	//添加广告位
	public function add_area($query)
	{
	   $data = array(
	       'area_name' => $query['area_name'],
	       'site_name' => $query['site_name'],
	       'page_name' => $query['page_name'],
	       'type' => $query['type'],
	       'size_id' => $query['size_id'],
	       'comment' => $query['comment'],
		   'is_all' => $query['is_all'],
	       'update_time' => time(),
	       'update_admin_id' => $this->session->userdata('id'),
	   );
	   
	   if($query['area'])
	   {
			$data['val_region']=implode("|",$query['area']); 
			$data['show_region']=str_replace("unlimited","不限",$data['val_region']);  
	   }

	   if($query['id'])
	   {
	      $where = array('id' => $query['id']);
	      $res = $this->update('ad_area', $data, $where);
	      $area_id = $query['id'];
	      
		  //先删除此广告位下所有定位信息；
		  $this->del_area_region($query['id']);

	      $this->db->where('area_id', $area_id);
	      $res = $this->db->delete('area_scene');
	   }
	   else
	   {
		  $data['add_time'] = time();
		  $res = $this->insert('ad_area', $data);
		  $area_id = $this->db->insert_id();
		  
		   $channel_area_data= array(
				'channel_id' => $query['channel_id'],
				'ad_area_id' => $area_id
		   );

		   $this->insert('channel_ad_area', $channel_area_data);
		   
		   	   	  
	   }
	   
	   if($query['scene_id'])
	   {
	       $scene_id = explode(',', $query['scene_id']);
	       foreach ($scene_id as $k=>$val)
	       {
	           $area_scene = array(
	               'area_id' => $area_id,
	               'scene_id' => $val,
	           );
	           $this->insert('area_scene', $area_scene);
	       }
	   }

	   if($query['area'] && $query['is_all']!=1)
	   {//print_r($query['area']);die();
		   if($query['id'])
		   {
				$area_area_id=$query['id'];
		   }
		   else
		   {
				$area_area_id= $area_id;  
		   }
		   //储存地区数组
		    $province_arr=array();
			$city_arr=array();
			$districts_arr=array();
			
			foreach($query['area'] as $k => $area)
			{
				$area_arr=explode("-",$area);				
				
				//初始化数据
				$p_data=array();
				$c_data=array();	
				$d_data=array();
							
				$p_data['area_id']=$area_area_id;
				
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
					$c_data['area_id']=$area_area_id;
			
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
						$d_data['area_id']=$area_area_id;
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
								$this->insert('area_region', $d_data);
								$districts_arr[$c['region_id']][]=$dis;
							}
						}	
					}
				}
				
				//检验是否有重复加入，如果没有。则把该省份加入数据表
				if(!in_array($area_arr[0],$province_arr))
				{
					//写入省份，并加入数组，避免重复加入
					$this->insert('area_region', $p_data);
					$province_arr[]=$area_arr[0];
				}
				
				//检验是否有重复加入，如果没有。则把该城市加入数据表
				if(!in_array($area_arr[1],$city_arr) && $area_arr[1]!='unlimited')
				{
					//写入城市，并加入数组，避免重复加入
					$this->insert('area_region', $c_data);
					$city_arr[]=$area_arr[1];
				}
			}   
	   }
	   
	   return $res;
	}
	
	//删除广告位
	public function del_area($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('ad_area');
		
		//删除渠道与广告位关系
		$this->db->where('ad_area_id', $id);
	    $this->db->delete('channel_ad_area');
		
		//删除广告位与广告关系
		$this->db->where('ad_area_id', $id);
	    $this->db->delete('area_ad');
		
	    $this->db->where('area_id', $id);
	    $this->db->delete('area_scene');
	    
	    return $res;
	}
	
	//根据广告位ID获取对应渠道
	public function get_channel_id($ad_area_id)
	{
		$this->db->select('channel.distribution_id');
		$this->db->from('channel_ad_area');
		$this->db->join('channel', 'channel.id = channel_ad_area.channel_id', 'left');
		
		$this->db->where('channel_ad_area.ad_area_id',$ad_area_id);
		return $this->db->get()->row_array();
	}
	
	//是否启用广告位
	public function use_area($status, $id)
	{
	    $data = array(
	        'status' => $status,
	        'update_time' => time(),
	    );
	     
	    $where = array('id' => $id);
	    $update_status = $this->update('ad_area', $data, $where);
	     
	    $this->db->select('status');
	    $query = $this->db->get_where('ad_area', array('id' => $id))->row_array();
	     
	    return $query;
	}
	
	//修改广告位名称
	public function edit_area_name($id, $name)
	{
	    $data = array(
	        'area_name' => $name,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('ad_area', $data, $where);
	
	    $this->db->select('area_name');
	    $res = $this->db->get_where('ad_area', array('id' => $id));
	    return $res->row_array();
	}
	
	private function http_post($url, $param)
	{
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			$aPOST = array ();
			foreach ( $param as $key => $val ) {
				$aPOST [] = $key . "=" . urlencode ( $val );
			}
			$strPOST = join ( "&", $aPOST );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	public function get_distribution_id($id)
	{
		$this->db->select('distribution_id');
		return $this->db->get_where('channel',array('id' => $id))->row_array();
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
	
	//删除广告位下所有LBS信息
	public function del_area_region($area_id)
	{
		$this->db->where('area_id',$area_id);
		$this->db->delete('area_region');
	}
}
