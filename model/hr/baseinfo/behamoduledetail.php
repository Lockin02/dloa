<?php
/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:13:09
 * @version 1.0
 * @description:��ΪҪ�����ñ� Model�� 
 */
 class model_hr_baseinfo_behamoduledetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_behamoduledetail";
		$this->sql_map = "hr/baseinfo/behamoduledetailSql.php";
		parent::__construct ();
	}     
 }
?>