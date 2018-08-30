<?php
/**
 * @author Show
 * @Date 2012年12月6日 星期四 14:29:37
 * @version 1.0
 * @description:报销申请单 Model层 DetailType
 1.部门报销
 2.(工程)项目报销
 3.研发项目报销
 4.售前费用
 5.售后费用
 */
class model_finance_expense_expenselist extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_list";
		$this->sql_map = "finance/expense/expenselistSql.php";
		parent :: __construct();
	}

	/**
	 * 根据主键修改记录
	 */
	function updateById($object) {
		$condition = array ("HeadID" => $object ['HeadID'] );
		return $this->update ( $condition, $object );
	}
}
?>