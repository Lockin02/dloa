<?php
/**
 * @author Show
 * @Date 2013��8��2�� ������ 14:41:42
 * @version 1.0
 * @description:����ģ��������ϸ�� Model�� 
 */
 class model_stock_template_protemplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_templateitem";
		$this->sql_map = "stock/template/protemplateitemSql.php";
		parent::__construct ();
	}     
 }
?>