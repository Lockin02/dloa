<?php
/**
 * @author Show
 * @Date 2014��3��6�� ������ 10:13:50
 * @version 1.0
 * @description:�⳵��ͬǩ�ռ�¼�� Model�� 
 */
 class model_outsourcing_contract_rentcar_signlog  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_signlog";
		$this->sql_map = "outsourcing/contract/rentcar_signlogSql.php";
		parent::__construct ();
	}     
 }
?>