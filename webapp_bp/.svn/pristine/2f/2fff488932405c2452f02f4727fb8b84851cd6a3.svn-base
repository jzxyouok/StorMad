<%@page import="cn770880.jutil.net.NetUtil"%>
<%@page import="cn770880.jutil.string.StringUtil"%>
<%@page import="com.yonghui.webapp.bp.util.BankInfoUtil"%>
<%@page import="com.yonghui.webapp.bp.util.JsonUtil"%>
<%@page import="cn770880.jutil.data.RespWrapper"%>
<%@ page language="java" contentType="application/json; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	String bankNo = NetUtil.getStringParameter(request, "bankNo", "");
	String bankName = "";
	if (StringUtil.isNotEmpty(bankNo)) {
		bankName = BankInfoUtil.getNameOfBank(bankNo.toCharArray(), 0);
	}
	if (StringUtil.isNotEmpty(bankName)) {
		int lastIndex = bankName.lastIndexOf(".");
		if (lastIndex > 0) {
			bankName = bankName.substring(0, lastIndex);
		}
	}
	JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", bankName) );	
%>