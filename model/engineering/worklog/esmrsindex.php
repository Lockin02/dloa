<?php
/**
 * @author Show
 * @Date 2012��12��3�� ����һ 10:42:07
 * @version 1.0
 * @description:�ܱ�����ָ�� Model��
 */
class model_engineering_worklog_esmrsindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_weeklog_rsindex";
		$this->sql_map = "engineering/worklog/esmrsindexSql.php";
		parent :: __construct();
	}
}
?>