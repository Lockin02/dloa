<?php
/**
 * @author Administrator
 * @Date 2012-10-29 14:47:08
 * @version 1.0
 * @description:Éè±¸ÅäÖÃÃ÷Ï¸ Model²ã 
 */
 class model_equipment_budget_deployinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_equ_baseinfo_deployInfo";
		$this->sql_map = "equipment/budget/deployinfoSql.php";
		parent::__construct ();
	}     }
?>