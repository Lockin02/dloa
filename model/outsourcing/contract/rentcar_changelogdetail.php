<?php
/**
 * @author Show
 * @Date 2014年3月6日 星期四 10:12:51
 * @version 1.0
 * @description:租车合同变更明细表 Model层 
 */
 class model_outsourcing_contract_rentcar_changelogdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_changelogdetail";
		$this->sql_map = "outsourcing/contract/rentcar_changelogdetailSql.php";
		parent::__construct ();
	}     
 }
?>