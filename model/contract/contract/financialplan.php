<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:15:29
 * @version 1.0
 * @description:��ͬ�տ��ƻ��� Model��
 */
 class model_contract_contract_financialplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_financialplan";
		$this->sql_map = "contract/contract/financialplanSql.php";
		parent::__construct ();
	}

	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetail_d($contractId) {
		$this->searchArr ['contractId'] = $contractId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}
}
?>