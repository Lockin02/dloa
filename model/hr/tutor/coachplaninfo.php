<?php
/**
 * @author Administrator
 * @Date 2012-08-23 16:55:05
 * @version 1.0
 * @description:Ա�������ƻ���ϸ�� Model��
 */
 class model_hr_tutor_coachplaninfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_coachplan_info";
		$this->sql_map = "hr/tutor/coachplaninfoSql.php";
		parent::__construct ();
	}
 }
?>