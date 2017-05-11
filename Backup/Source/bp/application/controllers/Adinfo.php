<?php
class Adinfo extends MY_Controller {

    private $order_field;
    private $order_type;
    
    public function adinfo_list($campaign_id=0, $adgroup_id=0, $title=0, $time=0, $order_field='id', $order_type='desc', $cur_page=1)
	{
/*		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}*/
		
	    $title = urldecode($title);
	    $data['time'] = $time;
	    $data['campaign_id'] = $campaign_id;
	    $data['adgroup_id'] = $adgroup_id;
	    $data['order_field'] = $order_field;
	    $data['order_type'] = $order_type;
	    $this->order_field = $order_field;
	    $this->order_type = $order_type;
	    $data['campaign_url'] = $campaign_id?$campaign_id:0;
	    $data['adgroup_url'] = $adgroup_id?$adgroup_id:0;
	    
	    $this->layout_data['title'] = "广告管理";
	    $this->load->model('adinfo_model');
	    $this->load->model('ad_report_model');
	    $conditions = array();
	    
	    //获取推广计划
	    $data['campaign_name'] = $this->adinfo_model->get_campaign();
	    //获取推广组
	    $data['adgroup_name'] = $this->adinfo_model->get_adgroup();
	    
	    if($campaign_id)
	    {
	        $conditions['campaign_id'] = $campaign_id;
	        $data['campaign'] = $this->adinfo_model->get_campaign_id($campaign_id, 0);
	    }
	    if($adgroup_id)
	    {
	        $conditions['campaign_id'] = $campaign_id;
	        $data['campaign'] = $this->adinfo_model->get_campaign_id(0, $adgroup_id);
	        $conditions['adgroup_id'] = $adgroup_id;
	        $data['adgroup'] = $this->adinfo_model->get_adgroup_id($adgroup_id);
	    }
	    if($title)
	    {
	        $conditions['title'] = $data['adinfo_title'] = $title;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/adinfo/adinfo_list/{$campaign_id}/{$adgroup_id}/{$title}/{$time}/{$order_field}/{$order_type}/";
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    //获取广告资源数据
	    $data['adinfo'] = $this->adinfo_model->get_adinfo($conditions, $this->session->userdata('id'));
	    $config['total_rows'] = count($data['adinfo']);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/adinfo/adinfo_list/{$campaign_id}/{$adgroup_id}/{$title}/{$time}/{$order_field}/{$order_type}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
	    if($time==0)
	    {
	        $time = date('Y-m-d 00:00:00');
	    }elseif($time==1)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-1 day'));
	    }
	    elseif($time==7)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-7 day'));
	    }
	    elseif($time==30)
	    {
	        $time = date('Y-m-d 00:00:00', strtotime('-30 day'));
	    }
	    
	    //获取广告资源数据
	    foreach ($data['adinfo'] as $k=>$val)
	    {
	        $report = $this->ad_report_model->get_report(0, 0, $val['id'], strtotime($time));
	        $data['adinfo'][$k]['click'] = $report['clicks']?$report['clicks']:0;
	        $data['adinfo'][$k]['impressions'] = $report['impressions']?$report['impressions']:0;
	        $data['adinfo'][$k]['cost'] = sprintf("%1\$.2f", $report['cost']/100);
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
	        $data['adinfo'][$k]['ctr'] = sprintf("%1\$.2f", $ctr);
	        $data['adinfo'][$k]['average_cost'] = sprintf("%1\$.2f", $average_cost/100);
			
			//获取广告排名
			$data['adinfo'][$k]['rank']=$this->adinfo_model->get_rank($val['weight'],$val['id'],$val['size_id'],$val['status']);
	    }
		
	    //usort 排序
        if(count($data['adinfo']) > 10)
        {
            $data['adinfo'] = $adinfo_array = array_chunk($data['adinfo'], 10);
            $adinfo_array = $data['adinfo'][$cur_page - 1];
        }else{
            $adinfo_array = $data['adinfo'];
        }
/*	    usort($adinfo_array,function($a,$b)
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
	    });*/
	    $data['adinfo'] = $adinfo_array;
	    
		$this->render('adinfo/adinfo_list', $data);
	}
	
	public function add_adinfo($id=0, $campaign_id=0, $adgroup_id=0)
	{
	    if($id)
	    {
	        $this->layout_data['title'] = "编辑广告";
	    }else
	    {
	        $this->layout_data['title'] = "新增广告";
	    }
	    $this->load->model('adinfo_model');
	    $this->load->model('user_log_model');
	    $this->load->model('campaign_model');
	    $this->load->model('adgroup_model');
	    $this->load->model('customer_model');
	    $data['campaign_url'] = $campaign_id;
	    if($campaign_id)
	    {
	       $data['campaign'] = $this->campaign_model->find_campaign($campaign_id);
	    }
	    $data['adgroup_url'] = $adgroup_id;
	    if($adgroup_id)
	    {
	       $data['adgroup'] = $this->adgroup_model->find_adgroup($adgroup_id);
	    }
	    //获取规格
	    $data['size_name'] = $this->adinfo_model->get_size();
	    //获取推广计划
	    $data['campaign_name'] = $this->adinfo_model->get_campaign();
	    //获取推广组
	    $data['adgroup_name'] = $this->adinfo_model->get_adgroup();
	    //获取用户群
	    $data['customer_name'] = $this->adinfo_model->get_customer();
	    
	    if($this->input->post())
	    {
	        $adinfo = $this->input->post();
	        if($adinfo['id'])
	        {
	            $ad = $this->adinfo_model->edit_adinfo($adinfo['id']);
	            if($ad['title']!=$adinfo['title'] || $ad['type']!=$adinfo['type'] || $ad['size_id']!=$adinfo['size_id'] || $ad['content']!=$adinfo['content'] || $ad['comment']!=$adinfo['comment'] || $ad['link']!=$adinfo['link'] || $ad['start_time']!=strtotime($adinfo['start_time']) || $ad['end_time']!=strtotime($adinfo['end_time']))
	            {
	                $adinfo['status'] = 0;
	            }
	        }
	        
	        $upload_path = './uploads/'.date('Ym').'/';
	        if(!file_exists($upload_path))
	        {
	            mkdir($upload_path);
	        }
	        
	        $config['upload_path']      = $upload_path;  //图片上传路径
	        $config['allowed_types']    = 'jpg|png';  //支持上传的图片类型
	        $config['max_size']         = 2048;  //限制图片上传的最大值(KB)
            $config['max_width']        = 0;  //无限制图片的宽度
            $config['max_height']       = 0;  //无限制图片的高度
            $config['encrypt_name']     = TRUE;  //随机生成的图片名
	        $this->load->library('upload', $config);
	        $this->upload->do_upload('ad_file');
	        
	        if($adinfo['type']==1)
	        {
	            if(!file_exists('.'.$adinfo['content']))
	            {
    	            if(!$this->upload->data('file_name'))
    	            {
    	                if($adinfo['campaign_url'])
    	                {
    	                    header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/'.$adinfo['campaign_url'].'/0');
    	                }
    	                elseif($adinfo['adgroup_url'])
    	                {
    	                    header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/0/'.$adinfo['adgroup_url']);
    	                }
    	                else
    	                {
    	                    header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/0/0');
    	                }
            	        print('图片上传不成功或没有图片上传!3秒后返回...');
            	        exit;
    	            }
    	        }
	        }
            if($this->upload->data('file_name'))
            {
                if(($this->upload->data('image_type')!='jpeg' && $this->upload->data('image_type')!='png') || $this->upload->data('file_size') > 2048)
                {
                    if($adinfo['campaign_url'])
                    {
                        header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/'.$adinfo['campaign_url'].'/0');
                    }
                    elseif($adinfo['adgroup_url'])
                    {
                        header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/0/'.$adinfo['adgroup_url']);
                    }
                    else
                    {
                        header('refresh:3;url=/adinfo/add_adinfo/'.$adinfo['id'].'/0/0');
                    }
                    print('只支持大小最大值为2M、jpg/png的图片格式上传!3秒后返回...');
                    exit;
                }
                $adinfo['status'] = 0;
	            $adinfo['content'] = '/uploads/'.date('Ym').'/'.$this->upload->data('file_name');
	            //如果有原图则删除
	            if($adinfo['file_name'])
	            {
	                unlink('.'.$adinfo['file_name']);  
	            }
	        }else
	        {
	            $adinfo['content'] = $adinfo['content'];
	        }
	        
	        $res = $this->adinfo_model->add_adinfo($adinfo);
	        
	        //生成操作日志
	        if($adinfo['id'])
	        {
	            $content = '编辑广告标题为'.$adinfo['title'];
	        }else
	        {
	            $content = '新建广告标题为'.$adinfo['title'];
	        }
	        $user_log = array('content' => $content, 'user_id' => $this->session->userdata('id'));
	        $this->user_log_model->write_log($user_log);
	        
	        if($adinfo['campaign_url'])
	        {
	            redirect(base_url('/adinfo/adinfo_list/'.$adinfo['campaign_url'].'/0/0/1/id/desc/1'));
	        }
	        elseif($adinfo['adgroup_url'])
	        {
	            redirect(base_url('/adinfo/adinfo_list/0/'.$adinfo['adgroup_url'].'/0/1/id/desc/1'));
	        }
	        else
	        {
	            redirect(base_url('/adinfo/adinfo_list'));
	        }
	    }
	    
	    if($id)
	    {
	        $data['adinfo'] = $this->adinfo_model->edit_adinfo($id);
			
			if($data['adinfo']['val_region'] && $data['adinfo']['show_region'])
			{
				$data['val_region']=explode("|",$data['adinfo']['val_region']);
				$data['show_region']=explode("|",$data['adinfo']['show_region']);
			}
	    }
	    
	    $this->render('adinfo/add_adinfo', $data);
	}
	
	public function edit_adinfo($id, $campaign_id, $adgroup_id)
	{
	    redirect(base_url('/adinfo/add_adinfo/'.$id.'/'.$campaign_id.'/'.$adgroup_id));
	}
	
	public function del_adinfo($id)
	{
	    $this->load->model('adinfo_model');
	    $this->load->model('user_log_model');
	    
	    //查找广告
	    $adinfo_title = $this->adinfo_model->find_adinfo($id);
	     
	    //生成操作日志
	    $user_log = array('content' => '删除广告标题为'.$adinfo_title['title'], 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	    
	    $res = $this->adinfo_model->del_adinfo($id);
	    
	    echo json_encode($res);
	    exit;
	}
	
	//是否启用广告
	public function use_adinfo($status, $id)
	{
	    $this->load->model('adinfo_model');
	    $this->load->model('user_log_model');
	    $res = $this->adinfo_model->use_adinfo($status, $id);
	    
	    //查找广告
	    $adinfo_title = $this->adinfo_model->find_adinfo($id);
	    
	    //生成操作日志
	    $content = ($status==1)?'不启用':'启用';
	    $user_log = array('content' => $content.'广告标题为'.$adinfo_title['title'], 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	     
	    echo json_encode($res);
	    exit;
	}
	
	//Ajax二级联动通过推广计划ID获取推广组
	public function get_ajax_adgroup($campaign_id)
	{
	    $this->load->model('adgroup_model');
	    $res = $this->adgroup_model->get_ajax_adgroup($campaign_id);
	    
	    echo json_encode($res);
	    exit;
	}
	
	//获取广告的总数
	public function get_adinfo_num()
	{
	    $this->load->model('adinfo_model');
	    $res = $this->adinfo_model->get_adinfo_num();
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改广告标题
	public function edit_title($id, $title)
	{
	    $title = urldecode($title);
	    $this->load->model('adinfo_model');
	    $res = $this->adinfo_model->edit_title($id, $title);
	
	    echo json_encode($res);
	    exit;
	}
	
	//修改广告价格
	public function edit_price($id, $value)
	{
	    $this->load->model('adinfo_model');
	    $res = $this->adinfo_model->edit_price($id, $value);
	
	    echo json_encode($res);
	    exit;
	}

	public function view($ad_id=0)
    {
        if(!is_numeric($ad_id) || $ad_id<=0)
        {
			$result = array('ret'=>0,'content'=>'参数错误');
            print json_encode($result);
            exit;
        }

        $this->load->model('adinfo_model');
        $adinfo = $this->adinfo_model->get_adinfo_by_id($ad_id);
        $size_info = $this->adinfo_model->get_size_by_id($adinfo['size_id']);
        $result = array('ret'=>1,'width'=>$size_info['width'],'height'=>$size_info['height']);
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

    //按规格类型查找规格
    public function find_type_size($type)
    {
        $this->load->model('adinfo_model');
        $res = $this->adinfo_model->find_type_size($type);
    
        echo json_encode($res);
        exit;
    }
    
    //按规格ID查找规格宽高
    public function find_size($size_id)
    {
        $this->load->model('adinfo_model');
        $res = $this->adinfo_model->find_size($size_id);
    
        echo json_encode($res);
        exit;
    }
    
	/* public function test()
	{
        $upload_path = './uploads/'.date('Ym').'/';
        if(!file_exists($upload_path))
        {
            mkdir($upload_path);
        }

        $config['upload_path']      = $upload_path;
        $config['allowed_types']    = 'jpg|png';
        $config['max_size']     = 2048;
        $config['max_width']        = 0;
        $config['max_height']       = 0;
        $this->load->library('upload', $config);
        $this->upload->do_upload('image_file');
		print_r($this->upload->data);

	} */
}
