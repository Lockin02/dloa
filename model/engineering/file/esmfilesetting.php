<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:23:11
 * @version 1.0
 * @description:项目文档设置 Model层 
 */
class model_engineering_file_esmfilesetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_flie_setting";
		$this->sql_map = "engineering/file/esmfilesettingSql.php";
		parent::__construct ();
	}     
}