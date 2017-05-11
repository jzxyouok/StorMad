<?php
class Customer extends MY_Controller {

    
    public function customer_list($cur_page=1)
	{
		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}
		
	    $this->layout_data['title'] = "用户群管理";
	    $this->load->model('customer_model');
	    $conditions = array();
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/customer/customer_list/";
	    $config['total_rows'] = $this->customer_model->get_customer_count($conditions, $this->session->userdata('id'));
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['customer_name'] = $this->customer_model->get_customer($conditions, $this->session->userdata('id'));
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/customer/customer_list/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    foreach ($data['customer_name'] as $k=>$val)
	    {
	       $data['scene_name'][$val['id']] = $this->customer_model->get_scene_id($val['id']);
	    }
	    
		$this->render('customer/customer_list', $data);
	}
	
	public function add_customer($id=0)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "编辑用户群";
	    }else
	    {
	        $this->layout_data['title'] = "新增用户群";
	    }
	    $this->load->model('customer_model');
	    $this->load->model('user_log_model');
	    //获取标签分类
	    $data['scene_class'] = $this->customer_model->get_class();
	    //获取标签名
	    foreach ($data['scene_class'] as $k=>$val)
	    {
	        $data['scene_name'][$val['id']] = $this->customer_model->get_scene($val['id']);
	    }
	    
	    if($this->input->post())
	    {
	        $customer = $this->input->post();
	        $res = $this->customer_model->add_customer($customer);
	        
	        //生成操作日志
	        if($customer['id'])
	        {
	            $content = '编辑用户群名称为'.$customer['customer_name'];
	        }else
	        {
	            $content = '新增用户群名称为'.$customer['customer_name'];
	        }
	        $user_log = array('content' => $content, 'user_id' => $this->session->userdata('id'));
	        $this->user_log_model->write_log($user_log);
	        
	        redirect(base_url('/customer/customer_list'));
	    }
	    
	    if($id)
	    {
	        $data['customer'] = $this->customer_model->edit_customer($id);
	        $data['customer_scene'] = $this->customer_model->edit_customer_scene($id);
	    }
	    
	    $this->render('customer/add_customer', $data);
	}
	
	public function edit_customer($id)
	{
	    redirect(base_url('/customer/add_customer/'.$id));
	}
	
	public function del_customer($id)
	{
	    $this->load->model('customer_model');
	    $this->load->model('user_log_model');
	     
	    //查找用户群
	    $customer_name = $this->customer_model->find_customer($id);
	     
	    //生成操作日志
	    $user_log = array('content' => '删除用户群名称为'.$customer_name['customer_name'], 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	    
	    $res = $this->customer_model->del_customer($id);
	    
	    echo json_encode($res);
	    exit;
	}
	
	//获取用户群的总数
	public function get_customer_num()
	{
	    $this->load->model('customer_model');
	    $res = $this->customer_model->get_customer_num();
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改用户群名称
	public function edit_customer_name($id, $name)
	{
	    $name = urldecode($name);
	    $this->load->model('customer_model');
	    $res = $this->customer_model->edit_customer_name($id, $name);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查用户群名称是否存在
	public function check_customer($customer_name='')
	{
	    $customer_name = urldecode($customer_name);
	    $this->load->model('customer_model');
	    $res = $this->customer_model->check_customer($customer_name);
	
	    echo json_encode($res);
	    exit;
	}
}
