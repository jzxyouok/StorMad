package com.yonghui.webapp.bp.api.bid;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import cn770880.jutil.data.RespWrapper;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.bid.share.BidClient;
import com.yonghui.comp.bid.share.BidService;
import com.yonghui.comp.bid.share.bean.BidNotify;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.CommonService;
import com.yonghui.comp.common.share.bean.Industry;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;

/**
 * 
 * <br>
 * <b>功能：</b>档期竞拍通知（站内通知）<br>
 * <b>日期：</b>2016年11月30日<br>
 * <b>作者：</b>RUSH<br>
 *
 */
public class GetBidNotify implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AderEntity ader) throws IOException {
		BidService service = BidClient.getBidService();

		int adUin = ader.getAdUin();

		RespWrapper<BidNotify> notifyWrapper = service.bidNotify(adUin);
		BidNotify notify = notifyWrapper.getObj();
		if (notify != null) {
			CommonService cs = CommonClient.getCommonService();
			Industry industry = cs.getOneIndustry(notify.getIId()).getObj();
			String iName = "未知";
			if (industry != null)
				iName = industry.getIName();
			notify.setIName(iName);
		}
		JsonUtil.MAPPER.writeValue( out, notifyWrapper);
	}
}
