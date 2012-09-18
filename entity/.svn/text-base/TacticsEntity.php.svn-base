<?php
class TacticsEntity extends ShakespeareEntity {
	/**
	 *  返回所对应的表名称
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'tactics';
	}
	/**
	 *  返回所对应的字段名
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'name', 'quanpin', 'jianpin', 'content', 'tags', 'comments', 'createdtime', 'lastmodified');
	}
	/**
	 *  返回所对应的有效字段名
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('name', 'quanpin', 'jianpin', 'content', 'tags', 'comments', 'createdtime');
	}
	/*
	 * @deprecated 
	 * 定义该子类所对应的表名和拥有的字段, 在父类的构造函数中被调用
	 * ps:每个实例都保存一份，有点空间浪费
	 * 因此改成了get_table, get_fields 和 get_useful_fields三个函数
	public function beforeConstruct(){
		$this->pTable = 'feel';
		$this->fields = array('id', 'name', 'quanpin', 'jianpin', 'description', 'tags', 'comments', 'createdtime', 'lastmodified');
		$this->useful_fields = array('name', 'quanpin', 'jianpin', 'description', 'tags', 'comments', 'createdtime');
	}
	 */

}

