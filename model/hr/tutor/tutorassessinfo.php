<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 16:39:50
 * @version 1.0
 * @description:��ʦ���˱�----������ϸ Model�� 
 */
 class model_hr_tutor_tutorassessinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorassess_info";
		$this->sql_map = "hr/tutor/tutorassessinfoSql.php";
		parent::__construct ();
	}     
 }
?>