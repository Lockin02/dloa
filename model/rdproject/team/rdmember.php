<?php
/*
 * Created on 2010-9-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_team_rdmember extends model_base {
	function __construct() {
		$this->tbl_name = "oa_rd_team_member";
		$this->sql_map = "rdproject/team/rdmemberSql.php";
		parent::__construct ();
	}

	/******************************ҳ����ʾ********************************/
	/**
	 * Ĭ����ʾ�б�
	 */
	function showlist($rows,$isPerm) {
		if ($rows) {
			$i = 0;
			$str = null;
			$roleDao = new model_rdproject_role_rdrole ();
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
				if ($val ['isInternal'] == 1) { //������ڲ���Ա
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
					if( $isPerm ){
						$changeRoleStr = '<a href="javascript:toChangeRoles(' . $val [id] . ')"  title="�任��ɫ">�任��ɫ</a>';
					}
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
							<a id="save$val[id]" href="javascript:saveRole($val[id])" style="display:none">����</a>
							<a id="cancel$val[id]" href="javascript:cancelRole($val[id])" style="display:none">ȡ��</a>
						</td>
						<td>
							$changeRoleStr
							<a href="?model=rdproject_team_rdmember&action=init&id=$val[id]&perm=edit&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600"
								class="thickbox" title="�༭">�༭</a>
							<a href="#" onclick="delFun( $val[id] );" title="ȷ��ɾ��">ɾ��</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="20">���������Ϣ</td></tr>';
		}
		return $str;
	}

	/**
	 * �鿴�б�
	 */
	function showviewlist($rows) {
		if ($rows) {
			$i = 0;
			$str = null;
			$roleDao = new model_rdproject_role_rdrole ();
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
				if ($val ['isInternal'] == 1) { //������ڲ���Ա
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
							<a id="save$val[id]" href="javascript:saveRole($val[id])" style="display:none">����</a>
							<a id="cancel$val[id]" href="javascript:cancelRole($val[id])" style="display:none">ȡ��</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="20">���������Ϣ</td></tr>';
		}
		return $str;
	}

	/**************************���ݲ�����ֻ���ڲ�ʹ��***********************/

	/**
	 * ������ڲ���Ա��ʾ��ɫ��������ʾ��ɫ
	 */
	private function showThisMember($memberName, $isInternal = null) {
		if ($isInternal) {
			return $memberName;
		} else {
			return "<font color='blue'>" . $memberName . "</font>";
		}
	}

	/**************************ҵ��������ɱ��ⲿ����***********************/
	/**
	 * ���������Ŷӳ�Ա -- ��Ŀ��ʼ����༭��ʱ��
	 */
	function addByGroud($object) {
		$team = substr ( $object ['memberIds'], 0, - 1 );
		$teamName = substr ( $object ['memberNames'], 0, - 1 );
		$teamarr = explode ( ',', $team );
		$teamarr=array_flip($teamarr);
		$teamarr=array_flip($teamarr);
		$teamNameArr = explode ( ',', $teamName );
		$teamNameArr=array_flip($teamNameArr);
		$teamNameArr=array_flip($teamNameArr);
		try {
			$this->start_d ();
			$this->searchArr ['projectId'] =$object ['projectId'];
			$this->searchArr ['isInternal'] = 1;
			$member = $this->list_d ();
			if( is_array($member)&&count($member)>0 ){
				foreach( $member as $key=>$val ){
					foreach( $teamarr as $index=>$value ){
						if( $value==$val['memberId'] ){
							unset($teamarr[$index]);
							break;
						}
					}
				}
			}
//			$proteam = array();
//			foreach( $member as $key=>$val){
//				$proteam[$key]=$val['id'];
//			}
//			$proteamStr = implode(',',$proteam);
//			if($proteamStr){
//				$this->deletes_d($proteamStr);
//			}

			if(is_array($teamarr)&&count($teamarr)>0){
				$rdmemberRows = array();
				foreach ( $teamarr as $key=>$val ) {
					if($teamNameArr [$key]){
						$addrows ['projectName'] = $object ['projectName'];
						$addrows ['projectCode'] = $object ['projectCode'];
						$addrows ['projectId'] = $object ['projectId'];
						$addrows ['memberName'] = $teamNameArr [$key];
						$addrows ['memberId'] = $val;
						$addrows ['isInternal'] = $addrows ['isUsing'] = '1';
						$rdmemberRows[$key]=$addrows;
					}
				}
				$this->addBatch_d ( $rdmemberRows,true );
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * �����Ŷӳ�Ա-�ڲ� -- ��Ŀ��Աҳ��
	 */
	function addByGroudIndpt($object) {
		$team = substr ( $object ['memberIds'], 0, - 1 );
		$teamName = substr ( $object ['memberNames'], 0, - 1 );
		$teamarr = explode ( ',', $team );
		$teamNameArr = explode ( ',', $teamName );
		$i = 0;
		try {
			$this->start_d ();
			foreach ( $teamarr as $val ) {
				$addrows ['projectId'] = $object ['projectId'];
				$addrows ['memberId'] = $val;
				//����ͬ����Ŀ�ڲ���Ա�ظ�У��
				$this->searchArr ['projectId'] =$object ['projectId'];
				$this->searchArr ['memberId'] = $val;
				$member = $this->list_d ();
				if (is_array ( $member ) && count ( $member ) > 0) {
					//����ظ���ɶ������
				} else {
					$addrows ['memberName'] = $teamNameArr [$i];
					$addrows ['isInternal'] = $addrows ['isUsing'] = '1';
					$addrows = $this->addCreateInfo ( $addrows );
					parent::add_d ( $addrows );
				}
				$i ++;
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * ��дadd_d���� -�ⲿ
	 */
	function add_d($object) {
		$object = $this->addCreateInfo ( $object );
		$object ['isUsing'] = '1';
		$object ['isInternal'] = '0';
		$newId = $this->create ( $object );
		return $newId;
	}

	/**
	 * ɾ����Ŀ�ڳ�Ա
	 * ��������Ѿ��ϳ����ĳ�ֱ�Ӵ����ݿ�����ɾ�� update by chengl 2011-07-29
	 */
	function deleteMember($id) {
		$object ['id'] = $id;
		$object ['isUsing'] = '0';
		return parent::edit_d ( $object );
	}

	/**
	 * �����ɫ����Ա�Ĺ�����ϵ
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
	 * ������ĿID�����Ŷ�����
	 */
	function countMember($projectId) {
		if ($projectId) {
			$this->searchArr ['projectId'] = $projectId;
			$this->searchArr ['isUsing'] = 1;
			$rows = $this->listBySqlId ( 'countMember' );
			return $rows ['0'] ['num'];
		} else
			return 0;
	}

	/**
	 * ������ĿID������Ŀ��Ա
	 */
	function getMemberByProjectId($projectId) {
		$this->searchArr ['projectId'] = $projectId;
		return $this->listBySqlId ( 'onlyUser_list' );
	}

	/**
	 * ������ĿID����Ա���Ʒ�����Ŀ��ԱId
	 */
	function getMemberIdByName($projectId, $chargeName) {
		$searchArr = array ('projectId' => $projectId, 'memberName' => $chargeName );
		return $this->find ( $searchArr, null, 'memberId' );
	}
}
?>
