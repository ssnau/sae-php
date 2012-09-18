<?php
class ActionEntity extends ShakespeareEntity {
	/**
	 *  ��������Ӧ�ı�����
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'action';
	}
	/**
	 *  ��������Ӧ���ֶ���
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'name', 'quanpin', 'jianpin', 'substitute', 'tags', 'comments', 'createdtime', 'lastmodified');
	}
	/**
	 *  ��������Ӧ����Ч�ֶ���
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('name', 'quanpin', 'jianpin', 'substitute', 'tags', 'comments', 'createdtime');
	}

}

