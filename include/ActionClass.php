<?php
abstract class ActionClass {
	/**
	 * ��ʾģ�壬����в���(string���ͣ�Ϊ/template/�µ����·��)����Ѳ�������ģ��
	 * ���û�в����������Action������ģ���ļ�
	 * ע��֮������ ${ģ���ļ���} = ${Ŀ¼��} + "-" ${action������}, ������Ϊ����vim�������Ŀ¼
     * @static
     * @access public
	 */
	public static function display($template = '') {
		if ($template) {
			App::display($template);
		} else {
			$action_obj = App::getAction();
			$action = explode('::', $action_obj['action']);//�õ�array(������������)
			$dir = strtolower(substr($action[0], 0, -strlen('action')));//Ŀ¼����Сд��û��Action��׺
			$action = /*$dir. "-". */$action[1];//ģ���ļ�Ϊ ${action������}
			App::display($dir. '/'. $action);
		}
	}

}
