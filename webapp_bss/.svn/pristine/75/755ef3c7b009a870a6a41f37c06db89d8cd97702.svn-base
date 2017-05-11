package com.yonghui.webapp.bss.api.bidplan;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.comp.bidplan.share.enums.BidPlanStatus;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class StartHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(4002, "操作档期失败", false);
		
		int bpId = StringUtil.convertInt(request.getParameter("bpId"), 0);
		int status = StringUtil.convertInt(request.getParameter("status"), -1);
		
		BidPlanService service = BidPlanClient.getBidPlanService();
		BidPlanEntity entity = service.findBidPlanById(bpId).getObj();
		
		String msg = status == 0 ? "停用" : "启用";
		if(entity == null) {
			msg += "档期失败，未找到ID为["+bpId+"]的档期";
			resp.setErrMsg(msg);
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		if(status == BidPlanStatus.STOP.getStatus()) {
			resp = service.stop(bpId, status, admin.getAdmUin());
		}
		if(status == BidPlanStatus.USING.getStatus()) {
			resp = service.start(bpId, status, admin.getAdmUin());
		}
		
		OpLogUtil.writeOperateLog(msg+"档期["+ entity.getBpName() +"]", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
