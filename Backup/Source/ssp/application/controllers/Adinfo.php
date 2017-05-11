<?php
class Adinfo extends MY_Controller {

    private $order_field='';
    private $order_type='';
    
    public function adinfo_list($title=0, $cur_page=1)
	{
	    $title = urldecode($title);
	    
	    $this->layout_data['title'] = "广告审核";
	    $this->load->model('adinfo_model');
	    $conditions = array();
	    
	    if($title)
	    {
	        $conditions['title'] = $data['adinfo_title'] = $title;
	    }
	    
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/adinfo/adinfo_list/{$title}/";
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    //获取广告资源数据
	    $data['adinfo'] = $this->adinfo_model->get_adinfo($conditions, $this->session->userdata('id'));
	    $config['total_rows'] = count($data['adinfo']);
	    
		//检查广告位与广告关系设置状态
		foreach($data['adinfo'] as $k=>$row)
		{
			$area_ad_id=$this->adinfo_model->check_adarea_ad($row['id'], $row['ad_area_id']);
			if($area_ad_id)
			{
				$data['adinfo'][$k]['status']=1;	
			}
			else
			{
				$data['adinfo'][$k]['status']=2;		
			}
		}

	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    $data['url'] = base_url()."/adinfo/adinfo_list/{$title}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
        if(count($data['adinfo']) > 10)
        {
            $data['adinfo'] = $adinfo_array = array_chunk($data['adinfo'], 10);
            $adinfo_array = $data['adinfo'][$cur_page - 1];
        }
		else
		{
            $adinfo_array = $data['adinfo'];
        }

	    $data['adinfo'] = $adinfo_array;
	    
		$this->render('adinfo/adinfo_list', $data);
	}
	
	//是否启用广告
	public function use_adinfo($status, $id, $ad_area_id)
	{
	    $this->load->model('adinfo_model');
	    $this->load->model('user_log_model');
		$res=array();
		
	    //查找广告
	    $adinfo = $this->adinfo_model->find_adinfo($id);
		$areainfo = $this->adinfo_model->find_ad_area_name($ad_area_id);

		//检查广告是否存在
		if(!$adinfo)
		{
			$res['error']=1;
			echo json_encode($res);
	    	exit;
		}
		
		//检查广告位是否存在
		if(!$areainfo)
		{
			$res['error']=2;
			echo json_encode($res);
	    	exit;
		}
		
	    $res = $this->adinfo_model->use_adinfo($status, $id, $ad_area_id);
	    
	    //生成操作日志
	    $content = ($status==1)?'启用':'暂停';
	    $user_log = array('content' => $content.'广告：'.$adinfo['title']."投放", 'user_id' => $this->session->userdata('id'));
	    $this->user_log_model->write_log($user_log);
	     
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
            $result['content'] = '<a href="'.$adinfo['link'].'" target="_blank"><img style="width:auto;height:auto;max-width:100%;max-height:100%;" src="http://bp.stormad.cn/'.$adinfo['content'].'"/></a>';
        }
        else
        {
            $result['content'] = '<a href="'.$adinfo['link'].'" target="_blank">'.$adinfo['content'].'</a>';
        }
        print json_encode($result);
    }
	
	public function ad_detail($ad_id=0)
	{
		$this->load->model('adinfo_model');
		$this->layout_data['title'] = "广告详情";
		$res=array();
		
	    //查找广告
	    $data['adinfo'] = $this->adinfo_model->get_ad_detail($ad_id);

		//检查广告是否存在
		if(!$data['adinfo'])
		{
			$res['error']=1;
			echo json_encode($res);
	    	exit;
		}
		
		$this->render('adinfo/ad_detail', $data);
	}
	
	public function check_adinfo($ad_id=0)
	{
		$this->load->model('adinfo_model');
        $adinfo = $this->adinfo_model->find_adinfo($ad_id);	
		
		if($adinfo)
		{
			$res['error']=0;
			echo json_encode($res);
	    	exit;	
		}
		else
		{
			$res['error']=1;
			echo json_encode($res);
	    	exit;	
		}
	}
}
