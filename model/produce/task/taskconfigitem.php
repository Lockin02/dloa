<?php
/**
 * @author yxin1
 * @Date 2014��8��25�� 11:05:34
 * @version 1.0
 * @description:�������������嵥 Model��
 */
 class model_produce_task_taskconfigitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_item";
		$this->sql_map = "produce/task/taskconfigitemSql.php";
		parent::__construct ();
	}
 }
?>