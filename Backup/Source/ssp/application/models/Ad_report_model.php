<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_report_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取推广计划、推广组、广告投放信息
    public function get_report($area_id, $time)
    {
        $this->db->select('sum(clicks) clicks, sum(invalid_clicks) invalid_clicks, sum(impressions) impressions, sum(cost) cost');

        if($area_id)
        {
            $this->db->where('area_id', $area_id);
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
        
        $query = $this->db->get('area_report')->row_array();
        return $query;
    }
    
    //获取推广计划、推广组、广告投放总信息
    public function get_report_type($area_id, $s_time, $e_time)
    {
        $this->db->select('sum(clicks) clicks, sum(invalid_clicks) invalid_clicks, sum(impressions) impressions, sum(cost) cost');

        if($area_id)
        {
            $this->db->where_in('area_id', $area_id);
        }
        if($s_time && $e_time)
        {
            $where = array('end_time >=' => $s_time, 'end_time <' => $e_time,);
            $this->db->where($where);
        }
    
        $query = $this->db->get('area_report')->row_array();
        return $query;
    }
    
    //推广计划、推广组、广告统计数据时间段搜索
    public function get_report_time($area_id, $start_time, $end_time)
    {
        $this->db->select('sum(clicks) clicks, sum(invalid_clicks) invalid_clicks, sum(impressions) impressions, sum(cost) cost');

        if($area_id)
        {
            $this->db->where('area_id', $area_id);
        }
        if($start_time && $end_time)
        {
            $where = array('end_time >=' => strtotime($start_time), 'end_time <' => strtotime($end_time));
            $this->db->where($where);
        }
    
        $query = $this->db->get('area_report')->row_array();
        return $query;
    }
}
