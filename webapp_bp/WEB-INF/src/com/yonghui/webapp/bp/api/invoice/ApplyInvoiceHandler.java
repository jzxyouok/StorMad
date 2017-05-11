package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.comp.invoice.share.bean.InvoiceEntity;
import com.yonghui.comp.invoice.share.enums.InvoiceStatus;
import com.yonghui.comp.invoice.share.enums.TitleEnum;
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

public class ApplyInvoiceHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(7101, "申请开具发票信息出错", false);
		
		int addrId = StringUtil.convertInt(request.getParameter("addrId"), 0);
		if(addrId == 0) {
			resp.setErrMsg("请选择合法的地址");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		String baId = request.getParameter("baId");
		if(StringUtil.isEmpty(baId)) {
			resp.setErrMsg("请选择合法的消费流水");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		int title = StringUtil.convertInt(request.getParameter("title"), 0);
		
		BalanceService balanceService = MoneyClient.getBalanceService();
		RespWrapper<BalanceEntity> lResp = balanceService.findById(baId);
		BalanceEntity balance = lResp.getObj();
		
		if(lResp.getErrCode() != 0 || balance == null) {
			resp.setErrMsg("请选择合法的消费流水");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(ader.getAdUin() != balance.getAdUin()) {
			resp.setErrMsg("消费流水非法");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(balance.getStatus() != 2) {
			resp.setErrMsg("消费流水处于未交易成功状态，不能申请开发票");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		try {
			InvoiceService service = InvoiceClient.getInvoiceService();
			
			RespWrapper<InvoiceEntity> iResp = service.findByBaId(baId);
			
			System.out.println(iResp.getErrCode()  + "    " + (iResp.getObj() != null));
			
			if(iResp.getErrCode() == 0 || iResp.getObj() != null) {
				resp.setErrMsg("这条消费记录["+ iResp.getObj().getBaId() +"]已经申请过开具发票["+ iResp.getObj().getIvId() +"]");
				JsonUtil.MAPPER.writeValue(out, resp);
				return;
			}
			
			InvoiceEntity entity = new InvoiceEntity();
			entity.setBaId(baId);
			entity.setAcctPeriod(balance.getCrtTime());
			entity.setAdUin(balance.getAdUin());
			entity.setAddrId(addrId);
			entity.setCorporation(ader.getCorporation());
			entity.setMoney(Math.abs(balance.getCash()));
			entity.setTitle(TitleEnum.getTitleEnum(title));
			entity.setApplyTime(System.currentTimeMillis());
			entity.setStatus(InvoiceStatus.APPLY);
			
			resp = service.add(entity);
			if(resp.getObj()) {
				//充值成功，更新交易流水状态
				balance.setInvoiceStatus(BalanceInvoice.APPLIED.getStatus());
				balance.setOperator(ader.getAdUin());
				balance.setOptime(System.currentTimeMillis());
				
				balanceService.update(balance);
			}
		} catch(Exception ex) {
			ex.printStackTrace();
		}
		
		OpLogUtil.writeOperateLog("申请发票", ader.getAdUin(), ader.getLoginName(), OpType.ADD, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}