<?php
/**
 *��Ӧ����ϵ��model����
 */
class model_supplierManage_register_supplinkman extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_cont_temp";
		$this->sql_map = "supplierManage/register/supplinkmanSql.php";
		parent::__construct ();
	}
}
?>
