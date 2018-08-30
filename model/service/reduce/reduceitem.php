<?php
/**
 * @author huangzf
 * @Date 2011年12月3日 10:33:06
 * @version 1.0
 * @description:维修费用减免清单 Model层 
 */
class model_service_reduce_reduceitem extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_reduce_item";
		$this->sql_map = "service/reduce/reduceitemSql.php";
		parent::__construct ();
	}
}
?>