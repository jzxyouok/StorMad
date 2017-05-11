package com.yonghui.webapp.bss.api.ad;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdCacheService;
import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

public class RefreshCache implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		String key = NetUtil.getStringParameter(request, "key", "");
		AdCacheService cacheService = AdClient.getAdCacheService();
		RespWrapper<Boolean> bool = null;
		if (key.equals("sp")) {
			bool = cacheService.refreshSpreadPlan();
		} else if (key.equals("sg")) {
			bool = cacheService.refreshSpreadGroup();
		} else if (key.equals("adInfo")) {
			bool = cacheService.refreshAdInfo();
		} else if (key.equals("adLocation")) {
			bool = cacheService.refreshAdLocation();
		} else if (key.equals("adSize")) {
			bool = cacheService.refreshAdSize();
		} else if (key.equals("all")) {
			bool = cacheService.refreshAll();
		} else {
			bool = RespWrapper.makeResp(1001, "非法请求!", false);
		}
		JsonUtil.MAPPER.writeValue(out, bool);
	}
}
