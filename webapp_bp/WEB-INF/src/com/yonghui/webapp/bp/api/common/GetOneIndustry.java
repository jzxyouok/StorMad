package com.yonghui.webapp.bp.api.common;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.CommonService;
import com.yonghui.comp.common.share.bean.Industry;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取单个行业信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetOneIndustry implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		CommonService service = CommonClient.getCommonService();
		
		String iId = NetUtil.getStringParameter( request, "iId", "");
		
		if (StringUtil.isEmpty(iId)) {
			throw new RuntimeException("参数异常!");
		}

		RespWrapper<Industry> result = service.getOneIndustry(iId);
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
