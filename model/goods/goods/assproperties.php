<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:16:15
 * @version 1.0
 * @description:属性不可见性关系 Model层 
 */
 class model_goods_goods_assproperties  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_properties_assproperties";
		$this->sql_map = "goods/goods/asspropertiesSql.php";
		parent::__construct ();
	}     }
?>