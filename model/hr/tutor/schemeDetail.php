<?php
/**
 * @author Administrator
 * @Date 2012��10��7�� ������ 15:16:42
 * @version 1.0
 * @description:��ʦ����ģ����ϸ Model�� 
 */
 class model_hr_tutor_schemeDetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeDetail";
		$this->sql_map = "hr/tutor/schemeDetailSql.php";
		parent::__construct ();
	}     
 }
?>