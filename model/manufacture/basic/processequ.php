<?php
/**
 * @author Michael
 * @Date 2014��7��25�� 15:20:28
 * @version 1.0
 * @description:������Ϣ-�������� Model��
 */
 class model_manufacture_basic_processequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_processequ";
		$this->sql_map = "manufacture/basic/processequSql.php";
		parent::__construct ();
	}
 }
?>