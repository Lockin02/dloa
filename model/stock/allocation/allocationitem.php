<?php
/**
 * @author Administrator
 * @Date 2011年5月21日 16:09:16
 * @version 1.0
 * @description:调拨单物料清单 Model层 
 */
 class model_stock_allocation_allocationitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_allocation_item";
		$this->sql_map = "stock/allocation/allocationitemSql.php";
		parent::__construct ();
	}
 }
?>