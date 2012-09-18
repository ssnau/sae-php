<?php
	class UploadAction {
		public static function actDefault(){
			echo ToolLib::random_string();
		}

		public static function postDefault(){
			$file = ToolLib::files('fileToUpload');

			$tmp_name = ToolLib::random_string().'.jpg';
			ToolLib::save_file_as($file, $tmp_name);
			echo $tmp_name;
			/*
			var_dump($file);
			var_dump($author);
			var_dump($name);
			 */
		}
	}
