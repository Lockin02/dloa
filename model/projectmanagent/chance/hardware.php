<?php
/**
 * @author Administrator
 * @Date 2013年5月29日 11:33:37
 * @version 1.0
 * @description:商机硬件设备表 Model层 
 */
 class model_projectmanagent_chance_hardware  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_hardware";
		$this->sql_map = "projectmanagent/chance/hardwareSql.php";
		parent::__construct ();
	}     
 }
?>