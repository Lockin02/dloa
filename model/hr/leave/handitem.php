<?php
/**
 * @author Administrator
 * @Date 2013年4月24日 星期三 16:12:32
 * @version 1.0
 * @description:离职审批交接清单 Model层 
 */
 class model_hr_leave_handitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_handitem";
		$this->sql_map = "hr/leave/handitemSql.php";
		parent::__construct ();
	}     
 }
?>