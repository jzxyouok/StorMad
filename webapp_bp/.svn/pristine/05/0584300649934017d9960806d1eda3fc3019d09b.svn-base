package com.yonghui.webapp.bp.api.test.utils;

import java.security.MessageDigest;



public class MD5Coding{
	

	/**
	 * MD5算法加密字节数组
	 * @param bytes  要加密的字节
	 * @return byte[] 加密后的字节数组
	 */
	public static byte[] encode(byte[] bytes) {
		try {
			MessageDigest digest = MessageDigest.getInstance("MD5"); 
			digest.update(bytes);
			byte[] digesBytes = digest.digest();
			System.out.println(digesBytes.length);
			return digesBytes;
		}
		catch(Exception e){
			return null;
		}
	}
	
	public static byte[] encode(String str) {
		return encode(str.getBytes());
	}

	/**
	 * 用MD5算法加密后再转换成hex String
	 * @param bytes
	 * @return String
	 */
	public static String encode2HexStr(byte[] bytes){
		return HexUtil.bytes2HexStr(encode(bytes));
	}
	
	public static String encode2HexStr(String str){
		return HexUtil.bytes2HexStr(encode(str));
	}

	public static void main(String[] args) {
		System.out.println( MD5Coding.encode2HexStr( new byte[]{0,0,0} ));
	}
}
