<?php
/**
 * @author Michael
 * @Date 2014年7月21日 11:45:06
 * @version 1.0
 * @description:人事管理-导出勾选记录 Model层 
 */
 class model_hr_personnel_excel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_excel";
		$this->sql_map = "hr/personnel/excelSql.php";
		parent::__construct ();
	}     
 }
?>