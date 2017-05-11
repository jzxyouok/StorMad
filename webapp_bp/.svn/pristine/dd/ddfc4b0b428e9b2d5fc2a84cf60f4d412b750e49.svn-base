package com.yonghui.webapp.bp.util;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;

import cn770880.jutil.j4log.Logger;

import com.feizhu.redis.cluster.RedisClusterClient;

/**
 * 
 * <br>
 * <b>功能：</b>缓存工具类<br>
 * <b>日期：</b>2016年10月25日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public final class CacheUtil {

	private static Logger log = Logger.getLogger("webapp_bp");

	private static RedisClusterClient client = RedisClusterClient.getInstance();
	
	public static final String verify_code_key = "bp_login_verify_code";
	
	/**
	 * 图片上传临时文件缓存
	 */
	public static final String uploadTempKey = "upload_temp_key";
	
	/**
	 * hash(Map)存储 集群模式下分区进行优化。 
	 */
	private static int hashPartition = 10;
	
	public static <T> boolean saveBean(String key, String field, T bean) {
		String hashPartitionKey = getHashPartitionKey(key, field);
		Long result = client.hsetFromBean(hashPartitionKey, field, bean);
		if (null == result) {
			log.error("CacheUtil.saveBean[key="+hashPartitionKey+",field="+field+"] error : result["+result+"]");
			return false;
		}
		return true;
	}
	
	public static String hget(String key, String field) {
		String hashPartitionKey = getHashPartitionKey(key, field);
		return client.hget(hashPartitionKey, field);
	}
	
	public static Long hset(String key, String field, String value) {
		String hashPartitionKey = getHashPartitionKey(key, field);
		return client.hset(hashPartitionKey, field, value);
	}
	
	public static Long hdel(String key, String field) {
		String hashPartitionKey = getHashPartitionKey(key, field);
		return client.hdel(hashPartitionKey, field);
	}
	
	public static Long delListByVal(String key, long count, String val) {
		return client.lrem(key, count, val);
	}

	public static <T> Long delListByVal(String key, long count, T val) {
		return client.lrem(key, count, val);
	}

	public static <T> T getOneBean(String key, String field, Class<T> cls) {
		String hashPartitionKey = getHashPartitionKey(key, field);
		return client.hgetFromBean(hashPartitionKey, field, cls);
	}

	public static <T> Map<String, T> getMapByKey(String key, Class<T> cls) {
		Map<String, T> result = new HashMap<String, T>();
		for (int i = 0; i < hashPartition; i++) {
			String hashPartitionKey = key + "_" + i;
			Map<String, T> map = client.hgetAllFromBean(hashPartitionKey, cls);
			if (map != null)
				result.putAll(map);
		}
		return result;
	}

	public static <T> List<T> getValuesByKey(String key, Class<T> cls) {
		List<T> result = new ArrayList<T>();
		for (int i = 0; i < hashPartition; i++) {
			String hashPartitionKey = key + "_" + i;
			List<T> list = client.hvalsFromBean(hashPartitionKey, cls);
			if (list != null)
				result.addAll(list);
		}
		return result;
	}

	public static Set<String> getKeysByKey(String key) {
		Set<String> result = new HashSet<String>();
		for (int i = 0; i < hashPartition; i++) {
			String hashPartitionKey = key + "_" + i;
			Set<String> set = client.hkeysFromBean(hashPartitionKey);
			if (set != null)
				result.addAll(set);
		}
		return result;
	}
	
	public static String getHashPartitionKey(String key, String field) {
		int hashCode = field.hashCode();
		int partitionId = hashCode % hashPartition;
		if (partitionId < 0) 
			partitionId = Math.abs(partitionId);
		String hashPartitionKey = key + "_" + partitionId;
		return hashPartitionKey;
	}
}
