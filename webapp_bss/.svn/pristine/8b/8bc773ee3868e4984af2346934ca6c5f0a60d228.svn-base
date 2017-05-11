package com.yonghui.webapp.bss.api.ad.adsize;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.AdSizeService;
import com.yonghui.comp.ad.share.enums.AdType;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

/**
 * 
 * <br>
 * <b>功能：</b>添加广告规格信息<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class AddAdSize implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin) throws IOException {
		AdSizeService service = AdClient.getAdSizeService();

		String operator = admin.getUserName();
		String asName = NetUtil.getStringParameter(request, "asName", "");
		int adType = NetUtil.getIntParameter(request, "adType", 0);
		int width = 0;
		int height = 0;
		int textMaxLength = -1;
		AdType adTypeEnum = AdType.getType(adType);
		if (StringUtil.isEmpty(asName) || adTypeEnum == null) {
			throw new RuntimeException("参数异常!");
		}
		if (adType == AdType.IMG_TEXT.getId()) {
			width = NetUtil.getIntParameter(request, "width", 0);
			height = NetUtil.getIntParameter(request, "height", 0);
			if (width < 1 || height < 1)
				throw new RuntimeException("参数异常!");
			textMaxLength = NetUtil.getIntParameter(request, "textMaxLength", -1);
			if (textMaxLength < 1)
				throw new RuntimeException("图文类型规格：文本最大长度限制不能为空!");
		} else {
			textMaxLength = NetUtil.getIntParameter(request, "textMaxLength", -1);
			if (textMaxLength < 1)
				throw new RuntimeException("参数异常!");
		}
		RespWrapper<Integer> result = service.addAdSize(asName, adType, width, height, textMaxLength, operator);
		//===============日志记录
		String opContent = "新增" + AdType.getName(adType) + "类型广告规格[" + asName + "]";
		OpLogUtil.writeOperateLog(opContent, admin.getAdmUin(), 
				operator, OpType.ADD, (result.getErrCode() == 0));
		//=====
		JsonUtil.MAPPER.writeValue( out, result);
	}
}
