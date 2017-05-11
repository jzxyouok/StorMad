package com.yonghui.webapp.bss.util;

import com.fasterxml.jackson.databind.DeserializationFeature;
import com.fasterxml.jackson.databind.JavaType;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.SerializationFeature;

public class JsonUtil {
	public static final ObjectMapper MAPPER = new ObjectMapper();

	static {
		/** 枚举类型以toString()来输出 **/
		MAPPER.configure(SerializationFeature.WRITE_ENUMS_USING_TO_STRING, true);
		MAPPER.configure(DeserializationFeature.FAIL_ON_UNKNOWN_PROPERTIES, false);  
	}

	public static JavaType getCollectionType(Class<?> collectionClass, Class<?>... elementClasses) {
		return JsonUtil.MAPPER.getTypeFactory().constructParametricType(collectionClass, elementClasses);
	}

}
