<?php
/**
 * @description: Model²ã
 */
 class model_asset_checktask_checkitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_checkitem";
		$this->sql_map = "asset/checktask/checkitemSql.php";
		parent::__construct ();
	}
 }
?>