<?php
/**
 * @author Administrator
 * @Date 2013��4��24�� ������ 16:12:32
 * @version 1.0
 * @description:��ְ���������嵥 Model�� 
 */
 class model_hr_leave_handitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_handitem";
		$this->sql_map = "hr/leave/handitemSql.php";
		parent::__construct ();
	}     
 }
?>