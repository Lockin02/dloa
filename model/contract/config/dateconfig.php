<?php
/**
 * @author Show
 * @Date 2013年7月15日 10:44:32
 * @version 1.0
 * @description:日期设置 Model层 
 */
 class model_contract_config_dateconfig  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_dateconfig";
		$this->sql_map = "contract/config/dateconfigSql.php";
		parent::__construct ();
	}     
 }
?>