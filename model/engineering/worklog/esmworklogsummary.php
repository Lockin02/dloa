<?php
/**
 * @author Administrator
 * @Date 2010年12月5日 9:37:44
 * @version 1.0
 * @description:工作日志汇总(oa_esm_worklog_summary) Model层 
 */
 class model_engineering_worklog_esmworklogsummary  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_summary";
		$this->sql_map = "engineering/worklog/esmworklogsummarySql.php";
		parent::__construct ();
	}
 }
?>