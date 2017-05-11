package com.yonghui.webapp.bss.api.ader;

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
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.comp.log.share.enums.OpType;
import com.yonghui.webapp.bss.api.ApiHandler;
import com.yonghui.webapp.bss.util.CacheUtil;
import com.yonghui.webapp.bss.util.DateUtil;
import com.yonghui.webapp.bss.util.FileObj;
import com.yonghui.webapp.bss.util.JsonUtil;
import com.yonghui.webapp.bss.util.OpLogUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;



public class UpdateHandler implements ApiHandler {
	
	private static final String ADER_UPLOAD_PATH = "/img/ader/";

	@Override
	public void handle(HttpServletRequest request, HttpServletResponse response, Writer out, AdminEntity admin)
			throws IOException {
		RespWrapper<Boolean> resp = RespWrapper.makeResp(2002, "更新广告主资料失败", false);
		
		int tuin = StringUtil.convertInt(request.getParameter("tuin"), 0);
		if(tuin == 0) {
			resp.setErrMsg("请选择需要修改资料的广告主");
			JsonUtil.MAPPER.writeValue( out, resp);
			return;
		}
		
		AderService service = AderClient.getAderService();
		AderEntity entity = service.findById(tuin).getObj();
		if(entity == null) {
			resp.setErrMsg("请选择需要修改资料的广告主");
			JsonUtil.MAPPER.writeValue( out, resp);
			return;
		}
		
		String imgPrefix = ProfileManager.getStringByKey("bss.img_prefix", "https://superip.yonghui.cn/static");

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
		String loginName = request.getParameter("loginName");
		String contact = request.getParameter("contact");
		String phone = request.getParameter("phone");
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
				logoUrl = saveImg(logoUrl, loginName);
			} else {
				logoUrl = logoUrl.replace(imgPrefix, "");
			}
		}
		if(StringUtil.isNotEmpty(busiLicenseUrl)) {
			if(busiLicenseUrl.indexOf(imgPrefix) == -1) {
				busiLicenseUrl = saveImg(busiLicenseUrl, loginName);
			} else {
				busiLicenseUrl = busiLicenseUrl.replace(imgPrefix, "");
			}
		}
		if(StringUtil.isNotEmpty(taxCertifyUrl)) {
			if(taxCertifyUrl.indexOf(imgPrefix) == -1) {
				taxCertifyUrl = saveImg(taxCertifyUrl, loginName);
			} else {
				taxCertifyUrl = taxCertifyUrl.replace(imgPrefix, "");
			}
		}
		
		entity.setAdUin(tuin);
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
		entity.setLoginName(loginName);
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
		entity.setChgUser(admin.getAdmUin());
		entity.setChgTime(System.currentTimeMillis());
		
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
		
		OpLogUtil.writeOperateLog("更新广告主["+ entity.getLoginName() +"]资料", admin.getAdmUin(), admin.getUserName(), OpType.UPDATE, resp.getObj());
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

		String baseUploadPath = ProfileManager.getStringByKey("bss.upload_base_path", "/data/static");
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
