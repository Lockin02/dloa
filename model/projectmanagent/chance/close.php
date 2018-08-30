<?php
/**
 * @author Administrator
 * @Date 2012-11-08 19:21:39
 * @version 1.0
 * @description:商机关闭信息 Model层
 */
 class model_projectmanagent_chance_close  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_close";
		$this->sql_map = "projectmanagent/chance/closeSql.php";
		parent::__construct ();
	}

}
?>