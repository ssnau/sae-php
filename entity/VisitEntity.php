<?php
class VisitEntity extends EntityClass {
	/**
	 *  返回所对应的表名称
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'visit';
	}
	/**
	 *  返回所对应的字段名
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'ip', 'source', 'request_uri', 'time'); 
	}
	/**
	 *  返回所对应的有效字段名
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('ip', 'source', 'request_uri', 'time');
	}

}
