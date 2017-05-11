package com.yonghui.webapp.bss.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class AddrHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<AddrEntity> resp = RespWrapper.makeResp(7003, "根据ID查询地址信息详情出错", null);

		int addrId = StringUtil.convertInt(request.getParameter("addrId"), 0);
		
		AddrService addrService = InvoiceClient.getAddrService();
		resp = addrService.findById(addrId);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
