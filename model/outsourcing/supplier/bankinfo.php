<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:22:33
 * @version 1.0
 * @description:��Ӧ��������Ϣ Model�� 
 */
 class model_outsourcing_supplier_bankinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_bankinfo";
		$this->sql_map = "outsourcing/supplier/bankinfoSql.php";
		parent::__construct ();
	}     
 }
?>