<?php
header("Content-Type: text/html; charset=utf-8");
$isDebug = isset($_REQUEST['debug']) ? (($_REQUEST['debug'] == 'kaiqi') ? true  : false) : false; //判断是否为debug状态
//define('IS_DEBUG', $isDebug); 
define('IS_DEBUG', true); //默认为true
define('VERSION', 2984391); //当前版本号
//检查是否为sae线上环境，原本是想用class_exists("SaeMysql")的，但是因为已经定义的autoload作怪，检测不出
define('IS_SAE_ENV', defined('SAE_MYSQL_USER') ? true : false); 
define('LOG_SOURCE', true); //将访问来源储存到数据库中,见App::logSource
define('HOST', $_SERVER['HTTP_HOST']);
define('ROOT_DIR', __DIR__.'/../');
define('UPLOADED_FILE_PATH', ROOT_DIR. "uploaded/");

//类拦截器，用于自动加载类
function __autoload($classname) {
	//如果是以Lib结尾的话，则表示是放在lib文件夹下的工具类
	//如果是以Action结尾的话，则是Action的东西，放在action目录下
	//如果是以Entity结尾的话，则是实体类，放在entity目录下
	//如果都不是的话，就在include目录下
	if (strpos($classname, 'Lib') === strlen($classname) - 3) {
		$file = ROOT_DIR. "lib/". "$classname". '.php';
	} elseif (strpos($classname, 'Action') === strlen($classname) - 6) {
		$file = (ROOT_DIR."action/"."$classname".'.php');
	} elseif (strpos($classname, 'Entity') === strlen($classname) - 6) {
		$file = (ROOT_DIR."entity/"."$classname".'.php');
	} else {
		$file = (ROOT_DIR."include/"."$classname".'.php');
	}
	if (isset($file) && file_exists($file)) {
		include_once($file);
	} else {
		App::redirectError('404');
	}
}

//设置默认时区
date_default_timezone_set('Asia/Shanghai');

//开启memchache
//if (IS_SAE_ENV) memcache_init();
