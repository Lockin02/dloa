<?php
/**
 * @author yxin1
 * @Date 2014年4月24日 9:53:16
 * @version 1.0
 * @description:法定节假日详细 Model层 
 */
 class model_hr_worktime_setequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_worktime_setequ";
		$this->sql_map = "hr/worktime/setequSql.php";
		parent::__construct ();
	}     
 }
?>