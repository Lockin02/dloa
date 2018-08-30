<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:11:15
 * @version 1.0
 * @description:面试官 Model层
 */
class model_hr_recruitment_interviewer  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_invitation_interviewer";
		$this->sql_map = "hr/recruitment/interviewerSql.php";
		parent::__construct ();
	}
}
?>