<?php
class Adgroup extends MY_Controller {

	private $order_field='';
	private $order_type='';
    
    public function adgroup_list($campaign_id=0, $time=0, $order_field='id',$order_type='desc', $cur_page=1)
	{
		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}
		
	    $data['time'] = $time;
	    $data['campaign_id'] = $campaign_id;
	    $data['order_field'] = $order_field;
	    $data['order_type'] = $order_type;
	    $this->order_field = $order_field;
		$this->order_type = $order_type;
		$data['campaign_url'] = $campaign_id?$campaign_id:0;
		
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

	    $this->layout_data['title'] = "推广组管理";
	    $this->load->model('adgroup_model');
	    $this->load->model('ad_report_model');
	    $conditions = array();
	    
	    if($campaign_id)
	    {
	        $conditions['campaign_id'] = $campaign_id;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/adgroup/adgroup_list/{$campaign_id}/{$time}/{$order_field}/{$order_type}/";
	    $offset = $config['per_page'] * ($cur_page - 1);
	     
	    $data['adgroup'] = $this->adgroup_model->get_adgroup($conditions, $this->session->userdata('id'));
		$config['total_rows'] = count($data['adgroup']);
	     
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/adgroup/adgroup_list/{$campaign_id}/{$time}/{$order_field}/{$order_type}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    if($time==0)
	    {
	        $time = date('Y-m-d 00:00:00');
	    }elseif($time==1)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-1 day'));
	    }
	    elseif($time==7)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-7 day'));
	    }
	    elseif($time==30)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-30 day'));
	    }
	    
	    //获取推广组资源数据
	    foreach ($data['adgroup'] as $k=>$val)
	    {
			$report = $this->ad_report_model->get_report(0, $val['id'], 0, strtotime($time));
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
			$data['adgroup'][$k]['adinfo_count'] = $this->adgroup_model->get_adinfo_count($val['id']);
	    }
	    
		// usort 排序
		if(count($data['adgroup']) > 10)
		{
    	    $data['adgroup'] = $adgroup_array = array_chunk($data['adgroup'], 10);
    	    $adgroup_array = $data['adgroup'][$cur_page - 1];
		}else{
		    $adgroup_array = $data['adgroup'];
		}
/*		usort($adgroup_array,function($a,$b)
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
		$data['adgroup'] = $adgroup_array;
		
		$this->render('adgroup/adgroup_list', $data);
	}

	
	public function add_adgroup($id=0, $campaign_id=0)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "编辑推广组";
	    }else
	    {
	        $this->layout_data['title'] = "新增推广组";
	    }
	    $this->load->model('campaign_model');
	    $this->load->model('adgroup_model');
	    $this->load->model('user_log_model');
	    $data['campaign_url'] = $campaign_id;
	    if($campaign_id)
	    {
	        $data['campaign'] = $this->campaign_model->find_campaign($campaign_id);
	    }
        //获取推广计划
	    $data['campaign_name'] = $this->adgroup_model->get_campaign();
	    
	    if($this->input->post())
	    {
	        $adgroup = $this->input->post();
	        $this->adgroup_model->add_adgroup($adgroup);
	        
	        //生成操作日志
	        if($adgroup['id'])
	        {
	            $content = '编辑'.$adgroup['adgroup_name'].'推广组';
	        }else
	        {
	            $content = '新建'.$adgroup['adgroup_name'].'推广组';
	        }
	        $user_log = array('content' => $content, 'user_id' => $this->session->userdata('id'));
	        $this->user_log_model->write_log($user_log);
	        
	        if($adgroup['campaign_url'])
	        {
	            redirect(base_url('/adgroup/adgroup_list/'.$adgroup['campaign_url'].'/1/id/desc/1'));
	        }
	        else 
	        {
	            redirect(base_url('/adgroup/adgroup_list'));
	        }
	    }
	    
	    if($id)
	    {
	        $data['adgroup'] = $this->adgroup_model->edit_adgroup($id);
	    }
	    
	    $this->render('adgroup/add_adgroup', $data);
	}
	
	public function edit_adgroup($id, $campaign_id)
	{
	    redirect(base_url('/adgroup/add_adgroup/'.$id.'/'.$campaign_id));
	}
	
	public function del_adgroup($id)
	{
	    $this->load->model('adgroup_model');
	    $this->load->model('user_log_model');
	    
	    //查找推广组
	    $adgroup_name = $this->adgroup_model->find_adgroup($id);
	    
	    //生成操作日志
	    $user_log = array('content' => '删除推广组名称为'.$adgroup_name['adgroup_name'], 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	    
	    $res = $this->adgroup_model->del_adgroup($id);
	    
	    echo json_encode($res);
	    exit;
	}
	
	//设置推广组及推广组下的所有广告状态
	public function status($status, $id)
	{
	    $this->load->model('adgroup_model');
	    $this->load->model('user_log_model');
	    $res = $this->adgroup_model->set_status($status, $id);
	
	    //查找推广组
	    $adgroup_name = $this->adgroup_model->find_adgroup($id);
	     
	    //生成操作日志
	    $content = ($status==0)?'不推广':'推广';
	    $user_log = array('content' => '设置推广组名称为'.$adgroup_name['adgroup_name'].$content, 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	    
	    echo json_encode($res);
	    exit;
	}
	
    //获取推广组的总数
	public function get_adgroup_num()
	{
	    $this->load->model('adgroup_model');
	    $res = $this->adgroup_model->get_adgroup_num();
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改推广组名称
	public function edit_adgroup_name($id, $name)
	{
	    $name = urldecode($name);
	    $this->load->model('adgroup_model');
	    $res = $this->adgroup_model->edit_adgroup_name($id, $name);
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改推广计划日限额
	public function edit_day_sum($id, $value)
	{
	    $this->load->model('adgroup_model');
	    $res = $this->adgroup_model->edit_day_sum($id, $value);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查推广组名称是否存在
	public function check_adgroup($adgroup_name='')
	{
	    $adgroup_name = urldecode($adgroup_name);
	    $this->load->model('adgroup_model');
	    $res = $this->adgroup_model->check_adgroup($adgroup_name);
	
	    echo json_encode($res);
	    exit;
	}
}
