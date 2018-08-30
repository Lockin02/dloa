<?php

/**
 * @author Show
 * @Date 2012年8月2日 星期四 19:35:41
 * @version 1.0
 * @description:工程超权限申请 Model层
 */
class model_engineering_exceptionapply_exceptionapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_exceptionapply";
		$this->sql_map = "engineering/exceptionapply/exceptionapplySql.php";
		parent :: __construct();
	}

	/******************** 配置信息 *************************/

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'applyType','rentalType','useRange'
    );

	//对应业务代码
	private $relatedCode = array (
		'GCYCSQ-01' => 'loan', //借款
		'GCYCSQ-02' => 'cost', //报销
		'GCYCSQ-03' => 'buy', //请购
		'GCYCSQ-04' => 'car' //租车
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/******************** 增删改查 *************************/
	/**
	 * 重写add_d
	 */
	function add_d($object){
		$codeRuleDao = new model_common_codeRule();
		$object = $this->processDatadict($object);

		//项目编号生成
		$object['formNo'] = $codeRuleDao->exceptionApplyCode($this->tbl_name);

		return parent::add_d($object,true);
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

	/**
	 * 审核
	 */
	function audit_d($object){
		$object['ExaStatus'] = $object['ExaStatus'] == 1 ? '完成' : '打回';

		return parent::edit_d($object,true);
	}
}
?>