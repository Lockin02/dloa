<?php
/**
 * @author Administrator
 * @Date 2011��8��9�� 19:38:28
 * @version 1.0
 * @description:�̵������嵥 Model�� 
 */
 class model_stock_check_checkitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_check_item";
		$this->sql_map = "stock/check/checkitemSql.php";
		parent::__construct ();
	}
 }
?>