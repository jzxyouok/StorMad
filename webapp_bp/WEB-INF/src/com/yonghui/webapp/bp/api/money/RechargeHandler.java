package com.yonghui.webapp.bp.api.money;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.comp.money.share.DepositService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.DepositEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.MoneyUtil;
import com.yonghui.webapp.bp.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class RechargeHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		RespWrapper<String> resp = RespWrapper.makeResp(6201, "账户充值出错", null);
		
		long money = MoneyUtil.convertMoney(request.getParameter("money"));
		int type = StringUtil.convertInt(request.getParameter("type"), 1);
		int mode = StringUtil.convertInt(request.getParameter("mode"), 0);
		
		if(money < 100000 || money > 10000000) {
			resp.setErrMsg("充值金额范围为1000元~100000元，请重新輸入");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		DepositService service = MoneyClient.getDepositService();
		DepositEntity entity = new DepositEntity();
		entity.setAdUin(ader.getAdUin());
		entity.setType(type);
		entity.setMode(mode);
		entity.setMoney(money);
		entity.setOperator(ader.getAdUin());
		entity.setCrtTime(System.currentTimeMillis());
		resp = service.save(entity);
		
		OpLogUtil.writeOperateLog("广告主["+ader.getLoginName()+"]充值["+ entity.getMoney() +"]", ader.getAdUin(), ader.getLoginName(), OpType.UPDATE, StringUtil.isEmpty(resp.getObj()));
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}
}