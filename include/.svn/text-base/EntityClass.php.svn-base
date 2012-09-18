<?php
abstract class EntityClass {
	public $data;

	
	/**
	 *  返回所对应的表名称
	 * @access public
	 * @return string
	 */
	public abstract function get_table();
	/**
	 *  返回所对应的字段名
	 * @access public
	 * @return array
	 */
	public abstract function get_fields();
	/**
	 *  返回所对应的有效字段名,只要是在php里设置的字段都是有效字段，甚至包括time
	 * @access public
	 * @return array
	 */
	public abstract function get_useful_fields();

	/*
	 * 子类可选择重载的函数
	 */
	public function beforeConstruct(){
		//这是一个空方法，模板作用
	}

	/**
	 *  构造函数，如果传入的是字符串，则认为是id
	 *  如果传入的是数组，则认为是内容，直接赋值
	 * @access public
	 * @param string|array $init
	 */
	public function __construct($init = '') {
		$this->beforeConstruct();
		if (is_array($init)) {
			$this->data = $init;
		} else {
			$this->data = $this->getInstance($init);
		}
	}


	//子类可重载此方法
	public function beforeInsert() {
		//这是一个空方法，模板作用
	}
	//
	//子类可重载此方法
	public function beforeUpdate() {
		//这是一个空方法，模板作用
	}

	public function insert() {
		$this->beforeInsert();//调用子类的beforeInsert
		$data = $this->stripMETA($this->data);
		return App::exec('C', $this->get_table(), $data);
	}

	public function update() {
		if (!$this->data['id']) {
			App::redirectError('ssnau: cannot update when id is not supplied');
		}
		$cond = 'id'. "=". $this->data['id'];
		$this->beforeUpdate();
		$data = $this->stripMETA($this->data);
		return App::exec('U', $this->get_table(), $data, $cond);
	}

	public function delete() {
		$cond = 'id'. "=". $this->data['id'];
		return App::exec('D', $this->get_table(), '', $cond);
	}

	/**
	 * 设置字段属性
	 * @access public
	 * @param array $field_array
	 */
	public function set($field_array) {
		if (is_array($field_array)) {
			foreach($field_array as $k => $v) {
				//保证仅设置可修改属性段
				if (in_array($k, $this->get_useful_fields())) {
					$this->data[$k] = $v;
				}
			}	
		} else {
			App::redirectError('DBLib set function need a array as parameter');
		}
	}

	/**
	 * 设置字段属性，可设置一切字段，包括id, time这类
	 * @access public
	 * @param array $field_array
	 */
	public function unsafe_set($field_array) {
		if (is_array($field_array)) {
			foreach($field_array as $k => $v) {
				$this->data[$k] = $v;
			}	
		} else {
			App::redirectError('DBLib set function need a array as parameter');
		}
	}

	/**
	 * 通过id获取实例,如果参数为空，则返回null
	 * @access public
	 * @param string $id
	 * @return array
	 */
	public function getInstance($id) {
		$result = null;
		if ($id) {
			$cond = 'id'. '='. $id;
			$result = App::selectFirst($this->get_table(), $this->get_fields(), $cond);	
		}
		return $result;
	}

	/**
	 * 通过pCond获取实例，默认获取所有
	 * 注：理论上应该是静态方法的，但若是静态方法就获不到当前类名了
	 * @access public
	 * @param string|array $pCond
	 * @return array
	 */
	public function getInstanceArray($pCond='', $pConnector='and') {
		$className = get_class($this); //获得当前类名
		
		//如果是$pCond是string的话
		//忽略$pConnector参数
		if (is_string($pCond)){
			$dataArray = self::getDataArray('*', $pCond);
		}
		
		//如果是$pCond是array的话
		//array的格式是key=>value,默认连接符为$pConnector=and
		if (is_array($pCond)){
			$condArray = '';
			foreach($pCond as $k => $v) {
				$condArray[] = '`'. $k .'`="'. $v .'"';
			}
			$pCond = join(' '.$pConnector .' ', $condArray);
			$dataArray = self::getDataArray('*', $pCond);
		}

		$instanceArray = array();
		foreach($dataArray as $k => $v) {
			array_push($instanceArray, new $className($v));
		}
		return $instanceArray;
	}

	/**
	 * 通过pCond获取array
	 * @access public
	 * @param array|string $pFields
	 * @param string $pCond
	 * @return array
	 */
	public function getDataArray($pFields='*', $pCond='') {
		if ($pFields == '*') $pFields = array('*');
		$result = App::select($this->get_table(), $pFields, $pCond);	
		return $result ? $result : array();
	}

	/**
	* 剥去当前entity的META数据，如id, lastmodified
	* 实现方式是保留有效可填字段useful_fields
	* 返回一个新的array
	* @access public
	*/
	public function stripMETA(){
		$data = $this->data;
		$afterData = array();
		foreach($this->get_useful_fields() as $k => $v) {
			$afterData[$v] = $data[$v];
		}
		return $afterData;
	}

	/**
	* 获得最大的id
	* 如果没有传入pCond,则直接获得最大的
	* 返回一个新的array
	* @access public
	* @param $pCond String
	*/
	public function getMaxId($pCond = false){
		$ret =  App::querySql('select max(id) from `'. $this->get_table(). "` ". ($pCond ? ' where '. $pCond : ''));
		return $ret[0]['max(id)'];
	}
}
