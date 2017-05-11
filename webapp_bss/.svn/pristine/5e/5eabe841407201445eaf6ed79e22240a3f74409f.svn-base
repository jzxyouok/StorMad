<%--
-------------------------------------------------------------------------- 
-- 定义全局的变量 cookieBox bss_sid 
-- 如果未登录用户或没权限，在这里就返回错误了
--------------------------------------------------------------------------
--%>
<%@page import="com.yonghui.comp.admin.share.AdminClient"%>
<%@page import="com.yonghui.comp.admin.share.bean.AdminEntity"%>
<%@page import="cn770880.jutil.data.RespWrapper"%>
<%@page import="com.yonghui.webapp.bss.util.Exceptions"%>
<%@page import="com.yonghui.webapp.bss.util.JsonUtil"%>
<%@page import="com.feizhu.webutil.net.CookieBox"%>
<%@page import="cn770880.jutil.string.StringUtil"%>
<%@page import="cn770880.jutil.net.NetUtil"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<%@page import="java.util.Map"%>
<%@page import="cn770880.jutil.j4log.Logger"%>
<%
	Logger log = Logger.getLogger("webapp_bss_monitor");
	request.setCharacterEncoding("UTF-8");
	CookieBox cookieBox = new CookieBox(request, response);
	if (cookieBox.getCookie("bss_sid") == null) {
		response.sendRedirect("/ader/login.html");
		return;
	}
	String bss_sid = cookieBox.getCookie("bss_sid").getValue();
	AdminEntity admin = null;
	if (StringUtil.isNotEmpty(bss_sid))
		admin = AdminClient.getAdminService().getAdminBySid(bss_sid).getObj();
	if (admin == null) {
		response.sendRedirect("/ader/login.html");
		return;
	}
	if (admin.getStatus() == 0) {
		cookieBox.setCookie("bss_sid", "", ".yonghui.cn", 0, "/");
		response.sendRedirect("/ader/login.html");
		return;
	}
	String uri = request.getRequestURI();

	String _logoInfoMsg = "";
	Map<String, String[]> requestParams = request.getParameterMap();
	for (Iterator<String> iter = requestParams.keySet().iterator(); iter
			.hasNext();) {
		try {
			String name = iter.next();
			String[] values = requestParams.get(name);
			String valueStr = "";
			for (int i = 0; i < values.length; i++) {
				valueStr = (i == values.length - 1) ? valueStr
						+ values[i] : valueStr + values[i] + ",";
			}
			_logoInfoMsg += name + "=" + valueStr + "&";
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	log.info("monitor operator[" + admin.getUserName() + "] api[" + uri
			+ "] params[" + _logoInfoMsg + "]");
%>