<?php
/**
 * @author Administrator
 * @Date 2014��2��11�� 10:18:16
 * @version 1.0
 * @description:��Ʊ���� Model��
 */
 class model_contract_contract_invoiceType  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_invoiceType";
		$this->sql_map = "contract/contract/invoiceTypeSql.php";
		parent::__construct ();
	}

	/**
	 * ��ȡ�¿�Ʊ������Ϣ
	 */
	 function getInvoiceInfo_d($mainid){
	 	$sql = "select * from ".$this->tbl_name. " where mainId = '$mainid'";
		$arr = $this->_db->get_one($sql);
		return $arr;

	 }
 }
?>