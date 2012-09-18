<?php
	class DrawsthAction extends ActionClass {
		public static function actDefault(){
			self::display();
		}
		public static function actQuery(){
			//参数
			$string = ToolLib::request('string');
			$number = ToolLib::request('number');
			$string = preg_replace("[^a-zA-Z]", "", $string);
			$number = $number - 0;

			$dict = OxfordLib::get_oxfordwords(); //获取牛津辞典
			$trie = new Trie($dict);//通过牛津辞典构建Trie树
			$wp = new WordProcessor($trie);//通过Trie树构建单词处理器
			$wordArray = $wp->findWordFromDisorder($string,$number);//查找符合要求的词语

			$page_words = array();
			foreach($wordArray as $k=>$v) {
				$page_words[] = $v."&nbsp;&nbsp;&nbsp". $dict[$v];
			}
			App::setVar('page_words', $page_words);
			self::display();
		}
	}
