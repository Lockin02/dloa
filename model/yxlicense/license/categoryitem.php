<?php
/**
 * @author sony
 * @Date 2013��9��9�� 11:12:38
 * @version 1.0
 * @description: Model��
 */
class model_yxlicense_license_categoryitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_license_category_item";
		$this->sql_map = "yxlicense/license/categoryitemSql.php";
		parent::__construct ();
	}
	
	/**
	 * ��ȡ�����Ӧ������
	 */
	function getCategoryItemInfo_d($obj){
		$arr = array();
		$sql = "select * from ".$this->tbl_name." where categoryId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
	/**
	 * ��ȡ����
	 */
	function getCategoryItemCount_d($obj){
		$arr = array();
		$sql = "select count(categoryId) as num from ".$this->tbl_name." where categoryId = '$obj'";
		$data = $this->_db->getArray($sql);
		return $data;
	}
}
?>