<%@page import="com.yonghui.webapp.bss.api.admin.QueryHandler"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bss.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%--
==================================
===========查询管理员===========
==================================
--%>
<%
try{
	new QueryHandler().handle( request,response,out, admin );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>