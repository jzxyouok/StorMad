package com.yonghui.webapp.bss.api.money;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.comp.money.share.DepositService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.DepositEntity;
import com.yonghui.comp.money.share.enums.DepositMode;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.MoneyUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class RechargeHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		RespWrapper<Boolean> resp = RespWrapper.makeResp(6201, "账户充值出错", false);
		
		long money = MoneyUtil.convertMoney(request.getParameter("money"));
		int adUin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		int type = StringUtil.convertInt(request.getParameter("type"), 1);
		int mode = StringUtil.convertInt(request.getParameter("mode"), DepositMode.ONLINE.getMode());
		
		if(money < 100000 || money > 10000000) {
			resp.setErrMsg("充值金额范围为1000元~100000元，请重新輸入");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		if(adUin == 0) {
			resp.setErrMsg("请选择要充值的广告主");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		AderService aderService = AderClient.getAderService();
		AderEntity ader = aderService.findById(adUin).getObj();
		if(ader == null) {
			resp.setErrMsg("请选择目标广告主账号进行充值");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		DepositService service = MoneyClient.getDepositService();
		DepositEntity entity = new DepositEntity();
		entity.setAdUin(adUin);
		entity.setType(type);
		entity.setMode(mode);
		entity.setMoney(money);
		entity.setStatus(1);
		entity.setOperator(admin.getAdmUin());
		entity.setCrtTime(System.currentTimeMillis());
		RespWrapper<String> mResp = service.save(entity);
		if(mResp.getErrCode() != 0) {
			resp.setErrCode(mResp.getErrCode());
			resp.setErrMsg(mResp.getErrMsg());
		} else {
			resp.setErrCode(0);
			resp.setErrMsg("在线充值成功");
			resp.setObj(true);
		}
		
		OpLogUtil.writeOperateLog("为广告主["+ ader.getLoginName() +"]充值["+ money +"]", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}
