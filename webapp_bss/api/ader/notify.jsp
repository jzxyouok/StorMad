<%@page import="com.yonghui.webapp.bss.api.ader.NotifyHandler"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bss.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%--
==================================
===========发送短信通知============
==================================
--%>
<%
try{
	new NotifyHandler().handle( request,response,out, admin );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>