<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 10:31:09
 * @version 1.0
 * @description:ָ������ѡ�� Model��
 */
class model_engineering_assess_esmassoption extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_options";
		$this->sql_map = "engineering/assess/esmassoptionSql.php";
		parent :: __construct();
	}
}
?>