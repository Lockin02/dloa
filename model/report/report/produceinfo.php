<?php
/**
 * @author Administrator
 * @Date 2013年10月9日 9:41:37
 * @version 1.0
 * @description:生产能力表明细 Model层 
 */
 class model_report_report_produceinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_report_produce_info";
		$this->sql_map = "report/report/produceinfoSql.php";
		parent::__construct ();
	}     
 }
?>