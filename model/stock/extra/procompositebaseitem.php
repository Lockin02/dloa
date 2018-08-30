<?php
/**
 * @author huangzf
 * @Date 2012年6月1日 星期五 16:54:00
 * @version 1.0
 * @description:产品物料库存采购销售综合表清单 Model层 
 */
 class model_stock_extra_procompositebaseitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_extra_procompositeitem";
		$this->sql_map = "stock/extra/procompositebaseitemSql.php";
		parent::__construct ();
	}     
 }
?>