<?php
/**
 * @author huangzf
 * @Date 2012年3月1日 20:16:01
 * @version 1.0
 * @description:数据项级联关系 Model层 
 */
 class model_goods_goods_assitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_properties_assitem";
		$this->sql_map = "goods/goods/assitemSql.php";
		parent::__construct ();
	}     }
?>