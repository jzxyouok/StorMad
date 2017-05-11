package com.yonghui.webapp.bp.api.ader;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class NotifyHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		StringBuilder phone = new StringBuilder(ader.getNotifyPhone());
		if(phone != null) {
			phone = phone.replace(3, 7, "****");
		}
		
		Map<String, Object> map = new HashMap<String, Object>();
		map.put("phone", phone);
		map.put("openNotify", ader.getOpenNotify());
		
		JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(0, "", map));
	}
}
