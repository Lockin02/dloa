<?php
/**
 * @author Administrator
 * @Date 2012年8月30日 19:17:07
 * @version 1.0
 * @description:盘点总结属性 Model层 
 */
 class model_hr_inventory_templatesummary  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_templatesummary";
		$this->sql_map = "hr/inventory/templatesummarySql.php";
		parent::__construct ();
	}     
 }
?>