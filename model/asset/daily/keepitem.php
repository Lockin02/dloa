<?php

/**
 * �ʲ�ά����ϸmodel����
 *@linzx
 */
class model_asset_daily_keepitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_keepitem";
		$this->sql_map = "asset/daily/keepitemSql.php";
		parent :: __construct();

	}
}
?>