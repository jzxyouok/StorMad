package com.yonghui.webapp.bp.resp.spread;

import java.io.Serializable;

import com.yonghui.comp.ad.share.bean.SpreadPlan;

public class FindSpreadGroupPageResp implements Serializable {

	private static final long serialVersionUID = 4972608471648195147L;
	
	private int sgId;	//ID
	private String sgName;	//推广组名称
	private SpreadPlan spreadPlan;	//推广计划
	private int sgStatus;	//推广组状态：0未推广 1推广中
	private int adCount;	//广告条数
	private int pv;		//展现量(曝光量)
	private int click;	//点击量
	private int ctr;	//点击率 整数 最高为100
	private long createTime;	//创建时间
	private long optime;	//操作时间
	
	public int getSgId() {
		return sgId;
	}
	public void setSgId(int sgId) {
		this.sgId = sgId;
	}
	public String getSgName() {
		return sgName;
	}
	public void setSgName(String sgName) {
		this.sgName = sgName;
	}
	public SpreadPlan getSpreadPlan() {
		return spreadPlan;
	}
	public void setSpreadPlan(SpreadPlan spreadPlan) {
		this.spreadPlan = spreadPlan;
	}
	public int getSgStatus() {
		return sgStatus;
	}
	public void setSgStatus(int sgStatus) {
		this.sgStatus = sgStatus;
	}
	public int getAdCount() {
		return adCount;
	}
	public void setAdCount(int adCount) {
		this.adCount = adCount;
	}
	public int getPv() {
		return pv;
	}
	public void setPv(int pv) {
		this.pv = pv;
	}
	public int getClick() {
		return click;
	}
	public void setClick(int click) {
		this.click = click;
	}
	public int getCtr() {
		return ctr;
	}
	public void setCtr(int ctr) {
		this.ctr = ctr;
	}
	public long getCreateTime() {
		return createTime;
	}
	public void setCreateTime(long createTime) {
		this.createTime = createTime;
	}
	public long getOptime() {
		return optime;
	}
	public void setOptime(long optime) {
		this.optime = optime;
	}
	@Override
	public String toString() {
		return "FindSpreadGroupPageResp [sgId=" + sgId + ", sgName=" + sgName
				+ ", spreadPlan=" + spreadPlan + ", sgStatus=" + sgStatus
				+ ", adCount=" + adCount + ", pv=" + pv + ", click=" + click
				+ ", ctr=" + ctr + ", createTime=" + createTime + ", optime="
				+ optime + "]";
	}
}
