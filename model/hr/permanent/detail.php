<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:45
 * @version 1.0
 * @description:员工转正考核明细表 Model层 
 */
 class model_hr_permanent_detail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_detail";
		$this->sql_map = "hr/permanent/detailSql.php";
		parent::__construct ();
	}     
 }
?>