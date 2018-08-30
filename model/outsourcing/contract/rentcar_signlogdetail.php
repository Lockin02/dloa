<?php
/**
 * @author Michael
 * @Date 2014年3月6日 星期四 10:14:03
 * @version 1.0
 * @description:租车合同签收明细 Model层
 */
 class model_outsourcing_contract_rentcar_signlogdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_signlogdetail";
		$this->sql_map = "outsourcing/contract/rentcar_signlogdetailSql.php";
		parent::__construct ();
	}
 }
?>