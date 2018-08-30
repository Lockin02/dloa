<?php
/**
 * @author Administrator
 * @Date 2012年10月30日 星期二 14:49:22
 * @version 1.0
 * @description:离职交接清单明细接收人 Model层 
 */
 class model_hr_leave_handoverMember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_handover_member";
		$this->sql_map = "hr/leave/handoverMemberSql.php";
		parent::__construct ();
	}     
 }
?>