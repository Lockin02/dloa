<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 9:52:49
 * @version 1.0
 * @description:零配件价格表 Model层 
 */
class model_service_accessprice_accessprice extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_access_price";
		$this->sql_map = "service/accessprice/accesspriceSql.php";
		parent::__construct ();
	}
}
?>