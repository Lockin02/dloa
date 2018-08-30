<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 星期日 15:16:42
 * @version 1.0
 * @description:导师考核模板明细 Model层 
 */
 class model_hr_tutor_schemeDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeDetail";
		$this->sql_map = "hr/tutor/schemeDetailSql.php";
		parent::__construct ();
	}     
 }
?>