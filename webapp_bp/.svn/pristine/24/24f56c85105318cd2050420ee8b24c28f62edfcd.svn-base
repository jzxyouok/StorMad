package com.yonghui.webapp.bp.api.ad.spread;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.SpreadService;
import com.yonghui.comp.ad.share.bean.SpreadPlan;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取所有推广计划<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAllSpreadPlan implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		SpreadService service = AdClient.getSpreadService();
		int adUin = ader.getAdUin();
		Map<String, Object> findParams = new HashMap<String, Object>();
		RespWrapper<DataPage<SpreadPlan>> result = service.findSpreadPlanPage(findParams, adUin, 1, Integer.MAX_VALUE);
		if (result.getErrCode() == 0) {
			JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(0, "", result.getObj().getRecord()));
			return;
		}
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
