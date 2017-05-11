<%@page import="com.yonghui.webapp.bss.util.ImageVerifyCode"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="cn770880.jutil.data.RespWrapper"%>
<%@ page language="java" contentType="application/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	String id = ImageVerifyCode.createCode();
	JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", id) );	
%>