<?php

/**
 * �ʲ��黹��ϸmodel����
 *@linzx
 */
class model_asset_daily_returnitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_returnitem";
		$this->sql_map = "asset/daily/returnitemSql.php";
		parent :: __construct();
	}

}
?>