<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_money_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //当天账户情况
    public function get_money($today, $user_id)
    {
        $this->db->select('id, type, money');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', 1);
    
        if(isset($today) && $today)
        {
            $time_where = array('add_time >=' => strtotime($today));
            $this->db->where($time_where);
        }
    
        $query = $this->db->get('user_money_log')->result_array();
        return $query;
    }
    
    //账户余额
    public function get_my_money()
    {
        $this->db->select('id, money');
        $this->db->where('id', $this->session->userdata('id'));
    
        $query = $this->db->get('user')->row_array();
        return $query;
    }
    
    //获取财务记录资源数据总数
    public function get_money_log_count($conditions, $user_id)
    {
        if(isset($conditions['start_time']) && ($conditions['start_time']) && isset($conditions['end_time']) && ($conditions['end_time']))
        {
            $time_where = array('add_time >' => strtotime($conditions['start_time']), 'add_time <' => strtotime($conditions['end_time']));
            $this->db->where($time_where);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get('user_money_log')->num_rows();  
        return $query;
    }
    
    public function get_user_money($conditions, $user_id)
    {
        $this->db->select('id, type, money, remain_sum, comment, add_time');
        $this->db->where('user_id', $user_id);
        //近三个月财务记录条件
        $three_months = date('Y-m-d 00:00:00', strtotime('-3 month'));
        $where = array('add_time >=' => strtotime($three_months));
        $this->db->where($where);
        
        if(isset($conditions['start_time']) && ($conditions['start_time']) && isset($conditions['end_time']) && ($conditions['end_time']))
        {
            $time_where = array('add_time >' => strtotime($conditions['start_time']), 'add_time <' => strtotime($conditions['end_time']));
            $this->db->where($time_where);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->order_by('id', 'DESC');
        
        $query = $this->db->get('user_money_log')->result_array();
        return $query;
    }
    
    //修改密码
    public function update_password($password)
    {
        $data = array(
	        'password' => md5($password['password']),
	        'update_time' => time(),
	    );
	    
	    $where = array('id' => $this->session->userdata('id'));
	    $res = $this->update('user', $data, $where);
	    
	    return $res;
    }
    
    //验证密码
    public function check_password($old_password, $user_id)
    {
        $this->db->select('password');
        $this->db->where('id', $user_id);
        $res = $this->db->get('user')->row_array();
        //1密码正确  0密码错误
        if($res['password']==md5($old_password))
        {
            return 1;
        }else
        {
            return 0;
        }
    }
}
