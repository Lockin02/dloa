<?php
/**
 * @author Show
 * @Date 2012��8��22�� ������ 17:25:00
 * @version 1.0
 * @description:��ְ�ʸ�-��רҵ������ Model�� 
 */
 class model_hr_personnel_certifyapplyexp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_certifyapplyexp";
		$this->sql_map = "hr/personnel/certifyapplyexpSql.php";
		parent::__construct ();
	}     
 }
?>