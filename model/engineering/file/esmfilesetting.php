<?php
/**
 * @author tse
 * @Date 2014��1��4�� 9:23:11
 * @version 1.0
 * @description:��Ŀ�ĵ����� Model�� 
 */
class model_engineering_file_esmfilesetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_flie_setting";
		$this->sql_map = "engineering/file/esmfilesettingSql.php";
		parent::__construct ();
	}     
}