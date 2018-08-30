<?php
/**
 * @author Administrator
 * @Date 2012年2月20日 14:00:37
 * @version 1.0
 * @description:发货计划进度备注 Model层 
 */
 class model_stock_outplan_outplanrate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_outplan_rate";
		$this->sql_map = "stock/outplan/outplanrateSql.php";
		parent::__construct ();
	}
 }
?>