<?php
/*
 * Created on 2010-12-6
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_assessment_assplan extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_plan";
		$this->sql_map = "engineering/assessment/assplanSql.php";
		parent::__construct ();
	}


}
?>
