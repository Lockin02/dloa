<?php
/**
 * @author Administrator
 * @Date 2012年7月13日 星期五 14:05:50
 * @version 1.0
 * @description:协助人 Model层
 */
class model_hr_recruitment_recommendmember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recommend_menber";
		$this->sql_map = "hr/recruitment/recommendmemberSql.php";
		parent::__construct ();
	}
}
?>