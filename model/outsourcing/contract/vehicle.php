<?php
/**
 * @author Michael
 * @Date 2014��3��24�� ����һ 14:50:04
 * @version 1.0
 * @description:�⳵��ͬ���޳�����Ϣ Model��
 */
 class model_outsourcing_contract_vehicle  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_vehicle";
		$this->sql_map = "outsourcing/contract/vehicleSql.php";
		parent::__construct ();
	}
 }
?>