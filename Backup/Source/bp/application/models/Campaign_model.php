<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取推广计划资源数据总数
    public function get_campaign_count($conditions, $user_id)
    {
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
         
        $query = $this->db->get('campaign')->num_rows();
        return $query;
    }
    
	public function get_campaign($conditions, $user_id)
	{
	    if(isset($conditions['page_size']) && isset($conditions['offset']))
	    {
	        $this->db->limit($conditions['page_size'],$conditions['offset']);
	    }
	    $this->db->where('user_id', $user_id);
	    
	    $this->db->order_by('id', 'DESC');
	    $query = $this->db->get('campaign')->result_array();
	    return $query;
	}
	
	//获取推广组个数
	public function get_adgroup_count($id)
	{
	    $this->db->select('adgroup_name');
	    $this->db->where('campaign_id', $id);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $query = $this->db->get('adgroup')->num_rows();
	    
	    return $query;
	}
	
	//查找推广计划
	public function find_campaign($id)
	{
	    $this->db->select('id, campaign_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('campaign');
	
	    return $query->row_array();
	}
	
	//获取广告条数
	public function get_adinfo_count($id)
	{
	    $this->db->select('title');
	    $this->db->where('campaign_id', $id);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $query = $this->db->get('adinfo')->num_rows();
	     
	    return $query;
	}
	
	//添加、编辑推广计划
	public function add_campaign($campaign)
	{
	    $data = array(
	        'campaign_name' => $campaign['campaign_name'],
	        'day_sum' => $campaign['day_sum']*100,
	        'update_time' => time(),
	        'user_id' => $this->session->userdata('id'),
	    );
	    if($campaign['id'])
	    {
	        $where = array('id' => $campaign['id']);
	        $res = $this->update('campaign', $data, $where);
	    }else
	    {
	        $data['status'] = 1;
	        $data['add_time'] = time();
	        $res = $this->insert('campaign', $data);
	    }
	     
	    return $res;
	}
	
	//获取要修改的推广计划
	public function edit_campaign($id)
	{
	    $this->db->select('id, campaign_name, day_sum');
	    $this->db->where('id', $id);
	    
	    $res = $this->db->get('campaign')->row_array();
	
	    return $res;
	}
	
	//设置推广计划、推广组及推广组下的所有广告状态
	public function set_status($status, $id)
	{
	    $campaign_data = array(
	        'status' => $status,
	        'update_time' => time(),
	    );
	    $campaign_where = array('id' => $id);
	    $update_status = $this->update('campaign', $campaign_data, $campaign_where);
	    
	    $adgroup_data = array(
	        'status' => $status,
	        'update_time' => time(),
	    );
	    $adgroup_where = array('campaign_id' => $id);
	    $adgroup_status = $this->update('adgroup', $adgroup_data, $adgroup_where);
	     
	    if($status==0)
	    {
	        $ad_status = 0;
	    
            $ad_data = array(
                'status' => $ad_status,
                'update_time' => time(),
            );
             
            $ad_where = array('campaign_id' => $id);
            $this->update('adinfo', $ad_data, $ad_where);
	    }
	    $this->db->select('status');
	    $query = $this->db->get_where('campaign', array('id' => $id));
	    return $query->row_array();
	}
	
	//获取推广计划的总数
	public function get_campaign_num()
	{
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('campaign')->num_rows();
	
	    return $res;
	}
	
	//修改推广计划名称
	public function edit_campaign_name($id, $name)
	{
	    $data = array(
	        'campaign_name' => $name,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('campaign', $data, $where);
	    
	    $this->db->select('campaign_name');
	    $res = $this->db->get_where('campaign', array('id' => $id));
	    return $res->row_array();
	}
	
	//修改推广计划日限额
	public function edit_day_sum($id, $value)
	{
	    $data = array(
	        'day_sum' => $value*100,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('campaign', $data, $where);
	     
	    $this->db->select('day_sum');
	    $res = $this->db->get_where('campaign', array('id' => $id));
	    return $res->row_array();
	}
	
	//检查推广计划名称是否存在
	public function check_campaign($campaign_name)
	{
	    $this->db->select('campaign_name');
	    $this->db->where('campaign_name', $campaign_name);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('campaign')->row_array();
	     
	    return $res;
	}
}
