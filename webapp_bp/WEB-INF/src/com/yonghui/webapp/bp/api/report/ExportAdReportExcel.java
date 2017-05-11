package com.yonghui.webapp.bp.api.report;

import java.io.IOException;
import java.io.OutputStream;
import java.io.Writer;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.poi.hssf.usermodel.HSSFCell;
import org.apache.poi.hssf.usermodel.HSSFRow;
import org.apache.poi.hssf.usermodel.HSSFSheet;
import org.apache.poi.hssf.usermodel.HSSFWorkbook;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.report.share.ReportClient;
import com.yonghui.comp.report.share.ReportService;
import com.yonghui.comp.report.share.bean.AdReport;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.DateUtil;
import com.yonghui.webapp.bp.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;
import cn770880.jutil.string.StringUtil;


public class ExportAdReportExcel implements ApiHandler {

	private List<Map<Integer,String>> rowData = new ArrayList<Map<Integer, String>>();
	private Map<Integer,Column> columnData = new HashMap<Integer, Column>();


	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		ReportService service = ReportClient.getReportService();
		int adUin = ader.getAdUin();
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

		RespWrapper<List<AdReport>> pageWrapper = service.findAdReportToExcel(findParams, adUin);
		List<AdReport> reports = pageWrapper.getObj();
		if (pageWrapper.getErrCode() != 0 || reports == null || reports.isEmpty()) {
			String msg = pageWrapper.getErrCode() == 0 ? "报表数据为空!" : pageWrapper.getErrMsg();
			JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(pageWrapper.getErrCode(), msg, null));
			return;
		}

		System.out.println("需要导出execl的报表条数:"+reports.size());

		//生成导表数据
		SimpleDateFormat sf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss SSS");
		System.out.println("生成导表数据开始:"+sf.format(System.currentTimeMillis()));
		String exportDate = formatDate(System.currentTimeMillis());
		createExeclData(reports, exportDate);
		System.out.println("生成导表数据结束:"+sf.format(System.currentTimeMillis()));
		//导出表格
		try {
			outExecl(response, exportDate);
			System.out.println("导出结束:"+sf.format(System.currentTimeMillis()));
		} catch (Exception e) {
			e.printStackTrace();
			throw new RuntimeException("输出Execl时出现异常!");
		}
	}



	public void createExeclData(List<AdReport> reports, String exportDate) {
		
		if(reports.isEmpty()){
			return;
		}
		//第一行列名
		String[] headers = new String[] {"导表日期", "档期", "投放开始日期", "投放结束日期", "投放行业", 
				"广告位置", "门店", "广告标题", "展现量", "点击量", "点击率"};
		initColumnName(headers);
		addRowDta(columnData);

		//AreaShopService asService = CommonClient.getAreaShopService();
		//定义参数名
		Column bpName = columnData.get(1);	   	 	//档期
		Column bpStartDate = columnData.get(2);	 	//投放开始日期
		Column bpEndDate = columnData.get(3);	   	//投放结束日期
		Column iName = columnData.get(4);		 	//投放行业
		Column alName = columnData.get(5);       	//广告位置
		Column shop = columnData.get(6);       		//门店
		Column adTitle = columnData.get(7);        	//广告标题
		Column eprCount = columnData.get(8);      	//展现量
		Column clickCount = columnData.get(9);    	//点击量
		Column clickRate = columnData.get(10);   	//点击率

		//---设值开始---
		for (int i=0; i<reports.size(); i++) {
			//只清空columnData的值,保留键
			removeVal();
			//开始取值
			AdReport report = reports.get(i);
			columnData.get(0).val = exportDate;							      //导表日期
			bpName.val = report.getBpName();	  								
			bpStartDate.val = DateUtil.getDateFormatter(report.getBpStartTime(), DateUtil.dateSimple);
			bpEndDate.val = DateUtil.getDateFormatter(report.getBpEndTime(), DateUtil.dateSimple);
			iName.val = report.getIName();
			alName.val = report.getAlName();
			shop.val = report.getShopCode();
			adTitle.val = report.getAdTitle();
			eprCount.val = report.getEprCount() + "";
			clickCount.val = report.getClickCount() + "";
			clickRate.val = String.valueOf(report.getClickRate()) + "%";
			addRowDta(columnData);
		}
	}

	//输出数据Execl
	@SuppressWarnings("resource")
	private void outExecl(HttpServletResponse response, String exportDate) throws Exception{
		HSSFWorkbook wb = new HSSFWorkbook();
		HSSFSheet sheet = wb.createSheet("sheet1");
		for (int i = 0; i < rowData.size(); i++) {
			HSSFRow row = sheet.createRow(i);
			Map<Integer,String> colMap = rowData.get(i);
			for (int j = 0; j < colMap.size(); j++) {
				HSSFCell cell = row.createCell(j);
				String valStr = colMap.get(j);
				int val = StringUtil.convertInt(valStr, Integer.MIN_VALUE);
				//判断一下内容值可以转为数字则按照数字输出到execl否则一律按字符串输出到execl中
				if(val != Integer.MIN_VALUE){
					cell.setCellValue(val);
				}else{
					cell.setCellValue(valStr);
				}
			}
		}
		
		String fileName = URLEncoder.encode("投放数据"+exportDate+".xls","UTF-8");
		response.reset();
		response.setContentType("application/x-download");
		response.setCharacterEncoding("UTF-8");
		response.addHeader("Content-Disposition","attachment;filename="+fileName); 
		OutputStream os = response.getOutputStream();
		wb.write(os);
		os.flush();
		os.close();
	}

	//初始化列名
	void initColumnName(String[] columnNames){
		for (int i = 0; i < columnNames.length; i++) {
			columnData.put(i, Column.makeObj(columnNames[i]));
		}
	}

	//清空列值,保留列数
	void removeVal(){
		Set<Integer> keys = columnData.keySet();
		for (Integer key : keys) {
			columnData.get(key).val="";
		}
	}

	//记录一行数据
	void addRowDta(Map<Integer,Column> colData){
		Map<Integer,String> newData = new HashMap<Integer, String>();
		for (Entry<Integer, Column> en : colData.entrySet()) {
			newData.put(en.getKey(), en.getValue().val);
		}
		rowData.add(newData);
	}

	//格式化时间戳
	String formatDate(long time){
		if(time < 1)
			return "";
		String formatStr = "yyyy-MM-dd";
		SimpleDateFormat sdf = new SimpleDateFormat(formatStr);
		return sdf.format(new Date(time));
	}

	/**每行每的单元格对象*/
	static class Column{
		private String val;  //单元格中的内容值

		public static Column makeObj(String str){
			Column col = new Column();
			col.val = str;
			return col;
		}
	}
}