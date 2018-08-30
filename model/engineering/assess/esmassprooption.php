<?php
/**
 * @author Show
 * @Date 2012年12月10日 星期一 14:20:22
 * @version 1.0
 * @description:项目指标选项 Model层
 */
class model_engineering_assess_esmassprooption extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assoptions";
		$this->sql_map = "engineering/assess/esmassprooptionSql.php";
		parent :: __construct();
	}
}
?>