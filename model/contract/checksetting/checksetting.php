<?php
/**
 * @author tse
 * @Date 2014��4��1�� 10:47:06
 * @version 1.0
 * @description:���չ������� Model��
 */
 class model_contract_checksetting_checksetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_check_setting";
		$this->sql_map = "contract/checksetting/checksettingSql.php";
		parent::__construct ();
	}
 }
?>