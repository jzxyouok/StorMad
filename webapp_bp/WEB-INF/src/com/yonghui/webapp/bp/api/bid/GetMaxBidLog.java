package com.yonghui.webapp.bp.api.bid;

import java.io.IOException;
import java.io.Writer;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdLocationService;
import com.yonghui.comp.ad.share.bean.AdLocation;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bid.share.BidClient;
import com.yonghui.comp.bid.share.BidService;
import com.yonghui.comp.bid.share.bean.BidLog;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.comp.bidplan.share.enums.ChargeEnum;
import com.yonghui.comp.common.share.AreaShopService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.CommonService;
import com.yonghui.comp.common.share.bean.BaseShop;
import com.yonghui.comp.common.share.bean.Industry;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.resp.bid.GetMaxBidLogResp;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.RespException;

/**
 * 
 * <br>
 * <b>功能：</b>获取广告主在某一个档期中出价的最高纪录<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetMaxBidLog implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		BidService service = BidClient.getBidService();
		
		int adUin = ader.getAdUin();
		int bpId = NetUtil.getIntParameter(request, "bpId", 0);
		String iId = NetUtil.getStringParameter( request, "iId", "");
		
		if (bpId < 1 || StringUtil.isEmpty(iId)) {
			throw new RuntimeException("参数异常!");
		}

		RespWrapper<BidLog> result = service.getMaxBidLogByAdUin(adUin, bpId, iId);
		JsonUtil.MAPPER.writeValue( out, getRespPage(result));
	}
	
	/**
	 * 
	 * <br>
	 * <b>功能：</b>封装前端显示所需参数<br>
	 * <b>日期：</b>2016年11月17日<br>
	 * <b>作者：</b>RUSH<br>
	 *
	 * @param adInfoPageWrapper
	 * @return
	 */
	public static RespWrapper<GetMaxBidLogResp> getRespPage(RespWrapper<BidLog> blWrapper) {
		
		RespWrapper<GetMaxBidLogResp> resp = RespWrapper.makeResp(blWrapper.getErrCode(), blWrapper.getErrMsg(), null);
		BidLog bidLog = blWrapper.getObj();
		
		if (bidLog != null) {
			GetMaxBidLogResp respBean = new GetMaxBidLogResp();
			int bpId = bidLog.getBpId();
			String iId = bidLog.getIId();
			BidPlanService bpService = BidPlanClient.getBidPlanService();
			RespWrapper<BidPlanEntity> bidPlanWrapper = bpService.findBidPlanById(bpId);
			BidPlanEntity bidPlan = bidPlanWrapper.getObj();
			if (bidPlanWrapper.getErrCode() != 0 || bidPlan == null) 
				throw new RespException(bidPlanWrapper.getErrCode(), bidPlanWrapper.getErrMsg());
			CommonService commonService = CommonClient.getCommonService();
			RespWrapper<Industry> industryWrapper = commonService.getOneIndustry(iId);
			Industry industry = industryWrapper.getObj();
			if (industryWrapper.getErrCode() != 0 || industry == null) 
				throw new RespException(RespException.UNKOWN_ERROR, "系统异常["+industryWrapper.getErrCode()+"]，请稍后再试!");
			String bpName = bidPlan.getBpName();	//档期名称	
			long cStartDate = bidPlan.getStartDate();	//档期投放开始日期
			long cEndDate = bidPlan.getEndDate();		//档期投放结束日期
			long cStartTime = bidPlan.getStartTime();	//档期投放开始时间点
			long cEndTime = bidPlan.getEndTime();		//档期投放结束时间点
			long bidStartTime = bidPlan.getCStartTime();	//竞拍开始时间
			long bidEndTime = bidPlan.getCEndTime();		//竞拍结束时间
			int chargeType = bidPlan.getChargeType();	//收费方式
			int cBasePrice = bidPlan.getCBasePrice();	//竞拍底价
			int cIncRange = bidPlan.getCIncRange();	//竞拍加价幅度
			String iName = industry.getIName();	//行业名称
			String alIds = bidPlan.getAlIds();	//参与档期竞拍的广告位ID列表用 小写逗号分隔 ','
			String shopCodes = bidPlan.getShopCodes();	//参与档期竞拍的门店ID列表用 小写逗号分隔 ','
			List<AdLocation> als = new ArrayList<AdLocation>();
			if (StringUtil.isEmpty(alIds)) 
				throw new RespException(RespException.UNKOWN_ERROR, "系统异常[广告位信息获取失败]，请稍后再试!");
			String _alIds[] = alIds.split(",");
			AdLocationService alService = AdClient.getAdLocationService();
			for (String _alId : _alIds) {
				RespWrapper<AdLocation> alWrapper = alService.getOneAdLocation(Integer.valueOf(_alId));
				AdLocation al = alWrapper.getObj();
				if (alWrapper.getErrCode() != 0 || al == null) 
					throw new RespException(RespException.UNKOWN_ERROR, "系统异常["+alWrapper.getErrCode()+"]，请稍后再试!");
				als.add(al);
			}
			String [] _shopCodes = shopCodes.split(",");
			AreaShopService areaShopService = CommonClient.getAreaShopService();
			RespWrapper<List<BaseShop>> baseShops = areaShopService.getBaseShopsByShopCodes(Arrays.asList(_shopCodes));
			List<BaseShop> shops = baseShops.getObj();
			respBean.setBlId(bidLog.getBlId());
			respBean.setBpId(bidLog.getBpId());
			respBean.setBpName(bpName);
			respBean.setCStartDate(cStartDate);
			respBean.setCEndDate(cEndDate);
			respBean.setCStartTime(cStartTime);
			respBean.setCEndTime(cEndTime);
			respBean.setBidStartTime(bidStartTime);
			respBean.setBidEndTime(bidEndTime);
			respBean.setIId(iId);
			respBean.setIName(iName);
			respBean.setAlList(als);
			respBean.setShops(shops);
			respBean.setChargeType(chargeType);
			respBean.setChargeTypeName(ChargeEnum.getChargeCN(chargeType));
			respBean.setCBasePrice(cBasePrice);
			respBean.setMaxMoney(bidLog.getMaxMoney());
			respBean.setCIncRange(cIncRange);
			respBean.setMoney(bidLog.getMoney());
			resp.setObj(respBean);
		}
		return resp;
	}
}