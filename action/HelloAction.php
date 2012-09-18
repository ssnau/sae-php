<?php
	class HelloAction {
		public static function actHello(){
			echo "only hello:". ToolLib::request('msg');
		}
	}
