<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 14:58:38
 * @version 1.0
 * @description:�̻���ϵ����Ϣ�� Model�� 
 */
 class model_projectmanagent_chancelinker_chancelinker  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_linkman";
		$this->sql_map = "projectmanagent/chancelinker/chancelinkerSql.php";
		parent::__construct ();
	}
 }
?>