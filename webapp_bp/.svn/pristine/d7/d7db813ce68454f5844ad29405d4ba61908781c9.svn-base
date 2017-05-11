package com.yonghui.webapp.bp.api.test.utils;

public enum DataType {
	
	JSON(1, "JSON");
	//XML(2, "XML");

	private int id;
	private String name;

	private DataType(int id, String name) {
		this.id = id;
		this.name = name;
	}
	
	public static String getName(int id) {
		for (DataType e : DataType.values()) {
			if (e.getId() == id) {
				return e.getName();
			}
		}
		return "";
	}
	
	public static DataType getType(String name) {
		for (DataType e : DataType.values()) {
			if (e.getName().equalsIgnoreCase(name)) {
				return e;
			}
		}
		return null;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
	
	@Override
	public String toString() {
		return id + "," + name;
	}

}
