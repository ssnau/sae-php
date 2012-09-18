<?php
	/*
	 * 签名管理Action
	 */
	class SignatureAction extends ActionClass {
		public static function actDefault(){

		}
		/*
		 * 计算签名值页面
		 */
		public static function actCompute(){
			$userid = $_SESSION['id'];
			$keylist = new KeyEntity();
			$keylist = $keylist->getInstanceArray(array('ownerid'=>$userid));

			App::setJs(array('p-signature-compute'));
			App::setVar('page_keylist', $keylist);
			self::display();
		}
		/*
		 * 真正开始计算签名值，异步模式
		 */
		public static function ajaxCompute(){
			$keyid = ToolLib::request('key');	
			$method = ToolLib::request('method');	
			$imgurl = ToolLib::request('imgurl');	

			//echo json_encode(array('key'=>$keyid, 'method'=>$method, 'imgurl'=>$imgurl));

		}
		/*
		 * 验证签名
		 */
		public static function actVerify(){
			self::display();
		}
		/*
		 * 已生成的签名
		 */
		public static function actMine(){
			self::display();
		}
	}
