<?php
/**
 * @author Administrator
 * @Date 2013��8��1�� 8:23:39
 * @version 1.0
 * @description:oa_asset_requireback Model�� 
 */
 class model_asset_require_requireback  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireback";
		$this->sql_map = "asset/require/requirebackSql.php";
		parent::__construct ();
	}     
 }
?>