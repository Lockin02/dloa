<?php
/**
 * @author huangzf
 * @Date 2012��4��20�� ������ 9:57:12
 * @version 1.0
 * @description:���Ͽ��Ԥ����Ϣ���� Model�� 
 */
class model_stock_productinfo_prostockwarn extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_productstock_warn";
		$this->sql_map = "stock/productinfo/prostockwarnSql.php";
		parent::__construct ();
	}
}
?>