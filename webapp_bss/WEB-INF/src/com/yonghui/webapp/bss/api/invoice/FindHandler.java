package com.yonghui.webapp.bss.api.invoice;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.comp.invoice.share.bean.InvoiceEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(7103, "根据ID查询发票信息详情出错", null);
		
		int ivId = StringUtil.convertInt(request.getParameter("ivId"), 0);
		if(ivId == 0) {
			resp.setErrMsg("请输入合法的发票ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		Map<String, Object> map = new HashMap<String, Object>();
		
		InvoiceService service = InvoiceClient.getInvoiceService();
		RespWrapper<InvoiceEntity> iResp = service.findById(ivId);
		InvoiceEntity invoice = iResp.getObj();
		if(iResp.getErrCode() == 0 && invoice != null) {
			map.put("ivId", invoice.getIvId());
			map.put("corporation", invoice.getCorporation());
			map.put("money", invoice.getMoney());
			map.put("acctPeriod", invoice.getAcctPeriod());
			map.put("title", invoice.getTitle().getTitle());
			map.put("status", invoice.getStatus().getStatus());
			map.put("aduin", invoice.getAdUin());
			map.put("addrId", invoice.getAddrId());
			
			AderService aderService = AderClient.getAderService();
			RespWrapper<AderEntity> aResp = aderService.findById(invoice.getAdUin());
			AderEntity ader = aResp.getObj();
			if(aResp.getErrCode() == 0 && ader != null) {
				map.put("bank", ader.getBank());
				map.put("phone", ader.getPhone());
			}
			
			AddrService addrService = InvoiceClient.getAddrService();
			RespWrapper<AddrEntity> dResp = addrService.findById(invoice.getAddrId());
			AddrEntity addr = dResp.getObj();
			if(dResp.getErrCode() == 0 && addr != null) {
				map.put("address", addr.getProvince() + addr.getCity() + addr.getDistrict() + addr.getAddress());
			}
		}
		resp.setErrCode(0);
		resp.setErrMsg("");
		resp.setObj(map);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
