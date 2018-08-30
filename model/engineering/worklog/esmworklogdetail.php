<?php
/**
 * @author huangzf
 * @Date 2012年2月2日 15:46:00
 * @version 1.0
 * @description:日志清单(oa_esm_worklog_detail) Model层 
 */
 class model_engineering_worklog_esmworklogdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_detail";
		$this->sql_map = "engineering/worklog/esmworklogdetailSql.php";
		parent::__construct ();
	}     }
?>