<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_report_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取推广计划、推广组、广告投放信息
    public function get_report($campaign_id, $adgroup_id, $ad_id, $time)
    {
        $this->db->select('sum(clicks) clicks, sum(impressions) impressions, sum(cost) cost');
        if($campaign_id)
        {
            $this->db->where('campaign_id', $campaign_id);
        }
        if($adgroup_id)
        {
            $this->db->where('adgroup_id', $adgroup_id);
        }
        if($ad_id)
        {
            $this->db->where('ad_id', $ad_id);
        }
        if($time)
        {
            if($time >= strtotime(date('Y-m-d 00:00:00')))
            {
                $where = array('end_time >=' => strtotime(date('Y-m-d 00:00:00')));
            }
            elseif($time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 day'))))
            {
                $where = array('end_time >=' => $time, 'end_time <' => strtotime(date('Y-m-d 00:00:00')));
            }
            elseif($time >= strtotime(date('Y-m-d 00:00:00', strtotime('-7 day'))))
            {
                $where = array('end_time >=' => $time, 'end_time <' => strtotime(date('Y-m-d 00:00:00')));
            }
            elseif($time >= strtotime(date('Y-m-d 00:00:00', strtotime('-30 day'))))
            {
                $where = array('end_time >=' => $time, 'end_time <' => strtotime(date('Y-m-d 00:00:00')));
            }
            $this->db->where($where);
        }
        
        $query = $this->db->get('ad_report')->row_array();
        return $query;
    }
    
    //获取推广计划、推广组、广告投放总信息
    public function get_report_type($campaign_id, $adgroup_id, $ad_id, $type, $s_time, $e_time)
    {
        $this->db->select('sum(clicks) clicks, sum(impressions) impressions, sum(cost) cost');
        if($type=='campaign')
        {
            $this->db->where_in('campaign_id', $campaign_id);
        }
        if($type=='adgroup')
        {
            $this->db->where_in('adgroup_id', $adgroup_id);
        }
        if($type=='adinfo')
        {
            $this->db->where_in('ad_id', $ad_id);
        }
        if($s_time && $e_time)
        {
            $where = array('end_time >=' => $s_time, 'end_time <' => $e_time,);
            $this->db->where($where);
        }
    
        $query = $this->db->get('ad_report')->row_array();
        return $query;
    }
    
    //推广计划、推广组、广告统计数据时间段搜索
    public function get_report_time($campaign_id, $adgroup_id, $ad_id, $start_time, $end_time)
    {
        $this->db->select('sum(clicks) clicks, sum(impressions) impressions, sum(cost) cost');
        if($campaign_id)
        {
            $this->db->where('campaign_id', $campaign_id);
        }
        if($adgroup_id)
        {
            $this->db->where('adgroup_id', $adgroup_id);
        }
        if($ad_id)
        {
            $this->db->where('ad_id', $ad_id);
        }
        if($start_time && $end_time)
        {
            $where = array('end_time >=' => strtotime($start_time), 'end_time <' => strtotime($end_time));
            $this->db->where($where);
        }
    
        $query = $this->db->get('ad_report')->row_array();
        return $query;
    }
}
