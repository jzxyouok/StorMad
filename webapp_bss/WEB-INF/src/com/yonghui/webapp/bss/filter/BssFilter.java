package com.yonghui.webapp.bss.filter;

import java.io.IOException;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.string.StringUtil;

import com.feizhu.webutil.net.CookieBox;
import com.yonghui.comp.admin.share.AdminClient;
import com.yonghui.comp.admin.share.bean.AdminEntity;


public class BssFilter implements Filter{
	@Override
	public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain) throws IOException, ServletException 	{
        HttpServletRequest req = (HttpServletRequest)  request;
        HttpServletResponse res = (HttpServletResponse) response;
        String uri = req.getRequestURI();
    	
    	//让浏览器不缓存html文件
    	if( uri.endsWith( ".html") || uri.endsWith("/") ){
        	res.setHeader("Cache-Control", "no-store");  
        	res.setHeader("Pragma", "no-cache");  
        	res.setDateHeader("Expires", 0); 
    	}
    	String excludeUri = "/ader/login.html,/ader/login-new-account.html,/ader/login-forgot-password.html";
    	//如果sid失效就302到 login.html
    	if( uri.endsWith("/") || (excludeUri.indexOf(uri) == -1 
    			&& uri.endsWith( ".html")) ){
			CookieBox cookieBox = new CookieBox( req, res );
			Cookie cookie = cookieBox.getCookie( "bss_sid");
			if( cookie == null ){
				res.sendRedirect("/ader/login.html");
				return;
			}
			String bss_sid = cookie.getValue();
			AdminEntity admin = null;
			if (StringUtil.isNotEmpty(bss_sid))
				admin = AdminClient.getAdminService().getAdminBySid(bss_sid).getObj();
			if (admin == null) {
				res.sendRedirect("/ader/login.html");
				return;
			}
		}
		//继续执行下一个filter
		chain.doFilter( request, response );
		return;
	}
	@Override
	public void init(FilterConfig filterConfig) throws ServletException {
	}
	@Override
	public void destroy(){
	}
}
