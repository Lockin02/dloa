<?php
/**
 * @author Show
 * @Date 2012年10月15日 星期一 9:44:04
 * @version 1.0
 * @description:发票汇总单 Model层
 */
class model_finance_expense_exbill extends model_base {

	function __construct() {
		$this->tbl_name = "bill_list";
		$this->sql_map = "finance/expense/exbillSql.php";
		parent :: __construct();
	}

	//新增 -通过汇总
	function addSummary_d($BillNo,$codeRuleDao){
		$object = array();
		try{
			//先将传过来的BillNo专程conBillNo
			$object['ConBillNo'] = $BillNo;

			//自动生成系统编号和表单状态
			$object['BillNo'] = $codeRuleDao->exbillCode('exbill');

			//录入日期
			$object['InputDt'] = day_date;

			return parent::add_d($object);
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>