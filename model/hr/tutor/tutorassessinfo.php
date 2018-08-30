<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:39:50
 * @version 1.0
 * @description:导师考核表----考核明细 Model层 
 */
 class model_hr_tutor_tutorassessinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorassess_info";
		$this->sql_map = "hr/tutor/tutorassessinfoSql.php";
		parent::__construct ();
	}     
 }
?>