<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index($time=0, $start_time=0, $end_time=0, $order_field='id', $order_type='desc', $cur_page=1)
	{
	    $cookie_impressions = $this->input->cookie('impressions');
	    $cookie_clicks = $this->input->cookie('clicks');
	    $cookie_click_rate = $this->input->cookie('click_rate');
	    $cookie_costs = $this->input->cookie('costs');
	    $cookie_click_cost = $this->input->cookie('click_cost');
	    
	    $this->load->model('adinfo_model');
	    $this->load->model('ad_report_model');
	    $this->layout_data['title'] = "渠道系统首页";
	    $conditions = array();
	    $report_time = '';
	    
	    if(!$time)
	    {
	        $time = date('Y-m-d 00:00:00');
	    }

	    $data['order_field'] = $order_field;  //排序字段
	    $data['order_type'] = $order_type;  //排序类型
	    $this->order_field = $order_field;
	    $this->order_type = $order_type;
	    
	    if(!$this->input->post())
	    {
	        $data['data_time'] = $report_time = strtotime(urldecode($time));
	        $data['start_time'] = urldecode($start_time);
	        $data['end_time'] = urldecode($end_time);
	    }
	    
	    if($this->input->post())
	    {
	        $post = $this->input->post();
	        if(isset($post['cur_page']) && $post['cur_page'])
	        {
	            $data['cur_page'] = $cur_page = $post['cur_page'];
	        }
	        
	        $time = 30*24*60*60;
	        //记录用户查询数据类型的COOKIE、数据时段的信息
	        if(isset($post['impressions']) && $post['impressions'])
	        {
	            $cookie['impressions'] = array(
	                'name'   => 'impressions',
	                'value'  => $post['impressions'],
	                'expire' => $time,
	            );
	            $this->input->set_cookie($cookie['impressions']);
	            $cookie_impressions = $post['impressions'];
	        } 
	        else
	        {
	            $cookie['impressions'] = array(
	                'name'   => 'impressions',
	                'value'  => '',
	                'expire' => '',
	            );
	            $this->input->set_cookie($cookie['impressions']);
	            $cookie_impressions ='';
	        }
	        if(isset($post['clicks']) && $post['clicks'])
	        {
	            $cookie['clicks'] = array(
	                'name'   => 'clicks',
	                'value'  => $post['clicks'],
	                'expire' => $time,
	            );
	            $this->input->set_cookie($cookie['clicks']);
	            $cookie_clicks = $post['clicks'];
	        }
	        else
	        {
	            $cookie['clicks'] = array(
	                'name'   => 'clicks',
	                'value'  => '',
	                'expire' => '',
	            );
	            $this->input->set_cookie($cookie['clicks']);
	            $cookie_clicks = '';
	        }
	        if(isset($post['click_rate']) && $post['click_rate'])
	        {
	            $cookie['click_rate'] = array(
	                'name'   => 'click_rate',
	                'value'  => $post['click_rate'],
	                'expire' => $time,
	            );
	            $this->input->set_cookie($cookie['click_rate']);
	            $cookie_click_rate = $post['click_rate'];
	        }
	        else
	        {
	            $cookie['click_rate'] = array(
	                'name'   => 'click_rate',
	                'value'  => '',
	                'expire' => '',
	            );
	            $this->input->set_cookie($cookie['click_rate']);
	            $cookie_click_rate = '';
	        }
	        if(isset($post['costs']) && $post['costs'])
	        {
	            $cookie['costs'] = array(
	                'name'   => 'costs',
	                'value'  => $post['costs'],
	                'expire' => $time,
	            );
	            $this->input->set_cookie($cookie['costs']);
	            $cookie_costs = $post['costs'];
	        }
	        else
	        {
	            $cookie['costs'] = array(
	                'name'   => 'costs',
	                'value'  => '',
	                'expire' => '',
	            );
	            $this->input->set_cookie($cookie['costs']);
	            $cookie_costs = '';
	        }
	        if(isset($post['click_cost']) && $post['click_cost'])
	        {
	            $cookie['click_cost'] = array(
	                'name'   => 'click_cost',
	                'value'  => $post['click_cost'],
	                'expire' => $time,
	            );
	            $this->input->set_cookie($cookie['click_cost']);
	            $cookie_click_cost = $post['click_cost'];
	        }
	        else
	        {
	            $cookie['click_cost'] = array(
	                'name'   => 'click_cost',
	                'value'  => '',
	                'expire' => '',
	            );
	            $this->input->set_cookie($cookie['click_cost']);
	            $cookie_click_cost = '';
	        }
	        if(isset($post['time']) && $post['time'])
	        {
	            $data['data_time'] = $report_time = strtotime($post['time']);
	        }
	        
	        if(isset($post['start_time']) && $post['start_time'])
	        {
	            $data['start_time'] = $post['start_time'];
	        }
	        if(isset($post['end_time']) && $post['end_time'])
	        {
	            $data['end_time'] = $post['end_time'];
	        }
	    }
	    //如果数据类型都没选中则全选
	    if(!$cookie_impressions && !$cookie_clicks && !$cookie_click_rate && !$cookie_costs && !$cookie_click_cost)
	    {
	        $cookie_impressions = $cookie_clicks = $cookie_click_rate = $cookie_costs = $cookie_click_cost = 1;
	    }
	    
	    if($cookie_impressions)
	    {
	       $data['impressions'] = $cookie_impressions;
	    }
	    if($cookie_clicks)
	    {
	        $data['clicks'] = $cookie_clicks;
	    }
	    if($cookie_click_rate)
	    {
	        $data['click_rate'] = $cookie_click_rate;
	    }
	    if($cookie_costs)
	    {
	        $data['costs'] = $cookie_costs;
	    }
	    if($cookie_click_cost)
	    {
	        $data['click_cost'] = $cookie_click_cost;
	    }
	     

	        if($order_field=='impressions')
	        {
	            $data['impressions_sort'] = $order_type;
	        }
	        if($order_field=='click')
	        {
	            $data['click_sort'] = $order_type;
	        }
	        if($order_field=='ctr')
	        {
	            $data['ctr_sort'] = $order_type;
	        }
	        if($order_field=='cost')
	        {
	            $data['cost_sort'] = $order_type;
	        }
	        if($order_field=='average_cost')
	        {
	            $data['average_cost_sort'] = $order_type;
	        }

	        //分页配置
	        $this->load->library('pagination');
	        $this->load->config('pagination');
	        $config = $this->config->item('pagination_common_list');
	        $config['base_url'] = base_url()."/home/index/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
	        $offset = $config['per_page'] * ($cur_page - 1);
	        	
	        //获取广告资源数据
	        $data['adinfo'] = $this->adinfo_model->get_all_area($this->session->userdata('id'));
	        $config['total_rows'] = count($data['adinfo']);
	        	
	        $this->pagination->initialize($config);
	        $data['page'] = $this->pagination->create_links();
	        	
	        $data['url'] = base_url()."/home/index/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
	        $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	        $data['cur_page'] = $cur_page;
	        	
	        //获取广告资源数据
	        if($data['adinfo'])
	        {
    	        $adinfo_id = array();
    	        foreach ($data['adinfo'] as $k=>$val)
    	        {
    	            if(isset($data['start_time']) && $data['start_time'] && isset($data['end_time']) && $data['end_time'])
    	            {
    	                $report = $this->ad_report_model->get_report_time($val['ad_area_id'], $data['start_time'], $data['end_time']);
    	                $data['adinfo'][$k]['click'] = $report['clicks']?$report['clicks']:0;
    	                $data['adinfo'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
    	                $data['adinfo'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
    	                $ctr = 0;
    	                if($report['impressions']>0)
    	                {
    	                    $ctr = round($report['clicks']/$report['impressions'],2);
    	                }
    	                $average_cost = 0;
    	                if($report['clicks']>0)
    	                {
    	                    $average_cost = round($report['cost']/$report['clicks'],2);
    	                }
    	                $data['adinfo'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	                $data['adinfo'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
    	            }
    	            else
    	            {
    	                $report = $this->ad_report_model->get_report($val['ad_area_id'], $report_time);
    	                $data['adinfo'][$k]['click'] = $report['clicks']?$report['clicks']:0;
    	                $data['adinfo'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
    	                $data['adinfo'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
    	                $ctr = 0;
    	                if($report['impressions']>0)
    	                {
    	                    $ctr = round($report['clicks']/$report['impressions'],2);
    	                }
    	                $average_cost = 0;
    	                if($report['clicks']>0)
    	                {
    	                    $average_cost = round($report['cost']/$report['clicks'],2);
    	                }
    	                $data['adinfo'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	                $data['adinfo'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
    	            }
    	            $adinfo_id[$k] = $val['ad_area_id'];
    	        }
    	        //广告投放效果趋势图数据
    	        $data['report_field'] = array();
    	        if($report_time < strtotime('-30 day') && $report_time > strtotime('-31 day'))
    	        {
    	            for($s=1;$s<=30;$s++)
    	            {
    	                $s_hours = ($s - 1) * 86400;
    	                $e_hours = $s * 86400;
    	                $t_time = strtotime(date('Y-m-d 00:00:00', strtotime('-30 day')));
    	                $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $t_time + $s_hours, $t_time + $e_hours);
    	            }
    	        }
    	        if($report_time < strtotime('-7 day') && $report_time > strtotime('-30 day'))
    	        {
    	            for($s=1;$s<=7;$s++)
    	            {
    	                $s_hours = ($s - 1) * 86400;
    	                $e_hours = $s * 86400;
    	                $t_time = strtotime(date('Y-m-d 00:00:00', strtotime('-7 day')));
    	                $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $t_time + $s_hours, $t_time + $e_hours);
    	            }
    	        }
    	        if($report_time < strtotime('-1 day') && $report_time > strtotime('-7 day'))
    	        {
    	            for($s=1;$s<=24;$s++)
    	            {
    	                $s_hours = ($s - 1) * 3600;
    	                $e_hours = $s * 3600;
    	                $t_time = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
    	                $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $t_time + $s_hours, $t_time + $e_hours);
    	            }
    	        }
    	        if($report_time < time() && $report_time > strtotime('-1 day'))
    	        {
    	            for($s=1;$s<=24;$s++)
    	            {
    	                $s_hours = ($s - 1) * 3600;
    	                $e_hours = $s * 3600;
    	                $t_time = strtotime(date('Y-m-d 00:00:00'));
    	                $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $t_time + $s_hours, $t_time + $e_hours);
    	            }
    	        }
    	        if(isset($data['start_time']) && $data['start_time'] && isset($data['end_time']) && $data['end_time'])
    	        {
    	            $data['report_field'] = array();
    	            $date1 = strtotime($data['start_time']);
    	            $date2 = strtotime($data['end_time']);
    	            $data['custom_time'] = $custom_time = round(($date2 - $date1)/3600/24);  //时间段间隔天数
    	            if($custom_time <= 30 && $custom_time > 7)
    	            {
    	                for($i=0;$i<=$custom_time - 1;$i++)
    	                {
    	                    $s_day = $i * 86400;
    	                    $e_day = ($i + 1) * 86400;
    	                    $s_time = strtotime($data['start_time']) + $s_day;  //开始的时间戳
    	                    $e_time = strtotime($data['start_time']) + $e_day;  //后一天时间戳
    	                    $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $s_time, $e_time);
    	                }
    	            }
    	            if($custom_time <= 7 && $custom_time > 1)
    	            {
    	                $c_day = 24/4 * $custom_time;
    	                for($i=0;$i<=$c_day;$i++)
    	                {
    	                    $s_day = $i * 14400;
    	                    $e_day = ($i + 1) * 14400;
    	                    $s_time = strtotime($data['start_time']) + $s_day;  //开始的时间戳
    	                    $e_time = strtotime($data['start_time']) + $e_day;  //后四个小时时间戳
    	                    $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id, $s_time, $e_time);
    	                }
    	            }
    	            if($custom_time <= 1)
    	            {
    	                $data['custom_hours'] = $custom_hours = round(($date2 - $date1)/3600);  //时间段间隔小时数
    	                for($i=0;$i<=$custom_hours;$i++)
    	                {
    	                    $s_day = $i * 3600;
    	                    $e_day = ($i + 1) * 3600;
    	                    $s_time = strtotime($data['start_time']) + $s_day;  //开始的时间戳
    	                    $e_time = strtotime($data['start_time']) + $e_day;  //后一个小时时间戳
    	                    $data['report_field'][] = $this->ad_report_model->get_report_type($adinfo_id,$s_time, $e_time);
    	                }
    	            }
    	        }
    	        // usort 排序
    	        if(count($data['adinfo']) > 10)
    	        {
    	            $data['adinfo'] = $adinfo_array = array_chunk($data['adinfo'], 10);
    	            $adinfo_array = $data['adinfo'][$cur_page - 1];
    	        }
    	        else{
    	            $adinfo_array = $data['adinfo'];
    	        }
/*    	        usort($adinfo_array,function($a,$b)
    	        {
    	            $value_a = $value_b = 0;
    	            $value_a = $a[$this->order_field];
    	            $value_b = $b[$this->order_field];
    	            if($this->order_type!='asc')
    	            {
    	                list($value_a, $value_b) = array($value_b, $value_a);
    	            }
    	            if ($value_a == $value_b) {
    	                return 0;
    	            }
    	            return ($value_a < $value_b) ? -1 : 1;
    	        });*/
    	        $data['adinfo'] = $adinfo_array;
    	    }
	    
	    //账务情况
/*	    $today = date('Y-m-d 00:00:00');
	    $data['money'] = $this->user_money_model->get_money($today, $this->session->userdata('id'));
	    $data['spend'] = '';
	    $data['user_money'] = $this->user_money_model->get_my_money();
	    
	    foreach ($data['money'] as $k=>$val)
	    {
	        if($val['type']==1)
	        {
	           $data['spend'] += $val['money'];  //花费支出
	        }
	    }
	    //账户金额字符串处理
	    $spend = sprintf("%1\$.2f", $data['spend']/100);
	    $spend_num = substr($spend, 0, strrpos($spend, '.'));
	    $spend_str = strrev(chunk_split(strrev($spend_num), 3,','));
	    $data['spend_money'] = substr($spend_str, 1).substr($spend, -3);  //当日花费
	    
	    $user_money = sprintf("%1\$.2f", $data['user_money']['money']/100);
	    $user_money_nums = substr($user_money, 0, strrpos($user_money, '.'));
	    $user_money_strs = strrev(chunk_split(strrev($user_money_nums), 3,','));
	    $data['remain_sum'] = substr($user_money_strs, 1).substr($user_money, -3);  //账户余额*/
	    
		$this->render('home/index', $data);
	}
}
