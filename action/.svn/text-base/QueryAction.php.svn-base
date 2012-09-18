<?php
class QueryAction extends ActionClass {
	public static function actDefault() {
		$filter = ToolLib::request('grep_filter');
		$origin_filter = str_replace('\\'.'\\', "\\", $filter);//把\\译成\，因为\的转义行为，所以只好通过字符串拼接的方式表示两个连续的\了。用以后向引用
		$filter = self::stardarizeFilter($origin_filter);

		$type = ToolLib::request('query_type');
		if (trim($type) == 0) App::redirect("/"); //如果为空，则转向首页

		$start = 0;
		$over = false;
		$msg = "没有您需要的数据...";
		if (strlen($origin_filter) > 30) {
			$msg = "您的表达式太长了...已经超过了30个字符";
			$over = true;
		}
		$result = $over ? false :self::query($filter, $type, $start);// 如果over为true，则不用去搜索了
		if ($result) {
			//注：cs指的是页面的count start
			App::setVar('page_data',array_merge($result, array('grep_filter' => $origin_filter, 'query_type' => $type, 'cs' => 1)));
			App::setVar('page_complete', $result['complete']);
		} else {
			App::setVar('page_data', false);
			App::setVar('page_complete', true);//如果返回的$result为空的话，则表示已经complete了
		}
		App::setVar('page_msg', $msg);
		App::setVar('page_filter', $origin_filter);//设置搜索框内容
		App::setVar('page_type', $type);//设置下拉框内容
		App::setCss(array('query','widgets'));
		App::setJs(array('p-query'));
		self::display();
	}
	public static function ajaxDefault() {
		$origin_filter = ToolLib::request('grep_filter');
		$origin_filter = str_replace('\\'.'\\', "\\", $origin_filter);//把\\译成\，因为\的转义行为，所以只好通过字符串拼接的方式表示两个连续的\了。用以后向引用
		$type = ToolLib::request('query_type');
		$start = ToolLib::request('start');
		$cs = ToolLib::request('cs');
		if (trim($origin_filter) == '') die("错误的请求,filter不能为空");
		if (trim($type) == 0) die("错误的请求,类型错误");
		//将#翻译为\
		$filter = self::stardarizeFilter($origin_filter);
		App::mylog($filter);
		
		$over = false;
		$msg = "没有更多的数据了..";
		if (strlen($origin_filter) > 30) {
			$msg = "您的表达式太长了...已经超过了25个字符";
			$over = true;
		}
		$result = $over ? false :self::query($filter, $type, $start);
		if ($result) {
			echo ToolLib::ajax(true, array_merge($result, array('grep_filter' => $origin_filter, 'query_type' => $type, 'cs' => $cs)));
		} else {
			echo ToolLib::ajax(false, $msg);
		}
	}
	public static function query($filter, $type, $start) {
		$ids = array();
		$complete = false;
		$startTime = time(); //开始查询时间
		$count = 0;
		$sc_entity = new TongdianEntity();
		//如果type 或 start 不是纯数字的话
		if (!preg_match('#\d+#', $type) || !preg_match('#\d+#', $start)) {
			return false;
		}
		$max_id = $sc_entity->getMaxId('type='. $type. ' and id>'. $start);
		if ($max_id == $start) $complete = true;
		while($count < 30 && $start != $max_id) {
			//最大执行20秒...
			if (time() - $startTime > 20) {
				$complete = false;
				break;
			}
			$sc_entity = new TongdianEntity();
			$sc_array = $sc_entity->getDataArray(array('id', 'word', 'py'), 
				'type='. $type. ' and id>'. $start. ' limit 10000'); //取出最多5000个结果
			if (!$sc_array) {
				$complete = true; //已经检索到数据库最尾部
				break;
			}
			foreach($sc_array as $v) {
				$vv = $v;
				if (@preg_match($filter, $v['word']) || @preg_match($filter, $v['py'])) {
					//$v['py'] = null; //去掉py这一项，仅保留std_py
					//$v['definition'] = str_replace("\n", "<br>", $v['definition']);
					$ids[] = $v['id'];
					$count++;
				}
				if ($count === 30){
					$complete = false;
					$start = $vv['id']; //确保返回的最后的这个id是break前的最后的结果
				   	break; //最多搜索三十个
				}
			}
			$start = $vv['id'];
		}
		$temp = self::query_from_ids($ids);
			//不应该依赖count($ids)来判断是否complete
			//$complete = count($ids) < 30 ? true : false;
		return array('complete' => $complete, 'data' => $temp, 'end' => $start/*$temp[count($temp) - 1]['id']*/, 'count' => $count);
	}
	/**
	 * 通过ids获取DataArray
	 * @access public
	 * @param array $ids
	 * @return array
	 */
	private static function query_from_ids($ids) {
		if (count($ids) == 0) return array(); //如果ids为空，则返回空数组
		$sc_entity = new TongdianEntity();
		$sc_array = $sc_entity->getDataArray(array('id', 'word', 'std_py', 'definition'), 
			'type='. $type. ' and id>'. $start. ' limit 5000'); //取出最多5000个结果
		$sql = array();
		foreach($ids as $v) {
			$sql[] = 'id='. $v. ' ';
		}
		$sql = join(' or ', $sql);
		$sc_array = $sc_entity->getDataArray(array('id', 'word', 'std_py', 'definition'), $sql);
		foreach($sc_array as $k =>$v) {
			$temp =  str_replace("\n", "<br>", $v['definition']);
			$temp =  str_replace("【例】", "", $temp);
			$sc_array[$k]['definition'] = $temp;
		}
		return $sc_array;
	}
	private static function stardarizeFilter($filter) {
		if (trim($filter) == '') App::redirect("/"); //如果为空，则转向首页
		//将#翻译为\
		$filter = preg_replace("/\[hz\]/", "[\x{4e00}-\x{9fa5}]", $filter);//把对用户友好的[hanzi]替换为难读懂的[\x{4e00}-\x{9fa5}]
		$filter = preg_replace("/\[py\]/", "[a-z0-9A-Z]+", $filter);//把对用户友好的[hanzi]替换为难读懂的[\x{4e00}-\x{9fa5}]
		$filter = "/". $filter. "/u";//确保后面的u，保证是unicode匹配
		return $filter;
	}
}
