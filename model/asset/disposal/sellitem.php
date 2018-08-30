<?php

/**
 * 资产出售明细model层类
 *@linzx
 */
class model_asset_disposal_sellitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_sellitem";
		$this->sql_map = "asset/disposal/sellitemSql.php";
		parent :: __construct();

	}
}
?>