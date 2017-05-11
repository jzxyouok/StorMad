<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_report_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_adinfo($ad_id=0)
	{
		$ad_id = (int) $ad_id;
		if($ad_id==0)
		{
			return array();
		}	
		$this->db->select('id,campaign_id,adgroup_id,user_id');
		$this->db->where('id', $ad_id);
		$query = $this->db->get('adinfo');
	
		return $query->row_array();
	}

	public function create_ad_report($report)
	{
		$where = array('ad_id'=>$report['ad_id'],'start_time'=>$report['start_time'],'end_time'=>$report['end_time']);
		if($this->check_ad_report($where))
		{
			// 更新
			$this->update('ad_report',$report,$where);
		}
		else
		{
			// 添加
			$this->insert('ad_report',$report);
		}
		
	}

	public function create_user_money_log($log)
	{
		$where = array('user_id'=>$log['user_id'],'add_time'=>$log['add_time'],'comment'=>$log['comment']);
        if($this->check_user_money_log($where))
        {
            // 更新
			unset($log['remain_sum']);//更新时不更新余额
            $this->update('user_money_log',$log,$where);
        }
        else
        {
            // 添加
            $this->insert('user_money_log',$log);
        }

	}

	public function check_ad_report($where)
	{
		$ad_id = (int)$where['ad_id'];
		$start_time = (int)$where['start_time'];
		$end_time = (int)$where['end_time'];
		$this->db->where('ad_id', $ad_id);
		$this->db->where('start_time', $start_time);
		$this->db->where('end_time', $end_time);
		$this->db->from('ad_report');
		return $this->db->count_all_results();
	}

	public function create_area_report($report)
	{
		$where = array('area_id'=>$report['area_id'],'start_time'=>$report['start_time'],'end_time'=>$report['end_time']);
		if($this->check_area_report($where))
		{
			// 更新
			$this->update('area_report',$report,$where);
		}
		else
		{
			// 添加
			$this->insert('area_report',$report);
		}
		
	}

	public function check_area_report($where)
	{
		$area_id = (int)$where['area_id'];
		$start_time = (int)$where['start_time'];
		$end_time = (int)$where['end_time'];
		$this->db->where('area_id', $area_id);
		$this->db->where('start_time', $start_time);
		$this->db->where('end_time', $end_time);
		$this->db->from('area_report');
		return $this->db->count_all_results();
	}

	public function check_user_money_log($where)
	{
		$user_id = (int)$where['user_id'];
		$add_time = (int)$where['add_time'];
		$comment = (int)$where['comment'];
		$this->db->where('user_id', $user_id);
		$this->db->where('add_time', $add_time);
		$this->db->where('comment', $comment);
		$this->db->from('user_money_log');
		return $this->db->count_all_results();
	}

}
