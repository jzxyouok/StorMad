package com.yonghui.webapp.bp.api.report;

import java.io.IOException;
import java.io.Writer;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.report.share.ReportClient;
import com.yonghui.comp.report.share.ReportService;
import com.yonghui.comp.report.share.bean.AdReport;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.DateUtil;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>分页查询广告数据报表<br>
 * <b>日期：</b>2016年12月14日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class FindAdReportPage implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		ReportService service = ReportClient.getReportService();

		int adUin = ader.getAdUin();
		int pageNo = NetUtil.getIntParameter( request, "pageNo", 1);
		int pageSize = NetUtil.getIntParameter( request, "pageSize", 20);
		String reportDate = NetUtil.getStringParameter( request, "reportDate", "");
		String bpName = NetUtil.getStringParameter( request, "bpName", "");
		String iName = NetUtil.getStringParameter( request, "iName", "");
		String adTitle = NetUtil.getStringParameter( request, "adTitle", "");

		Map<String, Object> findParams = new HashMap<String, Object>();
		if (StringUtil.isNotEmpty(reportDate)) {
			String [] yearMonthSplitTemp = reportDate.split("-");
			if (yearMonthSplitTemp.length == 2) {
				int startYear = StringUtil.convertInt(yearMonthSplitTemp[0], 0);
				int startMonth = StringUtil.convertInt(yearMonthSplitTemp[1], 0);
				if (startYear > 0 && startMonth > 0) {
					int endYear = startYear;
					int endMonth = startMonth + 1;
					if (endMonth > 12) {
						endYear = endYear + 1;
						endMonth = 1;
					}
					String startTimeStr = startYear + "-" + startMonth + "-01 00:00:00";
					String endTimeStr = endYear + "-" + endMonth + "-01 00:00:00";
					long startTime = DateUtil.getTime(startTimeStr);
					long endTime = DateUtil.getTime(endTimeStr);
					findParams.put("startTime", startTime);
					findParams.put("endTime", endTime);
				}
			}
		}
		if (StringUtil.isNotEmpty(bpName))
			findParams.put("bpName", bpName);
		if (StringUtil.isNotEmpty(iName)) 
			findParams.put("iName", iName);
		if (StringUtil.isNotEmpty(adTitle))
			findParams.put("adTitle", adTitle);

		RespWrapper<DataPage<AdReport>> pageWrapper = service.findAdReportPage(findParams, adUin, pageNo, pageSize);
		JsonUtil.MAPPER.writeValue( out, pageWrapper);
	}
}
