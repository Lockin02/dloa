<?php
/**
 * @author Michael
 * @Date 2014��7��21�� 11:45:06
 * @version 1.0
 * @description:���¹���-������ѡ��¼ Model�� 
 */
 class model_hr_personnel_excel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_excel";
		$this->sql_map = "hr/personnel/excelSql.php";
		parent::__construct ();
	}     
 }
?>