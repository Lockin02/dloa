<?php
/**
 * @author Michael
 * @Date 2014��3��6�� ������ 10:14:03
 * @version 1.0
 * @description:�⳵��ͬǩ����ϸ Model��
 */
 class model_outsourcing_contract_rentcar_signlogdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_signlogdetail";
		$this->sql_map = "outsourcing/contract/rentcar_signlogdetailSql.php";
		parent::__construct ();
	}
 }
?>