<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:50:05
 * @version 1.0
 * @description:供应商工作经验信息 Model层 
 */
 class model_outsourcing_supplier_workInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_workinfo";
		$this->sql_map = "outsourcing/supplier/workInfoSql.php";
		parent::__construct ();
	}     
 }
?>