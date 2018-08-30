<?php
/**
 * @author Administrator
 * @Date 2012年8月31日 10:01:18
 * @version 1.0
 * @description:盘点总结值 Model层 
 */
 class model_hr_inventory_inventorysummaryvalue  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventorysummaryvalue";
		$this->sql_map = "hr/inventory/inventorysummaryvalueSql.php";
		parent::__construct ();
	}     
 }
?>