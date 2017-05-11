package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.comp.money.share.BalanceService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.BalanceEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class InvoiceTitleHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(7103, "根据ID查询发票信息详情出错", null);
		
		int addrId = StringUtil.convertInt(request.getParameter("addrId"), 0);
		String baId = request.getParameter("baId");
		
		if(addrId == 0) {
			resp.setErrMsg("请输入合法的地址ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(StringUtil.isEmpty(baId)) {
			resp.setErrMsg("请选择需要开发票的消费流水");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		Map<String, Object> map = new HashMap<String, Object>();
		
		map.put("corporation", ader.getCorporation());
		map.put("bank", ader.getBank());
		
		AddrService addrService = InvoiceClient.getAddrService();
		RespWrapper<AddrEntity> dResp = addrService.findById(addrId);
		AddrEntity addr = dResp.getObj();
		if(dResp.getErrCode() == 0 && addr != null) {
			map.put("address", addr.getProvince() + addr.getCity() + addr.getDistrict() + addr.getAddress());
			map.put("phone", addr.getPhone());
			map.put("onsignee", addr.getConsignee());
		}
		
		BalanceService balanceService = MoneyClient.getBalanceService();
		BalanceEntity balance = balanceService.findById(baId).getObj();
		if(balance == null) {
			resp.setErrMsg("请选择需要开发票的消费流水");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		map.put("invoiceStatus", balance.getInvoiceStatus());
		
		resp.setErrCode(0);
		resp.setErrMsg("");
		resp.setObj(map);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
