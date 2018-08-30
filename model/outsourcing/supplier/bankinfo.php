<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:33
 * @version 1.0
 * @description:供应商银行信息 Model层 
 */
 class model_outsourcing_supplier_bankinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_bankinfo";
		$this->sql_map = "outsourcing/supplier/bankinfoSql.php";
		parent::__construct ();
	}     
 }
?>