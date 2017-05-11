<?php

/**
 * 自定义模型类
 * @date	2016-06-21
 * @author	huangshiwei
 */

class MY_Model extends CI_Model {

	/**
	 * 构造函数
	 * @param string $dbgroup 配置文件的数据库组名
	 */
	public function __construct($dbgroup = 'default') 
	{
		parent::__construct();
		$this->db = $this->load->database($dbgroup, TRUE);
	}

	/**
	 * 插入
	 * @param type $table
	 * @param type $data
	 * @return type
	 */
	public function insert($table, $data) {
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	/**
	 * 更新
	 * @param type $table
	 * @param type $set
	 * @param type $where
	 * @return type
	 */
	public function update($table, $set, $where) {
		$this->db->update($table, $set, $where);
		return $this->db->affected_rows();
	}

	/**
	 * 删除
	 * @param type $table
	 * @param type $where
	 * @param type $limit
	 * @return type
	 */
	public function del($table, $where, $limit) {
		$this->db->delete($table, $where, $limit);
		return $this->db->affected_rows();
	}

	/**
	 * 执行sql，返回结果集
	 * @param string $sql
	 * @return mixed 成功返回资源型，失败返回false
	 */
	public function query($sql) {
		return $this->db->query($sql);
	}

	/**
	 * 查询1条数据，返回数组
	 * @param string $sql
	 * @return array 成功返回一位数组，失败返回空数组
	 */
	public function query_one($sql) {
		return $this->db->query($sql)->row_array();
	}

	/**
	 * 查询list data
	 * @param string $sql
	 * @return array 成功返回二维数组，失败返回空数组
	 */
	public function query_list($sql) {
		$result = array();
		$query = $this->db->query($sql);
		if($query){
			foreach($query->result_array() as $row) {
				$result[] = $row;
			}
		}
		return $result;
	}

}
