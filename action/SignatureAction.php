<?php
	/*
	 * ǩ������Action
	 */
	class SignatureAction extends ActionClass {
		public static function actDefault(){

		}
		/*
		 * ����ǩ��ֵҳ��
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
		 * ������ʼ����ǩ��ֵ���첽ģʽ
		 */
		public static function ajaxCompute(){
			$keyid = ToolLib::request('key');	
			$method = ToolLib::request('method');	
			$imgurl = ToolLib::request('imgurl');	

			//echo json_encode(array('key'=>$keyid, 'method'=>$method, 'imgurl'=>$imgurl));

		}
		/*
		 * ��֤ǩ��
		 */
		public static function actVerify(){
			self::display();
		}
		/*
		 * �����ɵ�ǩ��
		 */
		public static function actMine(){
			self::display();
		}
	}
