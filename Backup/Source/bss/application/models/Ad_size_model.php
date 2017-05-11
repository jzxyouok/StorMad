<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_size_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	//获取广告规格资源数据总数
	public function get_size_count($conditions)
	{
	   if(isset($conditions['page_size']) && isset($conditions['offset']))
	   {
	      $this->db->limit($conditions['page_size'],$conditions['offset']);
	   }
	    
	   $query = $this->db->get('ad_size')->num_rows();  
	   return $query;
	}
	
	//获取广告规格资源数据
	public function get_size($conditions)
	{
	    $this->db->select('ad_size.id,ad_size.size_name,ad_size.type,ad_size.width,ad_size.height,ad_size.add_time,ad_size.update_time,admin_user.user_name,admin_user.true_name');
	    $this->db->from('ad_size');
	    $this->db->join('admin_user', 'admin_user.id = ad_size.update_admin_id', 'left');
	    
	   if(isset($conditions['page_size']) && isset($conditions['offset']))
	   {
	       $this->db->limit($conditions['page_size'],$conditions['offset']);
	   }
	   
	   $this->db->order_by('ad_size.update_time', 'DESC');
	   $query = $this->db->get()->result_array();  
	   return $query;
	}
	
	//查找规格
	public function find_size($id)
	{
	    $this->db->select('size_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('ad_size');
	
	    return $query->row_array();
	}
	
	//按规格类型查找规格
	public function find_type_size($type)
	{
	    $this->db->where('type', $type);
	    $query = $this->db->get('ad_size');
	
	    return $query->result_array();
	}
	
	//获取指定广告规格
	public function get_one_size($id)
	{
	    $this->db->where('id', $id);
	    $query = $this->db->get('ad_size')->row_array();
	    return $query;
	}
	
	//添加广告规格
	public function add_size($size_name)
	{
	   $data = array(
	       'size_name' => $size_name['size_name'],
	       'type' => $size_name['type'],
	       'width' => $size_name['size_width'],
	       'height' => $size_name['size_height'],
	       'update_time' => time(),
	       'update_admin_id' => $this->session->userdata('id'),
	   );
	    
	   if($size_name['id'])
	   {
	      $where = array('id' => $size_name['id']);
	      $res = $this->update('ad_size', $data, $where);
	   }else
	   {
	      $data['add_time'] = time();
	      $res = $this->insert('ad_size', $data);
	   }
	   return $res;
	}
	
	//删除广告规格
	public function del_size($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('ad_size');
	    
	    return $res;
	}
	
	//修改广告规格名称
	public function edit_size_name($id, $name)
	{
	    $data = array(
	        'size_name' => $name,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('ad_size', $data, $where);
	
	    $this->db->select('size_name');
	    $res = $this->db->get_where('ad_size', array('id' => $id));
	    return $res->row_array();
	}
}
