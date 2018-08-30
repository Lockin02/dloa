<?php

/**
 *portlet权限 model层
 */
class model_system_portal_portletperm extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_portlet_perm";
		$this->sql_map = "system/portal/portletpermSql.php";
		parent :: __construct();
	}

	 /**
	  * 保存用户与权限关联
	  */
	function saveUserPerms($perms,$selectUserId){
		try {
			$this->start_d ();
			$this->delete(array(
				"userId"=>$selectUserId
			));
			$permsArr=explode(",",$perms);
			$userDao=new model_deptuser_user_user();
			$user=$userDao->getUserById($selectUserId);
			foreach($permsArr as $key=>$val){
				$perm=array(
					"userId"=>$selectUserId,
					"userName"=>$user['USER_NAME'],
					"portletId"=>$val
				);
				$this->add_d($perm,true);
			}
			$this->commit_d ();
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	  * 保存角色与权限关联
	  */
	function saveRolePerms($perms,$selectRoleId){
		try {
			$this->start_d ();
			$this->delete(array(
				"roleId"=>$selectRoleId
			));
			$permsArr=explode(",",$perms);
			$jobsDao=new model_deptuser_jobs_jobs();
			$role=$jobsDao->get_d($selectRoleId);
			foreach($permsArr as $key=>$val){
				$perm=array(
					"roleId"=>$selectRoleId,
					"roleName"=>$role['name'],
					"portletId"=>$val
				);
				$this->add_d($perm,true);
			}
			$this->commit_d ();
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}


}
?>