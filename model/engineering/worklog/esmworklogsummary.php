<?php
/**
 * @author Administrator
 * @Date 2010��12��5�� 9:37:44
 * @version 1.0
 * @description:������־����(oa_esm_worklog_summary) Model�� 
 */
 class model_engineering_worklog_esmworklogsummary  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_summary";
		$this->sql_map = "engineering/worklog/esmworklogsummarySql.php";
		parent::__construct ();
	}
 }
?>