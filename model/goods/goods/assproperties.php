<?php
/**
 * @author Administrator
 * @Date 2012��3��1�� 20:16:15
 * @version 1.0
 * @description:���Բ��ɼ��Թ�ϵ Model�� 
 */
 class model_goods_goods_assproperties  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_properties_assproperties";
		$this->sql_map = "goods/goods/asspropertiesSql.php";
		parent::__construct ();
	}     }
?>