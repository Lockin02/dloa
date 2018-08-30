<?php
/**
 * @author Michael
 * @Date 2015年3月24日 9:40:31
 * @version 1.0
 * @description:基础物料配置表头 Model层 
 */
 class model_manufacture_basic_productconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_productconfig";
		$this->sql_map = "manufacture/basic/productconfigSql.php";
		parent::__construct ();
	}     
 }
?>