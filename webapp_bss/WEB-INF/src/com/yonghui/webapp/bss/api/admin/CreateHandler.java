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

public class CreateHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(10001, "新增管理员账号失败", false);
		
		String userName = request.getParameter("userName");
		String password = request.getParameter("password");
		String trueName = request.getParameter("trueName");
		
		AdminEntity entity = new AdminEntity();
		entity.setUserName(userName);
		entity.setPassword(password);
		entity.setTrueName(trueName);
		
		AdminService service = AdminClient.getAdminService();
		resp = service.add(entity);
		
		OpLogUtil.writeOperateLog("新增管理员["+ userName +"]密码", admin.getAdmUin(), admin.getUserName(), OpType.ADD, resp.getObj());
		
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}
