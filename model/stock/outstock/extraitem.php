<?php
/**
 * @author huangzf
 * @Date 2011年3月13日 13:41:42
 * @version 1.0
 * @description:出库单物料额外配套清单 Model层 包括产品的包装物、硬件产品对应的配件
 */
 class model_stock_outstock_extraitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_stockout_extraitem";
		$this->sql_map = "stock/outstock/extraitemSql.php";
		parent::__construct ();
	}
 }
?>