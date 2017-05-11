<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_user extends MY_Controller {

    
    public function user_list($distribution_name=0, $distribution_id=0, $status=2, $cur_page=1)
	{
	    $distribution_name = urldecode($distribution_name);
	    $this->layout_data['title'] = '渠道管理';
	    $this->load->model('channel_user_model');
	    $conditions = array();

	    if($distribution_id)
	    {
	        $conditions['distribution_id'] = $data['distribution_id'] = $distribution_id;
	    }	    
	    if($distribution_name)
	    {
	        $conditions['distribution_name'] = $data['distribution_name'] = $distribution_name;
	    }
	    if($status!=2)
	    {
	        $conditions['status'] = $data['status'] = $status;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/channel_user/user_list/{$distribution_name}/{$distribution_id}/{$status}/";
	    $config['total_rows'] = $this->channel_user_model->get_user_count($conditions);
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    //获取渠道资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['user'] = $this->channel_user_model->get_user($conditions);
	    
	    $data['url'] = base_url()."/channel_user/user_list/{$distribution_name}/{$distribution_id}/{$status}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    $this->render('channel_user/user_list', $data);
	}
	
	//增加新用户
	public function add_user()
	{
	    $this->layout_data['title'] = "增加新渠道";
	    $this->load->model('channel_user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $user = $this->input->post();
			
			$rs=$this->channel_user_model->sent_channel_info($user['distribution_id'],$user['distribution_name']);
			
			if($rs)
			{
				$this->channel_user_model->add_user($user);
				
				//查找管理员
				$admin_user = $this->channel_user_model->find_admin($this->session->userdata('id'));
				
				//生成操作日志
				$content = $admin_user['user_name'].'新增'.$user['distribution_name'].'渠道账号为'.$user['user_name'];
				$admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
				$this->admin_model->write_log($admin_log);
				
				redirect(base_url('/channel_user/user_list'));
			}
			else
			{
				echo "接口错误";
				exit;	
			}
	    }
	    
		//生成渠道号
		$data['distribution_id'] = $this->channel_user_model->create_distribution_id();
		
	    $this->render('channel_user/add_user', $data);
	}
	
	//重置用户密码
	public function edit_password($user_id=0)
	{
	    $this->layout_data['title'] = "重置密码";
	    $data['user_id'] = $user_id;
	    $this->load->model('channel_user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $post = $this->input->post();
	        if($post['user_id'])
	        {
	            //查找管理员
	            $admin_user = $this->channel_user_model->find_admin($this->session->userdata('id'));
	            //查找账户
	            $user_name = $this->channel_user_model->find_user($post['user_id']);
	            $res = $this->channel_user_model->edit_password($post['user_password'], $post['user_id']);
	            
	            //生成操作日志
	            $admin_log = array('content' => $admin_user['user_name'].'给'.$user_name['distribution_name'].'重置密码', 'admin_id' => $this->session->userdata('id'));
	            $this->admin_model->write_log($admin_log);
	            
	            redirect(base_url('/channel_user/user_list'));
	        }
	    }
	    
	    $this->render('/channel_user/edit_password', $data);
	}
	
	//账户状态设置
	public function set_status($status, $id)
	{
	    $this->load->model('channel_user_model');
	    $this->load->model('admin_model');
	    $res = $this->channel_user_model->set_status($status, $id);
	    
	    //查找管理员
	    $admin_user = $this->channel_user_model->find_admin($this->session->userdata('id'));
	    //查找账户
	    $user_name = $this->channel_user_model->find_user($id);
	    
	    //生成操作日志
	    $content = ($status==0)?'停用':'启用';
	    $admin_log = array('content' => $admin_user['user_name'].$content.$user_name['distribution_name'].'的账户', 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查用户账号是否存在
	public function check_user($username='')
	{
	    $username = urldecode($username);
	    $this->load->model('channel_user_model');
	    $res = $this->channel_user_model->check_user($username);
	    
	    echo json_encode($res);
	    exit;
	}
	
	public function check_distribution_name($distribution_name='')
	{
		$distribution_name = urldecode($distribution_name);
	    $this->load->model('channel_user_model');
	    $res = $this->channel_user_model->check_distribution_name($distribution_name);
	    
	    echo json_encode($res);
	    exit;	
	}
	
	public function export($id,$start_time,$end_time)
	{
		$this->load->model('channel_user_model');
	}
}
