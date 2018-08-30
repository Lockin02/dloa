<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:29
 * @version 1.0
 * @description:员工转正考核工作相关 Model层 
 */
 class model_hr_permanent_linkcontent  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_linkcontent";
		$this->sql_map = "hr/permanent/linkcontentSql.php";
		parent::__construct ();
	}     
 }
?>