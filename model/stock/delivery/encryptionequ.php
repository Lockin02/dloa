<?php
/**
 * @author yxin1
 * @Date 2014��5��30�� 10:13:44
 * @version 1.0
 * @description:��������������ӱ� Model�� 
 */
 class model_stock_delivery_encryptionequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_delivery_encryptionequ";
		$this->sql_map = "stock/delivery/encryptionequSql.php";
		parent::__construct ();
	}     
 }
?>