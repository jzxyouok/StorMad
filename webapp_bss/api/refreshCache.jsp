<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<%
	String path = request.getContextPath();
	String basePath = request.getScheme() + "://"
			+ request.getServerName() + ":" + request.getServerPort()
			+ path + "/";
%>
<head>
<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>图片上传测试</title>

<script type="text/javascript">
function refreshAd(key) {
	window.open("<%=basePath%>api/ad/refreshCache.jsp?key=" + key,"刷新缓存","fullscreen=1,width=800,height=400,left=200,top=100,status=yes,titlebar=0,resizable=0,menubar=0,location=0,toolbar=0,scrollbars=yes");
}

function refreshCommon(key) {
	window.open("<%=basePath%>api/common/refreshCache.jsp?key=" + key,"刷新缓存","fullscreen=1,width=800,height=400,left=200,top=100,status=yes,titlebar=0,resizable=0,menubar=0,location=0,toolbar=0,scrollbars=yes");
}

function refreshBid(key) {
	window.open("<%=basePath%>api/bid/refreshCache.jsp?key=" + key,"刷新缓存","fullscreen=1,width=800,height=400,left=200,top=100,status=yes,titlebar=0,resizable=0,menubar=0,location=0,toolbar=0,scrollbars=yes");
}

function magnify(btn) {
	btn.style = 'height:100px; font-size:40';
}

function lessen(btn) {
	btn.style = 'height:35px; font-size:12';
}
</script>
</head>
<body>
	<h1>======================================================</h1>
	<h2>广告Service</h2><br>
	&nbsp;&nbsp;<button onclick="refreshAd('sp')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新推广计划</button>&nbsp;&nbsp;
	<button onclick="refreshAd('sg')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新推广组</button>&nbsp;&nbsp;
	<button onclick="refreshAd('adInfo')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新广告</button><br><br>
	&nbsp;&nbsp;<button onclick="refreshAd('adLocation')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新广告位</button>&nbsp;&nbsp;
	<button onclick="refreshAd('adSize')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新广告规格</button>&nbsp;&nbsp;
	<button onclick="refreshAd('all')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新所有</button>&nbsp;&nbsp;
	<h1>======================================================</h1>
	<h2>CommonService</h2><br>
	&nbsp;&nbsp;<button onclick="refreshCommon('area')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新大区</button>&nbsp;&nbsp;
	<button onclick="refreshCommon('shop')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新门店</button>&nbsp;&nbsp;
	<button onclick="refreshCommon('industry')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新行业</button><br><br>
	&nbsp;&nbsp;<button onclick="refreshCommon('all')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新所有</button>&nbsp;&nbsp;
	<h1>======================================================</h1>
	<h2>竞拍Service</h2><br>
	&nbsp;&nbsp;<button onclick="refreshBid('bidMax')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新广告主档期竞价最高纪录</button>&nbsp;&nbsp;
	<button onclick="refreshBid('bidCount')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新行业竞拍总次数缓存</button><br><br>
	&nbsp;&nbsp;<button onclick="refreshBid('all')" style="height: 35px; font-size:12" onmousemove="magnify(this)" onmouseout="lessen(this)">刷新所有</button>&nbsp;&nbsp;
	<h1>======================================================</h1>
</body>
</html>