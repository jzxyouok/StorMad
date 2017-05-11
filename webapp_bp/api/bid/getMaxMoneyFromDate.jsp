<%@page import="com.yonghui.webapp.bp.api.bid.GetMaxMoneyFromDate"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bp.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%--
==================================
===========获取当前档期行业竞拍时间内每天最高竞价金额信息===========
==================================
--%>
<%
try{
	new GetMaxMoneyFromDate().handle( request,response,out, ader );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>