<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:20:37
 * @version 1.0
 * @description:ϵͳ��־������ϸ Model�� 
 */
 class model_syslog_setting_logsettingdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_syslog_setting_detail";
		$this->sql_map = "syslog/setting/logsettingdetailSql.php";
		parent::__construct ();
	}
 }
?>