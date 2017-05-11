<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    
	public function report_list($report_fid=0, $report_type='campaign', $time=0, $start_time=0, $end_time=0, $order_field='id', $order_type='desc', $cur_page=1)
	{
		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}
		
	    $cookie_impressions = $this->input->cookie('impressions');
	    $cookie_clicks = $this->input->cookie('clicks');
	    $cookie_click_rate = $this->input->cookie('click_rate');
	    $cookie_costs = $this->input->cookie('costs');
	    $cookie_click_cost = $this->input->cookie('click_cost');
	    
	    $this->load->model('campaign_model');
	    $this->load->model('adgroup_model');
	    $this->load->model('adinfo_model');
	    $this->load->model('ad_report_model');
	    $this->load->model('user_money_model');
	    $this->layout_data['title'] = "报表";
	    $conditions = array();
	    $report_time = '';
	    
	    if(!$time)
	    {
	        $time = date('Y-m-d 00:00:00');
	    }
	    
	    $data['report_fid'] = $report_fid;
	    $data['report_type'] = $report_type;  //报表类别
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
	        if(isset($post['report_fid']) && $post['report_fid'])
	        {
	            $data['report_fid']  = $report_fid = $post['report_fid'];
	        }
	        if(isset($post['report_type']) && $post['report_type'])
	        {
	            $data['report_type'] = $post['report_type'];
	        }
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
	    
	    if($data['report_type']=='campaign')
	    {
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
	        $data['campaign'] = $this->campaign_model->get_campaign($conditions, $this->session->userdata('id'));
	        
    	    //获取推广计划资源数据
    	    if($data['campaign'])
    	    {
        	    foreach ($data['campaign'] as $k=>$val)
        	    {
        	        if(isset($data['start_time']) && $data['start_time'] && isset($data['end_time']) && $data['end_time'])
        	        {
        	            $report = $this->ad_report_model->get_report_time($val['id'], 0, 0, $data['start_time'], $data['end_time']);
        	            $data['campaign'][$k]['click'] = $report['clicks']?$report['clicks']:0;
        	            $data['campaign'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
        	            $data['campaign'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
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
        	            $data['campaign'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
        	            $data['campaign'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
        	        }
        	        else 
        	        {
        	            $report = $this->ad_report_model->get_report($val['id'], 0, 0, $report_time);
        	            $data['campaign'][$k]['click'] = $report['clicks']?$report['clicks']:0;
        	            $data['campaign'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
        	            $data['campaign'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
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
        	            $data['campaign'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
        	            $data['campaign'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
                    }
        	    }
        	    // usort 排序
        	    if(count($data['campaign']) > 10)
        	    {
        	        $data['campaign'] = $campaign_array = array_chunk($data['campaign'], 10);
        	        $campaign_array = $data['campaign'][$cur_page - 1];
        	    }
        	    else{
        	        $campaign_array = $data['campaign'];
        	    }
        	    usort($campaign_array,function($a,$b)
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
        	    });
        	    $data['campaign'] = $campaign_array;
    	    }
	    }
	    
	    if($data['report_type']=='adgroup')
	    {
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
	        if($report_fid)
	        {
	            $conditions['campaign_id'] = $report_fid;
	        }
	        //分页配置
    	    $this->load->library('pagination');
    	    $this->load->config('pagination');
    	    $config = $this->config->item('pagination_common_list');
    	    $config['base_url'] = base_url()."/report/report_list/{$report_fid}/{$report_type}/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
    	    $offset = $config['per_page'] * ($cur_page - 1);
    	     
    	    $data['adgroup'] = $this->adgroup_model->get_adgroup($conditions, $this->session->userdata('id'));
    		$config['total_rows'] = count($data['adgroup']);
    	     
    	    $this->pagination->initialize($config);
    	    $data['page'] = $this->pagination->create_links();
    	    
    	    $data['url'] = base_url()."/report/report_list/{$report_fid}/{$report_type}/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
    	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
    	    $data['cur_page'] = $cur_page;
    	    
	        //获取推广组资源数据
	        if($data['adgroup'])
	        {
    	        foreach ($data['adgroup'] as $k=>$val)
    	        {
    	            if(isset($data['start_time']) && $data['start_time'] && isset($data['end_time']) && $data['end_time'])
    	            {
    	                $report = $this->ad_report_model->get_report_time(0, $val['id'], 0, $data['start_time'], $data['end_time']);
    	                $data['adgroup'][$k]['click'] = $report['clicks']?$report['clicks']:0;
    	                $data['adgroup'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
    	                $data['adgroup'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
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
    	                $data['adgroup'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	                $data['adgroup'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
    	            }
    	            else
    	            {
    	                $report = $this->ad_report_model->get_report(0, $val['id'], 0, $report_time);
    	                $data['adgroup'][$k]['click'] = $report['clicks']?$report['clicks']:0;
    	                $data['adgroup'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
    	                $data['adgroup'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
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
    	                $data['adgroup'][$k]['ctr'] = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	                $data['adgroup'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
    	            }
    	        }
    	        // usort 排序
        	    if(count($data['adgroup']) > 10)
        		{
            	    $data['adgroup'] = $adgroup_array = array_chunk($data['adgroup'], 10);
            	    $adgroup_array = $data['adgroup'][$cur_page - 1];
        		}
        		else
        		{
        		    $adgroup_array = $data['adgroup'];
        		}
    	        usort($adgroup_array,function($a,$b)
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
    	        });
    	        $data['adgroup'] = $adgroup_array;
	        }
	    }
	    
	    if($data['report_type']=='adinfo')
	    {
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
	        if($report_fid)
	        {
	            $conditions['adgroup_id'] = $report_fid;
	        }
	        //分页配置
    	    $this->load->library('pagination');
    	    $this->load->config('pagination');
    	    $config = $this->config->item('pagination_common_list');
    	    $config['base_url'] = base_url()."/report/report_list/{$report_fid}/{$report_type}/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
    	    $offset = $config['per_page'] * ($cur_page - 1);
    	    
    	    //获取广告资源数据
    	    $data['adinfo'] = $this->adinfo_model->get_adinfo($conditions, $this->session->userdata('id'));
    	    $config['total_rows'] = count($data['adinfo']);
    	    
    	    $this->pagination->initialize($config);
    	    $data['page'] = $this->pagination->create_links();
    	    
    	    $data['url'] = base_url()."/report/report_list/{$report_fid}/{$report_type}/{$time}/{$start_time}/{$end_time}/{$order_field}/{$order_type}/";
    	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
    	    $data['cur_page'] = $cur_page;
    	    
	        //获取广告资源数据
	        if($data['adinfo'])
	        {
    	        foreach ($data['adinfo'] as $k=>$val)
    	        {
    	            if(isset($data['start_time']) && $data['start_time'] && isset($data['end_time']) && $data['end_time'])
    	            {
    	                $report = $this->ad_report_model->get_report_time(0, 0, $val['id'], $data['start_time'], $data['end_time']);
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
    	                $report = $this->ad_report_model->get_report(0, 0, $val['id'], $report_time);
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
    	        usort($adinfo_array,function($a,$b)
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
    	        });
    	        $data['adinfo'] = $adinfo_array;
	        }
	    }
	    
		$this->render('report/report_list', $data);
	}
	
	//下载报表数据
	public function download($report_fid=0, $report_type='campaign', $time=0, $start_time=0, $end_time=0)
	{
	    header('Content-Type: text/comma-separated-values;charset=gb2312;');
	    header('Content-Encoding: none');  //内容不加密，gzip等，可选
	    header('Accept-Renges:bytes');
	    
	    $report_time = strtotime(urldecode($time));
	    $start_time = urldecode($start_time);
	    $end_time = urldecode($end_time);
	    
	    if($report_time)
	    {
	        if($report_time >= strtotime(date('Y-m-d 00:00:00')))
	        {
	            header('Content-Disposition: attachment; filename="'.$report_type.date('YmdHis').'.csv"');
	        }
	        else 
	        {
	            header('Content-Disposition: attachment; filename="'.$report_type.date('Ymd000000', strtotime('-1 day')).'.csv"');
	        }
	    }
	    elseif($start_time && $end_time)
	    {
	        header('Content-Disposition: attachment; filename="'.$report_type.date('YmdHis', strtotime($end_time)).'.csv"');
	    }
	    
	    if($report_type=='campaign')
	    {
	        $this->load->model('campaign_model');
	        $this->load->model('ad_report_model');
    	    $conditions = array();
    	    $campaign = $this->campaign_model->get_campaign($conditions, $this->session->userdata('id'));
    	    
    	    print mb_convert_encoding("推广计划名称,日限额(元),花费,平均点击花费,展现量,点击量,点击率,状态,日期","GBK","UTF-8");
    	    print "\n";
    	    
    	    foreach ($campaign as $k=>$val) {
    	        if(isset($start_time) && $start_time && isset($end_time) && $end_time)
    	        {
    	            $report = $this->ad_report_model->get_report_time($val['id'], 0, 0, $start_time, $end_time);
    	            $click = $report['clicks']?$report['clicks']:0;
    	            $impressions = $report['impressions']?$report['impressions']:0;
    	            $cost = sprintf("%1\$.2f", $report['cost']/100);
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
    	            $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	            $avg_cost = sprintf("%1\$.2f", $average_cost/100);
    	            $report_date = $start_time.'到'.$end_time;
    	        }
    	        else
    	        {
    	            $report = $this->ad_report_model->get_report($val['id'], 0, 0, $report_time);
    	            $click = $report['clicks']?$report['clicks']:0;
    	            $impressions = $report['impressions']?$report['impressions']:0;
    	            $cost = sprintf("%1\$.2f", $report['cost']/100);
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
    	            $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
    	            $avg_cost = sprintf("%1\$.2f", $average_cost/100);
    	            if($report_time >= strtotime(date('Y-m-d 00:00:00')))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d H:i:s');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 day'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 week'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 month'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	        }
    	        
    	        $campaign_name = $val['campaign_name'];
    	        $day_sum = sprintf("%1\$.2f", $val['day_sum']/100);
    	        
    	        if($val['status']==1)
    	        {
    	            $status = '推广中';
    	        }
    	        else
    	        {
    	            $status = '未推广';
    	        }
    	        print mb_convert_encoding($campaign_name,"GBK","UTF-8").",".$day_sum.",".$cost.",".$avg_cost.",".$impressions.",".$click.",".$cli_ctr.",".mb_convert_encoding($status,"GBK","UTF-8").",".mb_convert_encoding($report_date,"GBK","UTF-8")."\n";
    	    }
    	    exit;
	    }
	    
	    if($report_type=='adgroup')
	    {
	        $this->load->model('adgroup_model');
	        $this->load->model('ad_report_model');
	        $conditions = array();
	        if($report_fid)
	        {
	            $conditions['campaign_id'] = $report_fid;
	        }
	        $adgroup = $this->adgroup_model->get_adgroup($conditions, $this->session->userdata('id'));
	        	
	        print mb_convert_encoding("推广组名称,日限额(元),花费,平均点击花费,展现量,点击量,点击率,状态","GBK","UTF-8");
	        print "\n";
	        	
	        foreach ($adgroup as $k=>$val) {
	            if(isset($start_time) && $start_time && isset($end_time) && $end_time)
	            {
	                $report = $this->ad_report_model->get_report_time(0, $val['id'], 0, $start_time, $end_time);
	                $click = $report['clicks']?$report['clicks']:0;
	                $impressions = $report['impressions']?$report['impressions']:0;
	                $cost = sprintf("%1\$.2f", $report['cost']/100);
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
	                $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
	                $avg_cost = sprintf("%1\$.2f", $average_cost/100);
	                $report_date = $start_time.'到'.$end_time;
	            }
	            else
	            {
	                $report = $this->ad_report_model->get_report(0, $val['id'], 0, $report_time);
	                $click = $report['clicks']?$report['clicks']:0;
	                $impressions = $report['impressions']?$report['impressions']:0;
	                $cost = sprintf("%1\$.2f", $report['cost']/100);
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
	                $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
	                $avg_cost = sprintf("%1\$.2f", $average_cost/100);
	                if($report_time >= strtotime(date('Y-m-d 00:00:00')))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d H:i:s');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 day'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 week'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 month'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
	            }
	             
	            $adgroup_name = $val['adgroup_name'];
	            $day_sum = sprintf("%1\$.2f", $val['day_sum']/100);
	             
	            if($val['status']==1)
	            {
	                $status = '推广中';
	            }
	            else
	            {
	                $status = '未推广';
	            }
	            print mb_convert_encoding($adgroup_name,"GBK","UTF-8").",".$day_sum.",".$cost.",".$avg_cost.",".$impressions.",".$click.",".$cli_ctr.",".mb_convert_encoding($status,"GBK","UTF-8").",".mb_convert_encoding($report_date,"GBK","UTF-8")."\n";
	        }
	        exit;
	    }
	    
	    if($report_type=='adinfo')
	    {
	        $this->load->model('adinfo_model');
	        $this->load->model('ad_report_model');
	        $conditions = array();
	        if($report_fid)
	        {
	            $conditions['adgroup_id'] = $report_fid;
	        }
	        $adinfo = $this->adinfo_model->get_adinfo($conditions, $this->session->userdata('id'));
	    
	        print mb_convert_encoding("广告标题,单价(元),花费,平均点击花费,展现量,点击量,点击率,状态","GBK","UTF-8");
	        print "\n";
	    
	        foreach ($adinfo as $k=>$val) {
	            if(isset($start_time) && $start_time && isset($end_time) && $end_time)
	            {
	                $report = $this->ad_report_model->get_report_time(0, 0, $val['id'], $start_time, $end_time);
	                $click = $report['clicks']?$report['clicks']:0;
	                $impressions = $report['impressions']?$report['impressions']:0;
	                $cost = sprintf("%1\$.2f", $report['cost']/100);
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
	                $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
	                $avg_cost = sprintf("%1\$.2f", $average_cost/100);
	                $report_date = $start_time.'到'.$end_time;
	            }
	            else
	            {
	                $report = $this->ad_report_model->get_report(0, 0, $val['id'], $report_time);
	                $click = $report['clicks']?$report['clicks']:0;
	                $impressions = $report['impressions']?$report['impressions']:0;
	                $cost = sprintf("%1\$.2f", $report['cost']/100);
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
	                $cli_ctr = (sprintf("%1\$.2f", $ctr) * 100).'%';
	                $avg_cost = sprintf("%1\$.2f", $average_cost/100);
	                if($report_time >= strtotime(date('Y-m-d 00:00:00')))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d H:i:s');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 day'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 week'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
    	            elseif($report_time >= strtotime(date('Y-m-d 00:00:00', strtotime('-1 month'))))
    	            {
    	                $report_date = date('Y-m-d H:i:s', $report_time).'到'.date('Y-m-d 00:00:00');
    	            }
	            }
	    
	            $adinfo_name = $val['title'];
	            $price = sprintf("%1\$.2f", $val['price']/100);
	    
	            if($val['status']==0)
	            {
	                $status = '待审核';
	            }
	            elseif($val['status']==1)
	            {
	                $status = '审核通过';
	            }
	            elseif($val['status']==2)
	            {
	                $status = '启用';
	            }
	            elseif($val['status']==3)
	            {
	                $status = '审核不通过';
	            }
	            print mb_convert_encoding($adinfo_name,"GBK","UTF-8").",".$price.",".$cost.",".$avg_cost.",".$impressions.",".$click.",".$cli_ctr.",".mb_convert_encoding($status,"GBK","UTF-8").",".mb_convert_encoding($report_date,"GBK","UTF-8")."\n";
	        }
	        exit;
	    }
	}
}
