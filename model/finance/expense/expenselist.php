<?php
/**
 * @author Show
 * @Date 2012��12��6�� ������ 14:29:37
 * @version 1.0
 * @description:�������뵥 Model�� DetailType
 1.���ű���
 2.(����)��Ŀ����
 3.�з���Ŀ����
 4.��ǰ����
 5.�ۺ����
 */
class model_finance_expense_expenselist extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_list";
		$this->sql_map = "finance/expense/expenselistSql.php";
		parent :: __construct();
	}

	/**
	 * ���������޸ļ�¼
	 */
	function updateById($object) {
		$condition = array ("HeadID" => $object ['HeadID'] );
		return $this->update ( $condition, $object );
	}
}
?>