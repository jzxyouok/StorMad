<%@page import="java.io.InputStream"%>
<%@page import="java.io.BufferedOutputStream"%>
<%@page import="org.apache.commons.fileupload.FileItem"%>
<%@page import="com.yonghui.webapp.bp.util.FileObj"%>
<%@page import="com.yonghui.webapp.bp.util.CacheUtil"%>
<%@page import="com.feizhu.conf.ProfileManager"%>
<%@page import="com.yonghui.webapp.bp.util.ImageVerifyCode"%>
<%@page import="cn770880.jutil.string.StringUtil"%>
<%@page import="cn770880.jutil.net.NetUtil"%>
<%@page contentType="image/*" pageEncoding="UTF-8"%>
<%
	String imageType = ProfileManager.getStringByKey(
			"bp.upload_image_type", "");
	StringBuffer sb = new StringBuffer();
	String contentType = "image/jpeg";
	if (StringUtil.isNotEmpty(imageType)) {
		String its[] = imageType.split(",");
		for (int i = 0; i < its.length; i++) {
			sb.append("image/" + its[i]);
			if (i != its.length - 1) {
				sb.append(";");
			}
		}
	}
	if (sb.length() > 0)
		contentType = sb.toString();
	response.setContentType(contentType);
	response.setHeader("Pragma", "No-cache");
	response.setHeader("Cache-Control", "no-cache");
	response.setDateHeader("Expires", 0);

	String key = NetUtil.getStringParameter(request, "key", "");
	if (StringUtil.isEmpty(key))
		return;
	FileObj fileObj = CacheUtil.getOneBean(CacheUtil.uploadTempKey,
			key, FileObj.class);
	if (fileObj == null)
		return;
	byte[] fileBytes = fileObj.getFileBytes();
	BufferedOutputStream bout = new BufferedOutputStream(
			response.getOutputStream());
	try {
		bout.write(fileBytes);
	} catch (Exception e) {
		e.printStackTrace();
	} finally {
		bout.flush();
		bout.close();
	}
%>