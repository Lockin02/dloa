<?php
/**
 * @author ACan
 * @Date 2016��6��14�� 14:51:41
 * @version 1.0
 * @description:��Ӧ�������� Model�� 
 */
 class model_supplierManage_formal_usedname  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_usedname";
		$this->sql_map = "supplierManage/formal/usednameSql.php";
		parent::__construct ();
	}     
 }
?>