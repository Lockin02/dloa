<?php
/**
 * @author Administrator
 * @Date 2012��8��30�� 19:17:07
 * @version 1.0
 * @description:�̵��ܽ����� Model�� 
 */
 class model_hr_inventory_templatesummary  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_templatesummary";
		$this->sql_map = "hr/inventory/templatesummarySql.php";
		parent::__construct ();
	}     
 }
?>