<?php
/**
 * @author ACan
 * @Date 2015��4��13�� 14:25:37
 * @version 1.0
 * @description:������Ϣ-���� Model�� 
 */
 class model_produce_task_processequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_taskconfig_processequ";
		$this->sql_map = "produce/task/processequSql.php";
		parent::__construct ();
	}     
 }
?>