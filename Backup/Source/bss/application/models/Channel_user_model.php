<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_user_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_user_count($conditions)
    {
        $this->make_condition($conditions);
        $query = $this->db->get('channel')->num_rows();  //获取用户资源数据
        return $query;
    }
    
    private function make_condition($conditions)
    {
		if(isset($conditions['distribution_id']) && $conditions['distribution_id'])
        {
            $this->db->where('distribution_id', $conditions['distribution_id']);
        }
		
        if(isset($conditions['distribution_name']) && $conditions['distribution_name'])
        {
            $this->db->like('distribution_name', $conditions['distribution_name']);
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
	    
	    $query = $this->db->get('channel');
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
	    $this->db->select('user_name, distribution_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('channel');
	    
	    return $query->row_array();
	}
	
	//增加新用户
	public function add_user($user)
	{
	    $data = array(
	        'user_name' => $user['user_name'],
	        'password' => md5($user['password']),
	        'distribution_id' => $user['distribution_id'],
			'distribution_name' => $user['distribution_name'],
	        'comment' => $user['comment'],
	        'add_time' => time(),
	        'update_time' => time(),
	        'update_admin_id' => $this->session->userdata('id'),
			'status' => 1
	    );
		
		
		
	    $res = $this->insert('channel', $data);
	    
	    return $res;
	}
	
	//同步渠道信息到广告接口
	public function sent_channel_info($distribution_id,$distribution_name)
	{
		$rs=$this->http_post("https://openad.stormad.cn/get_channel.php",array('act'=>'add_channel','distribution_id'=>$distribution_id,'distribution_name'=>$distribution_name));

		return $rs;
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
	    $res = $this->update('channel', $data, $where);
	    
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
	    $update_num = $this->update('channel', $data, $where);
	
	    $this->db->select('status');
	    $query = $this->db->get_where('channel', array('id' => $id));
	    return $query->row_array();
	}
	
	//检查用户账号是否存在
	public function check_user($username)
	{
	    $this->db->select('user_name');
	    $this->db->where('user_name', $username);
	    $res = $this->db->get('channel')->row_array();
	    
	    return $res;
	}
	
	//检查渠道名称是否存在
	public function check_distribution_name($distribution_name)
	{
	    $this->db->select('distribution_name');
	    $this->db->where('distribution_name', $distribution_name);
	    $res = $this->db->get('channel')->row_array();
	    
	    return $res;
	}
	
	//生成渠道号
	public function create_distribution_id()
	{
		$str='';
				
		//检查是否有相同的渠道号，若相同则重新生成
		do{
			$str = array_merge(range(0,9),range(0,9)); 
			shuffle($str); 
			$str = implode('',array_slice($str,0,13));
			
			$this->db->select('distribution_id');
	    	$this->db->where('distribution_id', $str);
			$num = $this->db->get('channel')->num_rows();

		}while($num>0);				
		
		return $str; 
	}
	
	private function http_post($url, $param)
	{
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			$aPOST = array ();
			foreach ( $param as $key => $val ) {
				$aPOST [] = $key . "=" . urlencode ( $val );
			}
			$strPOST = join ( "&", $aPOST );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
}
