<%@page import="com.yonghui.webapp.bss.api.ader.VCodeHandler"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bss.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%--
==================================
===========获取手机验证码===========
==================================
--%>
<%
try{
	new VCodeHandler().handle( request,response,out, null );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>