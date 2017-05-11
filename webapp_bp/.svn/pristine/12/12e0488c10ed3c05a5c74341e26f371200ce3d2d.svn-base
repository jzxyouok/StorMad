package com.yonghui.webapp.bp.api.money;

import java.io.IOException;
import java.io.OutputStream;
import java.io.Writer;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
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
import com.yonghui.comp.bid.share.BidClient;
import com.yonghui.comp.bid.share.BidService;
import com.yonghui.comp.bid.share.bean.BidLog;
import com.yonghui.comp.bid.share.enums.BidStatus;
import com.yonghui.comp.bidplan.share.BidPlanClient;
import com.yonghui.comp.bidplan.share.BidPlanService;
import com.yonghui.comp.bidplan.share.bean.BidPlanEntity;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.CommonService;
import com.yonghui.comp.common.share.bean.Industry;
import com.yonghui.comp.money.share.AmountService;
import com.yonghui.comp.money.share.BalanceService;
import com.yonghui.comp.money.share.MoneyClient;
import com.yonghui.comp.money.share.bean.AmountEntity;
import com.yonghui.comp.money.share.bean.BalanceEntity;
import com.yonghui.comp.money.share.enums.BalanceInvoice;
import com.yonghui.comp.money.share.enums.FlowEnum;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.api.test.JsonUtil;
import com.yonghui.webapp.bp.resp.invoice.BalanceVo;

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
public class ExportPayHandler implements ApiHandler {

	private Logger log = Logger.getLogger("webapp_bp");
	
	//Excel存放地址
//	private static String ExcelPath = "/data/static/excel/";

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		List<BalanceVo> list = getPayList(request, ader);
		
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
	private List<BalanceVo> getPayList(HttpServletRequest request, AderEntity ader) {
		BalanceService bService = MoneyClient.getBalanceService();
		BidPlanService bpService = BidPlanClient.getBidPlanService();
		CommonService cService = CommonClient.getCommonService();
		BidService bidService = BidClient.getBidService();
		AmountService aService = MoneyClient.getAmountService();

		String yearMonth = request.getParameter("yearMonth");
		int invoiceStatus = StringUtil.convertInt(request.getParameter("invoiceStatus"), -1);
		String iId = request.getParameter("iid");
		String bpName = request.getParameter("bpName");
		StringBuilder bpId = new StringBuilder("");

		Map<String, Object> params = new HashMap<String, Object>();
		if (StringUtil.isNotEmpty(yearMonth)) {
			params.put("yearMonth", yearMonth);
		}
		if (invoiceStatus > -1) {
			params.put("invoice_status", invoiceStatus);
		}
		if (StringUtil.isNotEmpty(iId)) {
			params.put("i_id", iId);
		}
		if (StringUtil.isNotEmpty(bpName)) {
			params.put("bp_name", bpName);
			DataPage<BidPlanEntity> bpPage = bpService.query(params, 1, Integer.MAX_VALUE).getObj();
			if (bpPage != null) {
				List<BidPlanEntity> listBP = bpPage.getRecord();
				for (BidPlanEntity bpPlan : listBP) {
					bpId.append(bpPlan.getBpId());
					bpId.append(",");
				}
			}
			params.remove("bp_name");
			params.put("bp_id", bpId);
		}

		params.put("ad_uin", ader.getAdUin());
		params.put("flow_type",
				FlowEnum.PAY.getType() + "," + FlowEnum.FREEZE.getType() + "," + FlowEnum.UNFREEZE.getType());

		List<BalanceVo> listExt = new ArrayList<BalanceVo>();
		RespWrapper<DataPage<BalanceEntity>> bResp = bService.query(params, 1, Integer.MAX_VALUE);
		DataPage<BalanceEntity> page = bResp.getObj();
		if (bResp.getErrCode() == 0 && page != null) {
			BalanceVo balanceVo = null;
			BidPlanEntity bpPlan = null;
			Industry industry = null;
			BidLog bidLog = null;
			AmountEntity amountEntity = null;

			String iName;
			String invoiceStatusCN = "";
			String bidStatus;
			long amount;

			for (BalanceEntity entity : page.getRecord()) {
				bpName = "";
				bidStatus = "";
				iName = "";
				amount = 0;

				bpPlan = bpService.findBidPlanById(entity.getBpId()).getObj();
				if (bpPlan != null) {
					bpName = bpPlan.getBpName();
				}

				industry = cService.getOneIndustry(entity.getIId()).getObj();
				if (industry != null) {
					iName = industry.getIName();
				}
				invoiceStatusCN = BalanceInvoice.getStatusCN(entity.getInvoiceStatus());

				if(entity.getFlowType() == FlowEnum.FREEZE.getType()) {
					bidStatus = BidStatus.BIG_SUCCESS.getName();
				} else {
					bidLog = bidService.getOneBidLog(entity.getOriginId()).getObj();
					if (bidLog != null) {
						bidStatus = bidLog.getStatus().getName();
					}
				}
				amountEntity = aService.findById(entity.getAmId()).getObj();
				if (amountEntity != null) {
					amount = amountEntity.getCash() + amountEntity.getGoods();
				}

				balanceVo = BalanceVo.wrapper(entity, bpName, iName, invoiceStatusCN, bidStatus, amount);
				listExt.add(balanceVo);
			}
		}
		return listExt;
	}

	/**
	 * 
	 * <b>日期：2016年12月15日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：生成Excel</b><br>
	 * <b>@param list</b><br>
	 * <b>void</b>
	 */
	private void exportExcel(HttpServletResponse response, Writer out, List<BalanceVo> list, AderEntity ader) {
		Calendar calendar = Calendar.getInstance();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");

		try {
			Workbook wb = new HSSFWorkbook();

			CreationHelper createHelper = wb.getCreationHelper();
			Sheet sheet = wb.createSheet("消费记录");
			Row row = sheet.createRow((short) 0);

			// Create a cell and put a value in it.
			Cell cell = null;

			// Or do it on one line.
			row.createCell(0).setCellValue(createHelper.createRichTextString("消费时间"));
			row.createCell(1).setCellValue(createHelper.createRichTextString("消费档期"));
			row.createCell(2).setCellValue(createHelper.createRichTextString("行业"));
			row.createCell(3).setCellValue(createHelper.createRichTextString("竞拍状态"));
			row.createCell(4).setCellValue(createHelper.createRichTextString("消费金额（元）"));
			row.createCell(5).setCellValue(createHelper.createRichTextString("可开票金额"));
			row.createCell(6).setCellValue(createHelper.createRichTextString("账户余额（元）"));
			row.createCell(7).setCellValue(createHelper.createRichTextString("扣款状态"));
			row.createCell(8).setCellValue(createHelper.createRichTextString("发票状态"));

			if (list != null && !list.isEmpty()) {
				int index = 1;

				for (BalanceVo entity : list) {
					row = sheet.createRow((short) index);
					// 消费时间
					calendar.setTimeInMillis(entity.getCrtTime());
					cell = row.createCell(0);
					cell.setCellValue(sdf.format(calendar.getTime()));
					// 档期
					row.createCell(1).setCellValue(createHelper.createRichTextString(entity.getBpName()));
					//行业
					row.createCell(2).setCellValue(createHelper.createRichTextString(entity.getIndustryName()));
					// 竞拍状态
					row.createCell(3).setCellValue(createHelper.createRichTextString(entity.getBidStatus()));
					//消费金额
					cell = row.createCell(4);
					cell.setCellValue((entity.getCash() + entity.getGoods())/100.0);
					//可开票金额
					cell = row.createCell(5);
					cell.setCellValue(entity.getCash()/100.0);
					//账户余额
					cell = row.createCell(6);
					cell.setCellValue((entity.getAmount())/100.0);
					//扣款状态
					row.createCell(7).setCellValue(createHelper.createRichTextString(FlowEnum.getFlowCN(entity.getFlowType())));
					//发票状态
					row.createCell(8).setCellValue(createHelper.createRichTextString(entity.getInvoiceStatusCN()));

					index++;
				}
			}

//			FileOutputStream fileOut = new FileOutputStream(excelName);
//			wb.write(fileOut);
//			fileOut.close();
			
			String fileName = URLEncoder.encode("消费记录.xls","UTF-8");
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
		} catch (Exception ex) {
			log.error("到处消费记录异常", ex);
		}
	}
}
