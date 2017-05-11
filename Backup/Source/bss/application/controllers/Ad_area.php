<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_area extends MY_Controller {

	
	public function area_list($cur_page=1)
	{
		$this->layout_data['title'] = "广告位管理";
		$this->load->model('ad_area_model');
		$conditions = array();
		
		//分页配置
		$this->load->library('pagination');
		$this->load->config('pagination');
		$config = $this->config->item('pagination_common_list');
		$config['base_url'] = base_url()."/ad_area/area_list/";
		$config['total_rows'] = $this->ad_area_model->get_area_count($conditions);
		$offset = $config['per_page'] * ($cur_page - 1);
		
		//获取广告资源数据
		$conditions['offset'] = $offset;
		$conditions['page_size'] = $config['per_page'];
		$data['area'] = $this->ad_area_model->get_area($conditions);
		
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		
		$data['url'] = base_url()."/ad_area/area_list/";
		$data['total_page'] = ceil($config['total_rows']/$config['per_page']);
		$data['cur_page'] = $cur_page;
		
		$this->render('ad_area/area_list', $data);
	}
	
	public function add_area($id=0)
	{
		if($id)
		{
			$this->layout_data['title'] = "编辑广告位";
		}else
		{
			$this->layout_data['title'] = "新增广告位";
		}
		$this->load->model('ad_area_model');
		$this->load->model('user_model');
		$this->load->model('admin_model');
		$this->load->model('channel_user_model');
		
		//获取标签分类
		$data['scene_class'] = $this->ad_area_model->get_class();
		
		//获取渠道
		$data['channel'] = $this->channel_user_model->get_user(array('status'=>1));
		
		//获取标签名
		foreach ($data['scene_class'] as $k=>$val)
		{
			$data['scene_name'][$val['id']] = $this->ad_area_model->get_scene($val['id']);
		}
		//获取规格
		$data['ad_size'] = $this->ad_area_model->get_size();
		
		if($this->input->post())
		{
			$query = $this->input->post();
			$this->ad_area_model->add_area($query);
			
			//查找管理员
			$admin_user = $this->user_model->find_admin($this->session->userdata('id'));
			 
			//生成操作日志
			if($query['id'])
			{
			   $content = $admin_user['user_name'].'编辑'.$query['area_name'].'广告位';
			}
			else
			{
				$content = $admin_user['user_name'].'新增'.$query['area_name'].'广告位';
			}
			$admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
			$this->admin_model->write_log($admin_log);
			
			redirect(base_url('/ad_area/area_list'));
		}
		
		if($id)
		{
			$data['area'] = $this->ad_area_model->get_one_area($id);
			$data['area_scene'] = $this->ad_area_model->edit_area_scene($id);
			
			if($data['area']['val_region'] && $data['area']['show_region'])
			{
				$data['val_region']=explode("|",$data['area']['val_region']);
				$data['show_region']=explode("|",$data['area']['show_region']);
			}
		}
		
		$this->render('ad_area/add_area', $data);
	}
	
	public function edit_area($id)
	{
		redirect(base_url('/ad_area/add_area/'.$id));
	}
	
	public function del_area($id)
	{
		$this->load->model('ad_area_model');
		$this->load->model('user_model');
		$this->load->model('admin_model');
		
		//查找管理员
		$admin_user = $this->user_model->find_admin($this->session->userdata('id'));
		//查找广告
		$area_name = $this->ad_area_model->find_area($id);
		
		//生成操作日志
		$content = $admin_user['user_name'].'删除'.$area_name['area_name'].'广告位';
		
		$admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
		$this->admin_model->write_log($admin_log);
		
		$this->ad_area_model->del_area($id);
		
		redirect(base_url('/ad_area/area_list'));
	}
	
	//是否启用广告位
	public function use_area($status, $id)
	{
		$this->load->model('ad_area_model');
		$res = $this->ad_area_model->use_area($status, $id);
		
		echo json_encode($res);
		exit;
	}
	
	//修改广告位名称
	public function edit_area_name($id, $name)
	{
		$name = urldecode($name);
		$this->load->model('ad_area_model');
		$res = $this->ad_area_model->edit_area_name($id, $name);
	
		echo json_encode($res);
		exit;
	}

	/**
	 * 获取广告位推广代码
	 * @param $id int 广告位ID
	 */
	public function get_code($id=0)
	{
		if(!is_numeric($id) || $id<=0)
		{
			$result = array('ret'=>0,'content'=>'参数错误');
			print json_encode($result);
			exit;
		}

		$this->load->model('ad_area_model');
		$area_info = $this->ad_area_model->get_one_area($id);
		$result = array('ret'=>1,'width'=>$area_info['width'],'height'=>$area_info['height']);
		$ad_template = $this->config->item('ad_template');
		$ad_template = str_replace('{area_id}',$area_info['id'],$ad_template);
		$ad_template = str_replace('{ps_url}',$this->config->item('ps_url'),$ad_template);
		$result['ad_template'] = $ad_template;
		print json_encode($result);

	}

	/**
	 * 广告位预览
	 * @param $id int 广告位ID
	 */
	public function view($id=0)
	{
		$result_template = "<html>
		<head>
		<title>广告位预览</title
		</head>
		<body>{content}</body>
		</html>
		";
		if(!is_numeric($id) || $id<=0)
		{
			$result = str_replace('{content}','参数错误',$result_template);
			print $result;
			exit;
		}

		$this->load->model('ad_area_model');
		$area_info = $this->ad_area_model->get_one_area($id);
		$result = array('ret'=>1,'width'=>$area_info['width'],'height'=>$area_info['height']);
		$ad_template = $this->config->item('ad_template');
		$ad_template = str_replace('{area_id}',$area_info['id'],$ad_template);
		$ad_template = str_replace('{ps_url}',$this->config->item('ps_url'),$ad_template);
		$result = str_replace('{content}',$ad_template,$result_template);
		print $result;
	}
}
