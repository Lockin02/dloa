<?php
/**
 * @author huangzf
 * @Date 2011��3��13�� 13:41:42
 * @version 1.0
 * @description:���ⵥ���϶��������嵥 Model�� ������Ʒ�İ�װ�Ӳ����Ʒ��Ӧ�����
 */
 class model_stock_outstock_extraitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_stockout_extraitem";
		$this->sql_map = "stock/outstock/extraitemSql.php";
		parent::__construct ();
	}
 }
?>