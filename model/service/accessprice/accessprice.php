<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 9:52:49
 * @version 1.0
 * @description:������۸�� Model�� 
 */
class model_service_accessprice_accessprice extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_access_price";
		$this->sql_map = "service/accessprice/accesspriceSql.php";
		parent::__construct ();
	}
}
?>