package com.yonghui.webapp.bss.api;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.SmsService;
import com.yonghui.comp.common.share.enums.MsgEnum;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class NotifyHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		int adUin = StringUtil.convertInt(request.getParameter("adUin"), 0);
		int mType = StringUtil.convertInt(request.getParameter("mtype"), 0);
		RespWrapper<AderEntity> resp = RespWrapper.makeResp(2003, "未查询到ID为["+adUin+"]的广告主", null);
		
		if(mType > 0) {
			MsgEnum mEnum = MsgEnum.getEnum(mType);
			
			AderService service = AderClient.getAderService();
			resp = service.findById(adUin);
			AderEntity entity = resp.getObj();
			
			if(entity != null) {
				if(StringUtil.isNotEmpty(entity.getPhone())) {
					if(mEnum != null) {
						SmsService smsService = CommonClient.getSmsService();
						smsService.sendMsg(entity.getPhone(), mEnum.getMsgType(), mEnum.getContent());
					} else {
						resp.setErrMsg("未找到对应的消息模板");
					}
				} else {
					resp.setErrMsg("广告主电话号码为空，发送通知失败");
				}
			} else {
				resp.setErrMsg("未找到对应的广告主["+adUin+"]，发送通知失败");
			}
		}
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
