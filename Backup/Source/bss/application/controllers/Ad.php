<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function ad_list($user=0,$title=0,$type=0,$status=4,$ad_id=0,$put_time=0,$cur_page=1)
	{
		$user = urldecode($user);
		$title = urldecode($title);
        $type = (int)$type;
        $status = (int)$status;
        $adid = (int)$ad_id;
		$put_time = urldecode($put_time);
        $cur_page = (int)$cur_page;
		$this->layout_data['title'] = '广告审核';
		$this->load->model('ad_model');
		$conditions = array();
		
		if($user)
		{
			$conditions['user'] = $data['ad_user'] = $user;
		}
		if($title)
		{
			$conditions['title'] = $data['ad_title'] = $title;
		}
		if($type)
		{
			$conditions['type'] = $data['ad_type'] = $type;
		}
		if($status!=4)
		{
			$conditions['status'] = $data['ad_status'] = $status;
		}
		if($ad_id)
		{
			$conditions['id'] = $data['ad_id'] = $ad_id;
		}
		if($put_time)
		{
			$conditions['put_time'] = $data['ad_put_time'] = $put_time;
		}
		
		//分页配置
		$this->load->library('pagination');
		$this->load->config('pagination');
		$config = $this->config->item('pagination_common_list');
		$config['base_url'] = base_url()."/ad/ad_list/{$user}/{$title}/{$type}/{$status}/{$ad_id}/{$put_time}/";
		$config['total_rows'] = $this->ad_model->get_ad_count($conditions);
		$offset = $config['per_page'] * ($cur_page - 1);

		//获取广告资源数据
		$conditions['offset'] = $offset;
		$conditions['page_size'] = $config['per_page'];
		$data['ad'] = $this->ad_model->get_ad($conditions);
		
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		
		$data['url'] = base_url()."/ad/ad_list/{$user}/{$title}/{$type}/{$status}/{$ad_id}/{$put_time}/";
		$data['total_page'] = ceil($config['total_rows']/$config['per_page']);
		$data['cur_page'] = $cur_page;
		
		$this->render('ad/ad_list', $data);
	}
	
	//广告审核
	public function audit($status, $id)
	{
		$this->load->model('ad_model');
		$this->load->model('user_model');
		$this->load->model('admin_model');
		$res = $this->ad_model->audit_ad($status, $id);
		
		//查找管理员
		$admin_user = $this->user_model->find_admin($this->session->userdata('id'));
		//查找广告
		$ad_name = $this->ad_model->find_ad($id);
		
		//生成操作日志
		$content = ($status==1)?'通过':'不通过';
		$admin_log = array('content' => $admin_user['user_name'].'审核广告标题为'.$ad_name['title'].$content, 'admin_id' => $this->session->userdata('id'));
		$this->admin_model->write_log($admin_log);
		
		echo json_encode($res);
		exit;
	}

	public function view($ad_id=0)
	{
		if(!is_numeric($ad_id) || $ad_id<=0)
		{
			print "参数错误";
			exit;
		}

		$this->load->model('ad_model');
		$adinfo = $this->ad_model->get_adinfo_by_id($ad_id);
		$size_info = $this->ad_model->get_size_by_id($adinfo['size_id']);
		$result = array('width'=>$size_info['width'],'height'=>$size_info['height']);
        if($adinfo['type']==1)
        {
            $result['content'] = '<a href="'.$adinfo['link'].'" target="_blank"><img style="width:auto;height:auto;max-width:100%;max-height:100%;" src="'.$this->config->item('resource_url').$adinfo['content'].'"/></a>';
        }
        else
        {
            $result['content'] = '<a href="'.$adinfo['link'].'" target="_blank">'.$adinfo['content'].'</a>';
        }
		print json_encode($result);
	}
}
