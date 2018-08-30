<?php
/**
 * @author Administrator
 * @Date 2013年4月23日 星期二 16:38:39
 * @version 1.0
 * @description:季度考核信息 Model层 
 */
 class model_hr_assess_assessrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "appraisal_performance";
		$this->sql_map = "hr/assess/assessrecordsSql.php";
		parent::__construct ();
	}     
 }
?>