<?php
/**
 * @author Show
 * @Date 2014��3��6�� ������ 10:13:09
 * @version 1.0
 * @description:�⳵ͬ�����¼�� Model�� 
 */
 class model_outsourcing_contract_rentcar_changelog  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_changelog";
		$this->sql_map = "outsourcing/contract/rentcar_changelogSql.php";
		parent::__construct ();
	}     
 }
?>