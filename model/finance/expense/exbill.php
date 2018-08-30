<?php
/**
 * @author Show
 * @Date 2012��10��15�� ����һ 9:44:04
 * @version 1.0
 * @description:��Ʊ���ܵ� Model��
 */
class model_finance_expense_exbill extends model_base {

	function __construct() {
		$this->tbl_name = "bill_list";
		$this->sql_map = "finance/expense/exbillSql.php";
		parent :: __construct();
	}

	//���� -ͨ������
	function addSummary_d($BillNo,$codeRuleDao){
		$object = array();
		try{
			//�Ƚ���������BillNoר��conBillNo
			$object['ConBillNo'] = $BillNo;

			//�Զ�����ϵͳ��źͱ�״̬
			$object['BillNo'] = $codeRuleDao->exbillCode('exbill');

			//¼������
			$object['InputDt'] = day_date;

			return parent::add_d($object);
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>