<%@page import="com.yonghui.webapp.bp.api.ader.ForgetPwdHandler"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bp.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%--
==================================
===========忘记密码===========
==================================
--%>
<%
try{
	new ForgetPwdHandler().handle( request,response,out, null );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>