<?php
/**
 * �Ż�portletȨ�޿��Ʋ�
 */
class controller_system_portal_portletperm extends controller_base_action {

	function __construct() {
		$this->objName = "portletperm";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ȩҳ��
	 */
	 function c_toUserPerm(){
		$this->view ( 'userperm' );
	 }

	 /**
	 * ��ȡѡ�н�ɫ��portletȨ��
	 */
	 function c_getSelectRolePerms(){
	 	$service=$this->service;
	 	$service->searchArr['roleId']=$_POST['selectRoleId'];
		$rows=$service->list_d();
		echo util_jsonUtil :: encode ($rows);
	 }

	/**
	 * ��ȡѡ���˵�portletȨ��
	 */
	 function c_getSelectUserPerms(){
	 	$service=$this->service;
	 	$service->searchArr['userId']=$_POST['selectUserId'];
		$rows=$service->list_d();
		echo util_jsonUtil :: encode ($rows);
	 }



	 /**
	  * �����û���Ȩ�޹���
	  */
	 function c_savePerms(){
	 	if(!empty($_POST['selectUserId'])){
			$this->service->saveUserPerms($_POST['perms'],$_POST['selectUserId']);
	 	}else{
			$this->service->saveRolePerms($_POST['perms'],$_POST['selectRoleId']);
	 	}
	 }

 }
?>