<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_logs_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct('logs');
	}
	
	public function get_logs($type='epr',$start_time=0,$end_time=0)
	{
		$log_table = $type=='epr' ? 'epr_logs' : 'click_logs';
		$start_time = (int) $start_time;
		$end_time = (int) $end_time;
		if($start_time==0 || $end_time==0)
		{
			return array();
		}
		
		if($type=="epr")
		{
			$field="area_id,ad_id";	
		}
		else
		{
			$field="area_id,campaign_id,adgroup_id,ad_id,cost";		
		}
		
		$this->db->select($field);
		$this->db->where('time>=', $start_time);
		$this->db->where('time<', $end_time);
		$query = $this->db->get($log_table);

		return $query->result();
	}

	public function bak_get_logs($type='epr',$start_time=0,$end_time=0)
	{
		$log_table = $type=='epr' ? 'epr_logs_bak' : 'click_logs_bak';
		$start_time = (int) $start_time;
		$end_time = (int) $end_time;
		if($start_time==0 || $end_time==0)
		{
			return array();
		}
		
		if($type=="epr")
		{
			$field="area_id,ad_id";	
		}
		else
		{
			$field="area_id,campaign_id,adgroup_id,ad_id,cost";		
		}
		
		$this->db->select($field);
		$this->db->where('time>=', $start_time);
		$this->db->where('time<', $end_time);
		$query = $this->db->get($log_table);

		return $query->result();
	}

	/**
	 * 获取当日已产生金额
	 * @param $conditon array 条件数据，campaign_id：推广计划id，adgroup_id：推广组id
	 */
	function get_used_sum($condition)
	{
		if(!array_key_exists('campaign_id',$condition) && !array_key_exists('adgroup_id',$condition))
		{
			return 0;
		}
		$this->db->select('sum(cost) used_sum');
		$this->db->from('click_logs');
		$start_time = strtotime(date('Y-m-d'));
		$end_time = time();
		$this->db->where('time>=', $start_time);
		$this->db->where('time<', $end_time);
		if(isset($condition['campaign_id']) && $condition['campaign_id']>0)
		{
			$this->db->where('campaign_id',$condition['campaign_id']);
		}
		if(isset($condition['adgroup_id']) && $condition['adgroup_id']>0)
		{
			$this->db->where('adgroup_id',$condition['adgroup_id']);
		}
		$result = $this->db->get()->row_array();
		return $result['used_sum'] ? $result['used_sum'] : 0;
	}

}
