package com.yonghui.webapp.bss.api.ad.adinfo;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdInfoService;
import com.yonghui.comp.ad.share.bean.AdInfo;
import com.yonghui.comp.ad.share.enums.AdStatus;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

/**
 * 
 * <br>
 * <b>功能：</b>更新广告状态<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class UpdateAdStatus implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdInfoService service = AdClient.getAdInfoService();

		String operator = admin.getUserName();
		int adId = NetUtil.getIntParameter(request, "adId", 0);
		int adStatus = NetUtil.getIntParameter( request, "adStatus", 0);
		String verifyFailCause = NetUtil.getStringParameter(request, "verifyFailCause", "");

		if (adId < 1 || AdStatus.getStatus(adStatus) == null) 
			throw new RuntimeException("参数异常!");
		//		if (adStatus == AdStatus.VERIFY_FAIL.getId() && StringUtil.isEmpty(verifyFailCause)) 
		//			throw new RuntimeException("审核不通过原因不能为空!");
		
		RespWrapper<Boolean> result = service.updateAdStatus(adId, adStatus, verifyFailCause, operator);
		//===============日志记录
		AdInfo adInfo = service.getOneAdInfo(adId).getObj();
		String opContent = "广告["+(adInfo == null ? "" : adInfo.getTitle())+"]" + AdStatus.getName(adStatus);
		OpLogUtil.writeOperateLog(opContent, admin.getAdmUin(), 
				operator, OpType.DELETE, (result.getErrCode() == 0));
		//=====
		JsonUtil.MAPPER.writeValue( out, result);
	}
}