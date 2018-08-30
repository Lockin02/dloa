<?php
/**
 * @author Administrator
 * @Date 2012-06-29 10:15:12
 * @version 1.0
 * @description:销售助理操作记录 Model层
 */
 class model_contract_contract_aidhandle  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_aidhandle";
		$this->sql_map = "contract/contract/aidhandleSql.php";
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