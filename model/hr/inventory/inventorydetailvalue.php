<?php
/**
 * @author chengl
 * @Date 2012��8��20�� 17:03:17
 * @version 1.0
 * @description:�̵���Ϣ
 */
class model_hr_inventory_inventorydetailvalue  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventorydetailvalue";
		$this->sql_map = "hr/inventory/inventorydetailvalueSql.php";
		parent::__construct ();
	}

}
?>
