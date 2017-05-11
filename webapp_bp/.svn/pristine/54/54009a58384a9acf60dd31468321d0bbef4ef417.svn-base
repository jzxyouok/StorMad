package com.yonghui.webapp.bp.util;

import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics2D;
import java.awt.image.BufferedImage;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.Locale;
import java.util.Random;
import java.util.concurrent.atomic.AtomicInteger;

import javax.imageio.ImageIO;
import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;


import cn770880.jutil.string.StringUtil;

public class ImageVerifyCode {
	
	private static int imgWidth = 125;
    private static int imgHeight = 50;
    private static int codeCount = 5;
    private static int x = imgWidth / (codeCount + 1);;
    private static int fontHeight = imgHeight - 2;
    private static int codeY = imgHeight - 8;
    private static String fontStyle = "Times New Roman";
 

    public void processRequest(HttpServletRequest request, HttpServletResponse response, String id) throws Exception{
        response.setContentType("image/jpeg");
        response.setHeader("Pragma", "No-cache");
        response.setHeader("Cache-Control", "no-cache");
        response.setDateHeader("Expires", 0);
        String code = CacheUtil.hget(CacheUtil.verify_code_key, id);
        if (code == null || StringUtil.isEmpty(code))
        	return;
 
        // 在内存中创建图象
        BufferedImage image = new BufferedImage(imgWidth, imgHeight,
                BufferedImage.TYPE_INT_RGB);
 
        // 获取图形上下文
        Graphics2D g = image.createGraphics();
 
        // 生成随机类
        Random random = new Random();
 
        // 设定背景色
        g.setColor(Color.WHITE);
        g.fillRect(0, 0, imgWidth, imgHeight);
 
        // 设定字体
        g.setFont(new Font(fontStyle, Font.PLAIN + Font.ITALIC, fontHeight));
 
        // 画边框
        g.setColor(new Color(55, 55, 12));
        g.drawRect(0, 0, imgWidth - 1, imgHeight - 1);
 
        // 随机产生155条干扰线，使图象中的认证码不易被其它程序探测到
        g.setColor(getRandColor(160, 200));
        for (int i = 0; i < 160; i++) {
            int x = random.nextInt(imgWidth);
            int y = random.nextInt(imgHeight);
            int xl = random.nextInt(12);
            int yl = random.nextInt(12);
            g.drawLine(x, y, x + xl, y + yl);
        }
 
        int red = 0, green = 0, blue = 0;
        char [] chars = code.toCharArray();
        for (int i = 0; i < chars.length; i++) {
            red = random.nextInt(200);
            green = random.nextInt(200);
            blue = random.nextInt(120);
            char retWord = chars[i];
            g.setColor(new Color(red, green, blue));
            g.drawString(String.valueOf(retWord), (i) * x, codeY);
 
        }
        // 图象生效
        g.dispose();
        ServletOutputStream responseOutputStream = response.getOutputStream();
        // 输出图象到页面
        ImageIO.write(image, "JPEG", responseOutputStream);
 
        // 以下关闭输入流！
        responseOutputStream.flush();
        responseOutputStream.close();
    }
 
    Color getRandColor(int fc, int bc) {// 给定范围获得随机颜色
        Random random = new Random();
        if (fc > 255)
            fc = 255;
        if (bc > 255)
            bc = 255;
        int r = fc + random.nextInt(bc - fc);
        int g = fc + random.nextInt(bc - fc);
        int b = fc + random.nextInt(bc - fc);
        return new Color(r, g, b);
    }
 
    private static char getSingleNumberChar() {
        Random random = new Random();
        int numberResult = random.nextInt(10);
        int ret = numberResult + 48;
        return (char) ret;
    }
    
    public static String createCode() {
    	Random random = new Random();
        String sRand = "";	//随机Code
        for (int i = 0; i < codeCount; i++) {
            int wordType = random.nextInt(3);
            char retWord = 0;
            switch (wordType) {
            case 0:
            	while (true) {	//去除验证码中的0跟1
            		retWord = getSingleNumberChar();
            		if (retWord == '0' || retWord == '1')
            			continue;
            		break;
            	}
                break;
            case 1:
            	while (true) {	//去除验证码中的小写字母i、l、o
            		retWord = getLowerOrUpperChar(0);
            		if (retWord == 'i' || retWord == 'l' || retWord == 'o')
            			continue;
            		break;
            	}
                break;
            case 2:
            	while (true) {	//去除验证码中的大写字母I、L、O
            		retWord = getLowerOrUpperChar(1);
            		if (retWord == 'I' || retWord == 'L' || retWord == 'O')
            			continue;
            		break;
            	}
                break;
            }
            sRand += String.valueOf(retWord);
        }
        System.out.println(sRand);
        String id = newId();
        CacheUtil.hset(CacheUtil.verify_code_key, id, sRand);
        return id;
    }
    
	private static AtomicInteger a = new AtomicInteger();
	
	/**
	 * 多线程安全的获取一个ID
	 * @return
	 */
	public synchronized static String newId(){
		SimpleDateFormat df = new SimpleDateFormat( "yyMMddHHmmss", Locale.CHINA );
		DecimalFormat df1 = new DecimalFormat("00000");
		//获取一个0-999999的数
		int i = ( a.incrementAndGet() & Integer.MAX_VALUE ) % 100000;
		if (i == 0) {
			i = ( a.incrementAndGet() & Integer.MAX_VALUE ) % 100000;
		}
		return df.format( System.currentTimeMillis() )+df1.format(i);
	}
	
	public static boolean verifyCode(String id, String code) {
		String vcode = CacheUtil.hget(CacheUtil.verify_code_key, id);
		if (StringUtil.isNotEmpty(vcode) && vcode.equalsIgnoreCase(code)) {
			CacheUtil.hdel(CacheUtil.verify_code_key, id);
			return true;
		}
		return false;
	}
 
    private static char getLowerOrUpperChar(int upper) {
        Random random = new Random();
        int numberResult = random.nextInt(26);
        int ret = 0;
        if (upper == 0) {// 小写
            ret = numberResult + 97;
        } else if (upper == 1) {// 大写
            ret = numberResult + 65;
        }
        return (char) ret;
    }
    
    public static void main(String[] args) {
		for (int i = 0; i < 100; i++) 
			createCode();
	}
}
