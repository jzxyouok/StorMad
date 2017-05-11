package com.yonghui.webapp.bss.api.bidplan;

import java.io.IOException;
import java.io.Writer;
import java.util.Calendar;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.comp.bidplan.share.enums.BidPlanStatus;
import com.yonghui.comp.common.share.AreaShopService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.bean.Area;
import com.yonghui.comp.common.share.bean.Shop;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.MoneyUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class CreateHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Integer> resp = RespWrapper.makeResp(4001, "新增档期失败", null);
		
		String bpName = request.getParameter("bpName");
		String iids = request.getParameter("iids");
		long startDate = StringUtil.convertLong(request.getParameter("startDate"), 0);
		long endDate = StringUtil.convertLong(request.getParameter("endDate"), 0);
		long startTime = StringUtil.convertLong(request.getParameter("startTime"), 0);
		long endTime = StringUtil.convertLong(request.getParameter("endTime"), 0);
		long cStartTime = StringUtil.convertLong(request.getParameter("cStartTime"), 0);
		long cEndTime = StringUtil.convertLong(request.getParameter("cEndTime"), 0);
		int repeatType = StringUtil.convertInt(request.getParameter("repeatType"), 0);
		int chargeType = StringUtil.convertInt(request.getParameter("chargeType"), 0);
		String alids = request.getParameter("alIds");
		String areaCodes = request.getParameter("aIds");
		String shopCodes = request.getParameter("sIds");
		String basePrice = request.getParameter("basePrice");
		String incRange = request.getParameter("incRange");
		long crtTime = System.currentTimeMillis();
		int operator = admin.getAdmUin();
		long opTime = System.currentTimeMillis();
		
		startTime = startTime * 60 * 60 * 1000;
		if(endTime == 0) {
			endTime = 24 * 60 * 60 * 1000 - 1;
		} else {
			endTime = endTime * 60 * 60 * 1000;
		}
		
		Calendar calendar = Calendar.getInstance();
		calendar.setTimeInMillis(startDate);
		calendar.set(Calendar.HOUR_OF_DAY, 0);
		calendar.set(Calendar.MINUTE, 0);
		calendar.set(Calendar.SECOND, 0);
		calendar.set(Calendar.MILLISECOND, 0);
		startDate = calendar.getTimeInMillis();
		
		calendar.setTimeInMillis(endDate);
		calendar.set(Calendar.HOUR_OF_DAY, 23);
		calendar.set(Calendar.MINUTE, 59);
		calendar.set(Calendar.SECOND, 59);
		calendar.set(Calendar.MILLISECOND, 999);
		endDate = calendar.getTimeInMillis();
		
		if(shopCodes.equals("-1")) {
			AreaShopService shopService = CommonClient.getAreaShopService();
			RespWrapper<List<Area>> areaResp = shopService.getAllArea();
			if(areaResp.getErrCode() == 0 && areaResp.getObj() != null) {
				List<Area> listArea = areaResp.getObj();
				List<Shop> listShop = null;
				
				StringBuilder shopIds = new StringBuilder();
				
				String areaCode = "";
				for(Area area : listArea) {
					areaCode = area.getAreaCode();
					listShop = shopService.getShopsByAreaCode(areaCode).getObj();
					if(listShop != null && !listShop.isEmpty()) {
						for(Shop shop : listShop) {
							shopIds.append(shop.getShopCode() + ",");
						}
					}
				}
				
				shopCodes = shopIds.toString();
				if(shopCodes.length() > 0) {
					shopCodes = shopCodes.substring(0, shopCodes.length() - 1);
				}
			}
			
		}
		
		BidPlanEntity entity = new BidPlanEntity();
		entity.setBpName(bpName);
		entity.setIIds(iids);
		entity.setStartDate(startDate);
		entity.setEndDate(endDate);
		entity.setStartTime(startTime);
		entity.setEndTime(endTime);
		entity.setCStartTime(cStartTime);
		entity.setCEndTime(cEndTime);
		entity.setRepeatedType(repeatType);
		entity.setChargeType(chargeType);
		entity.setAlIds(alids);
		entity.setAreaCodes(areaCodes);;
		entity.setShopCodes(shopCodes);;
		entity.setCBasePrice(new Long(MoneyUtil.convertMoney(basePrice)).intValue());
		entity.setCIncRange(new Long(MoneyUtil.convertMoney(incRange)).intValue());
		entity.setCreateTime(crtTime);
		entity.setAdmUin(operator);
		entity.setOptime(opTime);
		entity.setStatus(BidPlanStatus.USING.getStatus());
		
		BidPlanService service = BidPlanClient.getBidPlanService();
		resp = service.add(entity);
		
		OpLogUtil.writeOperateLog("新增档期["+ bpName +"]", admin.getAdmUin(), admin.getUserName(), OpType.ADD, resp.getObj() > 0 ? true : false);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
