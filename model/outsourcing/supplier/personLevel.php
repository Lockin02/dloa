<?php
/**
 * @author Administrator
 * @Date 2013��11��5�� ���ڶ� 14:17:30
 * @version 1.0
 * @description:��Ա�������� Model�� 
 */
 class model_outsourcing_supplier_personLevel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_personlevel";
		$this->sql_map = "outsourcing/supplier/personLevelSql.php";
		parent::__construct ();
	}     
 }
?>