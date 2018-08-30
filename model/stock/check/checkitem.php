<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:38:28
 * @version 1.0
 * @description:盘点物料清单 Model层 
 */
 class model_stock_check_checkitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_check_item";
		$this->sql_map = "stock/check/checkitemSql.php";
		parent::__construct ();
	}
 }
?>