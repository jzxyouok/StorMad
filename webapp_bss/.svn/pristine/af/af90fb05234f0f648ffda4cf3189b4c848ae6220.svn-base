package com.yonghui.webapp.bss.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.ader.share.enums.StatusEnum;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.SmsService;
import com.yonghui.comp.common.share.enums.MsgEnum;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class NotifyHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		int adUin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2003, "未查询到ID为["+adUin+"]的广告主", false);
		
		AderService service = AderClient.getAderService();
		AderEntity entity = service.findById(adUin).getObj();
		
		if(entity != null) {
			MsgEnum mEnum = null;
			if(entity.getStatus() == StatusEnum.NOPASS.getStatus()) {
				mEnum = MsgEnum.NO_PASS;
			} else if(entity.getStatus() == StatusEnum.PASS.getStatus()) {
				mEnum = MsgEnum.PASS;
			}
			
			if(mEnum != null) {
				if(StringUtil.isNotEmpty(entity.getPhone())) {
					SmsService smsService = CommonClient.getSmsService();
					resp = smsService.sendMsg(entity.getPhone(), mEnum.getMsgType(), mEnum.getContent());
					if(resp.getObj()) {
						resp.setErrMsg("审核结果通知发送成功");
					}
				} else {
					resp.setErrMsg("广告主电话号码为空，发送通知失败");
				}
			} else {
				resp.setErrMsg("未找到对应的消息模板");
			}
		} else {
			resp.setErrMsg("未找到对应的广告主["+adUin+"]，发送通知失败");
		}
		
		OpLogUtil.writeOperateLog("管理员["+admin.getUserName()+"]发送审核结果通知给广告主["+ entity.getLoginName() +"]", admin.getAdmUin(), "发送审核结果通知", OpType.ADD, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
