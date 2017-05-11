package com.yonghui.webapp.bp.api.money;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.money.share.DepositService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.DepositEntity;
import com.yonghui.comp.money.share.enums.DepositStatus;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;

public class DepositHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		Map<String, Object> map = new HashMap<String, Object>();
		RespWrapper<Map<String, Object>> resp = RespWrapper.makeResp(0, "", map);
		
		String dsno = request.getParameter("dsno");
		DepositService service = MoneyClient.getDepositService();
		DepositEntity entity = service.findByDsno(dsno).getObj();
		
		if(entity != null) {
			if(entity.getAdUin() != ader.getAdUin()) {
				JsonUtil.MAPPER.writeValue(out, resp);
				return;
			}
			map.put("status", entity.getStatus());
			map.put("name", DepositStatus.getName(entity.getStatus()));
			map.put("finishTime", entity.getFinishTime());
			
			resp.setObj(map);
		}
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
