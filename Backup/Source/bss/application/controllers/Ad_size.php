<?php
class Ad_size extends MY_Controller {

    
    public function size_list($cur_page=1)
	{
	    $this->layout_data['title'] = "规格管理";
	    $this->load->model('ad_size_model');
	    $conditions = array();
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/ad_size/size_list/";
	    $config['total_rows'] = $this->ad_size_model->get_size_count($conditions);
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    //获取广告规格资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['size_name'] = $this->ad_size_model->get_size($conditions);
	     
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/ad_size/size_list/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
		$this->render('ad_size/size_list', $data);
	}
	
	public function add_size($id=0)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "编辑规格";
	    }else
	    {
	        $this->layout_data['title'] = "添加规格";
	    }
	    $this->load->model('ad_size_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $size_name = $this->input->post();
	        $this->ad_size_model->add_size($size_name);
	        
	        //查找管理员
	        $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	        
	        //生成操作日志
	        if($size_name['id'])
	        {
	            $content = $admin_user['user_name'].'编辑规格为'.$size_name['size_name'];
	        }else
	        {
	            $content = $admin_user['user_name'].'新增规格为'.$size_name['size_name'];
	        }
	        $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
	        $this->admin_model->write_log($admin_log);
	         
	        redirect(base_url('/ad_size/size_list'));
	    }
	    
	    if($id)
	    {
	        $data['size'] = $this->ad_size_model->get_one_size($id);
	    }
	    
	    $this->render('ad_size/add_size', $data);
	}
	
	public function edit_size($id)
	{
	    redirect(base_url('/ad_size/add_size/'.$id));
	}
	
	public function del_size($id)
	{
	    $this->load->model('ad_size_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    //查找管理员
	    $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	    //查找规格
	    $size_name = $this->ad_size_model->find_size($id);
	    
	    //生成操作日志
	    $admin_log = array('content' => $admin_user['user_name'].'删除规格为'.$size_name['size_name'], 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	    
	    $this->ad_size_model->del_size($id);
	     
	    redirect(base_url('/ad_size/size_list'));
	}

	//修改广告规格名称
	public function edit_size_name($id, $name)
	{
	    $name = urldecode($name);
	    $this->load->model('ad_size_model');
	    $res = $this->ad_size_model->edit_size_name($id, $name);
	
	    echo json_encode($res);
	    exit;
	}
	
	//按规格类型查找规格
	public function find_type_size($type)
	{
	    $this->load->model('ad_size_model');
	    $res = $this->ad_size_model->find_type_size($type);
	
	    echo json_encode($res);
	    exit;
	}
}
