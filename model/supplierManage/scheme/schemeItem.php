<?php

/**
 *
 * ÆÀ¹À·½°¸Ã÷Ï¸model
 * @author fengxw
 *
 */
class model_supplierManage_scheme_schemeItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_scheme_detail";
		$this->sql_map = "supplierManage/scheme/schemeItemSql.php";
		parent::__construct ();
	}

}
?>
