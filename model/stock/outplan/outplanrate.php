<?php
/**
 * @author Administrator
 * @Date 2012��2��20�� 14:00:37
 * @version 1.0
 * @description:�����ƻ����ȱ�ע Model�� 
 */
 class model_stock_outplan_outplanrate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_outplan_rate";
		$this->sql_map = "stock/outplan/outplanrateSql.php";
		parent::__construct ();
	}
 }
?>