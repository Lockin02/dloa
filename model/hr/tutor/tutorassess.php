<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 16:39:03
 * @version 1.0
 * @description:��ʦ���˱� Model�� 
 */
 class model_hr_tutor_tutorassess  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorassess";
		$this->sql_map = "hr/tutor/tutorassessSql.php";
		parent::__construct ();
	}     
 }
?>