<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取广告主资源数据总数
    public function get_ad_log_count($conditions)
    {
        $this->make_condition($conditions);
        $query = $this->db->get()->num_rows();  
        return $query;
    }
    
    private function make_condition($conditions)
    {
        $this->db->select('admin_log.id,admin_log.content,admin_log.add_time,admin_user.user_name,admin_user.true_name');
        $this->db->from('admin_log');
        $this->db->join('admin_user', 'admin_user.id = admin_log.admin_id', 'left');
        $this->db->order_by('admin_log.id', 'DESC');
        if(isset($conditions['user']) && $conditions['user'])
        {
            $this->db->like('admin_user.true_name', $conditions['user']);
        }	
        if(isset($conditions['content']) && ($conditions['content']))
        {
            $this->db->like('admin_log.content', $conditions['content']);
        }	
        if(isset($conditions['start_time']) && ($conditions['start_time']) && isset($conditions['end_time']) && ($conditions['end_time']))
        {
            $time_where = array('admin_log.add_time >' => strtotime($conditions['start_time']), 'admin_log.add_time <' => strtotime($conditions['end_time']));
            $this->db->where($time_where);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
    }
    
    public function get_ad_log($conditions)
	{
	    $this->make_condition($conditions);
	    
	    $query = $this->db->get();
	    return $query->result_array();
	}
	
	//操作日志
	public function write_log($admin_log) {
	    $data = array(
	        'content' => $admin_log['content'],
	        'add_time' => time(),
	        'admin_id' => $admin_log['admin_id'],
	    );
	     
	    $this->insert('admin_log', $data);
	    return false;
	}
}
