<?php
/**
 * @author Administrator
 * @Date 2012��2��29�� 19:19:15
 * @version 1.0
 * @description:����������ȱ�ע Model�� 
 */
 class model_stock_outplan_contractrate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rate";
		$this->sql_map = "stock/outplan/contractrateSql.php";
		parent::__construct ();
	}
 }
?>