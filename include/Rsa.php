<?php
class Rsa {
	private $pri;
	private $pub;
	public function __construct($id=0){
		if ($id === 0) {
			$res = openssl_pkey_new();
			openssl_pkey_export($res, $pri);
			$d= openssl_pkey_get_details($res);
			$pub = $d['key'];
			$this->pri = $pri;
			$this->pub = $pub;
		} else {
			$this->load($id);
		}
	}

	/*
	 * 加密，读入$data，输出$signature
	 */
	public function encrypt($data) {
		$res = openssl_get_privatekey($this->pri);
		openssl_private_encrypt($data,$crypttext,$res);
		
		return (base64_encode($crypttext));
	}

	/*
	 * 解密，读入$signature，得到原始文本
	 */
	public function decrypt($signature) {
		$signature = base64_decode($signature);
		$pub = $this->pub;
		openssl_public_decrypt($signature,$newsource,$pub);
		
		return $newsource;
	}

	/*
	 * @param int $id 即key的id
	 */
	public function load($id){
		$key = new KeyEntity($id);
		$data = $key->data;
		$this->pri = $data['pri'];
		$this->pub = $data['pub'];
	}

	/*
	 * @param string $name
	 */
	public function save($name){
		$file_id = strtoupper(ToolLib::random_string(5,false)).substr(time().'', 3);
		$createdtime = time();
		$ownerid = $_SESSION['id'];
		$key = new KeyEntity();
		$key->set(array('file_id'=> $file_id,
						'name' => $name,
						'createdtime' => $createdtime,
						'ownerid' => $ownerid,
						'pri' => $this->pri,
						'pub' => $this->pub,
					));
		$key->insert();
	}
}
