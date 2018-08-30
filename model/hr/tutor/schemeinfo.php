<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:40:48
 * @version 1.0
 * @description:导师考核表----考核明细 Model层 
 */
 class model_hr_tutor_schemeinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeinfo";
		$this->sql_map = "hr/tutor/schemeinfoSql.php";
		parent::__construct ();
	}     
 }
?>