<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:11:22
 * @version 1.0
 * @description:��Ӧ����Ŀ��Ϣ Model�� 
 */
 class model_outsourcing_supplier_proejct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_project";
		$this->sql_map = "outsourcing/supplier/proejctSql.php";
		parent::__construct ();
	}     
 }
?>