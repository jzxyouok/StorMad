package com.yonghui.webapp.bp.resp.bid;

import java.io.Serializable;
import java.util.List;

import com.yonghui.comp.ad.share.bean.AdLocation;
import com.yonghui.comp.common.share.bean.BaseShop;

public class GetMaxBidLogResp implements Serializable {

	private static final long serialVersionUID = 242763397739831339L;
	
	private String blId;	//标识字段
	private int bpId;	//档期ID
	private String bpName;	//档期名称
	private long cStartDate;	//投放开始日期
	private long cEndDate;	//投放结束日期
	private long cStartTime;	//投放开始时间
	private long cEndTime;	//投放结束时间
	private long bidStartTime;		//竞拍开始时间
	private long bidEndTime;		//竞拍结束时间
	private String iId;			//行业ID
	private String iName;	//行业名称
	private List<AdLocation> alList;	//广告位列表
	private List<BaseShop> shops;	//门店列表
	private int chargeType;	//收费方式：1、按时段收费
	private String chargeTypeName;	//收费方式名称
	private int cBasePrice;	//竞拍底价
	private int maxMoney;	//当期档期行业最高出价
	private int cIncRange;	//竞拍加价幅度
	private int money;	//竞投金额
	
	public String getBlId() {
		return blId;
	}
	public void setBlId(String blId) {
		this.blId = blId;
	}
	public int getBpId() {
		return bpId;
	}
	public void setBpId(int bpId) {
		this.bpId = bpId;
	}
	public String getBpName() {
		return bpName;
	}
	public void setBpName(String bpName) {
		this.bpName = bpName;
	}
	public long getCStartDate() {
		return cStartDate;
	}
	public void setCStartDate(long cStartDate) {
		this.cStartDate = cStartDate;
	}
	public long getCEndDate() {
		return cEndDate;
	}
	public void setCEndDate(long cEndDate) {
		this.cEndDate = cEndDate;
	}
	public long getCStartTime() {
		return cStartTime;
	}
	public void setCStartTime(long cStartTime) {
		this.cStartTime = cStartTime;
	}
	public long getCEndTime() {
		return cEndTime;
	}
	public void setCEndTime(long cEndTime) {
		this.cEndTime = cEndTime;
	}
	public long getBidStartTime() {
		return bidStartTime;
	}
	public void setBidStartTime(long bidStartTime) {
		this.bidStartTime = bidStartTime;
	}
	public long getBidEndTime() {
		return bidEndTime;
	}
	public void setBidEndTime(long bidEndTime) {
		this.bidEndTime = bidEndTime;
	}
	public String getIId() {
		return iId;
	}
	public void setIId(String iId) {
		this.iId = iId;
	}
	public String getIName() {
		return iName;
	}
	public void setIName(String iName) {
		this.iName = iName;
	}
	public List<AdLocation> getAlList() {
		return alList;
	}
	public void setAlList(List<AdLocation> alList) {
		this.alList = alList;
	}
	public List<BaseShop> getShops() {
		return shops;
	}
	public void setShops(List<BaseShop> shops) {
		this.shops = shops;
	}
	public int getChargeType() {
		return chargeType;
	}
	public void setChargeType(int chargeType) {
		this.chargeType = chargeType;
	}
	public String getChargeTypeName() {
		return chargeTypeName;
	}
	public void setChargeTypeName(String chargeTypeName) {
		this.chargeTypeName = chargeTypeName;
	}
	public int getCBasePrice() {
		return cBasePrice;
	}
	public void setCBasePrice(int cBasePrice) {
		this.cBasePrice = cBasePrice;
	}
	public int getMaxMoney() {
		return maxMoney;
	}
	public void setMaxMoney(int maxMoney) {
		this.maxMoney = maxMoney;
	}
	public int getCIncRange() {
		return cIncRange;
	}
	public void setCIncRange(int cIncRange) {
		this.cIncRange = cIncRange;
	}
	public int getMoney() {
		return money;
	}
	public void setMoney(int money) {
		this.money = money;
	}
	@Override
	public String toString() {
		return "GetMaxBidLogResp [blId=" + blId + ", bpId=" + bpId
				+ ", bpName=" + bpName + ", cStartDate=" + cStartDate
				+ ", cEndDate=" + cEndDate + ", cStartTime=" + cStartTime
				+ ", cEndTime=" + cEndTime + ", bidStartTime=" + bidStartTime
				+ ", bidEndTime=" + bidEndTime + ", iName=" + iName
				+ ", alList=" + alList + ", chargeType=" + chargeType
				+ ", cBasePrice=" + cBasePrice + ", maxMoney=" + maxMoney
				+ ", cIncRange=" + cIncRange + ", money=" + money + "]";
	}
}
