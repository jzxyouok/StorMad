package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FindAddrHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<AddrEntity> resp = RespWrapper.makeResp(7004, "根据ID查询地址信息详情出错", null);
		
		
		int addrId = StringUtil.convertInt(request.getParameter("addrId"), 0);
		if(addrId == 0) {
			resp.setErrMsg("请输入合法的地址ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}

		AddrService service = InvoiceClient.getAddrService();
		AddrEntity entity = service.findById(addrId).getObj();
		if(entity == null) {
			resp.setErrMsg("未找到ID["+addrId+"]对应的地址");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(entity.getAdUin() != ader.getAdUin()) {
			resp.setErrMsg("非法请求");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		resp.setObj(entity);
		resp.setErrCode(0);
		resp.setErrMsg("");
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
