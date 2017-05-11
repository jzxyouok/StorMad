package com.yonghui.webapp.bp.api.common.areashop;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.common.share.AreaShopService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.bean.Area;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取单个大区信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetOneArea implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		AreaShopService service = CommonClient.getAreaShopService();
		
		String areaCode = NetUtil.getStringParameter(request, "areaCode", "");
		
		if (StringUtil.isEmpty(areaCode)) {
			throw new RuntimeException("参数异常!");
		}
		
		RespWrapper<Area> result = service.getOneArea(areaCode);
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
