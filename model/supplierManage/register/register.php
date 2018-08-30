<?php
/**
 *供应商model层类
 */
class model_supplierManage_register_register extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_lib_temp";
		$this->sql_map = "supplierManage/register/registerSql.php";
		parent::__construct ();
	}
}
?>
