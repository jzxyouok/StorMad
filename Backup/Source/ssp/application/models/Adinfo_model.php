<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adinfo_model extends MY_Model {

    
    public function __construct()
    {
        parent::__construct();
    }
    
    //获取规格
    public function get_size()
    {
        $this->db->select('id, size_name, width, height');
        $query = $this->db->get('ad_size')->result_array();
        
        return $query;
    }
    //按规格类型查找规格
    public function find_type_size($type)
    {
        $this->db->where('type', $type);
        $query = $this->db->get('ad_size');
    
        return $query->result_array();
    }
    //按规格ID查找规格宽高
    public function find_size($size_id)
    {
        $this->db->select('id, size_name, width, height');
        $this->db->where('id', $size_id);
        $query = $this->db->get('ad_size')->row_array();
    
        return $query;
    }
    
    //获取广告资源数据总数
    public function get_adinfo_count($conditions, $user_id)
    {
        if(isset($conditions['title']) && $conditions['title'])
        {
            $this->db->like('title', $conditions['title']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get('adinfo')->num_rows();  
        return $query;
    }
    
	//获取广告信息
    public function get_adinfo($conditions, $user_id)
    {
        $this->db->select('adinfo.id,adinfo.title,adinfo.type,adinfo.comment,adinfo.price,adinfo.status,ad_size.width,ad_size.height,ad_size.size_name,channel_ad_area.ad_area_id');
        $this->db->from('adinfo');
        $this->db->join('ad_size', 'ad_size.id = adinfo.size_id', 'left');
		$this->db->join('ad_area', 'ad_area.size_id = ad_size.id', 'left');
		$this->db->join('channel_ad_area', 'channel_ad_area.ad_area_id = ad_area.id', 'left');
        $this->db->order_by('adinfo.id', 'DESC');
        

        if(isset($conditions['title']) && $conditions['title'])
        {
            $this->db->like('adinfo.title', $conditions['title']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
		$this->db->where('ad_area.status', 1);
        $this->db->where('channel_ad_area.channel_id', $user_id);
        $this->db->where('adinfo.status', 2);
		
        $query = $this->db->get()->result_array();
        return $query;
    }
	
	//查找广告
	public function find_adinfo($id)
	{
	    $this->db->select('title');
	    $this->db->where('id', $id);
	    $query = $this->db->get('adinfo');
	
	    return $query->row_array();
	}
	
	//查找广告位名称
	public function find_ad_area_name($ad_area_id)
	{
	    $this->db->select('area_name');
	    $this->db->where('id', $ad_area_id);
	    $query = $this->db->get('ad_area');
	
	    return $query->row_array();
	}

	//获取广告详细
	public function get_ad_detail($id)
    {
        $this->db->select('adinfo.id,adinfo.title,adinfo.type,adinfo.comment,adinfo.link,adinfo.start_time,adinfo.end_time,adinfo.content,ad_size.width,ad_size.height,ad_size.size_name');
        $this->db->from('adinfo');
        $this->db->join('ad_size', 'ad_size.id = adinfo.size_id', 'left');
     

        if(isset($conditions['title']) && $conditions['title'])
        {
            $this->db->like('adinfo.title', $conditions['title']);
        }
        if(isset($conditions['page_size']) && isset($conditions['offset']))
        {
            $this->db->limit($conditions['page_size'],$conditions['offset']);
        }
        $this->db->where('adinfo.id', $id);
        $this->db->where('adinfo.status', 2);
		
        $query = $this->db->get()->row_array();
        return $query;
    }
	
	//是否启用广告
	public function use_adinfo($status, $ad_id, $ad_area_id)
	{
		//检查关系
		$area_ad_id=$this->check_adarea_ad($ad_id, $ad_area_id);
		$res=array();
		
		//启动广告，加入广告与渠道关系表
		if($status==1)
		{
			//如果不存在关系，则插入广告与渠道关系
			if(!$area_ad_id)	
			{
				$data = array(
						'ad_area_id' => $ad_area_id,
						'ad_id' => $ad_id,
						'add_time' => time()
				);

				$this->insert('area_ad', $data);
				$res['error']=0;
				$res['status']=1;
			}
		}
		
		if($status==2)
		{
			//如果存在关系，则删除广告与渠道关系
			if($area_ad_id)	
			{
				$this->db->where('id', $area_ad_id['id']);
	     		$this->db->delete('area_ad');			
				$res['error']=0;
				$res['status']=2;
			}	
		}
	    
	    return $res;
	}
	
	//检查某广告位下，是否存在广告
	public function check_adarea_ad($ad_id, $ad_area_id)
	{
		$this->db->select("id");
		$this->db->from('area_ad');
		$this->db->where("ad_area_id",$ad_area_id);
		$this->db->where("ad_id",$ad_id);
		$res = $this->db->get()->row_array();

	    return $res;
	}
	
	//获取广告的总数
	public function get_adinfo_num()
	{
	    $this->db->where('user_id', $this->session->userdata('id'));
	    $res = $this->db->get('adinfo')->num_rows();
	
	    return $res;
	}

    /**
     * 根据广告id，获取此广告详细信息
     * @params $id int 广告ID
     */
    public function get_adinfo_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('adinfo');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * 根据广告规格id，获取此广告规则详细信息
     * @params $id int 广告规则ID
     */
    public function get_size_by_id($id=0)
    {
        $this->db->select('*');
        $this->db->from('ad_size');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }
	
	//获取渠道下所有广告位
	public function get_all_area($userid)
    {
        $this->db->select('channel_ad_area.ad_area_id,ad_area.area_name');
		$this->db->join('ad_area', 'ad_area.id = channel_ad_area.ad_area_id', 'left');
		
        $this->db->from('channel_ad_area');
		$this->db->where('ad_area.status', 1);
        $this->db->where('channel_ad_area.channel_id', $userid);
        return $this->db->get()->result_array();
    }
}
