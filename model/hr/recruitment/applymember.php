<?php
/**
 * @author Administrator
 * @Date 2012��7��14�� ������ 14:10:24
 * @version 1.0
 * @description:��Ա����Э���� Model�� 
 */
 class model_hr_recruitment_applymember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_apply_menber";
		$this->sql_map = "hr/recruitment/applymemberSql.php";
		parent::__construct ();
	}     
 }
?>