package com.yonghui.webapp.bss.api.invoice;

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

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.comp.invoice.share.bean.InvoiceEntity;
import com.yonghui.comp.invoice.share.enums.InvoiceStatus;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.j4log.Logger;
import cn770880.jutil.string.StringUtil;

/**
 * 导出发票记录
 * 
 * @author bob
 *
 */
public class ExportInvoiceHandler implements ApiHandler {

	private Logger log = Logger.getLogger("webapp_bp");
	
	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		
		List<InvoiceEntity> list = queryInvoiceList(request, admin);
		
		try {
			exportExcel(response, out, list, admin);
		} catch(Exception ex) {
			log.error("导出发票记录出现异常", ex);
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
	private List<InvoiceEntity> queryInvoiceList(HttpServletRequest request, AdminEntity admin) {
		int pageNo = 1;
		int pageSize = Integer.MAX_VALUE;
		String yearMonth = request.getParameter("yearMonth");
		String corpName = request.getParameter("corpName");

		Map<String, Object> params = new HashMap<String, Object>();
		if(StringUtil.isEmpty(yearMonth)) {
			Calendar calendar = Calendar.getInstance();
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM");
			yearMonth = sdf.format(calendar.getTime());
		}
		if(StringUtil.isNotEmpty(corpName)) {
			params.put("corpName", corpName);
		}
		params.put("status", InvoiceStatus.WAITING.getStatus());
		

		InvoiceService service = InvoiceClient.getInvoiceService();
		RespWrapper<DataPage<InvoiceEntity>> resp = service.query(params, pageNo, pageSize);
		
		if(resp.getObj() != null) {
			return resp.getObj().getRecord();
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
	private void exportExcel(HttpServletResponse response, Writer out, List<InvoiceEntity> list, AdminEntity admin) throws Exception {
		Calendar calendar = Calendar.getInstance();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

		try {
			Workbook wb = new HSSFWorkbook();

			CreationHelper createHelper = wb.getCreationHelper();
			Sheet sheet = wb.createSheet("消费记录");
			Row row = sheet.createRow((short) 0);

			// Create a cell and put a value in it.
			Cell cell = null;

			// Or do it on one line.
			row.createCell(0).setCellValue(createHelper.createRichTextString("标识字段"));
			row.createCell(1).setCellValue(createHelper.createRichTextString("申请时间"));
			row.createCell(2).setCellValue(createHelper.createRichTextString("广告主名称"));
			row.createCell(3).setCellValue(createHelper.createRichTextString("发票账期"));
			row.createCell(4).setCellValue(createHelper.createRichTextString("发票名称"));
			row.createCell(5).setCellValue(createHelper.createRichTextString("发票抬头"));
			row.createCell(6).setCellValue(createHelper.createRichTextString("发票金额（元）"));
			row.createCell(7).setCellValue(createHelper.createRichTextString("收件人姓名"));
			row.createCell(8).setCellValue(createHelper.createRichTextString("收件人手机号码"));
			row.createCell(9).setCellValue(createHelper.createRichTextString("收件人地址"));
			row.createCell(10).setCellValue(createHelper.createRichTextString("发票号"));
			row.createCell(11).setCellValue(createHelper.createRichTextString("快递单号"));

			if (list != null && !list.isEmpty()) {
				int index = 1;
				AddrService addrService = InvoiceClient.getAddrService();
				AddrEntity addrEntity = null;
				String consignee = "";
				String address = "";
				String phone = "";

				for (InvoiceEntity entity : list) {
					row = sheet.createRow((short) index);
					
					//标识字段
					row.createCell(0).setCellValue(entity.getIvId());
					
					// 申请时间
					calendar.setTimeInMillis(entity.getApplyTime());
					cell = row.createCell(1);
					cell.setCellValue(sdf.format(calendar.getTime()));
					
					
					// 广告主名称
					row.createCell(2).setCellValue(createHelper.createRichTextString(entity.getCorporation()));
					
					// 发票账期
					calendar.setTimeInMillis(entity.getAcctPeriod());
					cell = row.createCell(3);
					cell.setCellValue(sdf.format(calendar.getTime()));
					
					// 发票名称
					row.createCell(4).setCellValue(createHelper.createRichTextString(entity.getTitle().getTitle()));
					
					//发票抬头
					row.createCell(5).setCellValue(createHelper.createRichTextString(entity.getCorporation()));
					
					// 发票金额（元）
					cell = row.createCell(6);
					cell.setCellValue(entity.getMoney()/100.0);
					
					//收件人姓名
					consignee = "";
					address = "";
					phone = "";
					addrEntity = addrService.findById(entity.getAddrId()).getObj();
					if(addrEntity != null) {
						consignee = addrEntity.getConsignee();
						phone = addrEntity.getPhone();
						address = addrEntity.getProvince() + addrEntity.getCity() + addrEntity.getDistrict() + addrEntity.getAddress();
					}
					row.createCell(7).setCellValue(createHelper.createRichTextString(consignee));
					
					//收件人手机号码
					row.createCell(8).setCellValue(createHelper.createRichTextString(phone));
					
					//收件人地址
					row.createCell(9).setCellValue(createHelper.createRichTextString(address));
					
					//发票号
					row.createCell(10).setCellValue(createHelper.createRichTextString(entity.getInvoiceNo()));
					//快递单号
					row.createCell(11).setCellValue(createHelper.createRichTextString(entity.getExpressNo()));
					
					index++;
				}
			}
			
			String fileName = URLEncoder.encode("发票记录.xls","UTF-8");
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
			log.error("导出发票记录异常", ex);
		}
		JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(1003, "导出失败", false));
	}
}
