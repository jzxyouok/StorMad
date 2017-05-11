/**
 * 
 */
package com.yonghui.webapp.bss.util;


/**
 * <b>描述：</b>获取汉字首字母<br>
 * <b>日期：</b>2016年5月23日<br>
 * <b>作者：</b>rush<br>
 *
 */
public class GetFirstLetter {
	private static final int[] secPosvalueList = {45217, 45252, 45253, 45760, 45761, 46317, 
		46318, 46825, 46826, 47009, 47010, 47296, 47297, 47613, 47614, 48118, 
		48119, 49061, 49062, 49323, 49324, 49895, 49896, 50370, 50371, 50613, 
		50614, 50621, 50622, 50905, 50906, 51386, 51387, 51445, 51446, 52217, 
		52218, 52697, 52698, 52979, 52980, 53688, 53689, 54480, 54481, 55289};  
	private static final char[] firstLetter = {  
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J',  
		'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',  
		'T', 'W', 'X', 'Y', 'Z'};  

	public static String getPYIndexStr(String strChinese, boolean bUpCase){
		try{
			StringBuffer buffer = new StringBuffer();
			byte b[] = strChinese.getBytes("GBK");//把中文转化成byte数组
			for(int i = 0; i < b.length; i++){
				if((b[i] & 255) > 128){
					int char1 = b[i++] & 255;
					char1 <<= 8;//左移运算符用“<<”表示，是将运算符左边的对象，向左移动运算符右边指定的位数，并且在低位补零。其实，向左移n位，就相当于乘上2的n次方
					int chart = char1 + (b[i] & 255);
					buffer.append(getPYIndexChar((char)chart, bUpCase));
					continue;
				}
				char c = (char)b[i];
				if(!Character.isJavaIdentifierPart(c))//确定指定字符是否可以是 Java 标识符中首字符以外的部分。
					c = 'A';
				buffer.append(c);

			}
			return buffer.toString();
		} catch (Exception e) {

		}
		return null;
	}


	private static char getPYIndexChar(char strChinese, boolean bUpCase){
		int charGBK = strChinese;
		char result = '0';
		int index = 0;
		for (int i = 0; i < 23; i++) {  
			if (charGBK >= secPosvalueList[index] &&  
					charGBK < secPosvalueList[index + 1]) {  
				result = firstLetter[i];
				break;  
			}  
			index = index + 2;
		}
		if(!bUpCase)
			result = Character.toLowerCase(result);
		return result;
	}
	
	public static void main(String[] args) {
		System.out.println(getPYIndexStr("飒", true));
	}
}
