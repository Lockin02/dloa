<?php
/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:�̻���Ʒ�嵥 Model��
 */
 class model_projectmanagent_chance_goods  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_goods";
		$this->sql_map = "projectmanagent/chance/goodsSql.php";
		parent::__construct ();
	}     }
?>