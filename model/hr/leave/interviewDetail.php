<?php
/**
 * @author Administrator
 * @Date 2012年10月29日 星期一 15:17:23
 * @version 1.0
 * @description:离职--面谈记录表详细 Model层
 */
 class model_hr_leave_interviewDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_interviewDetail";
		$this->sql_map = "hr/leave/interviewDetailSql.php";
		parent::__construct ();
	}

 }
?>