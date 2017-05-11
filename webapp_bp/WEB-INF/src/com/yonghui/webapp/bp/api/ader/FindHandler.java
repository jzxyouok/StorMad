package com.yonghui.webapp.bp.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class FindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<AderEntity> resp = RespWrapper.makeResp(2003, "未查询到广告主信息", null);
		
		AderService service = AderClient.getAderService();
		resp = service.findById(ader.getAdUin());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
