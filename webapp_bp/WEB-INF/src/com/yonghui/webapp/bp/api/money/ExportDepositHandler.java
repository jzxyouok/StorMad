package com.yonghui.webapp.bp.api.money;

import java.io.IOException;
import java.io.OutputStream;
import java.io.Writer;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.CreationHelper;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.money.share.AmountService;
import com.yonghui.comp.money.share.DepositService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.AmountEntity;
import com.yonghui.comp.money.share.bean.DepositEntity;
import com.yonghui.comp.money.share.enums.DepositStatus;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.api.test.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.j4log.Logger;
import cn770880.jutil.string.StringUtil;

/**
 * 导出消费记录
 * 
 * @author bob
 *
 */
public class ExportDepositHandler implements ApiHandler {

	private Logger log = Logger.getLogger("webapp_bp");
	
	//Excel存放地址
//	private static String ExcelPath = "/data/static/excel/";

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		List<DepositEntity> list = getDepositList(request, ader);
		
		try {
			exportExcel(response, out, list, ader);
		} catch(Exception ex) {
			log.info("导出消费记录异常", ex);
		}
	}

	/**
	 * 
	 * <b>日期：2016年12月15日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：查询数据</b><br>
	 * <b>@param request
	 * <b>@param ader
	 * <b>@return</b><br>
	 * <b>List<BalanceVo></b>
	 */
	private List<DepositEntity> getDepositList(HttpServletRequest request, AderEntity ader) {
		int pageNo = 1;
		int pageSize = Integer.MAX_VALUE;
		int type = StringUtil.convertInt(request.getParameter("type"), -1);
		String yearMonth = request.getParameter("yearMonth");
		
		Map<String, Object> params = new HashMap<String, Object>();
		if(type > -1) {
			params.put("type", type);
		}
		if(StringUtil.isNotEmpty(yearMonth)) {
			params.put("yearMonth", yearMonth);
		} else {
			Calendar calendar = Calendar.getInstance();
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM");
			yearMonth = sdf.format(calendar.getTime());
		}
		params.put("ad_uin", ader.getAdUin());
		params.put("status", DepositStatus.PAY_OVER.getStatus());
		
		DepositService service = MoneyClient.getDepositService();
		DataPage<DepositEntity> page = service.query(params, pageNo, pageSize, ader.getAdUin()).getObj();
		
		if(page != null) {
			return page.getRecord();
		} else {
			return null;
		}
	}

	/**
	 * 
	 * <b>日期：2016年12月15日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：生成Excel</b><br>
	 * <b>@param list</b><br>
	 * <b>void</b>
	 */
	private void exportExcel(HttpServletResponse response, Writer out, List<DepositEntity> list, AderEntity ader) throws Exception {
		Calendar calendar = Calendar.getInstance();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

		try {
			Workbook wb = new HSSFWorkbook();

			CreationHelper createHelper = wb.getCreationHelper();
			Sheet sheet = wb.createSheet("消费记录");
			Row row = sheet.createRow((short) 0);

			// Create a cell and put a value in it.
			Cell cell = null;
//			CellStyle cellStyle = wb.createCellStyle();

			// Or do it on one line.
			row.createCell(0).setCellValue(createHelper.createRichTextString("充值时间"));
			row.createCell(1).setCellValue(createHelper.createRichTextString("充值类型"));
			row.createCell(2).setCellValue(createHelper.createRichTextString("充值金额（元）"));
			row.createCell(3).setCellValue(createHelper.createRichTextString("充值状态"));
			row.createCell(4).setCellValue(createHelper.createRichTextString("账户余额（元）"));

			if (list != null && !list.isEmpty()) {
				int index = 1;
				AmountService amountService = MoneyClient.getAmountService();
				AmountEntity amountEntity = null;
				long amount = 0;

				for (DepositEntity entity : list) {
					row = sheet.createRow((short) index);
					// 充值时间
					calendar.setTimeInMillis(entity.getCrtTime());
					cell = row.createCell(0);
					cell.setCellValue(sdf.format(calendar.getTime()));
					
					// 充值类型
					row.createCell(1).setCellValue(createHelper.createRichTextString(entity.getModeCN()));
					// 充值金额（元）
					cell = row.createCell(2);
					cell.setCellValue(entity.getMoney()/100.0);
					
					// 充值状态
					cell = row.createCell(3);
					row.createCell(3).setCellValue(createHelper.createRichTextString(DepositStatus.getName(entity.getStatus())));
					
					amount = 0;
					amountEntity = amountService.findByAdUin(entity.getAdUin()).getObj();
					if(amountEntity != null) {
						amount = amountEntity.getCash() + amountEntity.getGoods();
					}
					
					// 账户余额（元）
					cell = row.createCell(4);
					cell.setCellValue(amount/100.0);

					index++;
				}
			}
			
			String fileName = URLEncoder.encode("充值记录.xls","UTF-8");
			response.reset();
			response.setContentType("application/x-download");
			response.setCharacterEncoding("UTF-8");
			response.addHeader("Content-Disposition","attachment;filename="+fileName); 
			OutputStream os = response.getOutputStream();
			wb.write(os);
			os.flush();
			os.close();
			wb.close();
			
			JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(0, "", true));
			return;
		} catch (Exception ex) {
			log.error("到处消费记录异常", ex);
		}
		JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(1003, "导出失败", false));
	}
}
