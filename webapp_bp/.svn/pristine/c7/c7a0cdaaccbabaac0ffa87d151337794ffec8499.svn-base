package com.yonghui.webapp.bp.api.test;

import java.util.Collection;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import com.fasterxml.jackson.databind.DeserializationFeature;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.SerializationFeature;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;

@SuppressWarnings("unchecked")
public final class JsonUtil {
	
	public static final ObjectMapper MAPPER = new ObjectMapper();

	static {
		/** 枚举类型以toString()来输出 **/
		MAPPER.configure(SerializationFeature.WRITE_ENUMS_USING_TO_STRING, true);
		MAPPER.configure(DeserializationFeature.FAIL_ON_UNKNOWN_PROPERTIES, false);  
	}
	
	public static String toJson(Object obj) {
		if (obj == null) return "";
		if (obj instanceof Collection) {
			return JSONArray.fromObject(obj).toString();
		}
		return JSONObject.fromObject(obj).toString();
	}
	
	public static List<Object> toList(String jsonStr) {
		JSONArray ja = JSONArray.fromObject(jsonStr);
		List<Object> list = (List<Object>) JSONArray.toCollection(ja);
		return list;
	}
	
	public static Map<String, String> toMap(String jsonStr) {
		Map<String, String> result = new HashMap<String, String>();
		JSONObject jobj = JSONObject.fromObject(jsonStr);
		Iterator<String> it = jobj.keys();
		while (it.hasNext()) {
			String key = it.next();
			String value = jobj.getString(key);
			result.put(key, value);
		}
		return result;
	}
	
	public static <T> T toBean(String jsonStr, Class<T> cls) {
		JSONObject jobj = JSONObject.fromObject(jsonStr);
		T bean = (T) JSONObject.toBean(jobj, cls);
		return bean;
	}
}
