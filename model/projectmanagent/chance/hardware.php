<?php
/**
 * @author Administrator
 * @Date 2013��5��29�� 11:33:37
 * @version 1.0
 * @description:�̻�Ӳ���豸�� Model�� 
 */
 class model_projectmanagent_chance_hardware  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_hardware";
		$this->sql_map = "projectmanagent/chance/hardwareSql.php";
		parent::__construct ();
	}     
 }
?>