package com.yonghui.webapp.bss.api.ad.adinfo;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.util.Pair;

import com.yonghui.comp.ad.share.enums.AdStatus;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取所有广告状态<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAllAdStatus implements ApiHandler {

	@SuppressWarnings("unchecked")
	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdStatus [] adstatus = AdStatus.values();
		
		Pair<Integer,String> [] arr = new Pair[ adstatus.length ];
		for (int i = 0; i<arr.length; i++) {
			AdStatus status = adstatus[i];
			Pair<Integer, String> pair = Pair.makePair(status.getId(), status.getName());
			arr[i] = pair;
		}
		
		JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", arr));
	}
}
