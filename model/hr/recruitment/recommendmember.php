<?php
/**
 * @author Administrator
 * @Date 2012��7��13�� ������ 14:05:50
 * @version 1.0
 * @description:Э���� Model��
 */
class model_hr_recruitment_recommendmember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recommend_menber";
		$this->sql_map = "hr/recruitment/recommendmemberSql.php";
		parent::__construct ();
	}
}
?>