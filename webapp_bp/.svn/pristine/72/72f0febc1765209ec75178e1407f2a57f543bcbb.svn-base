package com.yonghui.webapp.bp.filter;

import java.io.IOException;
import java.net.URLEncoder;

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
import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.ader.share.enums.StatusEnum;


public class BpFilter implements Filter{
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
		
		//如果sid失效就302到 login.html
		String excludePage = "/ader/login-new-account.html;/ader/login.html;/ader/login-forgot-password.html;/ader/ader-info-rewrite.html;/ader/login-new-account-status.html;";
		
		if( ( excludePage.indexOf(uri) == -1 || uri.equals("/")) 
				&& ( uri.endsWith( ".html") || uri.endsWith("/")  )
				){
			CookieBox cookieBox = new CookieBox( req, res );
			Cookie cookie = cookieBox.getCookie( "bp_sid");
			if( cookie == null ){
				res.sendRedirect("/ader/login.html");
				return;
			}
			
			String bp_sid = cookie.getValue();
			AderEntity ader = null;
			if (StringUtil.isNotEmpty(bp_sid)) {
				ader = AderClient.getAderService().getAderBySid(bp_sid).getObj();
			}
			if (ader == null) {
				res.sendRedirect("/ader/login.html");
				return;
			} else {
				if(ader.getStatus() == StatusEnum.NOPASS.getStatus()) {
					res.sendRedirect("/ader/login.html");
					return;
				}
			}
			cookieBox.setCookie("bp_sid", bp_sid, ".yonghui.cn", 900, "/");
			cookieBox.setCookie("bp_logo_url", ader.getLogoUrl(), ".yonghui.cn", 15*60, "/");
            cookieBox.setCookie("bp_login_name", URLEncoder.encode(ader.getLoginName(), "UTF-8"), ".yonghui.cn", 15*60, "/");
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
