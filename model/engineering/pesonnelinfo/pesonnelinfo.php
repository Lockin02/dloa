<?php
/**
 * @author Show
 * @Date 2012��8��1�� 16:20:04
 * @version 1.0
 * @description:Ա��״��(oa_esm_pesonnelinfo) Model�� 
 */
 class model_engineering_pesonnelinfo_pesonnelinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_pesonnelinfo";
		$this->sql_map = "engineering/pesonnelinfo/pesonnelinfoSql.php";
		parent::__construct ();
	}     
 }
?>