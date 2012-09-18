<?php
class StaticAction {
	private static function addHeader(){
		if (IS_DEBUG) return; //如果是debug状态，则不用有expire..
		$expire_date = date('r', time() + 3600 * 24 * 30);
		header("Cache-Control:max-age=". (3600 * 24 * 30). ", public");
		header("Expires:". $expire_date);
		header("Pragma:");
		header("X-Powered-By:");
		header("Last-Modified:Sat, 25 Feb 2012 15:48:23 GMT");
		header("Etag:2000000002c32-16e73-f320023");
	}
	private static function if_not_modified(){
		if (IS_DEBUG) return; //如果是debug状态，则不要304,老实返回文件
		if(isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])) {
			if ( ToolLib::request('v') == VERSION) {
				header("304 Not Modified", true, 304);
			}
		}
	}
	public static function actJS($str) {
		self::if_not_modified();
		self::addHeader();
		header("Content-Type:application/javascript");

		$files = ToolLib::request('file');
		$files = explode(",", $files);
		$root = ROOT_DIR. "static/js/"; //在App中已经有define('ROOT_DIR', __DIR__.'/../');
		$file_contents = '';
		foreach($files as $v) {
			//如果是w-开头的js文件，则添上路径widgets
			//如果是p-开头的js文件，则添上路径pages
			if (strpos($v, 'w-') === 0) {
				$v = 'widgets/'. $v;
			} else if (strpos($v, 'p-') === 0) {
				$v = 'pages/'. $v;
			}
			$full_name = $v. (IS_DEBUG ? '' : '.min'). ".js";
			$file_contents .= "\n".trim(file_get_contents($root. $full_name));
		}
		echo $file_contents;
	}
	public static function actCss($str) {
		self::if_not_modified();
		self::addHeader();
		header("Content-Type:text/css");

		$files = ToolLib::request('file');
		$files = explode(",", $files);
		$root = ROOT_DIR. "static/css/"; //在App中已经有define('ROOT_DIR', __DIR__.'/../');
		$file_contents = '';
		foreach($files as $v) {
			$full_name = $v. (IS_DEBUG ? '' : '.min'). ".css";
			$file_contents .= "\n".trim(file_get_contents($root. $full_name));
		}
		echo $file_contents;
	}
}
