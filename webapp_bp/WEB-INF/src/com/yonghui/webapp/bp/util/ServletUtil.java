package com.yonghui.webapp.bp.util;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.StringReader;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.ServletRequest;

import cn770880.jutil.net.NetUtil;

public class ServletUtil {
	
	public static List<String> split( ServletRequest request, String name ) throws IOException{
		ArrayList<String> list = new ArrayList<String>();
		
		String str = NetUtil.getStringParameter(request, name, "");
		BufferedReader rd = new BufferedReader( new StringReader(str) );
		while( true ){
			String line = rd.readLine();
			if( line == null )
				break;
			String s = line.trim();
			if( s.length() > 0 )
				list.add( s );
		}
		return list;
	}
	
	public static String[] splitUrl( ServletRequest request, String name ) throws IOException{
		
		String str = NetUtil.getStringParameter(request, name, "");
		ArrayList<String> list = new ArrayList<String>();
		BufferedReader rd = new BufferedReader( new StringReader(str) );
		while( true ){
			String line = rd.readLine();
			if( line == null )
				break;
			String s = line.trim();
			if( s.length() == 0 )
				continue;
			if( ! s.startsWith( "http://" ) )
				throw new RuntimeException("图片地址列表每一行必须http开头");
			list.add( s );
		}
		String[] rtn = new String[ 0 ];
		return list.toArray( rtn );
	}
	
	/**
	 * 
	 * <br>
	 * <b>功能：</b>去除非数字和字母的所有字符<br>
	 * <b>日期：</b>2016年11月08日<br>
	 * <b>作者：</b>rush<br>
	 *
	 * @param str
	 * @return
	 */
	public static String removeIllegalStr(String str) {
		if (str == null || str.trim().length() == 0)
			return "";
		StringBuffer strBuf = new StringBuffer();
		for (int i = 0; i < str.length(); i++) {
			char c = str.charAt(i);
			if ((c >= '0' && c <= '9') || (c >= 'a' && c <= 'z') || (c >= 'A' && c <= 'Z')) {
				strBuf.append(c);
			}
		}
		return strBuf.toString();
	}
}
