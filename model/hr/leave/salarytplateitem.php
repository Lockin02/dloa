<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 15:29:33
 * @version 1.0
 * @description:工资清单模板清单 Model层 
 */
 class model_hr_leave_salarytplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarytplateitem";
		$this->sql_map = "hr/leave/salarytplateitemSql.php";
		parent::__construct ();
	}     
 }
?>