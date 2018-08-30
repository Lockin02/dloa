<?php
/**
 * @author Administrator
 * @Date 2014年2月11日 10:18:16
 * @version 1.0
 * @description:开票类型 Model层
 */
 class model_contract_contract_invoiceType  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_invoiceType";
		$this->sql_map = "contract/contract/invoiceTypeSql.php";
		parent::__construct ();
	}

	/**
	 * 获取新开票类型信息
	 */
	 function getInvoiceInfo_d($mainid){
	 	$sql = "select * from ".$this->tbl_name. " where mainId = '$mainid'";
		$arr = $this->_db->get_one($sql);
		return $arr;

	 }
 }
?>