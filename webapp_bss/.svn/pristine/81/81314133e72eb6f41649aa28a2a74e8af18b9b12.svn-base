package com.yonghui.webapp.bss.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.SmsService;
import com.yonghui.comp.common.share.enums.MsgEnum;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class VCodeHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		RespWrapper<String> resp = RespWrapper.makeResp(1003, "获取手机验证码失败", null);		
		String phone = request.getParameter("phone");

		if(StringUtil.isEmpty(phone)) {
			resp.setErrMsg("请输入手机号码！");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		SmsService service = CommonClient.getSmsService();
		resp = service.sendVCode(phone, MsgEnum.REGISTER.getMsgType());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
