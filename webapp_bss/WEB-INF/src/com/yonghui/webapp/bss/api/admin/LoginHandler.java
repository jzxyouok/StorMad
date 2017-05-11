package com.yonghui.webapp.bss.api.admin;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.feizhu.webutil.net.CookieBox;
import com.yonghui.comp.admin.share.AdminClient;
import com.yonghui.comp.admin.share.AdminService;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.ImageVerifyCode;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class LoginHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		String userName = request.getParameter("userName");
		String password = request.getParameter("password");
		String id = request.getParameter("id");
		String vCode = request.getParameter("vcode");
		
		if(!ImageVerifyCode.verifyCode(id, vCode)) {
			JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(2006, "请输入正确的验证码", null));
			return;
		}
		
		String sid = "";
		AdminService service = AdminClient.getAdminService();
		RespWrapper<String> resp = service.login(userName, password);
		
		if(resp.getErrCode() == 0) {
			sid = resp.getObj();
			CookieBox cookieBox = new CookieBox( request, response );
			cookieBox.setCookie("bss_sid", sid);
		}
		
		JsonUtil.MAPPER.writeValue( out, resp);
	}

}
