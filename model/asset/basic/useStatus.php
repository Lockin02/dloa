<?php

/**
 *
 * Ê¹ÓÃ×´Ì¬model
 *
 */
class model_asset_basic_useStatus extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_useStatus";
		$this->sql_map = "asset/basic/useStatusSql.php";
		parent::__construct ();
	}

}
?>
