<?php
/**
 * @author Administrator
 * @Date 2013��10��9�� 9:41:37
 * @version 1.0
 * @description:������������ϸ Model�� 
 */
 class model_report_report_produceinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_report_produce_info";
		$this->sql_map = "report/report/produceinfoSql.php";
		parent::__construct ();
	}     
 }
?>