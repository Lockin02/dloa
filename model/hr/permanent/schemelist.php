<?php
/**
 * @author jianjungki
 * @Date 2012年8月6日 15:23:48
 * @version 1.0
 * @description:员工考核项目细则 Model层 
 */
 class model_hr_permanent_schemelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_schemelist";
		$this->sql_map = "hr/permanent/schemelistSql.php";
		parent::__construct ();
	}     
 }
?>