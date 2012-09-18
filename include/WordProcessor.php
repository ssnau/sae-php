<?php
class WordProcessor {
	public $trie;
	public $wordArray;
	public $deserveLength;
	public function __construct($trie) {
		$this->trie = $trie;
		$this->wordArray = array();
	}
	public function getWordArray(){
		return $this->wordArray;
	}
	//从乱序字母中找到所有合法的单词
	public function findWordFromDisorder($word, $length=0) {
		if ($length>0) {
			$this->deserveLength = $length;
			$this->lengthSense = true;
		} else {
			$this->lengthSense = false;
		}
		$this->_findWordFromDisorder($word, $this->trie);
		return array_unique($this->wordArray);
	}
	private function _findWordFromDisorder($str, $trie) {
		$sLen = strlen($str);
		
		for($i=0;$i<$sLen;$i++) {
			//交换str[0]和str[$i]
			$temp = $str[$i];
			$str[$i] = $str[0];
			$str[0] = $temp;
			$cur = $trie->getChild($str[0]);
			if ($cur) { //如果不为空
				//检测此处是否为单词
				$curWord = $cur->getWord();
				if ($curWord) {
					//如果lengthSense为true，则表示有长度要求
					if ($this->lengthSense) {
						if (strlen($curWord) == $this->deserveLength) {
							$this->wordArray[] = $curWord;
						}
					} else {
						$this->wordArray[] = $curWord;
					}	
				}
				//用子字符串递归查找下去,不用判断长度，长度为0的字符串递归下去是一个空执行
				$sub = substr($str, 1);
				$this->_findWordFromDisorder($sub, $cur);
			}
			//换回来
			$temp = $str[$i];
			$str[$i] = $str[0];
			$str[0] = $temp;
		}
	}
}
