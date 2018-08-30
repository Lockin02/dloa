<?php

/**
 * @author Show
 * @Date 2012年10月11日 星期四 10:02:05
 * @version 1.0
 * @description:费用汇总表明细备注信息 Model层
 */
class model_finance_expense_exsummarydetail extends model_base {

	function __construct() {
		$this->tbl_name = "cost_summary";
		$this->sql_map = "finance/expense/exsummarydetailSql.php";
		parent :: __construct();
	}
}
?>