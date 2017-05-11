<%@page import="com.yonghui.webapp.bp.api.common.areashop.GetBaseShopByAreaCode"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bp.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%--
==================================
==========按大区编码获取所有门店基本信息===========
==================================
--%>
<%
try{
	new GetBaseShopByAreaCode().handle( request,response,out, ader );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>