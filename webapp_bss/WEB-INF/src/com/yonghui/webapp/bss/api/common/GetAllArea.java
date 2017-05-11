package com.yonghui.webapp.bss.api.common;

import java.io.IOException;
import java.io.Writer;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.AreaShopService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.bean.Area;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取所有大区信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAllArea implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AreaShopService service = CommonClient.getAreaShopService();
		
		
		RespWrapper<List<Area>> result = service.getAllArea();
		JsonUtil.MAPPER.writeValue( out, result);
	}
}