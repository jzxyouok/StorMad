<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }

    //获取标签分类
    public function get_class()
    {
        $query = $this->db->get_where('ad_scene', array('fid' => 0))->result_array();
        return $query;
    }
    
    //获取标签名
    public function get_scene($fid)
    {
        $query = $this->db->get_where('ad_scene', array('fid' => $fid))->result_array();
        return $query;
    }
    
    //获取用户群资源数据总数
    public function get_customer_count($conditions, $user_id)
    {
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
         
        $query = $this->db->get('customer')->num_rows();
        return $query;
    }
    
	public function get_customer($conditions, $user_id)
	{
	    $this->db->select('id, customer_name');
	    
	    if(isset($conditions['page_size']) && isset($conditions['offset']))
	    {
	        $this->db->limit($conditions['page_size'],$conditions['offset']);
	    }
	    $this->db->where('user_id', $user_id);
	    $this->db->order_by('id', 'DESC');
	    
	    $query = $this->db->get('customer')->result_array();
	    
	    return $query;
	}
	
	//通过用户群ID获取标签场景
	public function get_scene_id($customer_id)
	{
	    $this->db->select('ad_scene.id, ad_scene.scene_name');
	    $this->db->from('customer');
	    $this->db->join('customer_scene', 'customer_scene.customer_id = customer.id', 'left');
	    $this->db->join('ad_scene', 'ad_scene.id = customer_scene.scene_id', 'left');
	    $this->db->where('customer_id', $customer_id);
	    $res = $this->db->get()->result_array();
	    
	    return $res;
	}
	
	//添加、编辑用户群及处理用户群相关数据
	public function add_customer($customer)
	{
	    $customer_data = array(
	        'customer_name' => $customer['customer_name'],
	        'update_time' => time(),
	        'user_id' => $this->session->userdata('id'),
	    );
	    if($customer['id'])
	    {
	        $where = array('id' => $customer['id']);
	        $res = $this->update('customer', $customer_data, $where);
	        $customer_id = $customer['id'];
	        
	        $this->db->where('customer_id', $customer_id);
	        $res = $this->db->delete('customer_scene');
	    }else
	    {
	        $data['add_time'] = time();
	        $res = $this->insert('customer', $customer_data);
	        $customer_id = $this->db->insert_id();
	    }
	    //查询广告的用户群
	    $this->db->select('adinfo.id, adinfo.title');
	    $this->db->from('adinfo');
	    $this->db->join('ad_customers', 'ad_customers.ad_id = adinfo.id', 'left');
	    $this->db->where('ad_customers.customer_id', $customer_id);
	    $adinfo = $this->db->get()->result_array();
	    $scene_price = '';
	    
	    if($customer['scene_id'])
	    {
	        $scene_id = explode(',', $customer['scene_id']);
    	    foreach ($scene_id as $k=>$val)
    	    {
    	        $customer_scene = array(
    	            'customer_id' => $customer_id,
    	            'scene_id' => $val,
    	        );
    	        $this->insert('customer_scene', $customer_scene);
    	    }
    	    
    	    foreach ($scene_id as $k=>$val)
    	    {
    	        //搜索标签所属分类
    	        $this->db->select('fid');
    	        $this->db->or_where('id', $val);
    	    }
    	    $scene_fid = $this->db->get('ad_scene')->result_array();
    	    foreach ($scene_fid as $k=>$val)
    	    {
    	        $this->db->or_where('id', $val['fid']);
    	    }
    	    //获取指定标签所属分类的数目和场景价格
    	    $scene_class_num = $this->db->get('ad_scene')->num_rows(); 
    	    $scene_price = $scene_class_num*10;
	    }
	    
	    //更新广告的场景价格
	    $adinfo_data = array(
	        'scene_price' => $scene_price,
	        'update_time' => time(),
	    );
	    foreach ($adinfo as $k=>$val)
	    {
	        $adinfo_where = array('id' => $val['id']);
	        $adinfo_scene_price = $this->update('adinfo', $adinfo_data, $adinfo_where);
	    }
	    
	    return $res;
	}
	
	//查找推广组
	public function find_customer($id)
	{
	    $this->db->select('customer_name');
	    $this->db->where('id', $id);
	    $query = $this->db->get('customer');
	
	    return $query->row_array();
	}
	
	//获取要修改的用户群数据
	public function edit_customer($id)
	{
	    $this->db->select('id, customer_name');
	    $this->db->where('id', $id);
	
	    $res = $this->db->get('customer')->row_array();
	
	    return $res;
	}
	public function edit_customer_scene($id)
	{
	    $this->db->select('ad_scene.id as scene_id, ad_scene.scene_name');
	    $this->db->from('customer');
	    $this->db->join('customer_scene', 'customer_scene.customer_id = customer.id', 'left');
	    $this->db->join('ad_scene', 'ad_scene.id = customer_scene.scene_id', 'left');
	    $this->db->where('customer.id', $id);
	     
	    $res = $this->db->get()->result_array();
	
	    return $res;
	}
	
	//删除用户群
	public function del_customer($id)
	{
	    $this->db->where('id', $id);
	    $res = $this->db->delete('customer');
	    
	    $this->db->where('customer_id', $id);
	    $this->db->delete('customer_scene');
	    
	    $this->db->where('customer_id', $id);
	    $this->db->delete('ad_customers');
	
	    return $res;
	}
	
	//获取用户群的总数
	public function get_customer_num()
	{
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('customer')->num_rows();
	
	    return $res;
	}
	
	//修改用户群名称
	public function edit_customer_name($id, $name)
	{
	    $data = array(
	        'customer_name' => $name,
	        'update_time' => time(),
	    );
	    $where = array('id' => $id);
	    $this->update('customer', $data, $where);
	
	    $this->db->select('customer_name');
	    $res = $this->db->get_where('customer', array('id' => $id));
	    return $res->row_array();
	}
	
	//检查用户群名称是否存在
	public function check_customer($customer_name)
	{
	    $this->db->select('customer_name');
	    $this->db->where('customer_name', $customer_name);
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('customer')->row_array();
	
	    return $res;
	}
}
