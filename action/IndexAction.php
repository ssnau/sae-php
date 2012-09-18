<?php
	class IndexAction {
		public static function actDefault(){
			if (Session::isLogin() === true) {
				App::redirect('/signature/compute');
			} else {
				App::display('index');
			}
		}
	}
