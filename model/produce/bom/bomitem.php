<?php
/**
 * @author Administrator
 * @Date 2011��12��30�� 11:43:26
 * @version 1.0
 * @description:BOM��¼�� Model�� 
 */
 class model_produce_bom_bomitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_bom_item";
		$this->sql_map = "produce/bom/bomitemSql.php";
		parent::__construct ();
	}     }
?>