<?php
/**
 * @author yxin1
 * @Date 2014年8月26日 14:07:59
 * @version 1.0
 * @description:生产任务配置物料 Model层 
 */
 class model_produce_task_configproduct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_product";
		$this->sql_map = "produce/task/configproductSql.php";
		parent::__construct ();
	}     
 }
?>