package com.yonghui.webapp.bss.resp.ad;

import java.io.Serializable;

import com.yonghui.comp.ad.share.bean.AdSize;
import com.yonghui.comp.ad.share.enums.AdStatus;
import com.yonghui.comp.ad.share.enums.AdType;

public class FindAdInfoPageResp implements Serializable {
	
	private static final long serialVersionUID = 3127608935064723583L;
	
	private int adId;	//ID
	private String aderName; //广告主名称
	private String title;	//广告标题
	private AdType adType;	//广告类型
	private String content;	//文本内容
	private String imgUrl;	//广告图片路径
	private String link;	//广告链接地址
	private AdSize adSize;	//广告规格
	private AdStatus adStatus;	//广告状态
	private long createTime;	//创建时间
	private long optime;	//操作时间
	
	public int getAdId() {
		return adId;
	}
	public void setAdId(int adId) {
		this.adId = adId;
	}
	public String getAderName() {
		return aderName;
	}
	public void setAderName(String aderName) {
		this.aderName = aderName;
	}
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}
	public AdType getAdType() {
		return adType;
	}
	public void setAdType(AdType adType) {
		this.adType = adType;
	}
	public String getContent() {
		return content;
	}
	public void setContent(String content) {
		this.content = content;
	}
	public String getImgUrl() {
		return imgUrl;
	}
	public void setImgUrl(String imgUrl) {
		this.imgUrl = imgUrl;
	}
	public String getLink() {
		return link;
	}
	public void setLink(String link) {
		this.link = link;
	}
	public AdSize getAdSize() {
		return adSize;
	}
	public void setAdSize(AdSize adSize) {
		this.adSize = adSize;
	}
	public AdStatus getAdStatus() {
		return adStatus;
	}
	public void setAdStatus(AdStatus adStatus) {
		this.adStatus = adStatus;
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
		return "FindAdInfoPageResp [adId=" + adId + ", aderName=" + aderName
				+ ", title=" + title + ", adType=" + adType + ", content="
				+ content + ", imgUrl=" + imgUrl + ", link=" + link
				+ ", adSize=" + adSize + ", adStatus=" + adStatus
				+ ", createTime=" + createTime + ", optime=" + optime + "]";
	}
}
