<%@page import="com.yonghui.webapp.bp.api.ader.ApproveHandler"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="com.yonghui.webapp.bp.util.Exceptions"%>
<%@ page language="java" contentType="text/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%--
==================================
===========审核广告主资料===========
==================================
--%>
<%
try{
	new ApproveHandler().handle( request,response,out, null );
}catch( Exception e ){
	e.printStackTrace();
	JsonUtil.MAPPER.writeValue( out, Exceptions.makeUnknownException( e.getMessage() ) );	
}
%>