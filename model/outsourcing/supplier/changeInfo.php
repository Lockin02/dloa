<?php
/**
 * @author Administrator
 * @Date 2013��10��28�� ����һ 19:56:25
 * @version 1.0
 * @description:�ȼ������¼ Model�� 
 */
 class model_outsourcing_supplier_changeInfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_gradechange";
		$this->sql_map = "outsourcing/supplier/changeInfoSql.php";
		parent::__construct ();
	}     
 }
?>