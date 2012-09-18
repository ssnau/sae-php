<?php
class CreativeEntity extends ShakespeareEntity {
	/**
	 *  ��������Ӧ�ı�����
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'creative';
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

}

