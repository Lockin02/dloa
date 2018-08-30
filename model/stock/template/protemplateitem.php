<?php
/**
 * @author Show
 * @Date 2013年8月2日 星期五 14:41:42
 * @version 1.0
 * @description:物料模板配置明细表 Model层 
 */
 class model_stock_template_protemplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_templateitem";
		$this->sql_map = "stock/template/protemplateitemSql.php";
		parent::__construct ();
	}     
 }
?>