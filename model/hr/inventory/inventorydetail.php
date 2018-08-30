<?php
/**
 * @author chengl
 * @Date 2012年8月20日 17:03:17
 * @version 1.0
 * @description:盘点信息明细
 */
class model_hr_inventory_inventorydetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventorydetail";
		$this->sql_map = "hr/inventory/inventorydetailSql.php";
		parent::__construct ();
	}

}
?>
