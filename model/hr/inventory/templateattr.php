<?php
/**
 * @author chengl
 * @Date 2012��8��20�� 17:03:17
 * @version 1.0
 * @description:�̵�ģ��������Ϣ
 */
class model_hr_inventory_templateattr  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_templateattr";
		$this->sql_map = "hr/inventory/templateattrSql.php";
		parent::__construct ();
	}

}
?>
