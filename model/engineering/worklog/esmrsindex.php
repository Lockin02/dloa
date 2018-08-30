<?php
/**
 * @author Show
 * @Date 2012年12月3日 星期一 10:42:07
 * @version 1.0
 * @description:周报评价指标 Model层
 */
class model_engineering_worklog_esmrsindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_weeklog_rsindex";
		$this->sql_map = "engineering/worklog/esmrsindexSql.php";
		parent :: __construct();
	}
}
?>