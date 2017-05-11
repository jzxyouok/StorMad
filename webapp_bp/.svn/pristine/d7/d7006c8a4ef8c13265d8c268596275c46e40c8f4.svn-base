<%@page import="com.yonghui.webapp.bp.util.QrCodeUtil"%>
<%@page import="cn770880.jutil.string.StringUtil"%>
<%@page import="cn770880.jutil.net.NetUtil"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%

	String link = NetUtil.getStringParameter(request, "link", "");
	int width = NetUtil.getIntParameter(request, "width", 200);
	int height = NetUtil.getIntParameter(request, "height", 200);
	if (StringUtil.isNotEmpty(link)) {
		QrCodeUtil.writeQrcode(link, width, height, response);
	}
	
%>