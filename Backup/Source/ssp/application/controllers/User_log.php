<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_log extends MY_Controller {

    
    public function log_list($user=0, $content=0, $start_time=0, $end_time=0, $cur_page=1)
	{
	    $user = urldecode($user);
	    $content = urldecode($content);
	    $start_time = urldecode($start_time);
	    $end_time = urldecode($end_time);
	    $this->layout_data['title'] = "用户操作日志";
	    $this->load->model('user_log_model');
	    $conditions = array();
	    
	    if($user)
	    {
	        $conditions['user'] = $data['distribution_name'] = $user;
	    }
	    if($content)
	    {
	        $conditions['content'] = $data['content'] = $content;
	    }
	    if($start_time)
	    {
	        $conditions['start_time'] = $data['start_time'] = $start_time;
	    }
	    if($end_time)
	    {
	        $conditions['end_time'] = $data['end_time'] = $end_time;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/user_log/log_list/{$user}/{$content}/{$start_time}/{$end_time}/";
	    $config['total_rows'] = $this->user_log_model->get_user_log_count($conditions);
	    $offset = $config['per_page'] * ($cur_page - 1);
	     
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	     
	    //获取广告主资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['user_log'] = $this->user_log_model->get_user_log($conditions);
	    
	    $data['url'] = base_url()."user_log/log_list/{$user}/{$content}/{$start_time}/{$end_time}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
		$this->render('user_log/log_list', $data);
	}
}
