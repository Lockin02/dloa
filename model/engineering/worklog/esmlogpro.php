<?php
/**
 * @author Administrator
 * @Date 2010��12��5�� 9:38:40
 * @version 1.0
 * @description:��־������Ŀ Model��
 */
 class model_engineering_worklog_esmlogpro  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_proinfo";
		$this->sql_map = "engineering/worklog/esmlogproSql.php";
		parent::__construct ();
	}
 }
?>