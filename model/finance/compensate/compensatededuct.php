<?php
/**
 * @author show
 * @Date 2014��12��31�� 
 * @version 1.0
 * @description:�⳥���ۿ��¼ Model��
 */
class model_finance_compensate_compensatededuct extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_compensate_deduct";
		$this->sql_map = "finance/compensate/compensatedeductSql.php";
		parent :: __construct();
	}
}