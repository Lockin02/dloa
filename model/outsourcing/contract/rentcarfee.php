<?php
/**
 * @author Michael
 * @Date 2014��9��29�� 19:22:05
 * @version 1.0
 * @description:�⳵��ͬ���ӷ��� Model��
 */
 class model_outsourcing_contract_rentcarfee  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcarfee";
		$this->sql_map = "outsourcing/contract/rentcarfeeSql.php";
		parent::__construct ();
	}
 }
?>