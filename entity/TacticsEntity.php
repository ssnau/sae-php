<?php
class TacticsEntity extends ShakespeareEntity {
	/**
	 *  ��������Ӧ�ı�����
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'tactics';
	}
	/**
	 *  ��������Ӧ���ֶ���
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'name', 'quanpin', 'jianpin', 'content', 'tags', 'comments', 'createdtime', 'lastmodified');
	}
	/**
	 *  ��������Ӧ����Ч�ֶ���
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('name', 'quanpin', 'jianpin', 'content', 'tags', 'comments', 'createdtime');
	}
	/*
	 * @deprecated 
	 * �������������Ӧ�ı�����ӵ�е��ֶ�, �ڸ���Ĺ��캯���б�����
	 * ps:ÿ��ʵ��������һ�ݣ��е�ռ��˷�
	 * ��˸ĳ���get_table, get_fields �� get_useful_fields��������
	public function beforeConstruct(){
		$this->pTable = 'feel';
		$this->fields = array('id', 'name', 'quanpin', 'jianpin', 'description', 'tags', 'comments', 'createdtime', 'lastmodified');
		$this->useful_fields = array('name', 'quanpin', 'jianpin', 'description', 'tags', 'comments', 'createdtime');
	}
	 */

}

