<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 9:56:38
 * @version 1.0
 * @description:������Ŀ Model�� 
 */
 class model_produce_quality_dimension  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_dimension";
		$this->sql_map = "produce/quality/dimensionSql.php";
		parent::__construct ();
	}     
 }
?>