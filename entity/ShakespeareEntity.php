<?php
abstract class ShakespeareEntity extends EntityClass {
	public function beforeInsert() {
		$this->setCreatedtime();
		$this->setQuanpin();
		$this->setJianpin();
	}

	public function beforeUpdate() {
		$this->setQuanpin();
		$this->setJianpin();
	}

	public function setCreatedtime() {
		$data = $this->data;
		$fields = $this->get_fields();
		//设置首次插入时间, createdtime不属于useful_fiedls，但属于fields
		if (in_array('createdtime', $fields)) {
			$data['createdtime'] = date('Y-m-d H:i:s'); 
		}
		$this->data = $data;
	}

	public function setPy() {
		$data = $this->data;
		$useful_fields = $this->get_useful_fields();
		//设置全拼
		if (in_array('py', $useful_fields)){
			$arr_qp = PyLib::getQuan($data['name']);
			$py = '';
			foreach( $arr_qp as $k => $v) {
				$py = $py. $v. " ";
			}
			$data['py'] = trim($py);
		}
		$this->data = $data;
	}
}
