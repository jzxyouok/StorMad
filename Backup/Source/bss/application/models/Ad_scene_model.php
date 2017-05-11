<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_scene_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	//获取标签分类
	public function get_class()
	{
	    $query = $this->db->get_where('ad_scene', array('fid' => 0))->result_array();  //获取场景标签资源数据
	    return $query;
	}
	
	//获取标签名
	public function get_scene($fid)
	{
		$query = $this->db->get_where('ad_scene', array('fid' => $fid))->result_array();  //获取场景标签资源数据
		return $query;
	}
	
	//查找标签
	public function find_scene($id)
	{
	    $this->db->select('scene_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('ad_scene');
	
	    return $query->row_array();
	}
	
	//获取分类
	public function get_scene_class($id)
	{
	    $query = $this->db->get_where('ad_scene', array('id' => $id))->row_array();
	    return $query;
	}
	
	//增加分类
	public function add_class($scene_class)
	{
	    $data = array(
	        'scene_name' => $scene_class['scene_class'],
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    
	    if($scene_class['id'])
	    {
	        $where = array('id' => $scene_class['id']);
	        $res = $this->update('ad_scene', $data, $where);
	    }else
	    {
	        $data['add_time'] = time();
	        $res = $this->insert('ad_scene', $data);
	    }
	    
	    return $res;
	}
	
	//删除分类及子标签
	public function del_scene_class($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('ad_scene');
	     
	    $this->db->where('fid', $id);
	    $this->db->delete('ad_scene');
	     
	    return $res;
	}
	
	//增加标签
	public function add_scene($scene_name)
	{
	    $data = array(
	        'scene_name' => $scene_name['scene_name'],
			'field_label' => $scene_name['field_label'],
	        'fid' => $scene_name['fid'],
	        'add_time' => time(),
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    if($scene_name['id'])
	    { 
	        $where = array('id' => $scene_name['id']);
	        $res = $this->update('ad_scene', $data, $where);
	    }else
	    {
	        $res = $this->insert('ad_scene', $data);
	    }
	    return $res;
	}
	
	//获取要修改的标签
	public function edit_scene($id, $fid)
	{
	    $this->db->select('id, scene_name');
	    $where = array('id' => $id, 'fid' => $fid);
	
	    $res = $this->db->get_where('ad_scene', $where)->row_array();
	
	    return $res;
	}
	
	//删除标签
	public function del_scene($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('ad_scene');
	    
	    $this->db->where('scene_id', $id);
	    $this->db->delete('customer_scene');
	    
	    $this->db->where('scene_id', $id);
	    $this->db->delete('area_scene');
	    
	    return $res;
	}
}
