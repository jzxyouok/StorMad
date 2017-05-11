<%@page import="com.yonghui.webapp.bss.api.ad.adsize.GetAdSizeByAdType"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bss.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ include file="/api/head.jsp" %>
<%--
==================================
===========按广告类型获取广告规格信息===========
==================================
--%>
<%
try{
	new GetAdSizeByAdType().handle( request,response,out,admin );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>