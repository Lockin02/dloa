<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 19:40:48
 * @version 1.0
 * @description:��ʦ���˱�----������ϸ Model�� 
 */
 class model_hr_tutor_schemeinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeinfo";
		$this->sql_map = "hr/tutor/schemeinfoSql.php";
		parent::__construct ();
	}     
 }
?>