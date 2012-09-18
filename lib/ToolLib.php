<?php
class ToolLib {
	public static function get($varname) {
		if (isset($_GET[$varname])) {
			return $_GET[$varname];
		}
		return false;
	}

	public static function post($varname) {
		if (isset($_POST[$varname])) {
			return $_POST[$varname];
		}
		return false;
	}

	public static function request($varname) {
		if (isset($_REQUEST[$varname])) {
			return $_REQUEST[$varname];
		}
		return false;
	}

	public static function files($varname) {
		if (isset($_FILES[$varname])) {
			if( !($_FILES[$varname]['error'] > 0)) {
				return $_FILES[$varname];
			}
		}
		return false;
	}

	public static function save_file_as($origFile, $targetFileName) {
		move_uploaded_file($origFile['tmp_name'], UPLOADED_FILE_PATH. $targetFileName);//UPLOADED_FILE_PATH在prepend中有定义

	}

	public static function random_string($len=5, $with_time=true){
		$key  = 'q w e r t y u i o p a s d f g h j k l z x c v b n m _';
		$k_arr = explode(' ', $key);
		shuffle($k_arr);
		$time = time();
		if ($with_time) {
			return $time.join('', array_slice($k_arr,0, $len));
		} else {
			return join('',array_slice($k_arr,0, $len));
		}
	}

	public static function cookie($varname) {
		if (isset($_COOKIE[$varname])) {
			return $_COOKIE[$varname];
		}
		return false;
	}

	public static function requests_without_cookie() {
		$result = array();
		foreach($_REQUEST as $k => $v) {
			if (!array_key_exists($k, $_COOKIE)) {
				$result[$k] = $v;	
			}
		}
		return $result;
	}

	public static function session($varname){
		if (isset($_SESSION[$varname])) {
			return $_SESSION[$varname];
		}
		return false;
	}

	public static function isEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function mydie($val) {
		die(var_export($val));
	}

	/**
	 *  效用与javascript里的split(str, ‘’)一致，php原生的对中文的支持太烂
	 *  注：必须将相关文件的编码设置为UTF-8 without BOM
	 * @static
	 * @access public
	 * @param string $string
	 * @return array
	 */
	public static function split($string) {
		$result = array();	
		while(strlen($string)) {
			$matches = null;
			if (preg_match("/^[\x{4e00}-\x{9fa5}]/u", $string, $matches)) {
				array_push($result, $matches[0]);
				$string = substr($string, strlen($matches[0]));
			} else {
				array_push($result, substr($string, 0, 1));
				$string = substr($string, 1);
			}
		}
		return $result;
	}

	/**
	 *  效用与javascript里的join函数一致
	 * @static
	 * @access public
	 * @param string $string
	 * @return array
	 */
	public static function array_join($arr, $delimiter = '') {
		$result = '';
		foreach($arr as $k => $v) {
			$result .= $delimiter. $v;
		}
		return substr($result, strlen($delimiter));//去掉最前面的delimiter
	}

	/**
	 * 封装返回的ajax数据，status为1表示成功，为0表示失败。
	 * 成功时$data项指json内容，失败时data为失败信息
	 * @static
	 * @access public
	 * @param bool $status
	 * @param string|array $data
	 * @return array
	 */
	public static function ajax($isSuccess, $data=null) {
		//只要status不是false或false的等价物时，都认为是成功
		if ($isSuccess) {
			return json_encode(array('status' => '1', 'data' => $data));	
		} else {//当status为0, '',false时
			return json_encode(array('status' => '0', 'msg' => $data));
		}
	}
	public static function consoleJson($json) {
		echo '<script>'. 'console.log('. $json. ')'. '</script>';
	}
	public static function mylog($str) {
		$root_dir = $_SERVER['DOCUMENT_ROOT'];
		$log_dir = $root_dir. "/../";		
		$file_name = 'mylog.txt';
		$handle = fopen($log_dir. $file_name, 'a');
		fwrite($handle,  date('c'). "\t". $str. "\n");
	}
}
