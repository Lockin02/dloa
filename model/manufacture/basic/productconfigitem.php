<?php
/**
 * @author Michael
 * @Date 2015��3��23�� 16:29:21
 * @version 1.0
 * @description:���������������� Model�� 
 */
 class model_manufacture_basic_productconfigitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_productconfig_item";
		$this->sql_map = "manufacture/basic/productconfigitemSql.php";
		parent::__construct ();
	}     
 }
?>