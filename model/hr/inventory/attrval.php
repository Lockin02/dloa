<?php
/*
 * @author: zengq
 * Created on 2012-8-20
 *
 * @description:盘点表属性值 model层
 */
class model_hr_inventory_attrval  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_attrval";
		$this->sql_map = "hr/inventory/attrvalSql.php";
		parent::__construct ();
	}

}
?>
