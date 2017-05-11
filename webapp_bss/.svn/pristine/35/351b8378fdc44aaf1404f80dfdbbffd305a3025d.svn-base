package com.yonghui.webapp.bss.api.admin;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.admin.share.AdminClient;
import com.yonghui.comp.admin.share.AdminService;
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
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2010, "停用管理员账号失败", false);
		
		int status = StringUtil.convertInt(request.getParameter("status"), 0);
		int tuin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		
		if(tuin == 0) {
			resp.setErrMsg("请选择目标管理员账号进行操作");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		if(tuin == admin.getAdmUin()) {
			resp.setErrMsg("不能停用自己的账号");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		AdminService service = AdminClient.getAdminService();
		AdminEntity entity = service.findById(tuin).getObj();
		if(entity == null) {
			resp.setErrMsg("请选择目标管理员账号进行操作");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		resp = service.freeze(tuin, admin.getAdmUin(), status);
		
		OpLogUtil.writeOperateLog("冻结管理员["+ entity.getUserName() +"]账户", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}