<?php
/**
 * @author Administrator
 * @Date 2013��3��6�� ������ 17:29:18
 * @version 1.0
 * @description:���鷽ʽ Model�� 
 */
 class model_produce_quality_checktype  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_checktype";
		$this->sql_map = "produce/quality/checktypeSql.php";
		parent::__construct ();
	}     
 }
?>