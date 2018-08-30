<?php
/**
 * @author Show
 * @Date 2011��5��9�� ����һ 19:44:55
 * @version 1.0
 * @description:������Ŀ�� Model��
 */
class model_techsupport_tstask_tstask  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_ts_task";
		$this->sql_map = "techsupport/tstask/tstaskSql.php";
		parent::__construct ();
	}

	/********************�²��Բ���ʹ��************************/
	private $relatedStrategyArr = array (//��ͬ����������������,������Ҫ���������׷��
		'FWXMLX-01' => 'model_finance_payables_strategy_spayment', //��ǰ��Ŀ
		'FWXMLX-02' => 'model_finance_payables_strategy_advances', //�ۺ���Ŀ
	);

	private $relatedCode = array (
		'FWXMLX-01' => 'before',
		'FWXMLX-02' => 'after',
	);

	/**
	 * �������ͷ���ҵ������
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/**
	 * �����������ͷ�����
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/************************�ⲿ���ýӿ�************************/
	/**
	 * ��дadd_d
	 */
	function add_d($object){
		$codeRuleDao = new model_common_codeRule();
		//��Ʊ��¼���
		$object['formNo'] = $codeRuleDao->changeCode($this->tbl_name,$object['customerId']);

		$object['status'] = isset($object['status'] ) ? $object['status']: 'XMZT-01';
		return parent::add_d($object,true);
	}
}
?>