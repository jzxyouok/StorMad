<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user extends MY_Controller {

    
    public function admin_list($user=0, $status=2, $cur_page=1)
	{
	    $user = urldecode($user);
	    $this->layout_data['title'] = '管理员';
	    $this->load->model('admin_user_model');
	    $conditions = array();
	    
	    if($user)
	    {
	        $conditions['user'] = $data['user_name'] = $user;
	    }
	    if($status!=2)
	    {
	        $conditions['status'] = $data['user_status'] = $status;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/admin_user/admin_list/{$user}/{$status}/";
	    $config['total_rows'] = $this->admin_user_model->get_admin_count($conditions);
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    //获取广告主资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['admin'] = $this->admin_user_model->get_admin($conditions);
	    
	    $data['url'] = base_url()."/admin_user/admin_list/{$user}/{$status}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    $this->render('admin_user/admin_list', $data);
	}
	
	//增加管理员
	public function add_admin()
	{
	    $this->layout_data['title'] = "增加管理员";
	    $this->load->model('admin_user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $admin = $this->input->post();
	        $this->admin_user_model->add_admin($admin);
	        
	        //查找管理员
	        $admin_user = $this->admin_user_model->find_admin($this->session->userdata('id'));
	        
	        //生成操作日志
	        $content = $admin_user['user_name'].'新增管理员账号为'.$admin['user_name'];
	        $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
	        $this->admin_model->write_log($admin_log);
	        
	        redirect(base_url('/admin_user/admin_list'));
	    }
	    
	    $this->render('admin_user/add_admin', $data);
	}
	
	//重置用户密码
	public function edit_password($user_id=0)
	{
	    $this->layout_data['title'] = "重置密码";
	    $data['user_id'] = $user_id;
	    $this->load->model('admin_user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $post = $this->input->post();
	        if($post['user_id'])
	        {
	            //查找管理员
	            $admin_user = $this->admin_user_model->find_admin($this->session->userdata('id'));
	            //查找账户
	            $user_name = $this->admin_user_model->find_user($post['user_id']);
	            $res = $this->admin_user_model->edit_password($post['user_password'], $post['user_id']);
	            
	            //生成操作日志
	            $admin_log = array('content' => $admin_user['user_name'].'给'.$user_name['user_name'].'重置密码', 'admin_id' => $this->session->userdata('id'));
	            $this->admin_model->write_log($admin_log);
	            
	            redirect(base_url('/admin_user/admin_list'));
	        }
	    }
	    
	    $this->render('admin_user/edit_password', $data);
	}
	
	//账户状态设置
	public function set_status($status, $id)
	{
	    $this->load->model('admin_user_model');
	    $this->load->model('admin_model');
	    $res = $this->admin_user_model->set_status($status, $id);
	    
	    //查找管理员
	    $admin_user = $this->admin_user_model->find_admin($this->session->userdata('id'));
	    //查找账户
	    $user_name = $this->admin_user_model->find_user($id);
	    
	    //生成操作日志
	    $content = ($status==0)?'停用':'启用';
	    $admin_log = array('content' => $admin_user['user_name'].$content.$user_name['user_name'].'的账户', 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查管理员账号是否存在
	public function check_admin_user($username='')
	{
	    $username = urldecode($username);
	    $this->load->model('admin_user_model');
	    $res = $this->admin_user_model->check_admin_user($username);
	     
	    echo json_encode($res);
	    exit;
	}
}
