<?php
/**
 * @author Administrator
 * @Date 2011��5��21�� 16:09:16
 * @version 1.0
 * @description:�����������嵥 Model�� 
 */
 class model_stock_allocation_allocationitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_allocation_item";
		$this->sql_map = "stock/allocation/allocationitemSql.php";
		parent::__construct ();
	}
 }
?>