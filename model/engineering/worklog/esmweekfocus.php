<?php
/*
 * Created on 2010-12-1
 * ¹Ø×¢
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_worklog_esmweekfocus extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_worklog_focus";
		$this->sql_map = "engineering/worklog/esmweekfocusSql.php";
		parent::__construct ();
	}
}
?>
