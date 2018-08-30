<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:23:18
 * @version 1.0
 * @description:导师考核表模板 Model层 
 */
 class model_hr_tutor_rassesformwork  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorassess_formwork";
		$this->sql_map = "hr/tutor/rassesformworkSql.php";
		parent::__construct ();
	}     
 }
?>