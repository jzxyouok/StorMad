package com.yonghui.webapp.bp.api.test;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.Random;

import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;

import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.conn.ssl.SSLSocketFactory;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.PoolingClientConnectionManager;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.CoreConnectionPNames;
import org.apache.http.params.HttpParams;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;

@SuppressWarnings("deprecation")
public class TestClient {
	//设置连接超时时间    
	private static final int CONNECTION_TIMEOUT = 10*1000;  
	private static final int SO_TIMEOUT = 10*1000;  
	private HttpClient client = null;
	public TestClient() { init(); }
	
	private void init() {
		try {
			HttpParams params = new BasicHttpParams();
			//设置请求超时10秒钟    
			params.setParameter(CoreConnectionPNames.CONNECTION_TIMEOUT, CONNECTION_TIMEOUT);
			//设置等待数据超时时间10秒钟  
			params.setParameter(CoreConnectionPNames.SO_TIMEOUT, SO_TIMEOUT);
			
			// 创建SSLContext对象，并使用我们指定的信任管理器初始化
			TrustManager[] tm = { new MyX509TrustManager() };
			SSLContext sslContext = SSLContext.getInstance("SSL");
			sslContext.init(null, tm, new java.security.SecureRandom());
			SSLSocketFactory socketFactory = new SSLSocketFactory(sslContext);
			//设置访问协议   
		    SchemeRegistry schreg = new SchemeRegistry();    
		    schreg.register(new Scheme("http",80,PlainSocketFactory.getSocketFactory()));   
		    schreg.register(new Scheme("https", 443, socketFactory));
		    
		    PoolingClientConnectionManager pccm = new PoolingClientConnectionManager(schreg);  
		    pccm.setDefaultMaxPerRoute(20); //每个主机的最大并行链接数    
		    pccm.setMaxTotal(100);          //客户端总并行链接最大数     
			client = new DefaultHttpClient(pccm, params);

		} catch (Exception ex) {
			ex.printStackTrace();
		}
	}
	
	/**
	 * POST方式 JSON格式请求
	 * @param url 接口地址
	 * @param params URL参数
	 * @param data 需要发送的数据
	 * @return
	 */
	public Map<String, String> postJson(String url, Map<String, String> params, Map<String, String> data) { return postJson(url, params, data, "UTF-8"); }
	
	/**
	 * POST方式 JSON格式请求
	 * @param url 接口地址
	 * @param params URL参数
	 * @param data 需要发送的数据
	 * @param input_charset 编码
	 * @return
	 */
	public Map<String, String> postJson(String url, Map<String, String> params, Map<String, String> data, String input_charset) {
		List<NameValuePair> param = new ArrayList<NameValuePair>();
		if (params != null)
			for (String key : params.keySet()) {
				param.add(new BasicNameValuePair(key, params.get(key))); 
			}
		String uri = url + URLEncodedUtils.format(param, input_charset);
		try {
			HttpPost post = new HttpPost(uri);
			post.addHeader(HTTP.CONTENT_TYPE, "application/json");
			StringEntity entity = new StringEntity(JsonUtil.toJson(data), input_charset);
			System.out.println(EntityUtils.toString(entity));
			entity.setContentType("text/json");
			post.setEntity(entity);
			HttpResponse response = client.execute(post);
			System.out.println("postJson url = " + uri + "\n" + response.getStatusLine());
			if (response.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
				String jsonStr = EntityUtils.toString(response.getEntity());
				System.out.println(jsonStr);
				return JsonUtil.toMap(jsonStr);
			}
		} catch (Exception e) {
			e.printStackTrace();
		} 
		return null;
	}

	
	public static int getNumber() {
		Random rm = new Random();
		int a = rm.nextInt(10);
		return a;
	}
}
