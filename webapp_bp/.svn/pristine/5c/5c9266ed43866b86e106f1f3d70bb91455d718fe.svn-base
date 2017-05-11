package com.yonghui.webapp.bp.util;

import com.yonghui.comp.log.share.LogClient;
import com.yonghui.comp.log.share.LogService;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.comp.log.share.enums.SysType;

public class OpLogUtil {
	private static LogService logService = null;
	static {
		try {
			logService = LogClient.getLogService();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public static void writeOperateLog(String content, int operatorId, String operatorName,
			OpType opType, boolean isSuccess) {
		try {
			logService.writeOperateLog(content, operatorId,
					operatorName, opType, SysType.BP, isSuccess);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
