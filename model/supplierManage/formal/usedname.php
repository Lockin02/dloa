<?php
/**
 * @author ACan
 * @Date 2016年6月14日 14:51:41
 * @version 1.0
 * @description:供应商曾用名 Model层 
 */
 class model_supplierManage_formal_usedname  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_usedname";
		$this->sql_map = "supplierManage/formal/usednameSql.php";
		parent::__construct ();
	}     
 }
?>