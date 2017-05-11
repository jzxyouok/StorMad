package com.yonghui.webapp.bp.api.test.utils;

public class RespException extends RuntimeException {

	private static final long serialVersionUID = -8496650977947281846L;

	public static final int UNKOWN_ERROR = 10001;//未知错误
	
	/** 缺少必要参数 **/
	public static final int ERROR_PARAMS = 10002;
	
	/** 签名错误 **/
	public static final int ERROR_SIGN= 10003;
	
	/** 数据格式异常 **/
	public static final int ERROR_FORMAT= 10004;
	
	/** 非法请求 **/
	public static final int ERROR_ILLEGAL = 10005;
	
	
	private int code;
	public RespException(int code, String msg){
		super(msg);
		this.code = code;
	}
	public int getErrorCode(){
		return code;
	}
}
