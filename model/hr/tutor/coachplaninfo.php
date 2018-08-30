<?php
/**
 * @author Administrator
 * @Date 2012-08-23 16:55:05
 * @version 1.0
 * @description:员工辅导计划详细表 Model层
 */
 class model_hr_tutor_coachplaninfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_coachplan_info";
		$this->sql_map = "hr/tutor/coachplaninfoSql.php";
		parent::__construct ();
	}
 }
?>