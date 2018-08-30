<?php
/**
 * @author tse
 * @Date 2014年4月1日 10:47:06
 * @version 1.0
 * @description:验收管理设置 Model层
 */
 class model_contract_checksetting_checksetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_check_setting";
		$this->sql_map = "contract/checksetting/checksettingSql.php";
		parent::__construct ();
	}
 }
?>