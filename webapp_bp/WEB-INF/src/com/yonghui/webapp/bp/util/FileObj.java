package com.yonghui.webapp.bp.util;

import java.io.Serializable;

public class FileObj implements Serializable {

	private static final long serialVersionUID = 338946101002361158L;
	
	private String id;
	private int fileType;	//文件类型 1广告主资料  2广告图片
	private String fileName;
	private long createTime;
	private byte[] fileBytes;
	
	public String getId() {
		return id;
	}
	public void setId(String id) {
		this.id = id;
	}
	public int getFileType() {
		return fileType;
	}
	public void setFileType(int fileType) {
		this.fileType = fileType;
	}
	public String getFileName() {
		return fileName;
	}
	public void setFileName(String fileName) {
		this.fileName = fileName;
	}
	public long getCreateTime() {
		return createTime;
	}
	public void setCreateTime(long createTime) {
		this.createTime = createTime;
	}
	public byte[] getFileBytes() {
		return fileBytes;
	}
	public void setFileBytes(byte[] fileBytes) {
		this.fileBytes = fileBytes;
	}
}
