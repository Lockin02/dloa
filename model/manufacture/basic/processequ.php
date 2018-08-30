<?php
/**
 * @author Michael
 * @Date 2014年7月25日 15:20:28
 * @version 1.0
 * @description:基础信息-工序详情 Model层
 */
 class model_manufacture_basic_processequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_processequ";
		$this->sql_map = "manufacture/basic/processequSql.php";
		parent::__construct ();
	}
 }
?>