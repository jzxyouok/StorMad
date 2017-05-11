<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_model extends MY_Model {

	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 用户账户扣费
	 * @param $user_id int 用户ID
	 * @param $cost int 金额（单位：分）
	 */
	public function charge($user_id, $cost)
	{
		$this->db->where(array('id'=>$user_id));
		$this->db->set('money',"money - $cost",FALSE);
		$this->db->update('user');
	}

	/**
	 * 根据广告id，获取此广告的全部场景id
	 * @params $ad_id int 广告ID
	 */
	public function get_scene_by_ad_id($ad_id=0)
	{
		$this->db->select('customer_scene.scene_id,ad_scene.field_label,ad_scene.scene_name');
		$this->db->from('customer_scene');
		$this->db->join('ad_customers', 'customer_scene.customer_id = ad_customers.customer_id', 'left');
		$this->db->join('ad_scene', 'ad_scene.id = customer_scene.scene_id', 'left');
		$this->db->where('ad_customers.ad_id', $ad_id);
		return $this->db->get()->result_array();
	}

	/**
	 * 根据广告位id，获取此广告位的全部场景id
	 * @params $area_id int 广告位ID
	 */
	public function get_scene_by_area_id($area_id=0)
	{
		$this->db->select('area_scene.scene_id,ad_scene.field_label');
		$this->db->from('area_scene');
		$this->db->join('ad_scene', 'ad_scene.id = area_scene.scene_id', 'left');
		$this->db->where('area_scene.area_id', $area_id);
		return $this->db->get()->result_array();
	}
	
	/**
	 * 根据广告位id，获取此广告位的全部LBS
	 * @params $area_id int 广告位ID
	 */
	public function get_region_by_area_id($area_id=0)
	{
		$this->db->select('area_region.region_id,area_region.unlimited,area_region.fid,region.region_type');
		$this->db->from('area_region');
		$this->db->join('region', 'region.region_id = area_region.region_id', 'left');
		$this->db->where('area_region.area_id', $area_id);
		return $this->db->get()->result_array();
	}
	
	/**
	 * 根据广告位id，获取此广告位的全部LBS
	 * @params $area_id int 广告位ID
	 */
	public function get_region_by_ad_id($ad_id=0)
	{
		$this->db->select('ad_region.region_id,ad_region.unlimited,ad_region.fid,region.region_type,region.region_name');
		$this->db->from('ad_region');
		$this->db->join('region', 'region.region_id = ad_region.region_id', 'left');
		$this->db->where('ad_region.ad_id', $ad_id);
		return $this->db->get()->result_array();
	}	
	
	/**
	 * 根据广告位id，获取此广告位详细信息
	 * @params $area_id int 广告位ID
	 */
	public function get_area_by_id($area_id=0)
	{
		$this->db->select('*');
		$this->db->from('ad_area');
		$this->db->where('id', $area_id);
		return $this->db->get()->row_array();
	}

	/**
	 * 根据广告位id，获取广告位与广告关系表的信息
	 * @params $area_id int 广告位ID
	 */
	public function get_area_ads_info($area_id=0)
	{
		$this->db->select('ad_id');
		$this->db->from('area_ad');
		$this->db->where('ad_area_id', $area_id);
		return $this->db->get()->result_array();
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
	 * 根据广告主用户id，获取此广告主详细信息
	 * @params $id int 广告主用户ID
	 */
	public function get_user_by_id($id=0)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('id', $id);
		return $this->db->get()->row_array();
	}

	/**
	 * 根据推广计划id，获取此推广计划详细信息
	 * @params $id int 推广计划ID
	 */
	public function get_campaign_by_id($id=0)
	{
		$this->db->select('*');
		$this->db->from('campaign');
		$this->db->where('id', $id);
		return $this->db->get()->row_array();
	}

	/**
	 * 根据推广组id，获取此推广组详细信息
	 * @params $id int 推广组ID
	 */
	public function get_adgroup_by_id($id=0)
	{
		$this->db->select('*');
		$this->db->from('adgroup');
		$this->db->where('id', $id);
		return $this->db->get()->row_array();
	}

	/**
	 * 根据规格尺寸id，获取此规则尺寸详细信息
	 * @params $id int 规则尺寸ID
	 */
	public function get_size_by_id($id=0)
	{
		$this->db->select('*');
		$this->db->from('ad_size');
		$this->db->where('id', $id);
		return $this->db->get()->row_array();
	}

    /**
     * 获取全部正在推广的广告详细信息
	 * @param $condition array 获取条件：status:状态 update_time:更新时间 type:广告类型 size:规格id num:获取记录数,end_time 推广结束时间
	 * @return $result array
     */
    public function get_adinfo_list($condition = array())
    {
        $this->db->select('*');
        $this->db->from('adinfo');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
		if(array_key_exists('update_time',$condition))
		{
			$this->db->where('update_time>', $condition['update_time']);
		}
		$time_now = isset($condition['end_time']) ? $condition['end_time'] : time();
        $this->db->where('start_time<', $time_now);
        $this->db->where('end_time>', $time_now);
		if(isset($condition['type']))
		{
			$this->db->where('type',$condition['type']);
		}
		if(isset($condition['size_id']))
		{
			$this->db->where('size_id',$condition['size_id']);
		}
		if(isset($condition['ads_id']))
		{
			$this->db->where_in('id',$condition['ads_id']);
		}
		if(isset($condition['orderby']))
		{
			$direction = isset($condition['direction']) ? $condition['direction'] : '';
			$this->db->order_by($condition['orderby'],$direction);
		}
		if(isset($condition['num']))
		{
			$this->db->limit($condition['num']);
		}
        return $this->db->get()->result_array();
    }
	
    /**
     * 获取全部正在推广的广告主详细信息
	 * @params $condition array 条件(status:状态；update_time:更新时间)
     */
    public function get_user_list($condition=array())
    {
        $this->db->select('*');
        $this->db->from('user');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
		if(array_key_exists('update_time',$condition))
		{
			$this->db->where('update_time>', $condition['update_time']);
		}
        return $this->db->get()->result_array();
    }

    /**
     * 获取全部正在推广的推广计划详细信息
	 * @params $condition array 条件(status:状态；update_time:更新时间)
     */
    public function get_campaign_list($condition = array())
    {
        $this->db->select('*');
        $this->db->from('campaign');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
		if(array_key_exists('update_time',$condition))
		{
			$this->db->where('update_time>', $condition['update_time']);
		}
        return $this->db->get()->result_array();
    }

    /**
     * 获取全部正在推广的推广组详细信息
	 * @params $condition array 条件(status:状态；update_time:更新时间)
     */
    public function get_adgroup_list($condition = array())
    {
        $this->db->select('*');
        $this->db->from('adgroup');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
		if(array_key_exists('update_time',$condition))
		{
			$this->db->where('update_time>', $condition['update_time']);
		}
        return $this->db->get()->result_array();
    }

    /**
     * 获取全部正在启动状态的广告位详细信息
	 * @params $condition array 条件(status:状态；update_time:更新时间)
     */
    public function get_area_list($condition = array())
    {
        $this->db->select('*');
        $this->db->from('ad_area');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
		if(array_key_exists('update_time',$condition))
		{
			$this->db->where('update_time>', $condition['update_time']);
		}
        return $this->db->get()->result_array();
    }

    /**
     * 根据广告位ID获取随机广告ID
	 * @param $area_id int 广告位id
	 * @return $ad_id int 广告id 
     */
    public function get_random_ad_by_area_id($area_id)
    {
        $this->db->select('adinfo.id');
        $this->db->from('adinfo');
        $this->db->join('ad_area','adinfo.type=ad_area.type and adinfo.size_id=ad_area.size_id','left');
        $this->db->where('ad_area.id', $area_id);
		$this->db->order_by('rand()','ASC');
		$this->db->limit(1);
        $result = $this->db->get()->row_array();
		return $result['id'];
    }

    /**
     * 获取全部渠道正在启动状态的广告位详细信息
	 * @params $condition array 条件(status:广告位状态；)
	 * @params $channel_id int 渠道ID
     */
    public function get_area_by_channel_id($channel_id,$condition = array())
    {
        $this->db->select('channel_ad_area.ad_area_id,ad_area.type');
        $this->db->from('channel_ad_area');
		$this->db->join('ad_area','ad_area.id=channel_ad_area.ad_area_id', 'left');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('ad_area.status', $condition['status']);
		}
		$this->db->where('channel_ad_area.channel_id', $channel_id);
		
        return $this->db->get()->result_array();
    }
	
	/**
     * 获取全部渠道的信息
	 * @params $condition array 条件(status:状态；update_time:更新时间)
     */
	public function get_channel_list($condition = array())
	{
		$this->db->select('id,distribution_id');
        $this->db->from('channel');
		if(array_key_exists('status',$condition))
		{
			$this->db->where('status', $condition['status']);
		}
        return $this->db->get()->result_array();	
	}
	
	//更新广告权重
	public function update_ad_weight($ad_id)
	{
		//初始化数据
		$ctr=0;
		$weight=0;
		
		$time=strtotime("-1 hours",time());
		
		$ad=$this->get_ad_report_info($ad_id,$time);
		$wow=$this->get_ad_weight($ad_id);
		
		//只要有曝光证明广告已经投出去。无论点击是否为0.都要计算。
		if($ad && $ad['impressions']>0)
		{
			$weight=round($wow['price']*$ad['clicks'],5);
		}
		else
		{
			$weight=$wow['weight'];
		}

		$where = array('id' => $ad_id);
        $this->update('adinfo',array('weight'=>$weight), $where);
	}
	
	//获取广告的CTR
	public function get_ad_report_info($ad_id,$time)
	{
		$this->db->select('sum(clicks) clicks, sum(impressions) impressions');
		$this->db->where(array('ad_id' => $ad_id, 'start_time <' => $time));
		
		return $this->db->get('ad_report')->row_array();
	}
	
	//获取广告权重和点击单价
	public function get_ad_weight($ad_id)
	{
		$this->db->select('weight,price');
		$this->db->where('id', $ad_id);
		return $this->db->get('adinfo')->row_array();
	}
}
