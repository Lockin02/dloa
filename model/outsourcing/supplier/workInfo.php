<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:50:05
 * @version 1.0
 * @description:��Ӧ�̹���������Ϣ Model�� 
 */
 class model_outsourcing_supplier_workInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_workinfo";
		$this->sql_map = "outsourcing/supplier/workInfoSql.php";
		parent::__construct ();
	}     
 }
?>