package com.yonghui.webapp.bss.api.ad.adsize;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdSizeService;
import com.yonghui.comp.ad.share.bean.AdSize;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

/**
 * 
 * <br>
 * <b>功能：</b>分页查询广告规格信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class DeleteAdSize implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdSizeService service = AdClient.getAdSizeService();

		String operator = admin.getUserName();
		int asId = NetUtil.getIntParameter( request, "asId", 0);
		
		AdSize adSize = service.getOneAdSize(asId).getObj();
		RespWrapper<Boolean> result = service.deleteAdSize(asId, operator);
		//===============日志记录
		String opContent = "删除广告规格[" + (adSize == null ? "" : adSize.getAsName()) + "]";
		OpLogUtil.writeOperateLog(opContent, admin.getAdmUin(), 
				operator, OpType.DELETE, (result.getErrCode() == 0));
		//=====
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
