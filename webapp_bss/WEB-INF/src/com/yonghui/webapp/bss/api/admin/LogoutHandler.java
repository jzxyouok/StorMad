package com.yonghui.webapp.bss.api.admin;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.feizhu.webutil.net.CookieBox;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class LogoutHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		CookieBox cookieBox = new CookieBox( request, response );
		cookieBox.setCookie("bss_sid", null);
		
		JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "成功退出系统", true));
	}

}
