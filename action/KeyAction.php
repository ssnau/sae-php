<?php
	/*
	 * 密钥Action
	 */
	class KeyAction extends ActionClass {
		public static function actDefault(){

		}
		/*
		 * 自己的密钥
		 */
		public static function actMine(){
			$userid = $_SESSION['id'];
			$keylist = new KeyEntity();
			$keylist = $keylist->getInstanceArray(array('ownerid'=>$userid));

			App::setVar('page_keylist', $keylist);
			App::setJs(array('p-key-mine'));

			$success = ToolLib::request('success');
			$fail = ToolLib::request('fail');
			if ($success == true) {
				App::setVar('page_success', true);
				App::setVar('page_success_info', ToolLib::request('success_info'));
			}

			if ($fail == true) {
				App::setVar('page_fail', true);
				App::setVar('page_fail_info', ToolLib::request('fail_info'));
			}

			self::display();
		}
		/*
		 * 提交生成新的密钥
		 */
		public static function postNewkey(){
			$key_name = ToolLib::request('key_name');
			$key = new Rsa();	
			$key->save($key_name);

			//self::actMine(array('success'=>true));
			App::redirect('/key/mine?success=true&success_info=密钥生成功！');
		}
		/*
		 * 删除指定的密钥
		 */
		public static function actDelete(){
			$key_id = ToolLib::request('id');
			$key = new KeyEntity($key_id);
			$isSuccess = $key->delete(); //App::exec对于删除操作返回true或false

			if (isSuccess) {
				App::redirect('/key/mine?success=true&success_info=密钥删除成功！');
			} else {
				App::redirect('/key/mine?fail=true&fail_info=密钥删除失败！');
			}
		}
		/*
		 * 下载指定的密钥
		 * 请求的url格式为：/key/download/id
		 */
		public static function actDownload($key_id){
			$key = new KeyEntity($key_id);
			$userid = $_SESSION['id'];
			$key_data = $key->data;
			header("Content-Type:application/pem");
			echo $key_data['pub'];
		}
		/*
		 * 收集的密钥
		 */
		public static function actCollection(){
			self::display();
		}

		public static function actEncrypt(){
			$key_id = 15;
			$content = ToolLib::request('content');
			$rsa = new Rsa($key_id);
			echo $rsa->encrypt($content);
		}

		public static function actDecrypt(){
			$key_id = 15;
			$signature = ToolLib::request('signature');
			$rsa = new Rsa($key_id);
			$result =  $rsa->decrypt($signature);

		}
	}
