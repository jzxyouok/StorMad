<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function find_admin_user($post)
    {
        $this->db->select('id, user_name, true_name');
        $where = array('user_name' => $post['user_name'], 'password' => md5($post['password']), 'status' => 1);
    
        $query = $this->db->get_where('admin_user', $where)->row_array();
        return $query;
    }
    //登录验证
    public function check_login($username, $password, $code)
    {
        $num = '';
        $user_name = array();
        $query = array();
        if($username)
        {
            $where = array('user_name' => $username, 'status' => 1);
            $user_name = $this->db->get_where('admin_user', $where)->row_array();
        }
        if($username && $password)
        {
            $where = array('user_name' => $username, 'password' => md5($password), 'status' => 1);
            $query = $this->db->get_where('admin_user', $where)->row_array();
        }
        //管理员不存在或已被停用为1 密码不正确为2 验证码不正确为3
        if(!$user_name)
        {
            $num = 1;
        }
        if(!$query && !$num)
        {
            $num = 2;
        }
        if($user_name && $query && strtolower($code)!=strtolower($this->session->userdata('reg_code')))
        {
            $num = 3;
        }
    
        return $num;
    }
    
    public function get_admin_count($conditions)
    {
        $this->make_condition($conditions);
        $query = $this->db->get('admin_user')->num_rows();  //获取管理员资源数据
        return $query;
    }
    
    private function make_condition($conditions)
    {
        if(isset($conditions['user']) && $conditions['user'])
        {
            $this->db->like('true_name', $conditions['user']);
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
    
    public function get_admin($conditions)
	{
	    $this->make_condition($conditions);
	    $this->db->order_by('id', 'DESC');
	    
	    $query = $this->db->get('admin_user');
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
	
	//增加管理员
	public function add_admin($admin)
	{
	    $data = array(
	        'user_name' => $admin['user_name'],
	        'true_name' => $admin['true_name'],
	        'password' => md5($admin['user_password']),
	        'status' => 1,
	        'comment' => $admin['user_comment'],
	        'add_time' => time(),
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
	    );
	    
	    $res = $this->insert('admin_user', $data);
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
	    $res = $this->update('admin_user', $data, $where);
	    
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
	    $update_num = $this->update('admin_user', $data, $where);
	
	    $this->db->select('status');
	    $query = $this->db->get_where('admin_user', array('id' => $id));
	    return $query->row_array();
	}
	
	//检查管理员账号是否存在
	public function check_admin_user($username)
	{
	    $this->db->select('user_name');
	    $this->db->where('user_name', $username);
	    $res = $this->db->get('admin_user')->row_array();
	     
	    return $res;
	}
}
