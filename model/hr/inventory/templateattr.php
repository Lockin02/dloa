<?php
/**
 * @author chengl
 * @Date 2012年8月20日 17:03:17
 * @version 1.0
 * @description:盘点模板属性信息
 */
class model_hr_inventory_templateattr  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_templateattr";
		$this->sql_map = "hr/inventory/templateattrSql.php";
		parent::__construct ();
	}

}
?>
