<?php
/**
 * @author zengqin
 * @Date 2015-3-10
 * @version 1.0
 * @description:Ԥ����ϸ�޸���ʷ��¼
 */
 class model_finance_budget_budgetLog extends model_base{

	function __construct() {
		$this->tbl_name = "oa_finance_budget_log";
		$this->sql_map = "finance/budget/budgetLogSql.php";
		parent::__construct ();
	}
	/**
	 * ����Ԥ����ϸID��ȡԤ����ϸ
	 */
	function getDetailById($id){
		$budgetDetailDao = new model_finance_budget_budgetDetail();
		return $budgetDetailDao->get_d($id);
	}
 }
 ?>