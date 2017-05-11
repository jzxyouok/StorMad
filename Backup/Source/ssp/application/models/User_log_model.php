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
        $this->db->select('channel_log.id,channel_log.content,channel_log.add_time,channel.distribution_name');
        $this->db->from('channel_log');
        $this->db->join('channel', 'channel.id = channel_log.user_id', 'left');
        $this->db->where('channel_log.user_id', $this->session->userdata('id'));
        $this->db->order_by('channel_log.id', 'DESC');
        if(isset($conditions['user']) && $conditions['user'])
        {
            $this->db->like('channel.distribution_name', $conditions['user']);
        }	
        if(isset($conditions['content']) && ($conditions['content']))
        {
            $this->db->like('channel_log.content', $conditions['content']);
        }	
        if(isset($conditions['start_time']) && ($conditions['start_time']) && isset($conditions['end_time']) && ($conditions['end_time']))
        {
            $time_where = array('channel_log.add_time >' => strtotime($conditions['start_time']), 'channel_log.add_time <' => strtotime($conditions['end_time']));
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
	     
	    $this->insert('channel_log', $data);
	    return false;
	}
}
