<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 16:32:54
 * @version 1.0
 * @description:工资交接单清单 Model层 
 */
 class model_hr_leave_salarydocitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarydocitem";
		$this->sql_map = "hr/leave/salarydocitemSql.php";
		parent::__construct ();
	}     
 }
?>