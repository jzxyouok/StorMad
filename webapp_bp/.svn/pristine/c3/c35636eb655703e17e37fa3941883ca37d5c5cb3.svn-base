package com.yonghui.webapp.bp.api.ad.adsize;

import java.io.IOException;
import java.io.Writer;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdSizeService;
import com.yonghui.comp.ad.share.bean.AdSize;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>按广告类型获取有效的(有广告位在使用的规格)广告规格信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAdSizesByAdType implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		AdSizeService service = AdClient.getAdSizeService();
		int adType = NetUtil.getIntParameter( request, "adType", 0);
		RespWrapper<List<AdSize>> result = service.getValidAdSizesByAdType(adType);
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
