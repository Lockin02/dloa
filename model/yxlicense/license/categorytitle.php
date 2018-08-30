<?php
class model_yxlicense_license_categorytitle extends model_base {

	function __construct() {
		$this->tbl_name = "oa_license_category_title";
		$this->sql_map = "yxlicense/license/categorytitleSql.php";
		parent::__construct ();
	}
	
	/**
	 * 获取主表对应的数据
	 */
	function getCategoryItemInfo_d($obj){
		$arr = array();
		$sql = "select * from ".$this->tbl_name." where formId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
	/**
	 * 获取条数
	 */
	function getCategoryItemCount_d($obj){
		$arr = array();
		$sql = "select count(formId) as num from ".$this->tbl_name." where formId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
	/**
	 * 获取title表数据
	 */
	function getTitleName_d($id){
		$arr = array();
		$sql = "select id,titleName from ".$this->tbl_name." where formId = '$id' order by id asc";
		$data = $this->_db->getArray($sql);
		return $data;
	}
}
?>