<?php
/**
 * @author huangzf
 * @Date 2012��2��2�� 15:46:00
 * @version 1.0
 * @description:��־�嵥(oa_esm_worklog_detail) Model�� 
 */
 class model_engineering_worklog_esmworklogdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_detail";
		$this->sql_map = "engineering/worklog/esmworklogdetailSql.php";
		parent::__construct ();
	}     }
?>