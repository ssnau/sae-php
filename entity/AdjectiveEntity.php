<?php
class AdjectiveEntity extends ShakespeareEntity {
	/**
	 *  ��������Ӧ�ı�����
	 * @access public
	 * @return string
	 */
	public function get_table() {
		return 'adjective';
	}
	/**
	 *  ��������Ӧ���ֶ���
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return array('id', 'name', 'quanpin', 'jianpin', 'substitute', 'sentence', 'tags', 'comments', 'createdtime', 'lastmodified');
	}
	/**
	 *  ��������Ӧ����Ч�ֶ���
	 * @access public
	 * @return array
	 */
	public function get_useful_fields(){
		 return array('name', 'quanpin', 'jianpin',  'substitute', 'sentence', 'tags', 'comments', 'createdtime');
	}

}

