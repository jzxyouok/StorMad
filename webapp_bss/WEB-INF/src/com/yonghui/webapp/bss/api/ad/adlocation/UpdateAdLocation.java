package com.yonghui.webapp.bss.api.ad.adlocation;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdLocationService;
import com.yonghui.comp.ad.share.bean.AdLocation;
import com.yonghui.comp.ad.share.enums.AdType;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

/**
 * 
 * <br>
 * <b>功能：</b>更新广告位置信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class UpdateAdLocation implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdLocationService service = AdClient.getAdLocationService();

		String operator = admin.getUserName();
		int alId = NetUtil.getIntParameter(request, "alId", 0);
		String alName = NetUtil.getStringParameter( request, "alName", "");
		int adType = NetUtil.getIntParameter(request, "adType", 0);
		int asId = NetUtil.getIntParameter(request, "asId", 0);
		String description = NetUtil.getStringParameter( request, "description", "");
		String sketchMap = NetUtil.getStringParameter(request, "sketchMap", "");

		if (alId < 1 || 
				StringUtil.isEmpty(alName) || 
				AdType.getType(adType) == null || 
				asId < 1 || 
				StringUtil.isEmpty(sketchMap)) {
			throw new RuntimeException("参数异常!");
		}

		AdLocation adLocation = service.getOneAdLocation(alId).getObj();
		RespWrapper<Boolean> result = service.updateAdLocation(alId, alName, adType, asId, description, sketchMap, operator);
		//===============日志记录
		String opContent = "更新广告位["+(adLocation == null ? "" : adLocation.getAlName())+"]";
		OpLogUtil.writeOperateLog(opContent, admin.getAdmUin(), 
				operator, OpType.UPDATE, (result.getErrCode() == 0));
		//=====
		JsonUtil.MAPPER.writeValue( out, result);
	}
}