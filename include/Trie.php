<?php
class Trie {
	public $arr; //当前结点有哪些子结点
	public $word; //当前结点所对应的单词，若不对应任何单词则为空
	public $explain; //当前结点所对应的释义，若不对应任何单词则为空
	public function __construct($dict=false){
		$this->arr = array();
		if ($dict) {
			$this->setupFromDict($dict);
		}
	}
	public function setData($word, $explain) {
		$this->word = $word;
		$this->explain = $explain;
	}
	public function getWord(){
		return $this->word;
	}
	public function getExplain(){
		return $this->explain;
	}
	//添加一个子结点
	public function pushChild($word) {
		$arr = $this->arr;
		$this->arr[$word] = new Trie();
	}
	//获取一个子结点
	public function getChild($word){
		$arr = $this->arr;
		if (isset($arr[$word])) {
			return $arr[$word];
		} else {
			return null;
		}
	}
	/*
	 * 从字典中构建Trie树
	 * $dict的格式为array('word1'=>'explain2', 'word1' =>'explain2' ... )
	 */
	public function setupFromDict($dict) {
		foreach($dict as $k => $v) {
			 $wLen = strlen($k);
			 $cur = $this; //每处理一个单词返回一次树顶
			 for($i = 0;$i < $wLen;$i++) {
				 $char = $k[$i];
				 //如果还没有被设置，则设置之
				 if (!($cur->getChild($char))) {
					$cur->pushChild($char);
				 }
				 $cur = $cur->getChild($char);//当前指针指向刚被设置的这个
				 if ($i == ($wLen - 1)) {
					 $cur->setData($k, $v);
				 }
			 }
		}
	}
}
