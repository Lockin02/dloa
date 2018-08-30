<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:13:18
 * @version 1.0
 * @description:合同培训计划 Model层
 */
 class model_contract_contract_trainingplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_trainingplan";
		$this->sql_map = "contract/contract/trainingplanSql.php";
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