<?php
/**
 * @author huangzf
 * @Date 2012年7月11日 星期三 14:19:20
 * @version 1.0
 * @description:常用设备附属物料 Model层 
 */
class model_stock_extra_equipmentpro extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_extra_equipmentpro";
		$this->sql_map = "stock/extra/equipmentproSql.php";
		parent::__construct ();
	}
}
?>