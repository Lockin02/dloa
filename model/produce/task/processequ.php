<?php
/**
 * @author ACan
 * @Date 2015年4月13日 14:25:37
 * @version 1.0
 * @description:配置信息-工序 Model层 
 */
 class model_produce_task_processequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_processequ";
		$this->sql_map = "produce/task/processequSql.php";
		parent::__construct ();
	}     
 }
?>