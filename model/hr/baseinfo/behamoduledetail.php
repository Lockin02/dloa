<?php
/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:13:09
 * @version 1.0
 * @description:行为要项配置表 Model层 
 */
 class model_hr_baseinfo_behamoduledetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_behamoduledetail";
		$this->sql_map = "hr/baseinfo/behamoduledetailSql.php";
		parent::__construct ();
	}     
 }
?>