<?php
/**
 * @author huangzf
 * @Date 2011��12��3�� 10:33:06
 * @version 1.0
 * @description:ά�޷��ü����嵥 Model�� 
 */
class model_service_reduce_reduceitem extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_reduce_item";
		$this->sql_map = "service/reduce/reduceitemSql.php";
		parent::__construct ();
	}
}
?>