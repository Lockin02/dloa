<?php
/**
 * @author Show
 * @Date 2012年8月1日 16:20:04
 * @version 1.0
 * @description:员工状况(oa_esm_pesonnelinfo) Model层 
 */
 class model_engineering_pesonnelinfo_pesonnelinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_pesonnelinfo";
		$this->sql_map = "engineering/pesonnelinfo/pesonnelinfoSql.php";
		parent::__construct ();
	}     
 }
?>