<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:49:55
 * @version 1.0
 * @description:��Ӧ��������Դ��Ϣ Model�� 
 */
 class model_outsourcing_supplier_hrInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_hrinfo";
		$this->sql_map = "outsourcing/supplier/hrInfoSql.php";
		parent::__construct ();
	}     
 }
?>