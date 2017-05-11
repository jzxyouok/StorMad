package com.yonghui.webapp.bp.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.feizhu.webutil.net.CookieBox;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class LogoutHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		CookieBox cookieBox = new CookieBox(request, response);
		cookieBox.setCookie("bp_sid", "", ".yonghui.cn", 0, "/");
		
		JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(0, "退出系统成功", ""));
	}

}
