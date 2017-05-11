package com.yonghui.webapp.bp.util;

import java.text.DecimalFormat;

import cn770880.jutil.string.StringUtil;

public class MoneyUtil {

	/**
	 * 
	 * <b>日期：2016年12月8日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：换算金额，将元换算成分</b><br>
	 * <b>@param str
	 * <b>@return</b><br>
	 * <b>long</b>
	 */
	public static long convertMoney(String str) {
		if(StringUtil.isEmpty(str)) return 0;
		
		Float money = StringUtil.convertFloat(str, 0) * 100;
		return money.longValue();
	}
	
	/**
	 * 
	 * <b>日期：2016年12月8日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：将分转换成元</b><br>
	 * <b>@param money
	 * <b>@return</b><br>
	 * <b>float</b>
	 */
	public static float revertMoney(long money) {
		if(money == 0) return 0;
		
		Float f = money * 1.0F / 100;
		DecimalFormat df = new DecimalFormat("0.00");
		String number = df.format(f);
		
		return StringUtil.convertFloat(number, 0);
	}
}
