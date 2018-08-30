<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:39:03
 * @version 1.0
 * @description:导师考核表 Model层 
 */
 class model_hr_tutor_tutorassess  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorassess";
		$this->sql_map = "hr/tutor/tutorassessSql.php";
		parent::__construct ();
	}     
 }
?>