<%@page import="com.yonghui.webapp.bp.api.ader.ResetPwdHandler"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bp.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%--
==================================
================重置密码=============
==================================
--%>
<%
try{
	new ResetPwdHandler().handle( request,response,out, null );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>