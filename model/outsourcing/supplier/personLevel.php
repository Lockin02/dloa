<?php
/**
 * @author Administrator
 * @Date 2013年11月5日 星期二 14:17:30
 * @version 1.0
 * @description:人员技术类型 Model层 
 */
 class model_outsourcing_supplier_personLevel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_personlevel";
		$this->sql_map = "outsourcing/supplier/personLevelSql.php";
		parent::__construct ();
	}     
 }
?>