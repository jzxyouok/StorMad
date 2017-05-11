<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_log_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取广告主资源数据总数
    public function get_user_log_count($conditions)
    {
        $this->make_condition($conditions);
        $query = $this->db->get()->num_rows();  
        return $query;
    }
    
    private function make_condition($conditions)
    {
        $this->db->select('user_log.id,user_log.content,user_log.add_time,user.user_name,user.true_name');
        $this->db->from('user_log');
        $this->db->join('user', 'user.id = user_log.user_id', 'left');
        $this->db->where('user_log.user_id', $this->session->userdata('id'));
        $this->db->order_by('user_log.id', 'DESC');
        if(isset($conditions['user']) && $conditions['user'])
        {
            $this->db->like('user.true_name', $conditions['user']);
        }	
        if(isset($conditions['content']) && ($conditions['content']))
        {
            $this->db->like('user_log.content', $conditions['content']);
        }	
        if(isset($conditions['start_time']) && ($conditions['start_time']) && isset($conditions['end_time']) && ($conditions['end_time']))
        {
            $time_where = array('user_log.add_time >' => strtotime($conditions['start_time']), 'user_log.add_time <' => strtotime($conditions['end_time']));
            $this->db->where($time_where);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
    }
    
    public function get_user_log($conditions)
	{
	    $this->make_condition($conditions);
	    
	    $query = $this->db->get();
	    return $query->result_array();
	}
	
	//操作日志
	public function write_log($user_log) {
	    $data = array(
	        'content' => $user_log['content'],
	        'add_time' => time(),
	        'user_id' => $user_log['user_id'],
	    );
	     
	    $this->insert('user_log', $data);
	    return false;
	}
}
