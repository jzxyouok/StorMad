package com.yonghui.webapp.bss.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.feizhu.webutil.net.CookieBox;
import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.ImageVerifyCode;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class LoginHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		String loginName = request.getParameter("loginName");
		String password = request.getParameter("password");
		String id = request.getParameter("id");
		String vCode = request.getParameter("vcode");
		
		if(!ImageVerifyCode.verifyCode(id, vCode)) {
			JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(2006, "请输入正确的验证码", null));
			return;
		}
		
		String sid = "";
		AderService service = AderClient.getAderService();
		RespWrapper<String> resp = service.login(loginName, password);
		if(resp.getErrCode() == 0) {
			sid = resp.getObj();
			
			CookieBox cookieBox = new CookieBox( request, response );
            Cookie cookie = cookieBox.getCookie( "bp_sid");
            if(cookie == null) {
            	cookie = new Cookie("bp_sid", sid);
            } else {
            	cookieBox.setCookie("bp_sid", sid);
            }
		}
		
		JsonUtil.MAPPER.writeValue( out, resp);
	}
}