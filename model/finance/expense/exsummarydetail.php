<?php

/**
 * @author Show
 * @Date 2012��10��11�� ������ 10:02:05
 * @version 1.0
 * @description:���û��ܱ���ϸ��ע��Ϣ Model��
 */
class model_finance_expense_exsummarydetail extends model_base {

	function __construct() {
		$this->tbl_name = "cost_summary";
		$this->sql_map = "finance/expense/exsummarydetailSql.php";
		parent :: __construct();
	}
}
?>