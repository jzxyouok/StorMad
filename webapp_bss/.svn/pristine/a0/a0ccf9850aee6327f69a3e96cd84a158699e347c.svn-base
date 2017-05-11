package com.yonghui.webapp.bss.api.bidplan;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FindHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		int bpId = StringUtil.convertInt(request.getParameter("bpId"), 0);
		RespWrapper<BidPlanEntity> resp = RespWrapper.makeResp(4004, "根据id查询档期失败", null);
		
		BidPlanService service = BidPlanClient.getBidPlanService();
		resp = service.findBidPlanById(bpId);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
