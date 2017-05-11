package com.yonghui.webapp.bss.api.common;

import java.io.IOException;
import java.io.Writer;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.common.share.AreaShopService;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.bean.BaseShop;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>按大区编码获取门店信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetBaseShopsByAreaCodes implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AreaShopService service = CommonClient.getAreaShopService();
		
		String areaCodes = NetUtil.getStringParameter(request, "areaCodes", "");
		if (StringUtil.isEmpty(areaCodes)) 
			throw new RuntimeException("参数异常!");
		String [] _areaCodes = areaCodes.split(",");
		List<BaseShop> result = new ArrayList<BaseShop>();
		for (String areaCode : _areaCodes) {
			RespWrapper<List<BaseShop>> shopsWrapper = service.getBaseShopsByAreaCode(areaCode);
			List<BaseShop> baseShops = shopsWrapper.getObj();
			if (shopsWrapper.getErrCode() != 0) {
				JsonUtil.MAPPER.writeValue( out, shopsWrapper);
				return;
			}
			if (baseShops != null && !baseShops.isEmpty())
				result.addAll(baseShops);
				
		}
		JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", result));
	}
}
