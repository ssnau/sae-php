<?php
class ContentAction extends ActionClass{
	public static function actDefault() {
		$token = ToolLib::request('token');
		if (strcmp($token, '901287') !== 0) {
			echo "error";
			return false;
		}

		//选择列表
		//最后得到的data_list格式为 array( array(entity1, entity2, entity3 ... ), array(...), array(...))
		$entity_name_list = array('feel', 'adjective', 'action', 'philosophy', 'creative', 'template', 'tactics');
		$meta_class_list = array('修饰一种感觉', '形容词', '描述一件事的代替说话', '形容词/生活中的哲学', '创造性思维', '模板体裁', '话术');
		$entity_list = array_combine($entity_name_list, $meta_class_list);
		$entity = null;
		$entity_data = null;
		$a = new FeelEntity();
		$data_list = array();
		foreach($entity_list as $k => $v) {
			$class_name = ucfirst($k). 'Entity';
			$entity = new $class_name();
			$entity_data = $entity->getDataArray(array('id', 'name', 'quanpin', 'jianpin', 'tags'));
			array_push($data_list, array('name' => $k, 'title' => $v, 'data' => $entity_data));//将$entity_data压入$data_list中
		}
		App::setVar('page_data_list', $data_list);
		App::setCssAndJs('content,widgets,main', 'main,w-tablelist,w-tabstage,w-resizedwindow,p-content');
		self::display();
	}

	public static function ajaxGetentity() {
		$rType = ToolLib::request('type');
		$rId = ToolLib::request('id');

		if ($rType) {
			$className = ucfirst($rType). "Entity";//TODO:没有进行安全性检查，如果用户请求的字符是管理员访问权限的类就坏事了
			$entity = new $className($rId);
			$result = $entity->data ? ToolLib::ajax(true, $entity->data) : ToolLib::ajax(false, "no available data");
		}
		echo $result;
	}

	public static function ajaxEntity($operation) {
		$rType = ToolLib::request('type');
		$rId = ToolLib::request('id');
		$data = ToolLib::requests_without_cookie();

		if ($rType && $operation) {
			$className = ucfirst($rType). "Entity";//TODO:没有进行安全性检查，如果用户请求的字符是管理员访问权限的类就坏事了
			$entity = new $className($rId);//不用担心rId是空值，构造函数里会判断
			$entity->set($data);//不用担心$data里多余的键，在insert和update里会根据fields进行过滤
			switch($operation) {
				case 'add':
					$ifSuccess = $entity->insert();
					$result = $ifSuccess ? ToolLib::ajax(true, '增加成功') : ToolLib::ajax(false, '增加失败');
					break;
				case 'update':
					$ifSuccess = $entity->update();
					$result = $ifSuccess ? ToolLib::ajax(true, '更新成功') : ToolLib::ajax(false, '更新失败');
					break;
				case 'delete':
					$ifSuccess = $entity->delete();
					$result = $ifSuccess ? ToolLib::ajax(true, '删除成功') : ToolLib::ajax(false, '删除失败');
					break;
				default:
					$result = ToolLib::ajax(false, 'ssnau:undefined operation');
					break;
			}
		} else {
			$result = ToolLib::ajax(false, 'ssnau:undefined operation');
		}

		echo $result;
	}
}
