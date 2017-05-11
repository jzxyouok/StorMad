package com.yonghui.webapp.bp.api.bidplan;

import java.io.IOException;
import java.io.Writer;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.RegionService;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class RegionHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		int parentId = StringUtil.convertInt(request.getParameter("parentId"), 0);
		int hasShops = StringUtil.convertInt(request.getParameter("hasShops"), 0);
		
		RespWrapper<List<Map<String, Object>>> resp = RespWrapper.makeResp(11000, "未找到ID["+parentId+"]对应的下属区域]", null);
		
		RegionService service = CommonClient.getRegionService();
		resp = service.queryByParentId(parentId, hasShops);
		
//		List<Map<String, Object>> citys = null;
//		List<Map<String, Object>> provinces = resp.getObj();
//		if(resp.getErrCode() == 0 && provinces != null && !provinces.isEmpty()) {
//			for(Map<String, Object> map : provinces) {
//				parentId = StringUtil.convertInt(map.get("id").toString(), -1);
//				
//				resp = service.queryByParentId(parentId, hasShops);
//				citys = resp.getObj();
//				if(resp.getErrCode() == 0 && citys != null && !citys.isEmpty()) {
//					map.put("citys", citys);
//				}
//			}
//		}
//		resp.setObj(provinces);
				
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
