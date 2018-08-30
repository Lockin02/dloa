<?php
/**
 * @author Administrator
 * @Date 2013年10月28日 星期一 19:56:25
 * @version 1.0
 * @description:等级变更记录 Model层 
 */
 class model_outsourcing_supplier_changeInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_gradechange";
		$this->sql_map = "outsourcing/supplier/changeInfoSql.php";
		parent::__construct ();
	}     
 }
?>