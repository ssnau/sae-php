<?php
class ShiciAction extends ActionClass {
	public static function actDefault() {
		App::setCss(array('shici','widgets'));
		App::setJs(array('w-tabstage','w-pychooser','p-shici'));
		//self::display();
		self::display('shici.temp');
	}

	/**
	 * 通过正则匹配获得相应的结果，$filter不需要用/包围起来，用户只需输入结构体
	 */
	public static function ajaxQuery() {
		$filter = ToolLib::request('grep_filter');
		$type = ToolLib::request('query_type');
		$start = ToolLib::request('start') ? ToolLib::request('start') : 0;//从id为$start的开始查找
		$prev_start = ToolLib::request('prev_start') ? ToolLib::request('prev_start') : 0;//上一次查询的start id
		$cs = ToolLib::request('cs'); //count_start,用于在页面上显示是从第几个结果到第几个结果
		//将#翻译为\
		$filter = preg_replace("/\[hanzi\]/", "[\x{4e00}-\x{9fa5}]", $filter);//把对用户友好的[hanzi]替换为难读懂的[\x{4e00}-\x{9fa5}]
		//$filter = preg_replace("/#/", "\\", $filter);//把#译成\，用以后向引用(depreacate)
		$filter = str_replace('\\'.'\\', "\\", $filter);//把\\译成\，因为\的转义行为，所以只好通过字符串拼接的方式表示两个连续的\了。用以后向引用
		$filter = "/". $filter. "/u";//确保后面的u，保证是unicode匹配
		App::mylog($filter);

		$sc_entity = new TongdianEntity();
		$max_id = 111;
		$max_id = $sc_entity->getMaxId('type='. $type. ' and id>'. $start);
		$temp = array();
		$count = 0;
		$search_start = $start;
		$vv = null;
		$complete = false;
		while($count < 30 && $start != $max_id) {
			$sc_entity = new TongdianEntity();
			$sc_array = $sc_entity->getDataArray(array('id', 'word', 'py', 'definition'), 
				'type='. $type. ' and id>'. $search_start. ' limit 10000'); //取出最多10000个结果
			if (!$sc_array) {
				$complete = true; //已经检索到数据库最尾部
				break;
			}
			foreach($sc_array as $v) {
				$vv = $v;
				if ($count === 30) break; //最多搜索三十个
				if (preg_match($filter, $v['word']) || preg_match($filter, $v['py'])) {
					$temp[] = $v;
					$count++;
				}
			}
			if ($count === 30) break;
			$search_start = $vv['id'];
		}

		if (count($temp)) {
			echo ToolLib::ajax(true, array('count'=> $count, 'data'=> $temp, 'start' => $start, 'end'=> $temp[$count-1]['id'], 
				'prev_start' => $prev_start, 'complete' => $complete, 'cs' => $cs));
		} else {
			echo ToolLib::ajax(false,"没有您需要的数据");
		}
	}

	public static function ajaxInsert() {
		$word = ToolLib::request('word');
		$py = ToolLib::request('py');
		$definition = ToolLib::request('definition');
		$type = ToolLib::request('type');

		$sc_entity = new TongdianEntity();
		$sc_entity->set(array('word' => $sentence, 'py' => $quanpin, 'definition' => $definition, 'type' => $type));
		$ifSuccess = $sc_entity->insert();
		$result = $ifSuccess ? ToolLib::ajax(true, '增加成功') : ToolLib::ajax(false, '增加失败');
		echo $result;
	}
}
