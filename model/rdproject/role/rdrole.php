<?php
/**
 * @description: 团队角色Model
 * @author chengl
 * @version V1.0
 */
class model_rdproject_role_rdrole extends model_treeNode {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:46:46
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_team_role";
		$this->sql_map = "rdproject/role/rdroleSql.php";
		$this->treeCondFields = array ('projectType', 'projectId' ); //默认根据项目类型跟项目id分组形成多颗树
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 1.获取某一页角色  2.获取该页角色下的所有子孙角色
	 * @param searchArr 项目跟项目组合的搜索条件，key为搜索条件名称，value为搜索值
	 * @return Array
	 */
	function pageAll_d($searchArr) {
		//获取组合第一级分页
		$this->searchArr = $searchArr;
		$this->searchArr ["parentId"] = PARENT_ID;
		if (empty ( $searchArr ['projectId'] )) {
			$this->searchArr ["projectNullId"] = true;
		}
		$firstGroups = $this->page_d ();
		$datadictDao = new model_system_datadict_datadict ();
		foreach ( $firstGroups as $key => $val ) {
			$projectTypeName = $datadictDao->getDataNameByCode ( $val ['projectType'] );
			$firstGroups [$key] ['projectTypeName'] = $projectTypeName;
		}
		if (is_array ( $firstGroups )) {
			$sunNodes = $this->getChildrenByNodes ( $firstGroups );
			$mergeGroups = model_common_util::yx_array_merge ( $firstGroups, $sunNodes );
			//$mergeGroups = array_unique ($mergeGroups);
			foreach ( $mergeGroups as $key => $val ) {
				$projectTypeName = $datadictDao->getDataNameByCode ( $val ['projectType'] );
				$mergeGroups [$key] ['projectTypeName'] = $projectTypeName;
			}

		} else {
			$mergeGroups = array ();
		}
		return $mergeGroups;
	}

	/**
	 * @desription 保存角色跟权限关联关系
	 * @param $roleId 角色Id
	 * @param $perms 权限数组
	 */
	function saveAuthorize_d($roleId, $perms) {
		try {
			$this->start_d ();
			$sql = "delete from oa_rd_team_role_perm where roleId=" . $roleId;
			$this->_db->query ( $sql );
			if (is_array ( $perms )) {
				foreach ( $perms as $key => $value ) {
					$sql = "insert into oa_rd_team_role_perm (roleId,permCode) values(" . $roleId . ",'" . $value . "')";
					$this->_db->query ( $sql );
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			msg ( $e->getMessage () );
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @desription 根据多个角色id获取权限数组
	 * @param $roleId 角色Id
	 */
	function getAuthorizeByRoleId_d($roleIds) {
		$sql = "select roleId,permCode from oa_rd_team_role_perm where roleId in (" . $roleIds . ")";
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 * @desription 根据项目类型获取角色及权限(可选)
	 * @param $projectType 项目类型
	 */
	function getRolesByProjectType_d($projectType, $isPerm = false) {
		$this->searchArr = array ("projectType" => $projectType, "isTemplate" => 1 );
		$this->sort = 'lft';
		$this->asc = false; //使用左值升序，在插入角色的时候确保先插入父亲节点，再插入子节点（子节点需要获取到新插入的父亲节点）
		$roles = $this->list_d ();
		//如果需要角色权限，根据角色获取权限信息
		if ($isPerm == true) {
			$roleIds = "";
			if (is_array ( $roles )) {
				foreach ( $roles as $key => $value ) {
					$roleIds .= "," . $value ['id'];
				}
				$roleIds = substr ( $roleIds, 1 );
				$perms = $this->getAuthorizeByRoleId_d ( $roleIds );
				//print_r ( $perms );
				if (is_array ( $perms )) {
					foreach ( $perms as $key => $value ) {
						foreach ( $roles as $k => $v ) {
							if ($v ['id'] == $value ['roleId']) {
								if (! isset ( $roles [$k] ['perms'] )) {
									$roles [$k] ['perms'] = array ();
								}
								array_push ( $roles [$k] ['perms'], $value );
							}
						}
					}
				}
			}
		}
		return $roles;
	}

	/**
	 * @desription 根据项目及项目类型保存角色及权限（外部接口）
	 * @param roles 角色数组
	 */
	function saveRolesAndPermsByProject_d($project) {
		try {
			$this->start_d ();
			//根据项目类型获取所有角色及权限
			$roles = $this->getRolesByProjectType_d ( $project ['projectType'], true );
			$rolesInDBArr = array (); //用于存储已经添加到数据库的角色
			//批量添加角色及权限
			if (is_array ( $roles )) {
				foreach ( $roles as $key => $role ) {
					//去除无需插到数据库的属性
					unset ( $roles [$key] ['id'] );
					unset ( $roles [$key] ['text'] );
					unset ( $roles [$key] ['leaf'] );
					$roles [$key] ['isTemplate'] = 0;
					$roles [$key] ['projectId'] = $project ['id'];
					$roleId = $this->addBase_d ( $roles [$key], true );
					//插入的时候需要动态更新parentId
					foreach ( $rolesInDBArr as $k => $v ) {
						//左值比添加节点左值小，右值比添加节点右值大,即为插入节点的上N级节点
						if (($v ['lft'] < $role ['lft']) && ($v ['rgt'] > $role ['rgt'])) {
							$sql = "update " . $this->tbl_name . " set parentId=" . $v ['id'] . ",parentName='" . $v ['roleName'] . "' where id=" . $roleId; //更新父亲id
							//echo $sql;
							$this->_db->query ( $sql );
						}
					}

					$roles [$key] ['id'] = $roleId;
					$role ['id'] = $roleId;
					$rolesInDBArr [] = $role;

					//更改复制的权限点roleId
					if (isset ( $roles [$key] ['perms'] ) && is_array ( $roles [$key] ['perms'] )) {
						foreach ( $roles [$key] ['perms'] as $k => $v ) {
							$roles [$key] ['perms'] [$k] ['roleId'] = $roleId;
						}
						$this->tbl_name = "oa_rd_team_role_perm";
						$this->addBatch_d ( $roles [$key] ['perms'] );
						$this->tbl_name = "oa_rd_team_role";
					}
				}
			}
			//由于插入角色需要返回角色id，1.用于设置给子角色 2.用于获取权限  而且考虑到一个项目类型角色不会太多，所以使用普通插入
			//$this->addBatch_d ( $roles );
			//$sql = "update oa_rd_team_role set parentId=(select id from oa_rd_team_role where lft=lft-1)";
			$this->commit_d ();

		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}

	}

	/**
	 * @desription 根据成员获取角色列表
	 * @param $memberId 团队成员id(可以传入多个成员id，以,隔开)
	 * @return $roles 返回一个二维数组，第一维成员，第二维角色
	 */
	function getRolesByMemberId($memberIds) {
		$this->sort = "c.id";
		$this->searchArr = array ("memberIds" => $memberIds );
		$roles = $this->listBySqlId ( "roles_member" );
		$memberRoles = array ();
		if (is_array ( $roles )) {
			foreach ( $roles as $key => $value ) {
				if (is_array ( $memberRoles [$value ['memberId']] )) {
					array_push ( $memberRoles [$value ['memberId']], $value );
				} else {
					$memberRoles [$value ['memberId']] = array ($value );
				}
			}
		}
		return $memberRoles;
	}

	/**
	 * @desription 判断当前登录账号在某个项目上是否拥有某个/某些操作
	 * @param $projectId 项目Id
	 * @param $operate 权限编码 (可以传入多个权限编码，以,隔开)
	 * @return true 有权限   fale无权限
	 */
	function isCurUserPerm($projectId, $permCode) {
		return $this->isPerm ( $projectId, $_SESSION ['USER_ID'], $permCode );
	}

	/**
	 * @desription 判断某个账号在某个项目上是否拥有某个/某些操作
	 * @param $projectId 项目Id
	 * @param $userId 账号id
	 * @param $operate 权限编码 (可以传入多个权限编码，以,隔开)
	 * @return true 有权限   fale无权限
	 */
	function isPerm($projectId, $userId, $permCode) {
		$this->sort = "p.id";
		$this->searchArr = array ("rprojectId" => $projectId, "memberId" => $userId );
		$permCodes = $this->listBySqlId ( "role_perm" );
		if (is_array ( $permCodes )) {
			foreach ( $permCodes as $key => $value ) {
				if ($value ['permCode'] == $permCode) {
					//echo $permCode;
					return true;
				}
			}
		}
		return false;
	}

}

?>
