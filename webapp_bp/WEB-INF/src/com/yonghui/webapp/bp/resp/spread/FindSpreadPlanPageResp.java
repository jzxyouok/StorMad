package com.yonghui.webapp.bp.resp.spread;

import java.io.Serializable;

public class FindSpreadPlanPageResp implements Serializable {

	private static final long serialVersionUID = -8372049887574827901L;
	
	private int spId;	//ID
	private String spName;	//推广计划名称
	private int spStatus;	//推广计划状态：0未推广 1推广中
	private int sgCount;	//推广组个数
	private int adCount;	//广告条数
	private int pv;		//展现量(曝光量)
	private int click;	//点击量
	private int ctr;	//点击率 整数 最高为100
	private long createTime;	//创建时间
	private long optime;	//操作时间
	
	public int getSpId() {
		return spId;
	}
	public void setSpId(int spId) {
		this.spId = spId;
	}
	public String getSpName() {
		return spName;
	}
	public void setSpName(String spName) {
		this.spName = spName;
	}
	public int getSpStatus() {
		return spStatus;
	}
	public void setSpStatus(int spStatus) {
		this.spStatus = spStatus;
	}
	public int getSgCount() {
		return sgCount;
	}
	public void setSgCount(int sgCount) {
		this.sgCount = sgCount;
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
}
