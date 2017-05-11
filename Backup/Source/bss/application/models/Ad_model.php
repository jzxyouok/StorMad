<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_ad_count($conditions)
	{
		$this->make_condition($conditions);
		$query = $this->db->get()->num_rows();  //获取广告资源数据总数
		return $query;
	}
	
	private function make_condition($conditions)
	{
		$this->db->select('adinfo.id,adinfo.title,adinfo.type,adinfo.content,adinfo.status,adinfo.start_time,adinfo.end_time,adinfo.user_id,ad_size.size_name,ad_size.width,ad_size.height,user.user_name,user.true_name');
		$this->db->from('adinfo');
		$this->db->join('user', 'user.id = adinfo.user_id', 'left');
		$this->db->join('ad_size', 'ad_size.id = adinfo.size_id', 'left');
		$this->db->order_by('adinfo.id', 'DESC');
		if(isset($conditions['user']) && $conditions['user'])
		{
			$this->db->like('user.true_name', $conditions['user']);
		}
		if(isset($conditions['title']) && $conditions['title'])
		{
			$this->db->like('adinfo.title', $conditions['title']);
		}
		if(isset($conditions['type']) && ($conditions['type']))
		{
			$this->db->where('adinfo.type', $conditions['type']);
		}
		if(isset($conditions['id']) && ($conditions['id']))
		{
		    $this->db->where('adinfo.id', $conditions['id']);
		}
		if(isset($conditions['status']) && ($conditions['status'])!=4)
		{
			$this->db->where('adinfo.status', $conditions['status']);
		}
		if(isset($conditions['put_time']) && $conditions['put_time'])
		{
			$time_where = array('start_time <' => strtotime($conditions['put_time']), 'end_time >' => strtotime($conditions['put_time']));
			$this->db->where($time_where);
		}
		if(isset($conditions['page_size']) && isset($conditions['offset']))
		{
			$this->db->limit($conditions['page_size'],$conditions['offset']);
		}
	}
	
	public function get_ad($conditions)
	{
		$this->make_condition($conditions);
		
		$query = $this->db->get()->result_array();  //获取广告资源数据
		return $query;
	}
	
	//查找广告
	public function find_ad($id)
	{
	    $this->db->select('title');
	    $this->db->where('id', $id);
	    $query = $this->db->get('adinfo');
	     
	    return $query->row_array();
	}
	
	//广告审核
	public function audit_ad($status, $id)
	{
	    if($status==1)
	    {
	        $ad_status = 2;
	    }
	    else
	    {
	        $ad_status = $status;
	    }
		$data = array(
			'status' => $ad_status,
			'update_time' => time(),
		);
		
		$where = array('id' => $id);
		$update_num = $this->update('adinfo', $data, $where);
		
		$this->db->select('status');
		$query = $this->db->get_where('adinfo', array('id' => $id));
		return $query->row_array();
	}

    /**
     * 根据广告id，获取此广告详细信息
     * @params $id int 广告ID
     */
    public function get_adinfo_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('adinfo');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * 根据广告规格id，获取此广告规则详细信息
     * @params $id int 广告规则ID
     */
    public function get_size_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('ad_size');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

}
