<?php
/*
 * Created on 2010-12-14
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  class model_engineering_task_tkaudituser extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_task_audit_user";
		$this->sql_map = "engineering/task/tkaudituserSql.php";
		parent::__construct ();
	}
}
?>
