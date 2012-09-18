<?php
header("Content-Type: text/html; charset=utf-8");
$isDebug = isset($_REQUEST['debug']) ? (($_REQUEST['debug'] == 'kaiqi') ? true  : false) : false; //�ж��Ƿ�Ϊdebug״̬
//define('IS_DEBUG', $isDebug); 
define('IS_DEBUG', true); //Ĭ��Ϊtrue
define('VERSION', 2984391); //��ǰ�汾��
//����Ƿ�Ϊsae���ϻ�����ԭ��������class_exists("SaeMysql")�ģ�������Ϊ�Ѿ������autoload���֣���ⲻ��
define('IS_SAE_ENV', defined('SAE_MYSQL_USER') ? true : false); 
define('LOG_SOURCE', true); //��������Դ���浽���ݿ���,��App::logSource
define('HOST', $_SERVER['HTTP_HOST']);
define('ROOT_DIR', __DIR__.'/../');
define('UPLOADED_FILE_PATH', ROOT_DIR. "uploaded/");

//���������������Զ�������
function __autoload($classname) {
	//�������Lib��β�Ļ������ʾ�Ƿ���lib�ļ����µĹ�����
	//�������Action��β�Ļ�������Action�Ķ���������actionĿ¼��
	//�������Entity��β�Ļ�������ʵ���࣬����entityĿ¼��
	//��������ǵĻ�������includeĿ¼��
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

//����Ĭ��ʱ��
date_default_timezone_set('Asia/Shanghai');

//����memchache
//if (IS_SAE_ENV) memcache_init();
