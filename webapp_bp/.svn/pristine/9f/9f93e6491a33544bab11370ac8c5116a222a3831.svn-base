package com.yonghui.webapp.bp.api.ad.spread;

import java.io.IOException;
import java.io.Writer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.DataPage;
import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.net.NetUtil;

import com.yonghui.comp.ad.share.AdClient;
import com.yonghui.comp.ad.share.SpreadService;
import com.yonghui.comp.ad.share.bean.SpreadPlan;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.resp.spread.FindSpreadPlanPageResp;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>分页查询推广计划<br>
 * <b>日期：</b>2016年11月9日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class FindSpreadPlanPage implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		SpreadService service = AdClient.getSpreadService();
		int adUin = ader.getAdUin();
		int pageNo = NetUtil.getIntParameter( request, "pageNo", 1);
		int pageSize = NetUtil.getIntParameter( request, "pageSize", 20);
		Map<String, Object> findParams = new HashMap<String, Object>();
		RespWrapper<DataPage<SpreadPlan>> result = service.findSpreadPlanPage(findParams, adUin, pageNo, pageSize);

		JsonUtil.MAPPER.writeValue( out, getRespPage(result));
	}
	
	/**
	 * 
	 * <br>
	 * <b>功能：</b>封装前端显示所需参数<br>
	 * <b>日期：</b>2016年11月17日<br>
	 * <b>作者：</b>RUSH<br>
	 *
	 * @param adInfoPageWrapper
	 * @return
	 */
	public static RespWrapper<DataPage<FindSpreadPlanPageResp>> getRespPage(RespWrapper<DataPage<SpreadPlan>> spPageWrapper) {
		
		RespWrapper<DataPage<FindSpreadPlanPageResp>> resp = 
				RespWrapper.makeResp(spPageWrapper.getErrCode(), spPageWrapper.getErrMsg(), null);
		
		DataPage<SpreadPlan> spPage = spPageWrapper.getObj();
		
		if (spPage != null) {
			List<FindSpreadPlanPageResp> respDataList = new ArrayList<FindSpreadPlanPageResp>();
			DataPage<FindSpreadPlanPageResp> respPage = 
					new DataPage<FindSpreadPlanPageResp>(respDataList, spPage.getTotalRecordCount(), 
							spPage.getPageSize(), spPage.getPageNo());
			
			List<SpreadPlan> infos = spPage.getRecord();
			for (SpreadPlan info : infos) {
				
				FindSpreadPlanPageResp respData = new FindSpreadPlanPageResp();
				respData.setSpId(info.getSpId());
				respData.setSpName(info.getSpName());
				respData.setSpStatus(info.getSpStatus());
				respData.setPv(0);
				respData.setClick(0);
				respData.setCtr(0);
				respData.setSgCount(info.getSgCount());
				respData.setAdCount(info.getAdCount());
				respData.setCreateTime(info.getCreateTime());
				respData.setOptime(info.getOptime());
				respDataList.add(respData);
			}
			resp.setObj(respPage);
		}
		return resp;
	}
}
