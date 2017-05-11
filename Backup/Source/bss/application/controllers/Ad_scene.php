<?php
class Ad_scene extends MY_Controller {

    
    public function scene_list()
	{
	    $this->layout_data['title'] = "标签管理";
	    $this->load->model('ad_scene_model');
	    //获取标签分类
	    $data['scene_class'] = $this->ad_scene_model->get_class();
	    //获取标签名
	    foreach ($data['scene_class'] as $k=>$val)
	    {
	        $data['scene_name'][$val['id']] = $this->ad_scene_model->get_scene($val['id']);
	    }
	    
		$this->render('ad_scene/scene_list', $data);
	}
	
	public function add_scene($id=0, $fid=0, $field_label)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "修改标签";
	    }else
	    {
	        $this->layout_data['title'] = "增加标签";
	    }
	    $this->load->model('ad_scene_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    $data['fid'] = $fid;
	    $data['field_label'] = $field_label;
		
	    //增加标签
	    if($this->input->post())
        {
            $scene_name = $this->input->post();
            if($scene_name['fid'])
            {
                $this->ad_scene_model->add_scene($scene_name);
                
                //查找管理员
                $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
                
                //生成操作日志
                if($scene_name['id'])
                {
                    $content = $admin_user['user_name'].'编辑标签为'.$scene_name['scene_name'];
                }else
                {
                    $content = $admin_user['user_name'].'新增标签为'.$scene_name['scene_name'];
                }
                $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
                $this->admin_model->write_log($admin_log);
                
                redirect(base_url('/ad_scene/scene_list'));
            }
        }
	    //获取要修改的标签
	    if($id && $fid)
	    {
	        $data['scene_name'] = $this->ad_scene_model->edit_scene($id, $fid);
	    }
	    
	    $this->render('ad_scene/add_scene', $data);
	}
	
	public function edit_scene_class($id)
	{
	    redirect(base_url('/ad_scene/scene_class/'.$id));
	}
	
	public function scene_class($id=0)
	{
	    $this->layout_data['title'] = "增加标签分类";
	    $this->load->model('ad_scene_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	     
	    if($this->input->post())
	    {
	        $scene_class = $this->input->post();
	        $this->ad_scene_model->add_class($scene_class);
	         
	        //查找管理员
	        $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	         
	        //生成操作日志
	        if($scene_class['id'])
	        {
	            $content = $admin_user['user_name'].'修改分类为'.$scene_class['scene_class'];
	        }else
	        {
	            $content = $admin_user['user_name'].'增加分类为'.$scene_class['scene_class'];
	        }
	        
	        $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
	        $this->admin_model->write_log($admin_log);
	         
	        redirect(base_url('/ad_scene/scene_list'));
	    }
	    
	    if($id)
	    {
	        $data['scene_class'] = $this->ad_scene_model->get_scene_class($id);
	    }
	     
	    $this->render('ad_scene/scene_class', $data);
	}
	
	public function del_scene_class($id)
	{
	    $this->load->model('ad_scene_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	     
	    //查找管理员
	    $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	    //查找分类
	    $scene_class = $this->ad_scene_model->get_scene_class($id);
	     
	    //生成操作日志
	    $content = $admin_user['user_name'].'删除分类名为:'.$scene_class['scene_name'];
	     
	    $admin_log = array('content' => $content, 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	     
	    $this->ad_scene_model->del_scene_class($id);
	     
	    redirect(base_url('/ad_scene/scene_list'));
	}
	
	public function edit_scene($id, $fid)
	{
	    redirect(base_url('/ad_scene/add_scene/'.$id.'/'.$fid));
	}
	
	public function del_scene($id)
	{
	    $this->load->model('ad_scene_model');
	    $this->load->model('user_model');
	    $this->load->model('admin_model');
	    
	    //查找管理员
	    $admin_user = $this->user_model->find_admin($this->session->userdata('id'));
	    //查找标签
	    $scene_name = $this->ad_scene_model->find_scene($id);
	     
	    //生成操作日志
	    $admin_log = array('content' => $admin_user['user_name'].'删除标签为'.$scene_name['scene_name'], 'admin_id' => $this->session->userdata('id'));
	    $this->admin_model->write_log($admin_log);
	    
	    $this->ad_scene_model->del_scene($id);
	    
	    redirect(base_url('/ad_scene/scene_list'));
	}
}
