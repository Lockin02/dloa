<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 16:45:47
 * @version 1.0
 * @description:���鱨���嵥 Model�� 
 */
 class model_produce_quality_qualityereportitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_ereportitem";
		$this->sql_map = "produce/quality/qualityereportitemSql.php";
		parent::__construct ();
	}     
 }
?>