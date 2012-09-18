<?php
abstract class ActionClass {
	/**
	 * 显示模板，如果有参数(string类型，为/template/下的相对路径)，则把参数当做模板
	 * 如果没有参数，则根据Action来调用模板文件
	 * 注：之所以让 ${模板文件名} = ${目录名} + "-" ${action函数名}, 仅仅是为了让vim方便查找目录
     * @static
     * @access public
	 */
	public static function display($template = '') {
		if ($template) {
			App::display($template);
		} else {
			$action_obj = App::getAction();
			$action = explode('::', $action_obj['action']);//得到array(类名，函数名)
			$dir = strtolower(substr($action[0], 0, -strlen('action')));//目录名纯小写，没有Action后缀
			$action = /*$dir. "-". */$action[1];//模板文件为 ${action函数名}
			App::display($dir. '/'. $action);
		}
	}

}
