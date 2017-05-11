package com.yonghui.webapp.bp.api.test.utils;

import java.nio.ByteBuffer;
import java.util.Random;

import cn770880.jutil.crypto.DESCoding;
import cn770880.jutil.crypto.HexUtil;

public class KeyUtil {
    private static final byte[] keys = "%LB$)<#>#}F@ZlI1<7R>><*&".getBytes();
    private static DESCoding des = null; 
    static
    {
    	try {
    		des = new DESCoding(keys);
    	} catch (Exception ex) {
    		ex.printStackTrace();
    	}
    }
    
    public static class KeyInfo{
    	public int skey;
    	public long channel;
    	public long time;
    	
    	@Override
    	public String toString() {
    		return skey + "," + channel + "," + time;
    	}
    }
    
    public static String createKey( int skey, long channel, long now ){
		ByteBuffer buf = ByteBuffer.allocate(20);
		buf.putInt( skey );
		buf.putLong(channel);
		buf.putLong( now );
		buf.flip();
		byte[] encodeBytes = des.encode(buf.array());
		return HexUtil.bytes2HexStr(encodeBytes);//不用base64了，增强一些性能
    }
    
    public static KeyInfo decodeKey( String key  ){
    	byte[] bs = HexUtil.hexStr2Bytes( key );
    	byte[] bs2 = des.decode( bs );
    	if( bs2.length != 20 )
    		throw new RuntimeException( "key error ! ");
    	ByteBuffer buf = ByteBuffer.wrap( bs2 );
    	KeyInfo info = new KeyInfo();
    	info.skey = buf.getInt();
    	info.channel = buf.getLong();
    	info.time = buf.getLong();
    	return info;
    }
    
    public static void main(String[] args) {
    	Random random = new Random();
    	String key = createKey(random.nextInt(), 2016113291010165521L, System.currentTimeMillis());
    	System.out.println(key);//E3C7CE11D404808DB133CBCFFB0F2165A62D1E3BEA5314C8
    	System.out.println(decodeKey("E7D5FC56C64B49105F958F39AA1F577F3FBDC75D97A02CD7").toString());
	}
    
}
