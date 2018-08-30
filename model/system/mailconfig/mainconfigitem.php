<?php
/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:34
 * @version 1.0
 * @description:通用邮件配置从表 Model层
 */
class model_system_mailconfig_mainconfigitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_mailconfig_item";
		$this->sql_map = "system/mailconfig/mainconfigitemSql.php";
		parent :: __construct();
	}
}
?>