<?php
/**
 * @author Administrator
 * @Date 2012��8��31�� 10:01:18
 * @version 1.0
 * @description:�̵��ܽ�ֵ Model�� 
 */
 class model_hr_inventory_inventorysummaryvalue  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventorysummaryvalue";
		$this->sql_map = "hr/inventory/inventorysummaryvalueSql.php";
		parent::__construct ();
	}     
 }
?>