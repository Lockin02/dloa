<?php

/**
 * �̵�����model����
 */
class model_asset_checktask_checktask extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_checktask";
		$this->sql_map = "asset/checktask/checktaskSql.php";
		parent::__construct ();
	}



}
?>