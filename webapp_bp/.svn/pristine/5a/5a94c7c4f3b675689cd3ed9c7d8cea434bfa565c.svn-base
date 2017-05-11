<%@page import="com.yonghui.webapp.bp.util.ImageVerifyCode"%>
<%@page import="cn770880.jutil.string.StringUtil"%>
<%@page import="cn770880.jutil.net.NetUtil"%>
<%@page contentType="image/*" pageEncoding="UTF-8"%>
<%
	String id = NetUtil.getStringParameter(request, "id", "");
	if (StringUtil.isEmpty(id))
		return;
	ImageVerifyCode ivc = new ImageVerifyCode();
	ivc.processRequest(request, response, id);
%>