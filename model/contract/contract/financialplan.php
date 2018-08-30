<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:15:29
 * @version 1.0
 * @description:合同收开计划表 Model层
 */
 class model_contract_contract_financialplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_financialplan";
		$this->sql_map = "contract/contract/financialplanSql.php";
		parent::__construct ();
	}

	/**
	 * 根据合同ID 获取从表数据
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