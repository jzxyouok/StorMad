package com.yonghui.webapp.bss.api.ad.adlocation;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdLocationService;
import com.yonghui.comp.ad.share.bean.AdLocation;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>分页查询广告位置信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class FindAdLocationPage implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdLocationService service = AdClient.getAdLocationService();
		
		int pageNo = NetUtil.getIntParameter( request, "pageNo", 1);
		int pageSize = NetUtil.getIntParameter( request, "pageSize", 20);
		
		Map<String, Object> findParams = new HashMap<String, Object>();
		
		RespWrapper<DataPage<AdLocation>> result = service.findAdLocationPage(findParams, pageNo, pageSize);
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
