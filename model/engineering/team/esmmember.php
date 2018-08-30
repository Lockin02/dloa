<?php
/*
 * Created on 2010-9-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_team_esmmember extends model_base {
	function __construct() {
		$this->tbl_name = "oa_esm_team_member";
		$this->sql_map = "engineering/team/esmmemberSql.php";
		parent::__construct ();
	}

	/******************************页面显示********************************/
	/**
	 * 默认显示列表
	 */
	function showlist($rows) {
		if ($rows) {
			$i = 0;
			$str = null;
			$roleDao = new model_engineering_role_rdrole ();
			$memberIds = "";
			foreach ( $rows as $val ) {
				$memberIds .= "," . $val ['id'];
			}
			if (! empty ( $memberIds )) {
				$memberIds = substr ( $memberIds, 1 );
			}
			$roles = $roleDao->getRolesByMemberId ( $memberIds );

			foreach ( $rows as $val ) {
				$i ++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$memberName = $this->showThisMember ( $val ['memberName'], $val ['isInternal'] );

				$memberRoles = $roles [$val ['id']];
				$rolesName = "";
				$rolesId = "";
				$changeRoleStr = "";
				if ($val ['isInternal'] == 1) { //如果是内部人员
					if (is_array ( $memberRoles )) {
						foreach ( $memberRoles as $k => $v ) {
							$rolesName .= "," . $v ['roleName'];
							$rolesId .= "," . $v ['id'];
						}
						if (! empty ( $rolesName )) {
							$rolesName = substr ( $rolesName, 1 );
							$rolesId = substr ( $rolesId, 1 );
						}
					}
					$changeRoleStr = '<a href="javascript:toChangeRoles(' . $val [id] . ')"  title="变换角色">变换角色</a>';
				}
				$str .= <<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$memberName
						</td>
						<td>
							<div name="roleNameDiv[$i]"  id="roleNameDiv$val[id]">$rolesName</div>
							<textarea cols="50" name="roleName[$i]"  id="roleName$val[id]" style="display:none">$rolesName</textarea>
							<input type="hidden" name="roleId[$i]" id="roleId$val[id]" value="$rolesId"/>
							<a id="save$val[id]" href="javascript:saveRole($val[id])" style="display:none">保存</a>
							<a id="cancel$val[id]" href="javascript:cancelRole($val[id])" style="display:none">取消</a>
						</td>
						<td>
							$changeRoleStr
							<a href="?model=engineering_team_esmmember&action=init&id=$val[id]&perm=edit&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600"
								class="thickbox" title="编辑">编辑</a>
							<a href="?model=engineering_team_esmmember&action=makeSureDelete&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300"
								class="thickbox" title="确认删除">删除</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="20">暂无相关信息</td></tr>';
		}
		return $str;
	}

	/**
	 * 查看列表
	 */
	function showviewlist($rows) {
		if ($rows) {
			$i = 0;
			$str = null;
			$roleDao = new model_engineering_role_rdrole ();
			$memberIds = "";
			foreach ( $rows as $val ) {
				$memberIds .= "," . $val ['id'];
			}
			if (! empty ( $memberIds )) {
				$memberIds = substr ( $memberIds, 1 );
			}
			$roles = $roleDao->getRolesByMemberId ( $memberIds );

			foreach ( $rows as $val ) {
				$i ++;
				$memberName = $this->showThisMember ( $val ['memberName'], $val ['isInternal'] );

				$memberRoles = $roles [$val ['id']];
				$rolesName = "";
				$rolesId = "";
				$changeRoleStr = "";
				if ($val ['isInternal'] == 1) { //如果是内部人员
					if (is_array ( $memberRoles )) {
						foreach ( $memberRoles as $k => $v ) {
							$rolesName .= "," . $v ['roleName'];
						}
						if (! empty ( $rolesName )) {
							$rolesName = substr ( $rolesName, 1 );
						}
					}
				}
				$str .= <<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$memberName
						</td>
						<td>
							<div name="roleNameDiv[$i]"  id="roleNameDiv$val[id]">$rolesName</div>
							<textarea cols="50" name="roleName[$i]"  id="roleName$val[id]" style="display:none">$rolesName</textarea>
							<input type="hidden" name="roleId[$i]" id="roleId$val[id]" value="$rolesId"/>
							<a id="save$val[id]" href="javascript:saveRole($val[id])" style="display:none">保存</a>
							<a id="cancel$val[id]" href="javascript:cancelRole($val[id])" style="display:none">取消</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="20">暂无相关信息</td></tr>';
		}
		return $str;
	}

	/**************************数据操作，只能内部使用***********************/

	/**
	 * 如果是内部成员显示黑色，否则显示蓝色
	 */
	private function showThisMember($memberName, $isInternal = null) {
		if ($isInternal) {
			return $memberName;
		} else {
			return "<font color='blue'>" . $memberName . "</font>";
		}
	}

	/**************************业务操作，可被外部调用***********************/
	/**
	 * 批量增加团队成员-内部
	 */
	function addByGroud($object) {
		//		print_r($object);
		$team = substr ( $object ['memberIds'], 0, - 1 );
		$teamName = substr($object ['memberNames'], 0, - 1);
		$teamarr = explode ( ',', $team );
		$teamNameArr = explode ( ',', $teamName );
		$i = 0;
//		$globalDao = new includes_class_global ();
		try {
			$this->start_d ();
			foreach ( $teamarr as $val ) {
//				$rows = $globalDao->GetUserinfo ( $val, array ('USER_NAME', 'EMAIL' ) );
				$addrows ['projectId'] = $object ['projectId'];
				$addrows ['memberId'] = $val;
//				$addrows ['memberName'] = $rows ['USER_NAME'];
				$addrows ['memberName'] = $teamNameArr [$i];
//				$addrows ['email'] = $rows ['EMAIL'];
				$addrows ['isInternal'] = $addrows ['isUsing'] = '1';
				$addrows = $this->addCreateInfo ( $addrows );
				parent::add_d ( $addrows );
				$i ++;
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
		//		print_r($rows);
	}

	/**
	 * 重写add_d函数 -外部
	 */
	function add_d($object) {
		$object = $this->addCreateInfo ( $object );
		$object ['isUsing'] = '1';
		$object ['isInternal'] = '0';
		$newId = $this->create ( $object );
		return $newId;
	}

	/**
	 * 删除项目内成员
	 */
	function deleteMember($id) {
		$object ['id'] = $id;
		$object ['isUsing'] = '0';
		$object = $this->addUpdateInfo ( $object );
		return parent::edit_d ( $object );
	}

	/**
	 * 保存角色跟成员的关联关系
	 */
	function saveRolesToMember_d($rolesId, $memberId) {
		try {
			$this->start_d ();
			$this->_db->query ( "delete from oa_rd_team_member_role where memberId=" . $memberId );
			if (! empty ( $rolesId )) {
				$roleArr = explode ( ",", $rolesId );
				if (is_array ( $roleArr )) {
					foreach ( $roleArr as $key => $roleId ) {
						$sql = "insert into oa_rd_team_member_role (roleId,memberId) values(" . $roleId . "," . $memberId . ")";
						$this->_db->query ( $sql );
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}

	}

	/**
	 * 根据项目ID返回团队人数
	 */
	function countMember($projectId) {
		if ($projectId) {
			$this->searchArr ['projectId'] = $projectId;
			$rows = $this->listBySqlId ( 'countMember' );
			return $rows ['0'] ['num'];
		} else
			return 0;
	}

	/**
	 * 根据项目ID返回项目成员
	 */
	function getMemberByProjectId($projectId){
		$this->searchArr['projectId'] = $projectId;
		return $this->listBySqlId('onlyUser_list');
	}
}
?>
