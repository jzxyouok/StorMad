package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.money.share.DepositService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.DepositEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class QueryDepositHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(6202, "查询账户充值记录出错", null);
		
		int pageNo = StringUtil.convertInt(request.getParameter("pageNo"), 1);
		int pageSize = StringUtil.convertInt(request.getParameter("pageSize"), 10);
		int type = StringUtil.convertInt(request.getParameter("type"), -1);
		String yearMonth = request.getParameter("yearMonth");
		
		Map<String, Object> params = new HashMap<String, Object>();
		if(type > -1) {
			params.put("type", type);
		}
		if(StringUtil.isNotEmpty(yearMonth)) {
			params.put("yearMonth", yearMonth);
		}
		params.put("ad_uin", ader.getAdUin());
		params.put("status", 1);
		
		DepositService service = MoneyClient.getDepositService();
		DataPage<DepositEntity> page = service.query(params, pageNo, pageSize, ader.getAdUin()).getObj();
		
		if(StringUtil.isEmpty(yearMonth)) {
			Calendar calendar = Calendar.getInstance();
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM");
			yearMonth = sdf.format(calendar.getTime());
		}
		long balance = service.queryTotalCount(params).getObj();
		
		Map<String, Object> rMap = new HashMap<String, Object>();
		rMap.put("page", page);
		rMap.put("balance", balance);
		
		resp.setErrCode(0);
		resp.setErrMsg("");
		resp.setObj(rMap);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
