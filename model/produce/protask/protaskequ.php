<?php
/**
 * @author Administrator
 * @Date 2011��6��2�� 10:30:23
 * @version 1.0
 * @description:���������嵥 Model��
 */
 class model_produce_protask_protaskequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_protaskequ";
		$this->sql_map = "produce/protask/protaskequSql.php";
		parent::__construct ();
	}
 }
?>