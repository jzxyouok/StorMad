package com.yonghui.webapp.bp.api.ader;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.Writer;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.feizhu.conf.ProfileManager;
import com.yonghui.comp.ader.share.AderClient;
import com.yonghui.comp.ader.share.AderService;
import com.yonghui.comp.ader.share.bean.AderEntity;
import com.yonghui.comp.ader.share.enums.StatusEnum;
import com.yonghui.comp.common.share.CommonClient;
import com.yonghui.comp.common.share.SmsService;
import com.yonghui.comp.common.share.enums.MsgEnum;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bp.api.ApiHandler;
import com.yonghui.webapp.bp.util.CacheUtil;
import com.yonghui.webapp.bp.util.DateUtil;
import com.yonghui.webapp.bp.util.FileObj;
import com.yonghui.webapp.bp.util.JsonUtil;
import com.yonghui.webapp.bp.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;



public class UpdateHandler implements ApiHandler {
	
	private static final String ADER_UPLOAD_PATH = "/img/ader/";
	
	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AderEntity ader)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2002, "更新广告主资料失败", false);
		
		String loginName = ader.getLoginName();
		String vCode = request.getParameter("vCode");
		String phone = request.getParameter("phone");
		
		if(StringUtil.isEmpty(vCode)) {
			resp.setErrMsg("无效的验证码");
			JsonUtil.MAPPER.writeValue( out, resp);
			return;
		}
		
		SmsService smsService = CommonClient.getSmsService();
		RespWrapper<Boolean> smsResp = smsService.verifyCode(phone, MsgEnum.REGISTER.getMsgType(), vCode);
		if(!smsResp.getObj()) {
			resp.setErrMsg("无效的验证码");
			JsonUtil.MAPPER.writeValue( out, resp);
			return;
		}
		
		AderService service = AderClient.getAderService();
		RespWrapper<AderEntity> aResp = service.findById(ader.getAdUin());
		AderEntity entity = aResp.getObj();
		if(aResp.getErrCode() != 0 || entity == null) {
			resp.setErrMsg("请输入正确的账号和密码");
			JsonUtil.MAPPER.writeValue( out, resp);
			return;
		}
		
		String imgPrefix = ProfileManager.getStringByKey("bp.img_prefix", "https://superip.yonghui.cn/static");
		
		String corporation = request.getParameter("corporation");
		String province = request.getParameter("province");
		String city = request.getParameter("city");
		String district = request.getParameter("district");
		String address = request.getParameter("address");
		String legalPerson = request.getParameter("legalPerson");
		String legalIdcard = request.getParameter("legalIdcard");
		String bank = request.getParameter("bank");
		String accountName = request.getParameter("accountName");
		String cardNo = request.getParameter("cardNo");
		String contact = request.getParameter("contact");
		String email = request.getParameter("email");
		String orgCode = request.getParameter("orgCode");
		String busiRegNo = request.getParameter("busiRegNo");
		String logoUrl = request.getParameter("logoUrl");
		String busiLicenseUrl = request.getParameter("busiLicenseUrl");
		String taxCertifyUrl = request.getParameter("taxCertifyUrl");
		
		String logoImgKey = "";
		String licenseImgKey = "";
		String certifyImgKey = "";
		if(StringUtil.isNotEmpty(logoUrl)) {
			if(logoUrl.indexOf(imgPrefix) == -1) {
				logoImgKey = logoUrl;
				logoUrl = saveImg(logoUrl, loginName);
			} else {
				logoUrl = logoUrl.replace(imgPrefix, "");
			}
		}
		if(StringUtil.isNotEmpty(busiLicenseUrl)) {
			if(busiLicenseUrl.indexOf(imgPrefix) == -1) {
				licenseImgKey = "";
				busiLicenseUrl = saveImg(busiLicenseUrl, loginName);
			} else {
				busiLicenseUrl = busiLicenseUrl.replace(imgPrefix, "");
			}
		}
		if(StringUtil.isNotEmpty(taxCertifyUrl)) {
			if(taxCertifyUrl.indexOf(imgPrefix) == -1) {
				certifyImgKey = taxCertifyUrl;
				taxCertifyUrl = saveImg(taxCertifyUrl, loginName);
			} else {
				taxCertifyUrl = taxCertifyUrl.replace(imgPrefix, "");
			}
		}
		
		entity.setAdUin(entity.getAdUin());
		entity.setCorporation(corporation);
		entity.setProvince(province);
		entity.setCity(city);
		entity.setDistrict(district);
		entity.setAddress(address);
		entity.setLegalPerson(legalPerson);
		entity.setLegalIdcard(legalIdcard);
		entity.setBank(bank);
		entity.setAccountName(accountName);
		entity.setCardNo(cardNo);
		entity.setContact(contact);
		entity.setPhone(phone);
		entity.setEmail(email);
		entity.setOrgCode(orgCode);
		entity.setBusiRegNo(busiRegNo);
		entity.setLogoUrl(logoUrl);
		entity.setBusiLicenseUrl(busiLicenseUrl);
		entity.setTaxCertifyUrl(taxCertifyUrl);
		entity.setUtype(1);
		entity.setNotifyPhone(phone);
		entity.setChgUser(entity.getAdUin());
		entity.setChgTime(System.currentTimeMillis());
		entity.setStatus(StatusEnum.APPLY.getStatus());
		
		resp = service.update(entity);
		if(resp.getObj()) {
			if(StringUtil.isNotEmpty(logoImgKey)) {
				CacheUtil.hdel(CacheUtil.uploadTempKey, logoImgKey);	//清除logo缓存
			}
			if(StringUtil.isNotEmpty(licenseImgKey)) {
				CacheUtil.hdel(CacheUtil.uploadTempKey, licenseImgKey);	//清除营业执照缓存
			}
			if(StringUtil.isNotEmpty(certifyImgKey)) {
				CacheUtil.hdel(CacheUtil.uploadTempKey, certifyImgKey);	//清除图片缓存
			}
		}
		
		OpLogUtil.writeOperateLog("广告主["+loginName+"]更新资料", ader.getAdUin(), entity.getLoginName(), OpType.DELETE, resp.getObj());
		JsonUtil.MAPPER.writeValue( out, resp);
	}

	/**
	 * 
	 * <b>日期：2016年12月3日</b><br>
	 * <b>作者：bob</b><br>
	 * <b>功能：保存图片</b><br>
	 * <b>@param imgKey
	 * <b>@param loginName
	 * <b>@return</b><br>
	 * <b>String</b>
	 */
	private String saveImg(String imgKey, String loginName) {
		String imgUrl = "";
		
		FileObj imgFile = CacheUtil.getOneBean(CacheUtil.uploadTempKey, imgKey, FileObj.class);
		if (imgFile == null)
			throw new RuntimeException("抱歉，图片已失效，请重新上传!");

		String baseUploadPath = ProfileManager.getStringByKey("bp.upload_base_path", "/data/static");
		String uploadPath = baseUploadPath + ADER_UPLOAD_PATH + DateUtil.getDate();
		String fileName = loginName + "_" + imgFile.getFileName();
		FileOutputStream fos = null;
		try {
			File directory = new File(uploadPath);
			if (!directory.isDirectory()) {
				directory.mkdirs();
			}

			File file = new File(uploadPath + File.separator + fileName);
			if (!file.exists()) {
    			file.createNewFile();
    		}
			
			fos = new FileOutputStream(file);
			fos.write(imgFile.getFileBytes());
		} catch (Exception e) {
			e.printStackTrace();
			throw new RuntimeException("图片上传失败!");
		} finally {
			try {
				if (fos != null) {
					fos.flush();
					fos.close();
				}
			} catch(Exception ex) {
				ex.printStackTrace();
			}
		}
		imgUrl = ADER_UPLOAD_PATH + DateUtil.getDate() + File.separator + fileName;
		
		return imgUrl;
	}
}
