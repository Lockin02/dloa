<?php
/**
 * @author Administrator
 * @Date 2012-10-25 14:53:11
 * @version 1.0
 * @description:设备分类信息 Model层
 */
 class model_equipment_budget_budgetType  extends model_treeNode {

	function __construct() {
		$this->tbl_name = "oa_equ_budget_type";
		$this->sql_map = "equipment/budget/budgetTypeSql.php";
		parent::__construct ();
	}     }
?>