<?php
/**
 * @author Administrator
 * @Date 2012��10��30�� ���ڶ� 14:49:22
 * @version 1.0
 * @description:��ְ�����嵥��ϸ������ Model�� 
 */
 class model_hr_leave_handoverMember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_handover_member";
		$this->sql_map = "hr/leave/handoverMemberSql.php";
		parent::__construct ();
	}     
 }
?>