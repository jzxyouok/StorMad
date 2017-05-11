package com.yonghui.webapp.bss.api.admin;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.AdminClient;
import com.yonghui.comp.admin.share.AdminService;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class QueryHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<DataPage<AdminEntity>> resp = RespWrapper.makeResp(2012, "未查询到广告主信息", null);
		
		int pageNo = StringUtil.convertInt(request.getParameter("pageNo"), 1);
		int pageSize = StringUtil.convertInt(request.getParameter("pageSize"), 20);
		String userName = request.getParameter("userName");
		int status = StringUtil.convertInt(request.getParameter("status"), -1);
		
		
		Map<String, Object> params = new HashMap<String, Object>();
		params.put("user_name", userName);
		if(status > -1) {
			params.put("status", status);
		}
		
		AdminService service = AdminClient.getAdminService();
		resp = service.query(params, pageNo, pageSize);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
