<?php
/**
 * @author huangzf
 * @Date 2012��7��19�� ������ 10:43:44
 * @version 1.0
 * @description:ϵͳ��������ö�� Model�� 
 */
class model_system_configenum_configenum extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_system_configenum";
		$this->sql_map = "system/configenum/configenumSql.php";
		parent::__construct ();
	}
	
	/**
	 * 
	 * ��ȡĳ������ĳ��ֵ
	 * @param  $id
	 * @param  $fieldName
	 */
	function getEnumFieldVal($id, $fieldName) {
		$object = $this->get_d ( $id );
		return $object [$fieldName];
	}
}
?>