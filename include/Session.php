<?php
class Session {
	/*
	 * 获取$_SESSION变量
	 */
	public static function getSession(){
		return $_SESSION;
	}

	/*
	 * 设置session内容
	 */
	public static function setSession($arr){
		foreach($arr as $k => $v) {
			$_SESSION[$k] = $v;
		}
	}

	/*
	 * 销毁session
	 */
	public static function destory(){
		session_destroy();
	}
	/*
	 * 判断是否已经登录
	 */
	public static function isLogin(){
		if (isset($_SESSION['id'])) {
			return true;
		} else {
			return false;
		}
	}
}
