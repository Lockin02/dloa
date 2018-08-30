<?php

/**
 * @author Show
 * @Date 2012��8��2�� ������ 19:35:41
 * @version 1.0
 * @description:���̳�Ȩ������ Model��
 */
class model_engineering_exceptionapply_exceptionapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_exceptionapply";
		$this->sql_map = "engineering/exceptionapply/exceptionapplySql.php";
		parent :: __construct();
	}

	/******************** ������Ϣ *************************/

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'applyType','rentalType','useRange'
    );

	//��Ӧҵ�����
	private $relatedCode = array (
		'GCYCSQ-01' => 'loan', //���
		'GCYCSQ-02' => 'cost', //����
		'GCYCSQ-03' => 'buy', //�빺
		'GCYCSQ-04' => 'car' //�⳵
	);

	/**
	 * �������ͷ���ҵ������
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/******************** ��ɾ�Ĳ� *************************/
	/**
	 * ��дadd_d
	 */
	function add_d($object){
		$codeRuleDao = new model_common_codeRule();
		$object = $this->processDatadict($object);

		//��Ŀ�������
		$object['formNo'] = $codeRuleDao->exceptionApplyCode($this->tbl_name);

		return parent::add_d($object,true);
	}

	/**
	 * ��дedit_d
	 */
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

	/**
	 * ���
	 */
	function audit_d($object){
		$object['ExaStatus'] = $object['ExaStatus'] == 1 ? '���' : '���';

		return parent::edit_d($object,true);
	}
}
?>