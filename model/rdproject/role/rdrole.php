<?php
/**
 * @description: �Ŷӽ�ɫModel
 * @author chengl
 * @version V1.0
 */
class model_rdproject_role_rdrole extends model_treeNode {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:46:46
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_team_role";
		$this->sql_map = "rdproject/role/rdroleSql.php";
		$this->treeCondFields = array ('projectType', 'projectId' ); //Ĭ�ϸ�����Ŀ���͸���Ŀid�����γɶ����
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------����Ϊ�ӿڷ���,����Ϊ����ģ��������--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 1.��ȡĳһҳ��ɫ  2.��ȡ��ҳ��ɫ�µ����������ɫ
	 * @param searchArr ��Ŀ����Ŀ��ϵ�����������keyΪ�����������ƣ�valueΪ����ֵ
	 * @return Array
	 */
	function pageAll_d($searchArr) {
		//��ȡ��ϵ�һ����ҳ
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
	 * @desription �����ɫ��Ȩ�޹�����ϵ
	 * @param $roleId ��ɫId
	 * @param $perms Ȩ������
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
	 * @desription ���ݶ����ɫid��ȡȨ������
	 * @param $roleId ��ɫId
	 */
	function getAuthorizeByRoleId_d($roleIds) {
		$sql = "select roleId,permCode from oa_rd_team_role_perm where roleId in (" . $roleIds . ")";
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 * @desription ������Ŀ���ͻ�ȡ��ɫ��Ȩ��(��ѡ)
	 * @param $projectType ��Ŀ����
	 */
	function getRolesByProjectType_d($projectType, $isPerm = false) {
		$this->searchArr = array ("projectType" => $projectType, "isTemplate" => 1 );
		$this->sort = 'lft';
		$this->asc = false; //ʹ����ֵ�����ڲ����ɫ��ʱ��ȷ���Ȳ��븸�׽ڵ㣬�ٲ����ӽڵ㣨�ӽڵ���Ҫ��ȡ���²���ĸ��׽ڵ㣩
		$roles = $this->list_d ();
		//�����Ҫ��ɫȨ�ޣ����ݽ�ɫ��ȡȨ����Ϣ
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
	 * @desription ������Ŀ����Ŀ���ͱ����ɫ��Ȩ�ޣ��ⲿ�ӿڣ�
	 * @param roles ��ɫ����
	 */
	function saveRolesAndPermsByProject_d($project) {
		try {
			$this->start_d ();
			//������Ŀ���ͻ�ȡ���н�ɫ��Ȩ��
			$roles = $this->getRolesByProjectType_d ( $project ['projectType'], true );
			$rolesInDBArr = array (); //���ڴ洢�Ѿ���ӵ����ݿ�Ľ�ɫ
			//������ӽ�ɫ��Ȩ��
			if (is_array ( $roles )) {
				foreach ( $roles as $key => $role ) {
					//ȥ������嵽���ݿ������
					unset ( $roles [$key] ['id'] );
					unset ( $roles [$key] ['text'] );
					unset ( $roles [$key] ['leaf'] );
					$roles [$key] ['isTemplate'] = 0;
					$roles [$key] ['projectId'] = $project ['id'];
					$roleId = $this->addBase_d ( $roles [$key], true );
					//�����ʱ����Ҫ��̬����parentId
					foreach ( $rolesInDBArr as $k => $v ) {
						//��ֵ����ӽڵ���ֵС����ֵ����ӽڵ���ֵ��,��Ϊ����ڵ����N���ڵ�
						if (($v ['lft'] < $role ['lft']) && ($v ['rgt'] > $role ['rgt'])) {
							$sql = "update " . $this->tbl_name . " set parentId=" . $v ['id'] . ",parentName='" . $v ['roleName'] . "' where id=" . $roleId; //���¸���id
							//echo $sql;
							$this->_db->query ( $sql );
						}
					}

					$roles [$key] ['id'] = $roleId;
					$role ['id'] = $roleId;
					$rolesInDBArr [] = $role;

					//���ĸ��Ƶ�Ȩ�޵�roleId
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
			//���ڲ����ɫ��Ҫ���ؽ�ɫid��1.�������ø��ӽ�ɫ 2.���ڻ�ȡȨ��  ���ҿ��ǵ�һ����Ŀ���ͽ�ɫ����̫�࣬����ʹ����ͨ����
			//$this->addBatch_d ( $roles );
			//$sql = "update oa_rd_team_role set parentId=(select id from oa_rd_team_role where lft=lft-1)";
			$this->commit_d ();

		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}

	}

	/**
	 * @desription ���ݳ�Ա��ȡ��ɫ�б�
	 * @param $memberId �Ŷӳ�Աid(���Դ�������Աid����,����)
	 * @return $roles ����һ����ά���飬��һά��Ա���ڶ�ά��ɫ
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
	 * @desription �жϵ�ǰ��¼�˺���ĳ����Ŀ���Ƿ�ӵ��ĳ��/ĳЩ����
	 * @param $projectId ��ĿId
	 * @param $operate Ȩ�ޱ��� (���Դ�����Ȩ�ޱ��룬��,����)
	 * @return true ��Ȩ��   fale��Ȩ��
	 */
	function isCurUserPerm($projectId, $permCode) {
		return $this->isPerm ( $projectId, $_SESSION ['USER_ID'], $permCode );
	}

	/**
	 * @desription �ж�ĳ���˺���ĳ����Ŀ���Ƿ�ӵ��ĳ��/ĳЩ����
	 * @param $projectId ��ĿId
	 * @param $userId �˺�id
	 * @param $operate Ȩ�ޱ��� (���Դ�����Ȩ�ޱ��룬��,����)
	 * @return true ��Ȩ��   fale��Ȩ��
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
