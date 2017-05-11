package com.yonghui.webapp.bp.api.money;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.money.share.AmountService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.AmountEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class AmountHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(0, "", null);
		
		AmountService service = MoneyClient.getAmountService();
		AmountEntity entity = service.findByAdUin(ader.getAdUin()).getObj();
		
		Map<String, Object> map = new HashMap<String, Object>();
		map.put("acctName", ader.getCorporation());
		map.put("balance", 0);
		if(entity != null) {
			map.put("balance", entity.getAvailableCash() + entity.getAvailableGoods());
		}
		resp.setObj(map);
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
