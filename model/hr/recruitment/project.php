<?php
/**
 * @author Administrator
 * @Date 2012-07-23 14:04:07
 * @version 1.0
 * @description:ְλ�����-��Ŀ���� Model��
 */
class model_hr_recruitment_project  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_employment_project";
		$this->sql_map = "hr/recruitment/projectSql.php";
		parent::__construct ();
	}
}
?>