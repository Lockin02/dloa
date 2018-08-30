<?php
/**
 * 门户portlet权限控制层
 */
class controller_system_portal_portletperm extends controller_base_action {

	function __construct() {
		$this->objName = "portletperm";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }

	/**
	 * 跳转到授权页面
	 */
	 function c_toUserPerm(){
		$this->view ( 'userperm' );
	 }

	 /**
	 * 获取选中角色的portlet权限
	 */
	 function c_getSelectRolePerms(){
	 	$service=$this->service;
	 	$service->searchArr['roleId']=$_POST['selectRoleId'];
		$rows=$service->list_d();
		echo util_jsonUtil :: encode ($rows);
	 }

	/**
	 * 获取选中人的portlet权限
	 */
	 function c_getSelectUserPerms(){
	 	$service=$this->service;
	 	$service->searchArr['userId']=$_POST['selectUserId'];
		$rows=$service->list_d();
		echo util_jsonUtil :: encode ($rows);
	 }



	 /**
	  * 保存用户与权限关联
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