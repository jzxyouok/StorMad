<?php
class Campaign extends MY_Controller {

    private $order_field='';
    private $order_type='';
    
    public function campaign_list($time=0, $order_field='id', $order_type='desc', $cur_page=1)
	{
		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}
		
	    $data['time'] = $time;
	    $data['order_field'] = $order_field;
	    $data['order_type'] = $order_type;
	    $this->order_field = $order_field;
	    $this->order_type = $order_type;
	    
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
	    
	    $this->layout_data['title'] = "推广计划管理";
	    $this->load->model('campaign_model');
	    $this->load->model('ad_report_model');
	    $conditions = array();
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/campaign/campaign_list/{$time}/{$order_field}/{$order_type}/";
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $data['campaign'] = $this->campaign_model->get_campaign($conditions, $this->session->userdata('id'));
	    $config['total_rows'] = count($data['campaign']);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/campaign/campaign_list/{$time}/{$order_field}/{$order_type}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    if($time==0)
	    {
	        $time = date('Y-m-d 00:00:00');
	    }elseif($time==1)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-1 day'));
	    }elseif($time==7)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-7 day'));
	    }elseif($time==30)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-30 day'));
	    }
	    
	    //获取推广计划资源数据
	    foreach ($data['campaign'] as $k=>$val)
	    {
	        $report = $this->ad_report_model->get_report($val['id'], 0, 0, strtotime($time));
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
	        $data['campaign'][$k]['adgroup_count'] = $this->campaign_model->get_adgroup_count($val['id']);
	        $data['campaign'][$k]['adinfo_count'] = $this->campaign_model->get_adinfo_count($val['id']);
	    }
	    
	    // usort 排序
	    if(count($data['campaign']) > 10)
	    {
	        $data['campaign'] = $campaign_array = array_chunk($data['campaign'], 10);
	        $campaign_array = $data['campaign'][$cur_page - 1];
	    }else{
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
	    
		$this->render('campaign/campaign_list', $data);
	}
	
	public function add_campaign($id=0)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "编辑推广计划";
	    }else
	    {
	        $this->layout_data['title'] = "新增推广计划";
	    }
	    $this->load->model('campaign_model');
	    $this->load->model('user_log_model');
	    
	    if($this->input->post())
	    {
	        $campaign = $this->input->post();
	        $this->campaign_model->add_campaign($campaign);
	    
	        //生成操作日志
	        if($campaign['id'])
	        {
	            $content = '编辑'.$campaign['campaign_name'].'推广计划';
	        }else
	        {
	            $content = '新建'.$campaign['campaign_name'].'推广计划';
	        }
	        $user_log = array('content' => $content, 'user_id' => $this->session->userdata('id'));
	        $this->user_log_model->write_log($user_log);
	        
	        redirect(base_url('/campaign/campaign_list'));
	    }
	    
	    if($id)
	    {
	        $data['campaign'] = $this->campaign_model->edit_campaign($id);
	    }
	    
	    $this->render('campaign/add_campaign', $data);
	}
	
	public function edit_campaign($id)
	{
	    redirect(base_url('/campaign/add_campaign/'.$id));
	}
	
	//设置推广计划、推广组及推广组下的所有广告状态
	public function status($status, $id)
	{
	    $this->load->model('campaign_model');
	    $this->load->model('user_log_model');
	    $res = $this->campaign_model->set_status($status, $id);
	    
	    //查找推广计划
	    $campaign_name = $this->campaign_model->find_campaign($id);
	    
	    //生成操作日志
	    $content = ($status==0)?'不推广':'推广';
	    $user_log = array('content' => '设置推广计划名称为'.$campaign_name['campaign_name'].$content, 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	
	    echo json_encode($res);
	    exit;
	}
	
	//获取推广计划的总数
	public function get_campaign_num()
	{
	    $this->load->model('campaign_model');
	    $res = $this->campaign_model->get_campaign_num();
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改推广计划名称
	public function edit_campaign_name($id, $name)
	{
	    $name = urldecode($name);
	    $this->load->model('campaign_model');
	    $res = $this->campaign_model->edit_campaign_name($id, $name);
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改推广计划日限额
	public function edit_day_sum($id, $value)
	{
	    $this->load->model('campaign_model');
	    $res = $this->campaign_model->edit_day_sum($id, $value);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查推广计划名称是否存在
	public function check_campaign($campaign_name='')
	{
	    $campaign_name = urldecode($campaign_name);
	    $this->load->model('campaign_model');
	    $res = $this->campaign_model->check_campaign($campaign_name);
	     
	    echo json_encode($res);
	    exit;
	}
}
