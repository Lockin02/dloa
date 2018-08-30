<?php
/**
 * @description: ��ĿMode
 * @date 2010-9-13 ����05:25:55
 * @author oyzx
 * @version V1.0
 */
class model_rdproject_project_rdproject extends model_base {

	public $statusDao;

	/**
	 * @desription ���캯��
	 * @date 2010-9-13 ����05:26:49
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_project";
		$this->sql_map = "rdproject/project/rdprojectSql.php";
		parent::__construct ();

		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (array ("statusEName" => "save", "statusCName" => "����", "key" => "1" ), array ("statusEName" => "approval", "statusCName" => "������", "key" => "2" ), array ("statusEName" => "locking", "statusCName" => "����", "key" => "3" ), array ("statusEName" => "fightback", "statusCName" => "���", "key" => "4" ), array ("statusEName" => "wite", "statusCName" => "��ִ��", "key" => "5" ), array ("statusEName" => "execute", "statusCName" => "ִ����", "key" => "6" ), array ("statusEName" => "end", "statusCName" => "���", "key" => "7" ), array ("statusEName" => "close", "statusCName" => "�ر�", "key" => "8" ) );

	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ��ʾ��Ŀ�б�
	 * @param tags
	 * @return return_type
	 * @date 2010-9-17 ����10:42:30
	 */
	function rpPage_s($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ( $rows as $key => $val ) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							$val[managerName]
						</td>
						<td>
							$val[statusCN]
						</td>
						<td>
							$val[effortRate]
						</td>
						<td>
							$val[warpRate]
						</td>
						<td>
							$val[pointName]
						</td>
						<td>
							$val[projectCode]
						</td>
						<td>
							$val[projectTypeC]
						</td>
						<td>
							$val[planDateStart]
						</td>
						<td>
							$val[planDateClose]
						</td>
						<td>
							<a href='javascript:showOpenWin("?model=rdproject_project_rdproject&action=rpRead&pjId=$val[id]")' >�鿴</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription ��ʾ�������б�
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 ����08:01:41
	 */
	function rpApprovalNo_s($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ( $rows as $key => $val ) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[simpleName]
						</td>
						<td>
							$val[managerName]
						</td>
						<td>
							$val[projectTypeC]
						</td>
						<td>
							$val[projectLevelC]
						</td>
						<td>
							$val[groupSName]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='controller/rdproject/project/ewf_index.php?actTo=ewfExam&taskId=$val[wTask]&spid=$val[pId]&billId=$val[id]'>����</a> |
							<a href='javascript:showOpenWin("?model=rdproject_project_rdproject&action=rpRead&pjId=$val[id]")'>�鿴</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription ��ʾ�������б�
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 ����08:01:41
	 */
	function rpApprovalYes_s($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ( $rows as $key => $val ) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[simpleName]
						</td>
						<td>
							$val[managerName]
						</td>
						<td>
							$val[projectTypeC]
						</td>
						<td>
							$val[projectLevelC]
						</td>
						<td>
							$val[groupSName]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='javascript:showOpenWin("?model=rdproject_project_rdproject&action=rpRead&pjId=$val[id]")'>�鿴</a> |
							<a href="controller/common/readview.php?itemtype=oa_rd_project&pid=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750" class='thickbox'>��������</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ�����ֵ�ӿڷ���,����Ϊ����ģ��������--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription �����б����ݷ���
	 * @param tags
	 * @date 2010-9-26 ����07:20:57
	 */
	function rpApprovalPage_d() {
		$this->groupBy = " c.id ";
		$rows = $this->pageBySqlId ( 'select_Approval' );
		if (is_array ( $rows )) {
			$rows = $this->datadictArrName ( $rows, 'projectType', 'projectTypeC', 'YFXMGL' );
			$rows = $this->datadictArrName ( $rows, 'projectLevel', 'projectLevelC', 'XMYXJ' );
		}
		return $rows;
	}

	/**
	 * @desription ͨ����ĿId��ȡĿ��ϸ��Ϣ
	 * @param tags
	 * @return return_type
	 * @date 2010-9-16 ����03:07:53
	 */
	function rpArrById_d($id) {
		$searchArr = array ('rpid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_readAll' );
		$memberDao = new model_rdproject_team_rdmember ();
		$pointDao = new model_rdproject_milestone_rdmilespoint ();
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$rows [$key] ['statusCN'] = $this->statusDao->statusKtoC ( $rows [$key] ['status'] );
				$rows [$key] ['sn'] = "project" . $rows [$key] ['id'];
				$rows [$key] ['oParentId'] = "-1";
				$rows [$key] ['leaf'] = "0";
				$rows [$key] ['projectId'] = $rows [$key] ['id'];
				$rows [$key] ['oid'] = "p_" . $rows [$key] ['id'];
				$rows [$key] ['countMember'] = $memberDao->countMember ( $id );

				$arrWarpRate = $this->warpRateImg_d ( $rows [$key] ['planDateStart'], $rows [$key] ['planDateClose'], $rows [$key] ['actBeginDate'], $rows [$key] ['actEndDate'], $rows [$key] ['warpRate'] );
				$rows [$key] ['warpRate'] = $arrWarpRate ['warpRate'];
				$rows [$key] ['warpRateMig'] = $arrWarpRate ['warpRateMig'];
				$rows [$key] ['effortRate'] = $rows [$key] ['effortRate'] . "%";
			}
			$rows = $this->datadictArrName ( $rows, 'projectType', 'projectTypeC', 'YFXMGL' );
			$rows = $this->datadictArrName ( $rows, 'projectLevel', 'projectLevelC', 'XMYXJ' );
			$rows = $pointDao->rmPjMiles_d ( $rows );
		}
		//print_r($rows);
		return $rows;
	}
	function rpParentById_d($id) {
		$parentObj = new model_rdproject_group_rdgroup ();
		$parentObj->searchArr = array ('rgid' => $id );
		$parentInfo = $parentObj->listBySqlId ();
		return $parentInfo;

	}
	/**
	 * @desription ͨ��Id��ȡ�����뾭�������
	 * @param tags
	 * @date 2010-10-18 ����09:45:14
	 */
	function rpManageUserById_d($id) {
		$searchArr = array ('rpid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_ManageUser' );
		$arr1 = explode ( ",", substr ( $rows ['0'] ['assistantId'], 0, - 1 ) );
		$key = 0;
		if (isset ( $arr1 ['0'] ) && $arr1 ['0'] != "") {
			$arr2 = explode ( ",", substr ( $rows ['0'] ['assistantName'], 0, - 1 ) );
			$arr = array ();
			foreach ( $arr1 as $key => $val ) {
				$arr [$key] ['userId'] = $val;
				$arr [$key] ['name'] = $arr2 [$key];
				$arr [$key] ['type'] = "zhuli";
			}
		}
		$arr [$key + 1] ['userId'] = $rows ['0'] ['managerId'];
		$arr [$key + 1] ['name'] = $rows ['0'] ['managerName'];
		$arr [$key + 1] ['type'] = "jinli";
		return $arr;
	}

	/**
	 * @desription ��ȡ��Ŀ�б�
	 * @return return_type
	 * @date 2010-9-17 ����10:23:22
	 */
	function rpPage_d() {
		$this->groupBy = " c.id ";
		$arr = $this->pageBySqlId ( 'select_center_page' );
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['statusCN'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
				$arr [$key] ['sn'] = "project" . $arr [$key] ['id'];
				$arr [$key] ['oParentId'] = "-1";
				$arr [$key] ['leaf'] = "0";
				$arr [$key] ['projectId'] = $arr [$key] ['id'];
				$arr [$key] ['oid'] = "p_" . $arr [$key] ['id'];

				$arrWarpRate = $this->warpRateImg_d ( $arr [$key] ['planDateStart'], $arr [$key] ['planDateClose'], $arr [$key] ['actBeginDate'], $arr [$key] ['actEndDate'], $arr [$key] ['warpRate'] );
				$arr [$key] ['warpRate'] = $arrWarpRate ['warpRate'];
				$arr [$key] ['warpRateMig'] = $arrWarpRate ['warpRateMig'];
				$arr [$key] ['effortRate'] = $arr [$key] ['effortRate'] . "%";
			}
			$arr = $this->datadictArrName ( $arr, 'projectType', 'projectTypeC', 'YFXMGL' );
			$arr = $this->datadictArrName ( $arr, 'projectLevel', 'projectLevelC', 'XMYXJ' );
			$arr = $this->filterField ( '�ֶ�����', $arr, 'list' );
		}

		return $arr;
	}

	/**
	 * @desription ��ȡ��Ŀ�б�
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 ����04:03:20
	 */
	function rpList_d() {
		//$this->groupBy = " c.id ";
		$arr = $this->pageBySqlId ( 'select_center_page' );
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['statusCN'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
				$arr [$key] ['leaf'] = "1";
				//TODO:
				$arr [$key] ['projectId'] = $arr [$key] ['id'];
				$arr [$key] ['parentId'] = $arr [$key] ['groupId'];
				$arr [$key] ['warpRateVal'] = $arr [$key] ['warpRate'] . "%";

				$arr [$key] ['pid'] = $arr [$key] ['id'];
				$arr [$key] ['oid'] = "p_" . $arr [$key] ['id']; //��p-Ϊǰ׺����Ϊ��Ŀ
				$arr [$key] ['oParentId'] = "g_" . $arr [$key] ['groupId'];

				$arrWarpRate = $this->warpRateImg_d ( $arr [$key] ['planDateStart'], $arr [$key] ['planDateClose'], $arr [$key] ['actBeginDate'], $arr [$key] ['actEndDate'], $arr [$key] ['warpRate'] );
				$arr [$key] ['warpRate'] = $arrWarpRate ['warpRate'];
				$arr [$key] ['warpRateMig'] = $arrWarpRate ['warpRateMig'];
				$arr [$key] ['effortRate'] = $arr [$key] ['effortRate'] . "%";
			}
			$arr = $this->datadictArrName ( $arr, 'projectType', 'projectTypeC', 'YFXMGL' );
			$arr = $this->datadictArrName ( $arr, 'projectLevel', 'projectLevelC', 'XMYXJ' );
		}
		//		echo "<pre>";
		//		print_r($arr);
		return $arr;
	}

	/**
	 * @desription
	 * @date 2010-10-13 ����06:31:16
	 */
	function warpRateImg_d($planB = '', $planE = '', $actB = '', $actE = '', $warpRate = '') {
		if (isset ( $warpRate ) && $warpRate != "" && isset ( $actE ) && $actE != "") {
			if ($warpRate <= 0) {
				$warpRateMig = "<img src='images/icon/icon072.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			} else if ($warpRate > 0 && $warpRate < 50) {
				$warpRateMig = "<img src='images/icon/icon071.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			} else {
				$warpRateMig = "<img src='images/icon/icon070.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			}
		} else {
			$aa = (strtotime ( $planE ) - strtotime ( $planB ));
			$aa = $aa != 0 ? $aa : (strtotime ( "+24 hours" ) - strtotime ( "now" ));
			$actB = isset ( $actB ) ? $actB : $planB;
			$warpRate = (((strtotime ( date ( "Y-m-d" ) ) - strtotime ( $actB )) / $aa) - 1) * 100;
			if ($warpRate <= 0) {
				$warpRate = "0.00";
				$warpRateMig = "<img src='images/icon/icon072.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			} else if ($warpRate > 0 && $warpRate < 50) {
				$warpRateMig = "<img src='images/icon/icon071.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			} else {
				$warpRateMig = "<img src='images/icon/icon070.gif' title='ƫ���ʣ�" . $warpRate . "%,�̵� x=0%;�Ƶ� 50%>x>0%;��� x>50%'>";
			}
		}
		return array ("warpRate" => sprintf ( "%01.2f", $warpRate ) . "%", "warpRateMig" => $warpRateMig );
	}

	/**
	 * @desription �����ȡ�����ֵ�ķ���
	 * @param tags
	 * @date 2010-10-6 ����11:56:10
	 */
	function datadictArrName($pjArr, $code, $codeC, $datadictCode) {
		$datadictDao = new model_system_datadict_datadict ();
		$datadictArr = $datadictDao->getDatadictsByParentCodes ( $datadictCode );
		foreach ( $pjArr as $key => $val ) {
			foreach ( $datadictArr [$datadictCode] as $keyD => $valD ) {
				if ($valD ['dataCode'] == $pjArr [$key] [$code]) {
					$pjArr [$key] [$codeC] = $valD ['dataName'];
				}
			}
		}
		//		echo "<pre>";
		//		print_r($pjArr);
		return $pjArr;
	}

	/**
	 * @desription ������Ŀ
	 * @param $node ���뱣������ڵ�
	 */
	public function rpAdd_d($node) {
		try {
			$this->start_d ();
			//�ж��Ƿ������id
			if (! empty ( $node ['groupId'] )) {
				$sql = "select rgt from oa_rd_group where id=" . $node ['groupId'];
				$rs = $this->_db->get_one ( $sql );
				$parentNodeRgt = $rs ['rgt'] - 1;
				//������ֵ���ڸ��ڵ���ֵ����ֵ��2
				$sql = "update " . $this->tbl_name . " set lft=lft+2 where lft>" . $parentNodeRgt;
				$this->query ( $sql );
				$sql = "update oa_rd_group set lft=lft+2 where lft>" . $parentNodeRgt;
				$this->query ( $sql );
				//������ֵ���ڸ��ڵ���ֵ����ֵ��2
				$sql = "update " . $this->tbl_name . " set rgt=rgt+2 where rgt>" . $parentNodeRgt;
				$this->query ( $sql );
				$sql = "update oa_rd_group set rgt=rgt+2 where rgt>" . $parentNodeRgt;
				$this->query ( $sql );
				$node ['lft'] = $parentNodeRgt + 1;
				$node ['rgt'] = $parentNodeRgt + 2;
			} else {
				$node ['groupId'] = PARENT_ID;
				$node ['groupSName'] = "��ϸ��ڵ�";
			}
			$node ['actBeginDate'] = date ( "Y-m-d" );
			$newId = parent::add_d ( $node, true );

			$node ['id'] = $newId;
			//��ȡ��Ŀ�����µ���̱�
			$milestoneinfoDao = new model_rdproject_baseinfo_rdmilestoneinfo ();
			$milArr = $milestoneinfoDao->returnMilestoneInfo_d ( $node ['projectType'] );

			$milestonDao = new model_rdproject_milestone_rdmilestone ();
			$milestonDao->rmAdd ( $milArr, $node );

			$role = new model_rdproject_role_rdrole ();
			$role->saveRolesAndPermsByProject_d ( $node );

			//�����ĵ�ģ�� add by chengl 2011-06-01
			$temDao = new model_rdproject_uploadfile_template ();
			$temDao->copyUploadFileTemplate ( $node );
			//			$this->rollBack();
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			echo "�쳣*********************";
			$this->rollBack ();
			return null;
		}
	}

	public function rpGroupTreeSql_d($seachArr) {
		//$service->resetParam();
		$this->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r( $this->searchArr );
		$rows = $this->pageBySqlId ( 'select_center_page' );
		//		echo "<pre>";
		//		print_r($rows);
		$sql = " ( ";
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$val ['lft'];
				$val ['rgt'];
				$sql .= " ( c.lft<" . $val ['lft'] . " and c.rgt>" . $val ['rgt'] . " ) or";
			}
			$sql = substr ( $sql, 0, - 2 ) . " ) ";
		} else {
			$sql = null;
		}
		return $sql;
	}

	/**
	 * @desription ͨ����ĿId��ӹ�����
	 * @param tags
	 * @date 2010-10-18 ����07:42:34
	 */
	function rpAddWorkloadById_d($pjId, $val) {
		try {
			$sql = " update oa_rd_project set putWorkload=putWorkload+" . $val . " where id='" . $pjId . "' ";
			$this->query ( $sql );
			return true;
		} catch ( Exception $e ) {
			echo "�쳣*********************";
			throw $e;
			return false;
		}
	}

	/**
	 * ������Ŀ�б����ݷ���(����û����Ŀ��ϵ���Ŀ��һ����Ŀ���)
	 * add by chengl 2011-04-07
	 */
	function projectAndGroup_d() {
		//$rows = $this->page_d ();
		//$this->pageSize=999;
		$rows = $this->pageBySqlId ( "select_view" );
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if ($val ['type'] == 2) { //���
					$rows [$key] ['pid'] = $val ['id'];
					$rows [$key] ['oid'] = "g_" . $val ['id']; //��g-Ϊǰ׺����Ϊ���
					$rows [$key] ['oParentId'] = "g_" . $val ['parentId'];
				} else { //��Ŀ
					$rows [$key] ['statusCN'] = $this->statusDao->statusKtoC ( $val ['status'] );
					$rows [$key] ['leaf'] = "1";
					//TODO:
					$rows [$key] ['projectId'] = $val ['id'];
					$rows [$key] ['parentId'] = $val ['groupId'];
					$rows [$key] ['warpRateVal'] = $val ['warpRate'] . "%";

					$rows [$key] ['pid'] = $val ['id'];
					$rows [$key] ['oid'] = "p_" . $val ['id']; //��p-Ϊǰ׺����Ϊ��Ŀ
					$rows [$key] ['oParentId'] = "g_" . $val ['parentId'];

					$arrWarpRate = $this->warpRateImg_d ( $val ['planDateStart'], $val ['planDateClose'], $val ['actBeginDate'], $val ['actEndDate'], $val ['warpRate'] );
					$rows [$key] ['warpRate'] = $arrWarpRate ['warpRate'];
					$rows [$key] ['warpRateMig'] = $arrWarpRate ['warpRateMig'];
					$rows [$key] ['effortRate'] = $val ['effortRate'] . "%";

				}
			}
			$rows = $this->datadictArrName ( $rows, 'projectType', 'projectTypeC', 'YFXMGL' );
			$rows = $this->datadictArrName ( $rows, 'projectLevel', 'projectLevelC', 'XMYXJ' );
		}

		//��ȡû����Ŀ��ϵ���Ŀ��Ϣ add by chengl 2011-04-07
		//		$projectDao = new model_rdproject_project_rdproject ();
		//		$projectDao->searchArr = array ("groupIdNull" => true );
		//		$projectRows = $projectDao->rpList_d ();
		//		$rows = model_common_util::yx_array_merge ( $projectRows, $rows );


		return $rows;
	}

	/**
	 * ����projectCode����projectId --rdtask����������������ҪprojectId
	 */
	function getProjectIdByProjectCode_d($projectCode) {
		//	 	$searchArr = array( 'projectCode'=>$projectCode );
		$row = $this->findBy ( 'projectCode', $projectCode );
		//		echo $row['id'];
		return $row ['id'];
	}

}

?>
