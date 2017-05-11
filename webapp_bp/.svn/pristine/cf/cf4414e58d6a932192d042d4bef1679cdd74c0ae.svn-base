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
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class ResetPwdHandler implements ApiHandler {
	
	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2008, "重置密码失败", false);
		
		String loginName = request.getParameter("loginName");
		String password = request.getParameter("password");
		String vCode = request.getParameter("vCode");
		
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
			if(resp.getObj()) {
				resp = service.resetPwd(entity.getAdUin(), password);
			}
			OpLogUtil.writeOperateLog("广告主["+loginName+"]重置密码", 0, "重置密码", OpType.DELETE, resp.getObj());
		}
		
		JsonUtil.MAPPER.writeValue( out, resp);
	}
}
