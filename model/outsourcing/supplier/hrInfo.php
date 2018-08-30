<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:49:55
 * @version 1.0
 * @description:供应商人力资源信息 Model层 
 */
 class model_outsourcing_supplier_hrInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_hrinfo";
		$this->sql_map = "outsourcing/supplier/hrInfoSql.php";
		parent::__construct ();
	}     
 }
?>