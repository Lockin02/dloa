<?php
/**
 * @author yxin1
 * @Date 2014年8月25日 11:05:34
 * @version 1.0
 * @description:生产任务配置清单 Model层
 */
 class model_produce_task_taskconfigitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_item";
		$this->sql_map = "produce/task/taskconfigitemSql.php";
		parent::__construct ();
	}
 }
?>