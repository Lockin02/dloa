<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:14:51
 * @version 1.0
 * @description:��ͬ��Ʊ�ƻ� Model��
 */
 class model_contract_contract_invoice  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_invoice";
		$this->sql_map = "contract/contract/invoiceSql.php";
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