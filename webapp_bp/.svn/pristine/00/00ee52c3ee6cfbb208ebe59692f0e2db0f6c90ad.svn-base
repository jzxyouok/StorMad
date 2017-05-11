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
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class QueryAddrHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		int pageNo = StringUtil.convertInt(request.getParameter("pageNo"), 1);
		int pageSize = StringUtil.convertInt(request.getParameter("pageSize"), 20);
		
		
		Map<String, Object> params = new HashMap<String, Object>();
		params.put("ad_uin", ader.getAdUin());
		
		AddrService service = InvoiceClient.getAddrService();
		RespWrapper<DataPage<AddrEntity>> resp = service.query(params, pageNo, pageSize);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}
