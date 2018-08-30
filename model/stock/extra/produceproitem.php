<?php
/**
 * @author Administrator
 * @Date 2013年2月27日 星期三 14:28:01
 * @version 1.0
 * @description:生产物料需求 Model层 
 */
 class model_stock_extra_produceproitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_extra_produceproitem";
		$this->sql_map = "stock/extra/produceproitemSql.php";
		parent::__construct ();
	}     
 }
?>