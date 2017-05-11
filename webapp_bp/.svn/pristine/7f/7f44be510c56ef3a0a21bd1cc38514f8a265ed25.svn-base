package com.yonghui.webapp.bp.api.ad;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.util.Pair;

import com.yonghui.comp.ad.share.enums.AdType;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取所有广告类型<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAllAdType implements ApiHandler {

	@SuppressWarnings("unchecked")
	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		AdType [] adType = AdType.values();
		
		Pair<Integer,String> [] arr = new Pair[ adType.length ];
		for (int i = 0; i<arr.length; i++) {
			AdType type = adType[i];
			Pair<Integer, String> pair = Pair.makePair(type.getId(), type.getName());
			arr[i] = pair;
		}
		
		JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", arr));
	}
}
