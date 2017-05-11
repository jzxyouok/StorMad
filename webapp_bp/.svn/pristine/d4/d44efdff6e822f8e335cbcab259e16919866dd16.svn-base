package com.yonghui.webapp.bp.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.SmsService;
import com.yonghui.comp.common.share.enums.MsgEnum;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class VerifyHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2006, "验证码错误", false);
		
		String loginName = request.getParameter("loginName");
		String vCode = request.getParameter("vCode");
		
		if(StringUtil.isEmpty(loginName)) {
			resp.setErrMsg("请填写用户名");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		AderService service = AderClient.getAderService();
		AderEntity entity = service.findByLoginName(loginName).getObj();
		if(entity != null) {
			String phone = entity.getPhone();
			if(StringUtil.isEmpty(phone)) {
				resp.setErrMsg("没有找到广告主的手机号吗");
				JsonUtil.MAPPER.writeValue(out, resp);
				return;
			}
			
			SmsService smsService = CommonClient.getSmsService();
			resp = smsService.verifyCode(phone, MsgEnum.FINDPWD.getMsgType(), vCode);
		}
		JsonUtil.MAPPER.writeValue( out, resp);
	}
}
