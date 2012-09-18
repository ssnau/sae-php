<?php
class PyAction {
	public static function ajaxQuanpin($config) {
		$text = ToolLib::request('text');
		$text = trim($text);
		$py_array = array();
		$use_yindiao = false;

		if ($config == 'with_yindiao') {
			$use_yindiao = true;
		}
		if (strlen($text)) {
			$py_array = PyLib::getQuan($text, $use_yindiao);
		}
		//本例需要处理","前多余出来的一个空格
		$py_array = str_replace(' ,', ',', $py_array);//str_replace是可以处理数组的
		echo count($py_array) ? ToolLib::ajax(true, $py_array) : ToolLib::ajax(false, "no available data");
	}

	public static function ajaxJian($config) {
		$text = ToolLib::request('text');
		$text = trim($text);
		$py_array = array();
		$use_yindiao = false;

		if ($config == 'with_yindiao') {
			$use_yindiao = true;
		}
		if (strlen($text)) {
			$py_array = PyLib::getJian($text, $use_yindiao);
		}
		echo count($py_array) ? ToolLib::ajax(true, $py_array) : ToolLib::ajax(false, "no available data");
	}
}
