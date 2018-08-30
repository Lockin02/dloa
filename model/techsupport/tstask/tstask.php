<?php
/**
 * @author Show
 * @Date 2011年5月9日 星期一 19:44:55
 * @version 1.0
 * @description:服务项目表 Model层
 */
class model_techsupport_tstask_tstask  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_ts_task";
		$this->sql_map = "techsupport/tstask/tstaskSql.php";
		parent::__construct ();
	}

	/********************新策略部分使用************************/
	private $relatedStrategyArr = array (//不同类型入库申请策略类,根据需要在这里进行追加
		'FWXMLX-01' => 'model_finance_payables_strategy_spayment', //售前项目
		'FWXMLX-02' => 'model_finance_payables_strategy_advances', //售后项目
	);

	private $relatedCode = array (
		'FWXMLX-01' => 'before',
		'FWXMLX-02' => 'after',
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/************************外部调用接口************************/
	/**
	 * 重写add_d
	 */
	function add_d($object){
		$codeRuleDao = new model_common_codeRule();
		//开票记录编号
		$object['formNo'] = $codeRuleDao->changeCode($this->tbl_name,$object['customerId']);

		$object['status'] = isset($object['status'] ) ? $object['status']: 'XMZT-01';
		return parent::add_d($object,true);
	}
}
?>