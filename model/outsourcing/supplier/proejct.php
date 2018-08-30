<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:11:22
 * @version 1.0
 * @description:供应商项目信息 Model层 
 */
 class model_outsourcing_supplier_proejct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_project";
		$this->sql_map = "outsourcing/supplier/proejctSql.php";
		parent::__construct ();
	}     
 }
?>