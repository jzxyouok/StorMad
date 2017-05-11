<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adgroup_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取推广组资源数据总数
    public function get_adgroup_count($conditions, $user_id)
    {
        if(isset($conditions['campaign_id']) && $conditions['campaign_id'])
        {
            $this->db->where('campaign_id', $conditions['campaign_id']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
         
        $query = $this->db->get('adgroup')->num_rows();
        return $query;
    }
    
    //获取推广组数据
	public function get_adgroup($conditions, $user_id)
	{
	    $this->db->select('adgroup.id,adgroup.adgroup_name,adgroup.campaign_id,adgroup.day_sum,adgroup.status,campaign.campaign_name,user.true_name');
	    $this->db->from('adgroup');
	    $this->db->join('user', 'user.id = adgroup.user_id', 'left');
	    $this->db->join('campaign', 'campaign.id = adgroup.campaign_id', 'left');
	    $this->db->order_by('adgroup.id', 'DESC');
	    
	    if(isset($conditions['campaign_id']) && $conditions['campaign_id'])
	    {
	        $this->db->where('campaign_id', $conditions['campaign_id']);
	    }
	    if(isset($conditions['page_size']) && isset($conditions['offset']))
	    {
	        $this->db->limit($conditions['page_size'],$conditions['offset']);
	    }
	    $this->db->where('adgroup.user_id', $user_id);
	    
	    $query = $this->db->get()->result_array();    
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
	
	//查找推广组
	public function find_adgroup($id)
	{
	    $this->db->select('adgroup.id,adgroup.adgroup_name,adgroup.campaign_id,campaign.campaign_name');
	    $this->db->from('adgroup');
	    $query = $this->db->join('campaign', 'campaign.id = adgroup.campaign_id', 'left');
	    $this->db->where('adgroup.id', $id);
	    $query = $this->db->get()->row_array();
	
	    return $query;
	}
	
	//获取广告条数
	public function get_adinfo_count($id)
	{
	    $this->db->select('title');
	    $this->db->where('adgroup_id', $id);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $query = $this->db->get('adinfo')->num_rows();
	     
	    return $query;
	}
	
	//添加、编辑推广组
	public function add_adgroup($adgroup)
	{
    	$data = array(
    	    'adgroup_name' => $adgroup['adgroup_name'],
    	    'campaign_id' => $adgroup['campaign_id'],
    	    'day_sum' => $adgroup['day_sum']*100,
    	    'update_time' => time(),
    	    'user_id' => $this->session->userdata('id'),
    	);
    	if($adgroup['id'])
    	{
    	    $where = array('id' => $adgroup['id']);
    	    $res = $this->update('adgroup', $data, $where);
    	}else
    	{
    	    $data['status'] = 1;
    	    $data['add_time'] = time();
    	    $res = $this->insert('adgroup', $data);
    	} 
    	
    	return $res;
	}
	
	//删除推广组
	public function del_adgroup($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('adgroup');
	    
	    if($res)
	    {
    	    $adinfo_data = array(
    	        'adgroup_id' => 0,
    	        'status' => 0,
    	        'update_time' => time(),
    	    );
    	    $adinfo_where = $this->db->where('adgroup_id', $id);
    	    $adinfo = $this->db->get('adinfo')->result_array();
    	    if($adinfo)
    	    {
    	       $this->update('adinfo', $adinfo_data, $adinfo_where);
    	    }
	    }
	     
	    return $res;
	}
	
	//获取要修改的推广组
	public function edit_adgroup($id)
	{
	    $this->db->select('adgroup.id, adgroup.adgroup_name, adgroup.day_sum, adgroup.campaign_id, campaign.campaign_name');
	    $this->db->from('adgroup');
	    $this->db->join('campaign', 'campaign.id = adgroup.campaign_id', 'left');
	    $this->db->where('adgroup.id', $id);
	
	    $res = $this->db->get()->row_array();
	
	    return $res;
	}
	
	//设置推广组及推广组下的所有广告状态
	public function set_status($status, $id)
	{
	    $adgroup_data = array(
	        'status' => $status,
	        'update_time' => time(),
	    );
	    $adgroup_where = array('id' => $id);
	    $update_status = $this->update('adgroup', $adgroup_data, $adgroup_where);
	    
	    if($status==0)
	    {
	        $ad_status = 0;
	    
            $ad_data = array(
                'status' => $ad_status,
                'update_time' => time(),
            );
            
            $ad_where = array('adgroup_id' => $id);
            $this->update('adinfo', $ad_data, $ad_where);
	    }
	    $this->db->select('status');
	    $query = $this->db->get_where('adgroup', array('id' => $id));
	    return $query->row_array();
	}
	
	//Ajax二级联动通过推广计划ID获取推广组
	public function get_ajax_adgroup($campaign_id)
	{
	    $this->db->select('id, adgroup_name');
	    $where = array('campaign_id' => $campaign_id);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get_where('adgroup', $where)->result_array();
	    
	    return $res;
	}
	
	//获取推广组的总数
	public function get_adgroup_num()
	{
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('adgroup')->num_rows();
	
	    return $res;
	}
	
	//修改推广组名称
	public function edit_adgroup_name($id, $name)
	{
	    $data = array(
	        'adgroup_name' => $name,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('adgroup', $data, $where);
	     
	    $this->db->select('adgroup_name');
	    $res = $this->db->get_where('adgroup', array('id' => $id));
	    return $res->row_array();
	}
	
	//修改推广组日限额
	public function edit_day_sum($id, $value)
	{
	    $data = array(
	        'day_sum' => $value*100,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('adgroup', $data, $where);
	
	    $this->db->select('day_sum');
	    $res = $this->db->get_where('adgroup', array('id' => $id));
	    return $res->row_array();
	}
	
	//检查推广组名称是否存在
	public function check_adgroup($adgroup_name)
	{
	    $this->db->select('adgroup_name');
	    $this->db->where('adgroup_name', $adgroup_name);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('adgroup')->row_array();
	
	    return $res;
	}
}
