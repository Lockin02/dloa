<?php
/**
 * @author Administrator
 * @Date 2013��4��23�� ���ڶ� 16:38:39
 * @version 1.0
 * @description:���ȿ�����Ϣ Model�� 
 */
 class model_hr_assess_assessrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "appraisal_performance";
		$this->sql_map = "hr/assess/assessrecordsSql.php";
		parent::__construct ();
	}     
 }
?>