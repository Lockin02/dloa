<?php
/**
 * @author huangzf
 * @Date 2012��7��11�� ������ 14:19:20
 * @version 1.0
 * @description:�����豸�������� Model�� 
 */
class model_stock_extra_equipmentpro extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_extra_equipmentpro";
		$this->sql_map = "stock/extra/equipmentproSql.php";
		parent::__construct ();
	}
}
?>