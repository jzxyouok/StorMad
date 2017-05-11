<?php
class User_money extends MY_Controller {

    
    public function money_list($start_time=0, $end_time=0, $cur_page=1)
	{
		if($this->session->userdata('id')==10)
		{
			header("Location: http://bp.stormad.cn");	
		}
		
	    $start_time = urldecode($start_time);
	    $end_time = urldecode($end_time);
	    $this->layout_data['title'] = "账户中心";
	    $this->load->model('user_money_model');
	    $conditions = array();
	    
	    if($start_time)
	    {
	        $conditions['start_time'] = $data['start_time'] = $start_time;
	    }
	    if($end_time)
	    {
	        $conditions['end_time'] = $data['end_time'] = $end_time;
	    }
	     
	    //分页配置
	    $this->load->library('pagination');
	    $this->load->config('pagination');
	    $config = $this->config->item('pagination_common_list');
	    $config['base_url'] = base_url()."/user_money/money_list/{$start_time}/{$end_time}/";
	    $config['total_rows'] = $this->user_money_model->get_money_log_count($conditions, $this->session->userdata('id'));
	    $offset = $config['per_page'] * ($cur_page - 1);
	    
	    $this->pagination->initialize($config);
	    $data['page'] = $this->pagination->create_links();
	    
	    //获取广告主资源数据
	    $conditions['offset'] = $offset;
	    $conditions['page_size'] = $config['per_page'];
	    $data['money'] = $this->user_money_model->get_user_money($conditions, $this->session->userdata('id'));
	    
	    $data['url'] = base_url()."/user_money/money_list/{$start_time}/{$end_time}/";
	    $data['total_page'] = ceil($config['total_rows']/$config['per_page']);
	    $data['cur_page'] = $cur_page;
	    
		$this->render('user_money/money_list', $data);
	}
	
	public function update_password()
	{
	    $this->layout_data['title'] = "修改密码";
	    $this->load->model('user_money_model');
	    
	    if($this->input->post())
	    {
	        $password = $this->input->post();
	        $this->user_money_model->update_password($password);
	         
	        redirect(base_url('/user_money/money_list'));
	    }
	    
	    $this->render('user_money/update_password', $data);
	}
	
	//验证密码
	public function check_password($old_password)
	{
	    $this->load->model('user_money_model');
	    $res = $this->user_money_model->check_password($old_password, $this->session->userdata('id'));
	    
	    echo json_encode($res);
	    exit;
	}
	
	//下载日志
	public function download()
	{
	    $this->load->model('user_money_model');
	    $conditions = array();
	    $money_log = $this->user_money_model->get_user_money($conditions, $this->session->userdata('id'));
	    
	    header('Content-Type: text/comma-separated-values;charset=gb2312;');
	    header('Content-Encoding: none');  //内容不加密，gzip等，可选
	    header('Accept-Renges:bytes');
	    header('Content-Disposition: attachment; filename="accout.csv"');
	    
	    print mb_convert_encoding("日期,支出,收入,账户余额(元),备注","GBK","UTF-8");
	    print "\n";
	    foreach ($money_log as $k=>$val) {
	        $date = date('Y-m-d H:i:s', $val['add_time']);
	        $my_money = sprintf("%1\$.2f", $val['remain_sum']/100);
	        $comment = $val['comment'];
	        if($val['type']==1) {
	            $spend = sprintf("%1\$.2f", $val['money']/100);
	            print $date.",".$spend.",0.00,".$my_money.",".mb_convert_encoding($comment,"GBK","UTF-8")."\n";
	        }
	        if($val['type']==2) {
	            $income = sprintf("%1\$.2f", $val['money']/100);
	            print $date.",0.00,".$income.",".$my_money.",".mb_convert_encoding($comment,"GBK","UTF-8")."\n";
	        }
	    }
	    exit;
	}
}
