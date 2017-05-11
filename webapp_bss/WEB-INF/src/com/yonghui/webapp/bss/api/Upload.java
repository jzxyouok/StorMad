package com.yonghui.webapp.bss.api;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.Writer;
import java.util.Iterator;
import java.util.List;
import java.util.UUID;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.FileUploadException;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;

import com.feizhu.conf.ProfileManager;
import com.yonghui.comp.admin.share.bean.AdminEntity;
import com.yonghui.webapp.bss.util.CacheUtil;
import com.yonghui.webapp.bss.util.FileObj;
import com.yonghui.webapp.bss.util.JsonUtil;

import cn770880.jutil.data.RespWrapper;
import cn770880.jutil.string.StringUtil;

public class Upload implements ApiHandler {
	
	private static final long fileMaxSize = 2 * 1024 * 1024L;
	
	@Override
	public void handle(HttpServletRequest request,
			HttpServletResponse response, Writer out, AdminEntity admin)
					throws IOException {
		Object fileTypeObj = request.getAttribute("fileType");
		int fileType = fileTypeObj == null ? 0 : StringUtil.convertInt(fileTypeObj.toString(), 0);
		String imageType = ProfileManager.getStringByKey("bss.upload_image_type", "");
		DiskFileItemFactory fac = new DiskFileItemFactory();  
		ServletFileUpload upload = new ServletFileUpload(fac);
		List<FileItem> files = null;
		try {
			files = upload.parseRequest(request);
		} catch (FileUploadException e) {
			e.printStackTrace();
		}
		FileObj fileObj = null;
		if (files != null) {
			Iterator<FileItem> it = files.iterator();
			if (it.hasNext()) {
				FileItem fileItem = it.next();
				if (!fileItem.isFormField()) {	//如果是上传的文件
					String fileName = fileItem.getName();
					long size = fileItem.getSize();
					if (StringUtil.isEmpty(fileName) || size < 1 || fileName.lastIndexOf(".") == -1)
						throw new RuntimeException( "请勿传空文件!" );
					if (size > fileMaxSize)
						throw new RuntimeException( "上传文件最大不能超过2M!" );
					String extName = fileName.substring(fileName.lastIndexOf(".") + 1).toLowerCase();
					if (StringUtil.isNotEmpty(imageType))
						if (imageType.toLowerCase().indexOf(extName) == -1)
							throw new RuntimeException( "不支持的文件类型!" );
					String uuid = UUID.randomUUID().toString();
					String saveFileName = uuid + "." + extName;
					fileObj = new FileObj();
					fileObj.setId(uuid);
					InputStream in = fileItem.getInputStream();
					byte[] fileBytes = toByteArray(in);
					fileObj.setFileType(fileType);
					fileObj.setFileName(saveFileName);
					fileObj.setFileBytes(fileBytes);
					fileObj.setCreateTime(System.currentTimeMillis());
					CacheUtil.saveBean(CacheUtil.uploadTempKey, uuid, fileObj);
				} 
			}
		}
		if (fileObj == null)
			throw new RuntimeException( "上传失败!" );

		JsonUtil.MAPPER.writeValue( out, RespWrapper.makeResp(0, "", fileObj.getId()) ) ;
	}
	
	public static byte[] toByteArray(InputStream input) throws IOException {
	    ByteArrayOutputStream output = new ByteArrayOutputStream();
	    byte[] buffer = new byte[1024];
	    int n = 0;
	    while (-1 != (n = input.read(buffer))) {
	        output.write(buffer, 0, n);
	    }
	    return output.toByteArray();
	}
}
