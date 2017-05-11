package com.yonghui.webapp.bss.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.comp.invoice.share.bean.InvoiceEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class ApproveHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(7104, "审核发票信息出错", false);
		
		int ivId = StringUtil.convertInt(request.getParameter("ivId"), 0);
		int status = StringUtil.convertInt(request.getParameter("status"), 0);
		if(ivId == 0) {
			resp.setErrMsg("请输入合法的发票ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		InvoiceService service = InvoiceClient.getInvoiceService();
		InvoiceEntity invoice = service.findById(ivId).getObj();
		if(invoice == null) {
			resp.setErrMsg("无效的发票ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(status == 0) {
			resp.setErrMsg("审核操作无效");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		resp = service.approve(ivId, status, admin.getAdmUin());
		if(resp.getObj()) {
			
		}
		
		OpLogUtil.writeOperateLog("审核发票["+ invoice.getTitle() +"]", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}