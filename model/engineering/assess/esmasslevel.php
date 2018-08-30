<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 11:23:19
 * @version 1.0
 * @description:考核等级配置表 Model层 
 */
 class model_engineering_assess_esmasslevel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_level";
		$this->sql_map = "engineering/assess/esmasslevelSql.php";
		parent::__construct ();
	}     
 }
?>