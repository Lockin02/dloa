<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:43:26
 * @version 1.0
 * @description:BOM分录表 Model层 
 */
 class model_produce_bom_bomitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_bom_item";
		$this->sql_map = "produce/bom/bomitemSql.php";
		parent::__construct ();
	}     }
?>