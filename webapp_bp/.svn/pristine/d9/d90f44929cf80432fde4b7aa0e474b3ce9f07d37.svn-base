package com.yonghui.webapp.bp.api.test.utils;

public class HexUtil{
    private static final char[] digits = new char[] { '0', '1', '2', '3', '4',
            '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E',
            'F' };

    public static final byte[] emptybytes = new byte[0];

    /**
     * 将单个字节转成Hex String
     * @param b   字节
     * @return String Hex String
     */
    public static String byte2HexStr(byte b) {
        char[] buf = new char[2];
        buf[1] = digits[b & 0xF];
        b = (byte) (b >>> 4);
        buf[0] = digits[b & 0xF];
        return new String(buf);
    }

    /**
     * 将字节数组转成Hex String
     * @param b
     * @return String
     */
	public static String bytes2HexStr(byte[] bytes){
		if(bytes == null || bytes.length == 0) {
			return null;
		}
		return bytes2HexStr(bytes, 0, bytes.length);
	}
	

	public static String bytes2HexStr(byte[] bytes, int offset, int length){
		if (bytes == null || bytes.length == 0){
			return null;
		}

		if(offset < 0){			
			throw new IllegalArgumentException("offset(" + offset + ")");		
		}
		
		if (offset + length > bytes.length){
			throw new IllegalArgumentException(
					"offset + length(" + offset + length + ") > bytes.length(" + bytes.length + ")");
		}

		char[] buf = new char[2 * length];
		for (int i = 0; i < length; i++){
			byte b = bytes[i + offset];
			buf[2 * i + 1] = digits[b & 0xF];
			b = (byte) (b >>> 4);
			buf[2 * i + 0] = digits[b & 0xF];
		}
		return new String(buf);
	}
}
