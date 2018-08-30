<?php

/**
 *portlet类型 model层
 */
class model_system_portal_portlettype extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_portlet_type";
		$this->sql_map = "system/portal/portlettypeSql.php";
		parent :: __construct();
	}
	/*
	**重写删除方法
	*/
	function deletes($ids) {
		$default = $this->findBy("parentId",$ids);
		if($default){
			throw new Exception ( "父节点未删除，子节点不能删除！" );
		}
		if (! mysql_query ( "delete from " . $this->tbl_name . " where id in(" . $ids . ")" )) {
			throw new Exception ( mysql_error () );
		}
		return true;

	}


}
?>