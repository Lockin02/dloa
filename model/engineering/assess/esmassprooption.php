<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:22
 * @version 1.0
 * @description:��Ŀָ��ѡ�� Model��
 */
class model_engineering_assess_esmassprooption extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assoptions";
		$this->sql_map = "engineering/assess/esmassprooptionSql.php";
		parent :: __construct();
	}
}
?>