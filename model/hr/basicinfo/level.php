<?php
/**
 * @author Administrator
 * @Date 2013年6月13日 星期四 19:54:04
 * @version 1.0
 * @description:人员技术等级 Model层 
 */
 class model_hr_basicinfo_level  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_level";
		$this->sql_map = "hr/basicinfo/levelSql.php";
		parent::__construct ();
	}     
 }
?>