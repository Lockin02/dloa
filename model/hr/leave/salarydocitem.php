<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 16:32:54
 * @version 1.0
 * @description:���ʽ��ӵ��嵥 Model�� 
 */
 class model_hr_leave_salarydocitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarydocitem";
		$this->sql_map = "hr/leave/salarydocitemSql.php";
		parent::__construct ();
	}     
 }
?>