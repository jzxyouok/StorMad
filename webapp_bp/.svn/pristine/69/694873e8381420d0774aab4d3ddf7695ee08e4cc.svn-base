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
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class CountDownHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		Map<String, Long> map = new HashMap<String, Long>();
		map.put("freetime", 0L);
		
		int bpId = StringUtil.convertInt(request.getParameter("bpId"), 0);
		RespWrapper<Map<String, Long>> resp = RespWrapper.makeResp(4004, "未查询到档期", map);
		
		BidPlanService service = BidPlanClient.getBidPlanService();
		RespWrapper<BidPlanEntity> bpResp = service.findBidPlanById(bpId);
		BidPlanEntity entity = bpResp.getObj();
		if(entity != null) {
			long freeTime = entity.getCEndTime() - System.currentTimeMillis();
			if(freeTime > 0) {
				map.put("freeTime", freeTime);
			}
			resp.setErrCode(0);
			resp.setErrMsg("");
		}
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
