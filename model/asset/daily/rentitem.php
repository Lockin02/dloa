<?php
/**

 * @description:�ʲ������嵥 Model��
 */
 class model_asset_daily_rentitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_rentitem";
		$this->sql_map = "asset/daily/rentitemSql.php";
		parent::__construct ();
	}
 }
?>