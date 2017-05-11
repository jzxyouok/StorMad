package com.yonghui.webapp.bss.api.ad.adlocation;

import java.io.IOException;
import java.io.Writer;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.util.Pair;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdLocationService;
import com.yonghui.comp.ad.share.bean.AdLocation;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

public class GetAllBaseAdLocation implements ApiHandler {
	
	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdLocationService service = AdClient.getAdLocationService();
		
		
		RespWrapper<List<AdLocation>> result = service.getAllAdLocation();
		JsonUtil.MAPPER.writeValue( out, getRespPage(result));
	}
	/**
	 * 
	 * <br>
	 * <b>功能：</b>封装前端显示所需参数<br>
	 * <b>日期：</b>2016年11月17日<br>
	 * <b>作者：</b>RUSH<br>
	 *
	 * @param adInfoPageWrapper
	 * @return
	 */
	public static RespWrapper<List<Pair<Integer, String>>> getRespPage(RespWrapper<List<AdLocation>> alWrapper) {
		
		RespWrapper<List<Pair<Integer, String>>> resp = 
				RespWrapper.makeResp(alWrapper.getErrCode(), alWrapper.getErrMsg(), null);
		
		List<AdLocation> alList = alWrapper.getObj();
		
		if (alList != null) {
			List<Pair<Integer, String>> respDataList = new ArrayList<Pair<Integer, String>>();
			for (AdLocation al : alList) {
				Pair<Integer, String> respData = new Pair<Integer, String>();
				respData.setFirst(al.getAlId());
				respData.setSecond(al.getAlName());
				respDataList.add(respData);
			}
			resp.setObj(respDataList);
		}
		return resp;
	}
}
