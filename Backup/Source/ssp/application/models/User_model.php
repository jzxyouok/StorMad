<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function find_user($post)
    {
        $this->db->select('id, distribution_id, distribution_name');
        $where = array('user_name' => $post['user_name'], 'password' => md5($post['password']), 'status' => 1);
        
        $query = $this->db->get_where('channel', $where)->row_array();
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
            $user_name = $this->db->get_where('channel', $where)->row_array();
        }
        if($username && $password)
        {
            $where = array('user_name' => $username, 'password' => md5($password), 'status' => 1);
            $query = $this->db->get_where('channel', $where)->row_array();
        }
        //用户不存在或已被停用为1 密码不正确为2 验证码不正确为3
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
}
