<?php
/**
 * @author Show
 * @Date 2013��7��11�� ������ 13:30:34
 * @version 1.0
 * @description:ͨ���ʼ����ôӱ� Model��
 */
class model_system_mailconfig_mainconfigitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_mailconfig_item";
		$this->sql_map = "system/mailconfig/mainconfigitemSql.php";
		parent :: __construct();
	}
}
?>