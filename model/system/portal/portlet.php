<?php

/**
 *portlet model��
 */
class model_system_portal_portlet extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_portlet";
		$this->sql_map = "system/portal/portletSql.php";
		parent :: __construct();
	}


	/**
	 * ��ȡ�û���ɫ��portletȨ��
	 */
	function getUserRolePerm($userId,$roleId){
		$permDao=new model_system_portal_portletperm();
		$portletIdArr=array();

		//��ȡ��������Ȩ�޿��Ƶ�portlet
		$this->searchArr['isPerm']=0;
		$portlets=$this->list_d();
		foreach($portlets as $key=>$val){
			$portletIdArr[]=$val['id'];
		}

		$permDao->searchArr['userId']=$userId;
		$userPerms=$permDao->list_d();
		foreach($userPerms as $key=>$val){
			$portletIdArr[]=$val['portletId'];
		}

		unset($permDao->searchArr['userId']);
		$permDao->searchArr['roleId']=$roleId;
		$rolePerms=$permDao->list_d();
		foreach($rolePerms as $key=>$val){
			$portletIdArr[]=$val['portletId'];
		}
		$ids=join(",",$portletIdArr);

		unset($this->searchArr['isPerm']);
		$this->searchArr['ids']=$ids;
		return $this->page_d();
	}

}
?>