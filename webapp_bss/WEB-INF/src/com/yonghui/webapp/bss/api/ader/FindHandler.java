package com.yonghui.webapp.bss.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		int adUin = StringUtil.convertInt(request.getParameter("adUin"), 0);
		RespWrapper<AderEntity> resp = RespWrapper.makeResp(2003, "未查询到ID为["+adUin+"]的广告主", null);
		
		AderService service = AderClient.getAderService();
		resp = service.findById(adUin);
		
		System.out.println("logurl:   " + resp.getObj().getLogoUrl());;
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
