<?php
/**
 * @author Michael
 * @Date 2015��3��24�� 9:40:31
 * @version 1.0
 * @description:�����������ñ�ͷ Model�� 
 */
 class model_manufacture_basic_productconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_productconfig";
		$this->sql_map = "manufacture/basic/productconfigSql.php";
		parent::__construct ();
	}     
 }
?>