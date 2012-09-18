<?php
class KeyEntity extends EntityClass {
	/**
	 *  返回所对应的表名称
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'rsa_key';
	}
	/**
	 *  返回所对应的字段名
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'file_id', 'name', 'createdtime', 'ownerid', 'pri', 'pub');
	}

	/**
	 *  返回所对应的有效字段名
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		return array('file_id', 'name', 'createdtime', 'ownerid', 'pri', 'pub');
	}
}
