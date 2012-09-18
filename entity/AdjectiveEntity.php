<?php
class AdjectiveEntity extends ShakespeareEntity {
	/**
	 *  返回所对应的表名称
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'adjective';
	}
	/**
	 *  返回所对应的字段名
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'name', 'quanpin', 'jianpin', 'substitute', 'sentence', 'tags', 'comments', 'createdtime', 'lastmodified');
	}
	/**
	 *  返回所对应的有效字段名
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('name', 'quanpin', 'jianpin',  'substitute', 'sentence', 'tags', 'comments', 'createdtime');
	}

}

