<?php
/**
 * @author Administrator
 * @Date 2013��2��27�� ������ 14:28:01
 * @version 1.0
 * @description:������������ Model�� 
 */
 class model_stock_extra_produceproitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_extra_produceproitem";
		$this->sql_map = "stock/extra/produceproitemSql.php";
		parent::__construct ();
	}     
 }
?>