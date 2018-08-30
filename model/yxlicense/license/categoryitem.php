<?php
/**
 * @author sony
 * @Date 2013年9月9日 11:12:38
 * @version 1.0
 * @description: Model层
 */
class model_yxlicense_license_categoryitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_license_category_item";
		$this->sql_map = "yxlicense/license/categoryitemSql.php";
		parent::__construct ();
	}
	
	/**
	 * 获取主表对应的数据
	 */
	function getCategoryItemInfo_d($obj){
		$arr = array();
		$sql = "select * from ".$this->tbl_name." where categoryId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
	/**
	 * 获取条数
	 */
	function getCategoryItemCount_d($obj){
		$arr = array();
		$sql = "select count(categoryId) as num from ".$this->tbl_name." where categoryId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
}
?>