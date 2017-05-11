package com.yonghui.webapp.bp.util;

import cn770880.jutil.data.RespWrapper;

public class Exceptions {
	/**
	 * 创建未知错误
	 * @param errMsg
	 * @return
	 */
	public static RespWrapper<String> makeUnknownException( String errMsg ){
		return new RespWrapper<String>( -10001, errMsg, null);
	}
	/**
	 * 创建未登录错误
	 * @param errMsg
	 * @return
	 */
	public static RespWrapper<String> makeNotLoginException( ){
		return new RespWrapper<String>( -10000, "未登录用户", null);
	}
	
	/**
	 * 创建没有权限错误
	 * @param errMsg
	 * @return
	 */
	public static RespWrapper<String> makeNotAccessException( ){
		return new RespWrapper<String>( -10000, "没有权限访问!", null);
	}
}
