<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:04
 * @version 1.0
 * @description:供应商联系人 Model层 
 */
 class model_outsourcing_supplier_linkman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_linkman";
		$this->sql_map = "outsourcing/supplier/linkmanSql.php";
		parent::__construct ();
	}     
 }
?>