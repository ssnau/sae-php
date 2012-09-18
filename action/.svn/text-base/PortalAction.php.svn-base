<?php
	/*
	 * 登录管理Action
	 */
	class PortalAction extends ActionClass {
		public static function actDefault(){

		}
		/*
		 * 处理登录
		 */
		public static function postLogin(){
			$name = ToolLib::request('username');
			$pw = ToolLib::request('password');

			//检索数据库
			$user = new UserEntity();		
			$userArray = $user->getInstanceArray(array('name' => $name, 'password'=>$pw));
			//如果结果为0，则表示用户名密码错误
			if (count($userArray) === 0) {
				echo "error";	
			} else {
				$user = $userArray[0]->data;
				Session::setSession(array('id'=>$user['id'], 'name'=>$user['name'],));
				App::redirect('/signature/compute');
			}
		}
		/*
		 * 处理登出
		 */
		public static function actLogout(){
			Session::destory();
			App::redirect('/');
		}
	}
