<?php

/**
 *portlet���� model��
 */
class model_system_portal_portlettype extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_portlet_type";
		$this->sql_map = "system/portal/portlettypeSql.php";
		parent :: __construct();
	}
	/*
	**��дɾ������
	*/
	function deletes($ids) {
		$default = $this->findBy("parentId",$ids);
		if($default){
			throw new Exception ( "���ڵ�δɾ�����ӽڵ㲻��ɾ����" );
		}
		if (! mysql_query ( "delete from " . $this->tbl_name . " where id in(" . $ids . ")" )) {
			throw new Exception ( mysql_error () );
		}
		return true;

	}


}
?>