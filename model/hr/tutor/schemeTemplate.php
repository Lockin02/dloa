<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:39:28
 * @version 1.0
 * @description:导师考核表模板 Model层 
 */
 class model_hr_tutor_schemeTemplate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeTemplate";
		$this->sql_map = "hr/tutor/schemeTemplateSql.php";
		parent::__construct ();
	}     
 }
?>