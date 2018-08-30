<?php

 class model_contract_contract_confirm  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_confirm";
		$this->sql_map = "contract/contract/confirmSql.php";
		parent::__construct ();
	}

	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = true) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//加入数据字典处理 add by chengl 2011-05-15
		$newId = $this->create ( $object );
		return $newId;
	}
}
?>