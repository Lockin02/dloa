<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:42
 * @version 1.0
 * @description:设备预算表从表 Model层 
 */
 class model_equipment_budget_budgetinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_equ_budget_info";
		$this->sql_map = "equipment/budget/budgetinfoSql.php";
		parent::__construct ();
	}     }
?>