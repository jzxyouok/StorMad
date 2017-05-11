package com.yonghui.webapp.bp.api.report;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.report.share.ReportClient;
import com.yonghui.comp.report.share.ReportService;
import com.yonghui.comp.report.share.bean.AdReportDetail;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>获取单条数据报表详情<br>
 * <b>日期：</b>2016年12月14日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetAdReportDetail implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		ReportService service = ReportClient.getReportService();

		int adUin = ader.getAdUin();
		int bpId = NetUtil.getIntParameter( request, "bpId", 0);
		String iId = NetUtil.getStringParameter( request, "iId", "");
		int alId = NetUtil.getIntParameter( request, "alId", 0);
		int adId = NetUtil.getIntParameter( request, "adId", 0);
		
		RespWrapper<AdReportDetail> wrapper = service.getAdReportDetail(adUin, bpId, iId, alId, adId);
		JsonUtil.MAPPER.writeValue( out, wrapper);
	}
}
