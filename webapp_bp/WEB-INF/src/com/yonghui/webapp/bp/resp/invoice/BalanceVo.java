package com.yonghui.webapp.bp.resp.invoice;

import java.io.Serializable;

import com.yonghui.comp.money.share.bean.BalanceEntity;

public class BalanceVo extends BalanceEntity implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = 7321072142572695211L;
	
	public static BalanceVo wrapper(BalanceEntity entity, String bpName, String industryName, String invoiceStatusCN, String bidStatus, long amount) {
		BalanceVo vo = new BalanceVo();
		entity.copy(vo);
		vo.setBpName(bpName);
		vo.setIndustryName(industryName);
		vo.setInvoiceStatusCN(invoiceStatusCN);
		vo.setCash(Math.abs(entity.getCash()));
		vo.setGoods(Math.abs(entity.getGoods()));
		vo.setBidStatus(bidStatus);
		vo.setAmount(amount);
		
		return vo;
	}
	
	private String bpName;
	
	private String industryName;
	
	private String invoiceStatusCN;
	
	private String bidStatus;
	
	private long amount;

	public String getBpName() {
		return bpName;
	}

	public void setBpName(String bpName) {
		this.bpName = bpName;
	}

	public String getIndustryName() {
		return industryName;
	}

	public void setIndustryName(String industryName) {
		this.industryName = industryName;
	}

	public String getInvoiceStatusCN() {
		return invoiceStatusCN;
	}

	public void setInvoiceStatusCN(String invoiceStatusCN) {
		this.invoiceStatusCN = invoiceStatusCN;
	}

	/**
	 * @return the bidStatus
	 */
	public String getBidStatus() {
		return bidStatus;
	}

	/**
	 * @param bidStatus the bidStatus to set
	 */
	public void setBidStatus(String bidStatus) {
		this.bidStatus = bidStatus;
	}

	/**
	 * @return the amount
	 */
	public long getAmount() {
		return amount;
	}

	/**
	 * @param amount the amount to set
	 */
	public void setAmount(long amount) {
		this.amount = amount;
	}
}
