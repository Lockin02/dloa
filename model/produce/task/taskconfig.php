<?php
/**
 * @author yxin1
 * @Date 2014年8月25日 11:04:57
 * @version 1.0
 * @description:生产任务配置 Model层 
 */
 class model_produce_task_taskconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig";
		$this->sql_map = "produce/task/taskconfigSql.php";
		parent::__construct ();
	}     
 }
?>