<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:22:04
 * @version 1.0
 * @description:��Ӧ����ϵ�� Model�� 
 */
 class model_outsourcing_supplier_linkman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_linkman";
		$this->sql_map = "outsourcing/supplier/linkmanSql.php";
		parent::__construct ();
	}     
 }
?>