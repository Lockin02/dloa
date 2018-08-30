<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:53:06
 * @version 1.0
 * @description:设备管理-可替换设备管理-可替换物料 Model层 
 */
 class model_engineering_resources_replacedinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_replacedInfo";
		$this->sql_map = "engineering/resources/replacedinfoSql.php";
		parent::__construct ();
	}     
 }
?>