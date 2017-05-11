package com.yonghui.webapp.bss.api.common;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.CommonCacheService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

public class RefreshCache implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		String key = NetUtil.getStringParameter(request, "key", "");
		CommonCacheService cacheService = CommonClient.getCommonCacheService();
		RespWrapper<Boolean> bool = null;
		if (key.equals("area")) {
			bool = cacheService.refreshArea();
		} else if (key.equals("shop")) {
			bool = cacheService.refreshShop();
		} else if (key.equals("industry")) {
			bool = cacheService.refreshIndustry();
		} else if (key.equals("all")) {
			bool = cacheService.refreshAll();
		} else {
			bool = RespWrapper.makeResp(1001, "非法请求!", false);
		}
		JsonUtil.MAPPER.writeValue(out, bool);
	}
}
