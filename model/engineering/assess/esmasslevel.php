<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 11:23:19
 * @version 1.0
 * @description:���˵ȼ����ñ� Model�� 
 */
 class model_engineering_assess_esmasslevel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_level";
		$this->sql_map = "engineering/assess/esmasslevelSql.php";
		parent::__construct ();
	}     
 }
?>