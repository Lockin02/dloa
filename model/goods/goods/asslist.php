<?php
/**
 * @author Administrator
 * @Date 2012��3��16�� 11:56:51
 * @version 1.0
 * @description:������������ Model�� 
 */
 class model_goods_goods_asslist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_asslist";
		$this->sql_map = "goods/goods/asslistSql.php";
		parent::__construct ();
	}     }
?>