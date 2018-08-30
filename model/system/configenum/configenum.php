<?php
/**
 * @author huangzf
 * @Date 2012年7月19日 星期四 10:43:44
 * @version 1.0
 * @description:系统管理配置枚举 Model层 
 */
class model_system_configenum_configenum extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_system_configenum";
		$this->sql_map = "system/configenum/configenumSql.php";
		parent::__construct ();
	}
	
	/**
	 * 
	 * 获取某个配置某项值
	 * @param  $id
	 * @param  $fieldName
	 */
	function getEnumFieldVal($id, $fieldName) {
		$object = $this->get_d ( $id );
		return $object [$fieldName];
	}
}
?>