<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_user_count($conditions)
    {
        $this->make_condition($conditions);
        $query = $this->db->get('user')->num_rows();  //获取用户资源数据
        return $query;
    }
    
    private function make_condition($conditions)
    {
        if(isset($conditions['user']) && $conditions['user'])
        {
            $this->db->like('true_name', $conditions['user']);
        }
        	
        if(isset($conditions['type']) && ($conditions['type']))
        {
            $this->db->where('type', $conditions['type']);
        }
        	
        if(isset($conditions['status']) && $conditions['status']!=2)
        {
            $this->db->where('status', $conditions['status']);
        }
        
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
    }
    
    public function get_user($conditions)
	{
	    $this->make_condition($conditions);
	    $this->db->order_by('id', 'DESC');
	    
	    $query = $this->db->get('user');
	    return $query->result_array();
	}
	
	//查找管理员
	public function find_admin($id)
	{
	    $this->db->select('user_name, true_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('admin_user');
	     
	    return $query->row_array();
	}
	
	//查找账户
	public function find_user($id)
	{
	    $this->db->select('user_name, true_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('user');
	    
	    return $query->row_array();
	}
	
	//增加新用户
	public function add_user($user)
	{
	    $data = array(
	        'user_name' => $user['user_name'],
	        'true_name' => $user['true_name'],
			'logo' => $user['logo'],
	        'password' => md5($user['user_password']),
	        'type' => $user['user_type'],
	        'status' => 1,
	        'comment' => $user['user_comment'],
	        'add_time' => time(),
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    $res = $this->insert('user', $data);
	    $user_id = $this->db->insert_id();
	    //默认添加推广计划、推广组、用户群
	    $campaign_data = array(
	        'campaign_name' => '默认推广计划',
	        'day_sum' => 0,
	        'status' => 1,
	        'update_time' => time(),
	        'add_time' => time(),
	        'user_id' => $user_id,
	    );
	    $this->insert('campaign', $campaign_data);
	    $campaign_id = $this->db->insert_id();
	    
	    $adgroup_data = array(
	        'adgroup_name' => '默认推广组',
	        'campaign_id' => $campaign_id,
	        'day_sum' => 0,
	        'status' => 1,
	        'update_time' => time(),
	        'add_time' => time(),
	        'user_id' => $user_id,
	    );
	    $this->insert('adgroup', $adgroup_data);
	    
	    $customer_data = array(
	        'customer_name' => '默认用户群',
	        'update_time' => time(),
	        'add_time' => time(),
	        'user_id' => $user_id,
	    );
	    $this->insert('customer', $customer_data);
	    
	    return $res;
	}
	
	//添加账户金额
	public function add_money($add_money, $user_id)
	{
	    $this->db->select('money');
	    $user_money = $this->db->get_where('user', array('id' => $user_id))->row_array();
	    
	    $user_log = array(
	        'type' => 2,
	        'money' => $add_money*100,
	        'remain_sum' => $user_money['money'] + $add_money*100,
	        'comment' => '管理员添加金额',
	        'user_id' => $user_id,
	        'add_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    
	    $this->insert('user_money_log', $user_log);
	    
	    $data = array(
	        'money' => $user_money['money'] + $add_money*100,
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	     
	    $where = array('id' => $user_id);
	    $res = $this->update('user', $data, $where);
	     
	    return $res;
	}
	
	//重置账户密码
	public function edit_password($user_password, $user_id)
	{
	    $data = array(
	        'password' => md5($user_password),
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    
	    $where = array('id' => $user_id);
	    $res = $this->update('user', $data, $where);
	    
	    return $res;
	}
	
	//账户状态设置
	public function set_status($status, $id)
	{
	    $data = array(
	        'status' => $status,
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	
	    $where = array('id'=>$id);
	    $update_num = $this->update('user', $data, $where);
	
	    $this->db->select('status');
	    $query = $this->db->get_where('user', array('id' => $id));
	    return $query->row_array();
	}
	
	//检查用户账号是否存在
	public function check_user($username)
	{
	    $this->db->select('user_name');
	    $this->db->where('user_name', $username);
	    $res = $this->db->get('user')->row_array();
	    
	    return $res;
	}
}
