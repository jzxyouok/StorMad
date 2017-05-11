package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.CommonService;
import com.yonghui.comp.common.share.bean.Industry;
import com.yonghui.comp.money.share.BalanceService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.BalanceEntity;
import com.yonghui.comp.money.share.enums.BalanceInvoice;
import com.yonghui.comp.money.share.enums.BalanceStatus;
import com.yonghui.comp.money.share.enums.FlowEnum;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.resp.invoice.BalanceVo;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class QueryPayListHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(6005, "查询交易流水出错", new HashMap<String, Object>());
		
		BalanceService bService = MoneyClient.getBalanceService();
		BidPlanService bpService = BidPlanClient.getBidPlanService();
		CommonService cService = CommonClient.getCommonService();
		
		int pageNo = StringUtil.convertInt(request.getParameter("pageNo"), 1);
		int pageSize = StringUtil.convertInt(request.getParameter("pageSize"), 10);
		
		String yearMonth = request.getParameter("yearMonth");
		int invoiceStatus = StringUtil.convertInt(request.getParameter("invoiceStatus"), -1);
		String iId = request.getParameter("iid");
		String bpName = request.getParameter("bpName");
		StringBuilder bpId = new StringBuilder("");
		int balance = 0;
		
		Map<String, Object> params = new HashMap<String,Object>();
		if(StringUtil.isNotEmpty(yearMonth)) {
			params.put("yearMonth", yearMonth);
		}
		if(invoiceStatus > -1) {
			params.put("invoice_status", invoiceStatus);
		}
		if(StringUtil.isNotEmpty(iId)) {
			params.put("i_id", iId);
		}
		if(StringUtil.isNotEmpty(bpName)) {
			params.put("bp_name", bpName);
			DataPage<BidPlanEntity> bpPage = bpService.query(params, 1, Integer.MAX_VALUE).getObj();
			if(bpPage != null) {
				List<BidPlanEntity> listBP = bpPage.getRecord();
				for(BidPlanEntity bpPlan : listBP) {
					bpId.append(bpPlan.getBpId());
					bpId.append(",");
				}
			}
			params.remove("bp_name");
			params.put("bp_id", bpId);
		}
		
		params.put("ad_uin", ader.getAdUin());
		params.put("status", BalanceStatus.TRADE_SUCCESS.getStatus());
		params.put("flow_type", FlowEnum.PAY.getType());
		
		RespWrapper<DataPage<BalanceEntity>> bResp = bService.query(params, pageNo, pageSize);
		DataPage<BalanceEntity> page = bResp.getObj();
		if(bResp.getErrCode() == 0 && page != null) {
			BalanceVo balanceVo = null;
			BidPlanEntity bpPlan = null;
			Industry industry = null;
			
			String iName = "";
			String invoiceStatusCN = "";
			
			List<BalanceEntity> listExt = new ArrayList<BalanceEntity>();
			for(BalanceEntity entity : page.getRecord()) {
				bpName = "";
				iName = "";
				
				bpPlan = bpService.findBidPlanById(entity.getBpId()).getObj();
				if(bpPlan != null) {
					bpName = bpPlan.getBpName();
				}
				
				industry = cService.getOneIndustry(entity.getIId()).getObj();
				if(industry != null) {
					iName = industry.getIName();
				}
				invoiceStatusCN = BalanceInvoice.getStatusCN(entity.getInvoiceStatus());
				
				balanceVo = BalanceVo.wrapper(entity, bpName, iName, invoiceStatusCN, "", 0);
				listExt.add(balanceVo);
 			}
			page = new DataPage<BalanceEntity>(listExt, page.getTotalRecordCount(), page.getPageSize(), page.getPageNo());
			
			bResp.setErrCode(0);
			bResp.setErrMsg("");
			bResp.setObj(page);
		}
		
		params.clear();

		if(StringUtil.isEmpty(yearMonth)) {
			Calendar calendar = Calendar.getInstance();
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM");
			yearMonth = sdf.format(calendar.getTime());
		}
		params.put("yearMonth", yearMonth);
		params.put("ad_uin", ader.getAdUin());
		params.put("flow_type", FlowEnum.PAY.getType());
		params.put("status", BalanceStatus.TRADE_SUCCESS.getStatus());
		
		balance = bService.queryTotalCount(params).getObj();
		
		Map<String, Object> map = new HashMap<String, Object>();
		map.put("page", page);
		map.put("balance", balance);
		
		resp.setErrCode(0);
		resp.setErrMsg("");
		resp.setObj(map);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}