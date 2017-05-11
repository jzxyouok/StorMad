<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    
    public function user_list($user=0, $type=0, $status=2, $cur_page=1)
	{
	    $user = urldecode($user);
	    $this->layout_data['title'] = '广告主管理';
	    $this->load->model('user_model');
	    $conditions = array();
	    
	    if($user)
	    {
	        $conditions['user'] = $data['user_name'] = $user;
	    }
	    if($type)
	    {
	        $conditions['type'] = $data['user_type'] = $type;
	    }
	    if($status!=2)
	    {
	        $conditions['status'] = $data['user_status'] = $status;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/user/user_list/{$user}/{$type}/{$status}/";
	    $config['total_rows'] = $this->user_model->get_user_count($conditions);
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    //获取广告主资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['user'] = $this->user_model->get_user($conditions);
	    
	    $data['url'] = base_url()."/user/user_list/{$user}/{$type}/{$status}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    $this->render('user/user_list', $data);
	}
	
	//增加新用户
	public function add_user()
	{
	    $this->layout_data['title'] = "增加新用户";
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
			$user = $this->input->post();
			
	        $upload_path = './uploads/'.date('Ym').'/';
	        if(!file_exists($upload_path))
	        {
	            mkdir($upload_path);
	        }
	        
	        $config['upload_path']      = $upload_path;  //图片上传路径
	        $config['allowed_types']    = 'jpg|png';  //支持上传的图片类型
	        $config['max_size']         = 2048;  //限制图片上传的最大值(KB)
            $config['max_width']        = 260;  //无限制图片的宽度
            $config['max_height']       = 80;  //无限制图片的高度
            $config['encrypt_name']     = TRUE;  //随机生成的图片名
	        $this->load->library('upload', $config);
	        $this->upload->do_upload('logo_file');
			
			//echo $this->upload->display_errors();
			
			if($this->upload->data('file_name'))
            {
				if(($this->upload->data('image_type')!='jpeg' && $this->upload->data('image_type')!='png') || $this->upload->data('file_size') > 2048 || $this->upload->data('image_width') > 260 || $this->upload->data('image_height') > 60)
                {
					header('refresh:3;url=/user/add_user');
					print('只支持大小最大值为2M、jpg/png,宽260px,高60px的图片格式上传!3秒后返回...');
                    exit;
				}
				
				$user['logo'] = '/uploads/'.date('Ym').'/'.$this->upload->data('file_name');
			}
	        
	        $this->user_model->add_user($user);
	        
	        //查找管理员
	        $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	        
	        //生成操作日志
	        /* if($user['id'])
	        {
	            $content = $admin_user['true_name'].'编辑账户为'.$user['true_name'];
	        }else
	        {
	            $content = $admin_user['true_name'].'新增账户为'.$user['true_name'];
	        } */
	        $content = $admin_user['user_name'].'新增用户账号为'.$user['user_name'];
	        $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
	        $this->admin_model->write_log($admin_log);
	        
	        redirect(base_url('/user/user_list'));
	    }
	    
	    $this->render('user/add_user', $data);
	}
	
	//添加账户金额
	public function add_money($user_id=0)
	{
	    $this->layout_data['title'] = "添加金额";
	    $data['user_id'] = $user_id;
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	       $post = $this->input->post();
	       if($post['user_id'])
	       {
	           //查找管理员
	           $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	           //查找账户
	           $user_name = $this->user_model->find_user($post['user_id']);
	           $add_money = $post['user_money']?$post['user_money']:0;
	           $res = $this->user_model->add_money($post['user_money'], $post['user_id']);
	           
	           //生成操作日志
	           $admin_log = array('content' => $admin_user['user_name'].'给'.$user_name['user_name'].'添加'.sprintf("%1\$.2f", $add_money).'金额', 'admin_id' => $this->session->userdata('id'));
	           $this->admin_model->write_log($admin_log);
	           
	           redirect(base_url('/user/user_list'));
	       }
	    }
	    
	    $this->render('user/add_money', $data);
	}
	
	//重置用户密码
	public function edit_password($user_id=0)
	{
	    $this->layout_data['title'] = "重置密码";
	    $data['user_id'] = $user_id;
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    if($this->input->post())
	    {
	        $post = $this->input->post();
	        if($post['user_id'])
	        {
	            //查找管理员
	            $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	            //查找账户
	            $user_name = $this->user_model->find_user($post['user_id']);
	            $res = $this->user_model->edit_password($post['user_password'], $post['user_id']);
	            
	            //生成操作日志
	            $admin_log = array('content' => $admin_user['user_name'].'给'.$user_name['user_name'].'重置密码', 'admin_id' => $this->session->userdata('id'));
	            $this->admin_model->write_log($admin_log);
	            
	            redirect(base_url('/user/user_list'));
	        }
	    }
	    
	    $this->render('user/edit_password', $data);
	}
	
	//账户状态设置
	public function set_status($status, $id)
	{
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    $res = $this->user_model->set_status($status, $id);
	    
	    //查找管理员
	    $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	    //查找账户
	    $user_name = $this->user_model->find_user($id);
	    
	    //生成操作日志
	    $content = ($status==0)?'停用':'启用';
	    $admin_log = array('content' => $admin_user['user_name'].$content.$user_name['user_name'].'的账户', 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	
	    echo json_encode($res);
	    exit;
	}
	
	//检查用户账号是否存在
	public function check_user($username='')
	{
	    $username = urldecode($username);
	    $this->load->model('user_model');
	    $res = $this->user_model->check_user($username);
	    
	    echo json_encode($res);
	    exit;
	}
}
