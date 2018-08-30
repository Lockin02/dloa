<?php
/**
 * @author Administrator
 * @Date 2012年2月29日 19:19:15
 * @version 1.0
 * @description:发货需求进度备注 Model层 
 */
 class model_stock_outplan_contractrate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rate";
		$this->sql_map = "stock/outplan/contractrateSql.php";
		parent::__construct ();
	}
 }
?>