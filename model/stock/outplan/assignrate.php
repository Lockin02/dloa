<?php
/**
 * @author Administrator
 * @Date 2012年11月18日 14:40:16
 * @version 1.0
 * @description:物料确认进度备注 Model层 
 */
 class model_stock_outplan_assignrate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_assignment_rate";
		$this->sql_map = "stock/outplan/assignrateSql.php";
		parent::__construct ();
	}     
 }
?>