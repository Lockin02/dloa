<?php
/**
 * @author huangzf
 * @Date 2012年4月20日 星期五 9:57:12
 * @version 1.0
 * @description:物料库存预警信息配置 Model层 
 */
class model_stock_productinfo_prostockwarn extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_productstock_warn";
		$this->sql_map = "stock/productinfo/prostockwarnSql.php";
		parent::__construct ();
	}
}
?>