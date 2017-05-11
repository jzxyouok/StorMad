package com.yonghui.webapp.bp.api.invoice;

import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.invoice.share.AddrService;
import com.yonghui.comp.invoice.share.InvoiceClient;
import com.yonghui.comp.invoice.share.bean.AddrEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class UpdateAddrHandler implements ApiHandler {

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		
		int addrId = StringUtil.convertInt(request.getParameter("addrId"), 0);
		String province = request.getParameter("province");
		String city = request.getParameter("city");
		String district = request.getParameter("district");
		String address = request.getParameter("address");
//		String postcode = request.getParameter("postcode");
		String phone = request.getParameter("phone");
		String consignee = request.getParameter("consignee");
		
		if(StringUtil.isEmpty(province) 
				|| StringUtil.isEmpty(city)
				|| StringUtil.isEmpty(district)
				|| StringUtil.isEmpty(consignee)
				|| StringUtil.isEmpty(address)
				|| StringUtil.isEmpty(phone)
				|| StringUtil.isEmpty(consignee)) {
			RespWrapper<Boolean> resp = RespWrapper.makeResp(7102, "所有项均为必填项，请填写完成！", false);
			JsonUtil.MAPPER.writeValue(out, resp);
		}
		
		AddrService service = InvoiceClient.getAddrService();
		AddrEntity entity = service.findById(addrId).getObj();
		if(entity == null) {
			RespWrapper<Boolean> resp = RespWrapper.makeResp(7102, "该地址不存在，编辑失败！", false);
			JsonUtil.MAPPER.writeValue(out, resp);
			return;
		}
		String oldAddress = entity.getAddress();
		
		entity.setProvince(province);
		entity.setCity(city);
		entity.setDistrict(district);
		entity.setAddress(address);
		entity.setPhone(phone);
		entity.setConsignee(consignee);
		entity.setAdUin(ader.getAdUin());
		entity.setCrtTime(System.currentTimeMillis());
		entity.setCrtUser(ader.getAdUin());
		
		RespWrapper<Boolean> resp = service.update(entity);
		OpLogUtil.writeOperateLog("广告主["+ader.getLoginName()+"]更新地址["+ oldAddress +"]", ader.getAdUin(), ader.getLoginName(), OpType.UPDATE, resp.getObj());
		JsonUtil.MAPPER.writeValue(out, resp);
	}

}