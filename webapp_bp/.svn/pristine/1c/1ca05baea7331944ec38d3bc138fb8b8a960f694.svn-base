package com.yonghui.webapp.bp.api.bidplan;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bid.share.BidClient;
import com.yonghui.comp.bid.share.BidService;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class BidHotHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {

		//行业热度比例展示规则：0-30次=1星；31-60次=2星；61-100次=3星；101-200次=4星；201次以上=5星。（数据量大做翻倍叠加处理）
		String iid = request.getParameter("iid");
		
		BidService service = BidClient.getBidService();
		RespWrapper<Integer> resp = service.getIndustryBidHeat(iid);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
