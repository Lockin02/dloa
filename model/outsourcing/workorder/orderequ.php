<?php
/**
 * @author phz
 * @Date 2014��1��18�� ������ 17:09:44
 * @version 1.0
 * @description:�����ӱ� Model�� 
 */
 class model_outsourcing_workorder_orderequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workorder_orderequ";
		$this->sql_map = "outsourcing/workorder/orderequSql.php";
		parent::__construct ();
	}     
 }
?>