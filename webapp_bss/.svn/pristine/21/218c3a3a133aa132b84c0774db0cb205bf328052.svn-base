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

public class ResetPwdHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(10008, "重置密码失败", false);
		
		String password = request.getParameter("password");
		int tuin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		
		if(StringUtil.isEmpty(password)) {
			resp.setErrMsg("重置密码不能为空");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		if(tuin == 0) {
			resp.setErrMsg("请选择需要重置密码的管理员");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		AdminService service = AdminClient.getAdminService();
		AdminEntity entity = service.findById(tuin).getObj();
		if(entity == null) {
			resp.setErrMsg("请选择需要重置密码的管理员");
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		
		resp = service.resetPwd(password, tuin);
		
		OpLogUtil.writeOperateLog("重置管理员["+ entity.getUserName() +"]密码", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
