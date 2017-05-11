package com.yonghui.webapp.bp.api.bidplan;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanAdEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class QueryBindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<DataPage<BidPlanAdEntity>> resp = RespWrapper.makeResp(1001, "系统繁忙", null);
		
		int pageNo = StringUtil.convertInt(request.getParameter("pageNo"), 1);
		int pageSize = StringUtil.convertInt(request.getParameter("pageSize"), 10);
		String yearMonth = request.getParameter("yearMonth");
		
		Map<String, Object> params = new HashMap<String, Object>();
		params.put("ad_uin", ader.getAdUin());
		params.put("yearMonth", yearMonth);
		
		BidPlanService service = BidPlanClient.getBidPlanService();
		resp = service.queryBindList(params, pageNo, pageSize);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}
