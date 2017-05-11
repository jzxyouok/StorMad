package com.yonghui.webapp.bp.api.bidplan;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanAdEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FindBindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<BidPlanAdEntity> resp = RespWrapper.makeResp(4105, "根据id查询档期绑详情定失败", null);

		int bpaId = StringUtil.convertInt(request.getParameter("bpaId"), 0);
		
		BidPlanService bpService = BidPlanClient.getBidPlanService();
		resp = bpService.findBindAdById(bpaId);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
