<?php

/**
 * @author Administrator
 * @Date 2012-09-25 09:54:23
 * @version 1.0
 * @description:销售备货物料清单 Model层 
 */
class model_projectmanagent_stockup_equ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_stockup_equ";
		$this->sql_map = "projectmanagent/stockup/equSql.php";
		parent :: __construct();
	}
}
?>