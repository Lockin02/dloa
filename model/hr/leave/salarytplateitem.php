<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 15:29:33
 * @version 1.0
 * @description:�����嵥ģ���嵥 Model�� 
 */
 class model_hr_leave_salarytplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarytplateitem";
		$this->sql_map = "hr/leave/salarytplateitemSql.php";
		parent::__construct ();
	}     
 }
?>