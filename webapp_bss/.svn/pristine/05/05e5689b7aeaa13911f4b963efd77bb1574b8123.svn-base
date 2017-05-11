package com.yonghui.webapp.bss.api.ader;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class FreezeHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2010, "停用广告主账号失败", false);
		
		int status = StringUtil.convertInt(request.getParameter("status"), 0);
		int tuin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		
		if(tuin == 0) {
			resp.setErrMsg("请选择目标广告主账号进行操作");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		AderService service = AderClient.getAderService();
		AderEntity entity = service.findById(tuin).getObj();
		if(entity == null) {
			resp.setErrMsg("请选择目标广告主账号进行操作");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		resp = service.freeze(tuin, admin.getAdmUin(), status);
		
		OpLogUtil.writeOperateLog("冻结广告主账户["+ entity.getLoginName() +"]", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
