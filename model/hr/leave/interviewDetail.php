<?php
/**
 * @author Administrator
 * @Date 2012��10��29�� ����һ 15:17:23
 * @version 1.0
 * @description:��ְ--��̸��¼����ϸ Model��
 */
 class model_hr_leave_interviewDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_interviewDetail";
		$this->sql_map = "hr/leave/interviewDetailSql.php";
		parent::__construct ();
	}

 }
?>