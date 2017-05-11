package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.comp.invoice.share.bean.InvoiceEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.comp.money.share.BalanceService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.BalanceEntity;
import com.yonghui.comp.money.share.enums.BalanceInvoice;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class CancelHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<Boolean> resp = RespWrapper.makeResp(7103, "根据ID撤销发票信息出错", false);
		
		int ivId = StringUtil.convertInt(request.getParameter("ivId"), 0);
		if(ivId == 0) {
			resp.setErrMsg("请输入合法的发票ID");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		InvoiceService service = InvoiceClient.getInvoiceService();
		InvoiceEntity entity = service.findById(ivId).getObj();
		if(entity == null) {
			resp.setErrMsg("未找到ID为["+ivId+"]对应的发票");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		String baId = entity.getBaId();
		
		resp = service.cancelInvoice(ivId, ader.getAdUin());
		if(resp.getObj()) {
			BalanceService balanceService = MoneyClient.getBalanceService();
			BalanceEntity balance = balanceService.findById(baId).getObj();
			if(balance != null) {
				balance.setInvoiceStatus(BalanceInvoice.NO_APPLY.getStatus());
				RespWrapper<Boolean> bResp = balanceService.update(balance);
				if(!bResp.getObj()) {
					System.out.println("修改消费流水状态出错["+ baId +"]");
				}
			}
		}
		
		OpLogUtil.writeOperateLog("撤销发票["+ entity.getTitle() +"]", ader.getAdUin(), ader.getLoginName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}
