package com.yonghui.webapp.bss.util;

public class RespException extends RuntimeException {
	
	private static final long serialVersionUID = 1L;
	
	public static final int UNKOWN_ERROR = 1001;//未知错误
	
	
	private int code;
	public RespException(int code, String msg){
		super(msg);
		this.code = code;
	}
	public int getErrorCode(){
		return code;
	}
}
