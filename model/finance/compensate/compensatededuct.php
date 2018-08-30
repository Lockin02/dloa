<?php
/**
 * @author show
 * @Date 2014年12月31日 
 * @version 1.0
 * @description:赔偿单扣款记录 Model层
 */
class model_finance_compensate_compensatededuct extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_compensate_deduct";
		$this->sql_map = "finance/compensate/compensatedeductSql.php";
		parent :: __construct();
	}
}