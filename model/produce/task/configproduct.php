<?php
/**
 * @author yxin1
 * @Date 2014��8��26�� 14:07:59
 * @version 1.0
 * @description:���������������� Model�� 
 */
 class model_produce_task_configproduct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_product";
		$this->sql_map = "produce/task/configproductSql.php";
		parent::__construct ();
	}     
 }
?>