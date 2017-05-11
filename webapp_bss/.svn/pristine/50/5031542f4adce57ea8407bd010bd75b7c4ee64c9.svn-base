package com.yonghui.webapp.bss.api.invoice;

import java.io.IOException;
import java.io.InputStream;
import java.io.Writer;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.FileUploadException;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;
import org.apache.poi.hssf.usermodel.HSSFCell;
import org.apache.poi.hssf.usermodel.HSSFDateUtil;
import org.apache.poi.hssf.usermodel.HSSFRow;
import org.apache.poi.hssf.usermodel.HSSFSheet;
import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.poifs.filesystem.POIFSFileSystem;
import org.apache.poi.ss.usermodel.Cell;

import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.InvoiceService;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.j4log.Logger;
import cn770880.jutil.string.StringUtil;

public class ImportInvoiceHandler implements ApiHandler {

	private static final Logger log = Logger.getLogger("api_invoice");

	private static final long FILE_MAX_SIZE = 2 * 1024 * 1024L;

//	private static final String EXCEL_UPLOAD_PATH = "/excel/";

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {

		String execelType = "xls,xlsx";
//		String updateBasePath = ProfileManager.getStringByKey("bss.upload_base_path", "/data/static");
		
		RespWrapper<Boolean> resp = RespWrapper.makeResp(0, "", false);

		try {
			DiskFileItemFactory fac = new DiskFileItemFactory();
			ServletFileUpload upload = new ServletFileUpload(fac);
			List<FileItem> files = null;
			try {
				files = upload.parseRequest(request);
			} catch (FileUploadException e) {
				e.printStackTrace();
			}

			if (files != null) {
				Iterator<FileItem> it = files.iterator();
				if (it.hasNext()) {
					FileItem fileItem = it.next();
					if (!fileItem.isFormField()) { // 如果是上传的文件
						String fileName = fileItem.getName();
						long size = fileItem.getSize();

						if (StringUtil.isEmpty(fileName) || size < 1 || fileName.lastIndexOf(".") == -1) {
							throw new RuntimeException("请勿传空文件!");
						}
						if (size > FILE_MAX_SIZE) {
							throw new RuntimeException("上传文件最大不能超过2M!");
						}

						String extName = fileName.substring(fileName.lastIndexOf(".") + 1).toLowerCase();
						if (StringUtil.isNotEmpty(execelType))
							if (execelType.toLowerCase().indexOf(extName) == -1)
								throw new RuntimeException("不支持的文件类型!");

//						String uuid = UUID.randomUUID().toString();
//						String saveFileName = updateBasePath + EXCEL_UPLOAD_PATH + uuid + "." + extName;
//						File excelFile = new File(saveFileName);
//						fileItem.write(excelFile);
						resp = readExcel(fileItem.getInputStream());
						if(resp.getObj()) {
							resp.setErrMsg("导入成功");
							resp.setErrCode(0);
						}
					}
				}
			}
		} catch (Exception ex) {
			log.error("导入发票数据异常");
		}

		JsonUtil.MAPPER.writeValue(out, RespWrapper.makeResp(0, "", ""));
	}

	/**
	 * 
	 * <b>日期：2016年12月15日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：读取Excel文件，更新发票编号和快递单号</b><br>
	 * <b>@param is</b><br>
	 * <b>void</b>
	 */
	public RespWrapper<Boolean> readExcel(InputStream is) {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(7007, "撤销发票信息出错", false);
		
		try {
			List<Map<String, Object>> list = new ArrayList<Map<String, Object>>();
			Map<String, Object> map = null;
			int ivId = 0;
			String invoiceNo = "";
			String expressNo = "";

			POIFSFileSystem fs = new POIFSFileSystem(is);
			HSSFWorkbook wb = new HSSFWorkbook(fs);

			HSSFSheet sheet = wb.getSheetAt(0);
			// 得到总行数
			int rowNum = sheet.getLastRowNum();
			HSSFRow row = sheet.getRow(0);
			int colNum = row.getPhysicalNumberOfCells();
			// 正文内容应该从第二行开始,第一行为表头的标题
			for (int i = 1; i <= rowNum; i++) {
				row = sheet.getRow(i);
				if (null != row) {
					if (colNum >= 1) {
						ivId = Integer.valueOf(getCellFormatValue(row.getCell(0)));
						invoiceNo = getCellFormatValue(row.getCell(10));
						expressNo = getCellFormatValue(row.getCell(11));
						
						map = new HashMap<String, Object>();
						map.put("ivId", ivId);
						map.put("invoiceNo", invoiceNo);
						map.put("expressNo", expressNo);
						list.add(map);
					}
				} else {
					break;
				}
			}
			
			if(!list.isEmpty()) {
				InvoiceService service = InvoiceClient.getInvoiceService();
				resp = service.importInvoice(list);
			} else {
				resp.setErrMsg("导入文件内容为空，导入失败");
			}
			
			wb.close();
		} catch (Exception ex) {
			log.error("读取Excel文件异常", ex);
		}
		return resp;
	}

	/**
	 * 根据HSSFCell类型设置数据
	 * 
	 * @param cell
	 * @return
	 */
	public static String getCellFormatValue(HSSFCell cell) {
		String cellvalue = "";
		if (cell != null) {
			cell.setCellType(Cell.CELL_TYPE_STRING);
			cellvalue = cell.getStringCellValue();
		}
		return cellvalue;
	}

	/**
	 * 根据HSSFCell类型读取日期格式
	 */
	public static Date getCellFormatDateValue(HSSFCell cell) {
		Date date = null;
		if (HSSFDateUtil.isCellDateFormatted(cell)) {
			date = cell.getDateCellValue();
		}
		return date;
	}
}
