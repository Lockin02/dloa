<?php
/**

 * @description:�ʲ������嵥 Model��
 */
 class model_asset_daily_allocationitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_allocationitem";
		$this->sql_map = "asset/daily/allocationitemSql.php";
		parent::__construct ();
	}
 }
?>