<?php

/**
 * @author Show
 * @Date 2011��12��20�� ���ڶ� 15:23:55
 * @version 1.0
 * @description:��Ŀ��Ա(oa_esm_project_member) Model�� ��Ա����
 * 0 �ڲ�
 * 1 �ⲿ
 *
 * ��Ա״̬
 * 0 ��Ŀ��
 * 1 ���뿪
 */
class model_engineering_member_esmmember extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_member";
		$this->sql_map = "engineering/member/esmmemberSql.php";
		parent:: __construct();
	}

	/********************** ��ȡ�ⲿ��Ϣ�ӿ� *****************************/
	/**
	 * ��ȡ��Ŀ��Ա������Ϣ
	 */
	function getObjInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		return $esmprojectDao->find(array('id' => $projectId), null, 'id,projectCode,projectName,actBeginDate');
	}

	/*********************** �ڲ���Ϣ���� *********************************/

	/**
	 * ��������
	 * ��Ŀ�³�ʹ��
	 */
	function addAlong_d($object) {
		try {
			$this->start_d();

			//����
			$newId = parent::add_d($object, true);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//���roleIdΪ�գ�unset
			if (empty($object['roleId'])) {
				unset($object['roleId']);
				unset($object['roleName']);
			}

			//����
			$newId = parent::add_d($object, true);

			//������Ŀ������
			$this->updateProject_d($object['projectId']);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ��д�༭
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//���roleIdΪ�գ�unset
			if (empty($object['roleId'])) {
				unset($object['roleId']);
				unset($object['roleName']);
			}

			//����
			parent::edit_d($object, true);

			//������Ŀ������
			if (isset($object['projectId'])) {
				$this->updateProject_d($object['projectId']);
			}

			if (!empty($object['endDate'])) {
				//�����Ӧ��Ա��ʵ���뿪����ʱ����ոó�Ա�ĵ�����Ϣ�е���Ŀ����
				$personnelDao = new model_hr_personnel_personnel();
				$personnelInfo = $personnelDao->find(array('projectId' => $object['projectId'], 'userAccount' => $object['memberId']), null, 'id');
				if ($personnelInfo) {
					$updateArr = array(
						'id' => $personnelInfo['id'],
						'projectId' => 0,
						'projectCode' => '',
						'projectName' => '',
						'planEndDate' => '0000-00-00',
						'taskId' => 0,
						'taskName' => '',
						'taskPlanEnd' => '0000-00-00'
					);
					$personnelDao->editExtra_d($updateArr, true);
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���� - ����memberId
	 */
	function editByMemberId_d($object) {
		//��������
		$conditionArr = array('memberId' => $object['memberId']);

		//���roleIdΪ�գ�unset
		if (empty($object['roleId'])) {
			unset($object['roleId']);
			unset($object['roleName']);
		}
		$object = $this->addUpdateInfo($object);

		return $this->update($conditionArr, $object);
	}

	/**
	 * ���������� - ��Ա��ɾ��
	 */
	function dealMember_d($object, $activityId, $activityName) {
		if (!is_array($object)) {
			throw new Exception ("������������飡");
		}
		$returnObjs = array();
		foreach ($object as $key => $val) {
			//������Ϣ
			$val['activityId'] = $activityId;
			$val['activityName'] = $activityName;
			$val = $this->addCreateInfo($val);

			//��ȥ��ɾ������
			$isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
			if (empty ($val ['id']) && $isDelTag == 1) {

			} else if (empty ($val ['id'])) {
				if (empty($val['memberId'])) {
					continue;
				}
				$id = $this->add_d($val);
				$val ['id'] = $id;
				array_push($returnObjs, $val);
			} else if ($isDelTag == 1) {
				$this->deletes($val ['id']);
			} else {
				$this->edit_d($val);
				array_push($returnObjs, $val);
			}
		}
		return $returnObjs;
	}

	/**
	 * ������Ŀ��Ա��Ϣ
	 * object ��Ҫ����
	 * projectId ��Ŀid
	 * memberId ��Աid
	 */
	function updateMemberInfo_d($object, $esmactmemberDao) {
		try {
			//��ȡ��ǰ��Ŀ��ǰ��Ա����������
			$allTaskRow = $esmactmemberDao->getAllTask_d($object['projectId'], $object['memberId']);

			//���ҵ�ǰ��Ա��Ϣ
			$memberArr = $this->find(array('projectId' => $object['projectId'], 'memberId' => $object['memberId']));

			//���������Ա��Ϣ������£���������һ��������ɾ������
			if ($memberArr) {
				//����������£�����ֱ�����������Ϣ
				if ($allTaskRow) {
					//����id
					$activityIdArr = array();
					//��������
					$activityNameArr = array();

					foreach ($allTaskRow as $val) {
						array_push($activityNameArr, $val['activityName']);
						array_push($activityIdArr, $val['activityId']);
					}
					$memberArr['activityName'] = implode($activityNameArr, ',');
					$memberArr['activityId'] = implode($activityIdArr, ',');

					parent::edit_d($memberArr, true);
				} else {
					//���¹���һ������
					$memberArr = array(
						'memberId' => $object['memberId'],
						'projectId' => $object['projectId'],
						'activityId' => '',
						'activityName' => ''
					);
					$this->editByMemberId_d($memberArr);
				}

			} else {
				//����id
				$activityIdArr = array();
				//��������
				$activityNameArr = array();
				foreach ($allTaskRow as $key => $val) {
					array_push($activityNameArr, $val['activityName']);
					array_push($activityIdArr, $val['activityId']);
				}
				//���¹���һ������
				$memberArr = array(
					'memberId' => $object['memberId'],
					'memberName' => $object['memberName'],
					'projectId' => $object['projectId'],
					'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],
					'price' => $object['price'],
					'coefficient' => $object['coefficient'],
					'personLevel' => $object['personLevel'],
					'personLevelId' => $object['personLevelId'],
					'activityId' => implode($activityIdArr, ','),
					'activityName' => implode($activityNameArr, ',')
				);

				$this->add_d($memberArr);
			}
		} catch (exception $e) {
			throw $e;
		}
	}

	/**
	 * ������Ŀ��Ϣ
	 */
	function updateProject_d($projectId) {
		//ʵ������Ŀ
		$esmprojectDao = new model_engineering_project_esmproject();
		//���¶��������������
		$memberIds = $this->findAll(array('projectId' => $projectId, 'status' => 0), null, 'memberId');
		$memberArr = array();
		foreach ($memberIds as $k => $v) {
			if ($memberIds[$k]['memberId'] == 'SYSTEM') {
				continue;
			} else {
				array_push($memberArr, $memberIds[$k]['memberId']);
			}
		}
		//���㶦���������������
		$userDao = new model_deptuser_user_user();
		$memberIdstr = implode(',', $memberArr);
		$outPeople = $userDao->getOutPeople_d($memberIdstr);//�����Ա
		$dlPeople = count($memberArr) - $outPeople;//������Ա

		try {
			$this->start_d();
			$updateArr = array('dlPeople' => $dlPeople, 'outsourcingPeople' => $outPeople);
			//����
			$esmprojectDao->updateProjectPeople_d($projectId, $updateArr);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ����Ŀ��Ա�ĸ���
	 */
	function updateMember_d($roleObj, $esmroleDao = null) {
		if ($roleObj['memberId'] || $roleObj['orgMemberId']) {
			$memberIdArr = explode(',', $roleObj['memberId']); //��Աid����
			$memberNameArr = explode(',', $roleObj['memberName']); //��Ա��������

			$orgMemberIdArr = explode(',', $roleObj['orgMemberId']); //��Աid����

			//��ȥ����Ա
			$lostMemberArr = $this->array_diff_fast($orgMemberIdArr, $memberIdArr);

			//������Ϣ
			$personnelDao = new model_hr_personnel_personnel();

			if (!$esmroleDao) {
				$esmroleDao = new model_engineering_role_esmrole();
			}

			try {
				$this->start_d();

				foreach ($memberIdArr as $key => $val) {
					if (empty($val)) continue;

					$condition = array('projectId' => $roleObj['projectId'], 'memberId' => $val);
					$memberObj = $this->find($condition, null, 'id,roleId');
					if ($memberObj) {
						if ($memberObj['roleId'] != $roleObj['roleId']) {
							//�����Ŀ��Ա�Ѿ�����Ŀ������ˣ���Ĩ����ؽ�ɫ��Ϣ
							$esmroleDao->clearRoleMember_d($memberObj['roleId'], $val);
						}
						//���³�Ա��ɫ
						$this->update($condition, array(
							'roleId' => $roleObj['roleId'],
							'roleName' => $roleObj['roleName'],
							'fixedRate' => $roleObj['fixedRate'],
							'status' => 0
						));
					} else {
						//��ȡ��Ա�ȼ�
						$personnelInfo = $personnelDao->getPersonnelAndLevel_d($val);

						$obj = $roleObj;
						$obj['memberId'] = $val;
						$obj['memberName'] = $memberNameArr[$key];
						$obj['personLevelId'] = $personnelInfo['personLevelId'];
						$obj['personLevel'] = $personnelInfo['personLevel'];
						$obj['price'] = $personnelInfo['price'];
						$obj['coefficient'] = $personnelInfo['coefficient'];
						$this->add_d($obj);
					}
				}

				//�����ȥ����Ա���鲻Ϊ�գ������ɾ������
				if (!empty($lostMemberArr)) {
					$esmworklogDao = new model_engineering_worklog_esmworklog();
					foreach ($lostMemberArr as $key => $val) {
						if (empty($val)) continue;
						//�ж���Ա�Ƿ��Ѿ���д��־��δ��д��ɾ��������д����ս�ɫ��Ϣ
						if ($esmworklogDao->checkExistLogUsers_d($val, $roleObj['projectId'])) {
							//ȡ����ɫ����
							$this->cancelRoleByProjectMember_d($val, $roleObj['projectId']);
						} else {
							$this->delete(array('projectId' => $roleObj['projectId'], 'memberId' => $val));
						}
					}
				}

				//������Ŀ����Ա
				$this->updateProject_d($roleObj['projectId']);

				$this->commit_d();
				return true;
			} catch (Exception $e) {
				$this->rollBack();
				throw $e;
			}
		} else {
			return true;
		}
	}

	/************************** �߼������Լ��ж� *******************************/

	//���������Ա��ʵ�ʹ�����
	function updateDayInfo_d($projectId, $memberId, $actDays) {
		//		$sql = "update ". $this->tbl_name . " set feeDay = $actDays,feePeople = feeDay * coefficient,feePerson = feeDay * price where projectId = $projectId and memberId = '$memberId'";
		$sql = "update " . $this->tbl_name . " set feeDay = $actDays,feePeople = feeDay * coefficient where projectId = $projectId and memberId = '$memberId'";
		try {
			$this->_db->query($sql);
			return true;
		} catch (exception $e) {
			throw $e;
		}
	}

	/**
	 * ��Ŀ�г�ԱΨһ�ж�
	 */
	function isUnique_d($projectId, $userName) {
		return $this->find(array('projectId' => $projectId, 'memberName' => $userName));
	}

	/**
	 * �ж���Ŀ���Ƿ��Ѿ����ڸĳ�Ա
	 */
	function isExistedInProject_d($projectId, $userId) {
		return $this->find(array('projectId' => $projectId, 'memberId' => $userId)) ? true : false;
	}

	/**
	 * ��Ŀ�г�ԱΨһ�ж�
	 */
	function isManager_d($projectId, $userId) {
		return $this->find(array('projectId' => $projectId, 'memberId' => $userId, 'isManager' => 1), null, 'id');
	}

	/**
	 * ��ȡ��Ŀ������������Ϣ
	 */
	function getFeePerson_d($projectId) {
		$this->searchArr = array('projectId' => $projectId);
		$rs = $this->listBySqlId('count_all');
		if (is_array($rs)) {
			//������ͳ��Ԥ��ֵ
			$rs[0]['feeDay'] = empty($rs[0]['feeDay']) ? 0 : $rs[0]['feeDay'];
			$rs[0]['feePeople'] = empty($rs[0]['feePeople']) ? 0 : $rs[0]['feePeople'];
			$rs[0]['feePerson'] = empty($rs[0]['feePerson']) ? 0 : $rs[0]['feePerson'];

			return $rs[0];
		} else {
			return array(
				'feeDay' => 0,
				'feePeople' => 0,
				'feePerson' => 0,
				'peopleNumber' => 0
			);
		}
	}

	/**
	 * �ж���Ŀ���Ƿ�ûδ�뿪����Ŀ��Ա
	 */
	function checkMemberAllLeave_d($projectId) {
		$this->searchArr = array('projectId' => $projectId, 'noEndDate' => 1, 'memberIdNot' => 'SYSTEM');
		return $this->listBySqlId();
	}

	/**
	 * ��ȡ��Ŀ�е���Ŀ��Ա����
	 */
	function getMemberInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId), "status", 'memberId,memberName,status,endDate');
	}

	/**
	 * ��ȡ��Ŀ�е���Ŀ��Ա����
	 */
	function getExistMemberInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId, 'status' => 0), null, 'memberId,memberName');
	}

	/**
	 * ��ȡĳ��ʱ������Ŀ�е���Ŀ��Ա�ȼ�
	 */
	function memberCurrent_d($projectId, $beginDate, $endDate, $condition) {
		$groupSql =
			" GROUP BY
				  CASE
				  WHEN personLevel IS NULL THEN
				  '��'
				  WHEN personLevel = '' THEN
			      '��'
				  ELSE
				  personLevel
				  END";
		if ($condition == 0) {
			$conditionSql =
				" AND (
					beginDate IS NULL
					OR beginDate = ''
					OR beginDate <= '" . $endDate . "'
				)
				AND (
					endDate IS NULL
					OR endDate = ''
					OR endDate >= '" . $beginDate . "'
				)";
		} elseif ($condition == 1) {
			$conditionSql =
				" AND (
					endDate IS NULL
					OR endDate = ''
				)";
		} else {
			$conditionSql = "";
		}
		$sql =
			"SELECT
				personLevel,
				count(*)
			FROM
				" . $this->tbl_name . "
			WHERE
				projectId = '" . $projectId . "' AND memberId<>'SYSTEM'" . $conditionSql . $groupSql;
		return $this->listBySql($sql);
	}

	/**
	 * ��ȡĳ��ʱ������Ŀ�е���Ŀ��Ա�б�
	 */
	function memberListJson_d($projectId, $beginDate, $endDate, $condition) {
		//��ȡ��Ŀ���
		$projectCodeSql = "SELECT projectCode FROM " . $this->tbl_name . " WHERE projectId='" . $projectId . "' GROUP BY projectCode";
		$projectCodeRs = $this->findSql($projectCodeSql);
		$projectCode = $projectCodeRs[0]['projectCode'];
		$conditionSql = "";
		if ($condition == 0) {
			$conditionSql =
				" AND (
						c.beginDate IS NULL
						OR c.beginDate = ''
						OR c.beginDate <= '" . $endDate . "'
					)
					AND (
						c.endDate IS NULL
						OR c.endDate = ''
						OR c.endDate >= '" . $beginDate . "'
					)";
		} elseif ($condition == 1) {
			$conditionSql =
				" AND
						c.endDate IS NULL
						OR c.endDate = ''";
		}
		$sql = "
				SELECT
					c.id,
					c.projectId,
					c.memberId,
					c.memberName,
					c.projectCode,
					c.personLevel,
					c.roleName,
					c.beginDate,
					c.endDate,
					p.belongDeptName,
					p.userNo,
					w.createId,
					inWorkRate,
					workCoefficient,
					thisProjectProcess,
					processCoefficient,
					DATEDIFF(c.endDate, c.beginDate) - logNum + 1 AS logMissNum,
					feeFieldCount,
					loan
				FROM
					" . $this->tbl_name . " c
				LEFT JOIN (
					SELECT
						round(sum(inWorkRate / 100), 2) AS inWorkRate,
						sum(workCoefficient) AS workCoefficient,
						sum(thisProjectProcess) AS thisProjectProcess,
						sum(processCoefficient) AS processCoefficient,
						createId
					FROM
						oa_esm_worklog
					WHERE
						projectId = '" . $projectId . "'
					AND executionDate BETWEEN '" . $beginDate . "'
					AND '" . $endDate . "'
					GROUP BY
						createId
				) w ON c.memberId = w.createId
				LEFT JOIN (
					SELECT
						sum(Amount) AS feeFieldCount,
						CostMan
					FROM
						cost_summary_list
					WHERE
						ProjectNo = '" . $projectCode . "'
					AND (
						(
							isproject = '1'
							AND STATUS <> '���'
						)
						OR (
							isNew = '1'
							AND STATUS <> '���'
							AND STATUS <> '�༭'
						)
					)
					GROUP BY
						REPLACE ('" . $projectCode . "', '-', ''),
						CostMan
				) cc ON c.memberId = cc.CostMan
				LEFT JOIN (
					SELECT
						sum(Amount) AS loan,
						Debtor
					FROM
						loan_list
					WHERE
						ProjectNo = '" . $projectCode . "'
					AND STATUS <> '�༭'
					AND STATUS <> '���'
					GROUP BY
						REPLACE ('" . $projectCode . "', '-', ''),
						Debtor
				) cl ON c.memberId = cl.Debtor
				LEFT JOIN oa_hr_personnel p ON c.memberId = p.userAccount
				LEFT JOIN (
					SELECT
						COUNT(*) AS logNum,
						createId
					FROM
						oa_esm_worklog
					WHERE
						projectId = '" . $projectId . "'
					GROUP BY
						createId
				) wl ON c.memberId = wl.createId
					WHERE
						c.projectId = '" . $projectId . "' AND memberId<>'SYSTEM'" . $conditionSql;
		$this->sort = "c.memberName";
		return $this->listBySql($sql);
	}

	/**
	 * ��ȡ��Ŀ����
	 */
	function getManagerInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId, 'isManager' => 1), null, 'memberId,memberName');
	}

	/**
	 * �ж���Ŀ���Ƿ��Ѵ��ڶ���
	 */
	function memberIsExsist_d($projectId, $memberId) {
		$this->searchArr = array(
			'projectId' => $projectId,
			'memberIdArr' => $memberId
		);
		$rs = $this->list_d();
		if ($rs) {
			$memberArr = array();
			foreach ($rs as $val) {
				array_push($memberArr, $val['memberName']);
			}
			return implode($memberArr, ',');
		} else {
			return 0;
		}
	}

	/**
	 * �ж���Ŀ��Ա�Ƿ������
	 */
	function memberCanSet_d($projectId, $roleId, $memberId, $orgMemberId) {
		//�жϱ���ѡ�����Ա�Ƿ��Ѿ���������ɫ
		$this->searchArr = array(
			'projectId' => $projectId,
			'memberIdArr' => $memberId,
			'noRoleId' => $roleId
		);
		$rs = $this->list_d();
		if ($rs) {
			$memberArr = array();
			foreach ($rs as $val) {
				array_push($memberArr, $val['memberName']);
			}
			return array('val' => 2, 'member' => implode($memberArr, ','));
		}

		//��ʧ��Ա��������Ա��ȡ
		$memberIdArr = explode(',', $memberId);
		$orgMemberIdArr = explode(',', $orgMemberId);
		$lostMemberArr[0] = $this->array_diff_fast($memberIdArr, $orgMemberIdArr);
		$lostMemberArr[1] = $this->array_diff_fast($orgMemberIdArr, $memberIdArr);
		$lostMemberArr = array_merge($lostMemberArr[0], $lostMemberArr[1]);

		//�����Ա�ж�
		if (!empty($lostMemberArr)) {
			$lostMemberId = implode($lostMemberArr, ',');
			$esmactmemberDao = new model_engineering_activity_esmactmember();
			$memberRs = $esmactmemberDao->isExsistActivity_d($projectId, $lostMemberId);
			if ($memberRs != '0') {
				return array('val' => 3, 'member' => $memberRs);
			}
		}

		//��־�����ж�
		if (!empty($lostMemberArr)) {
			$lostMemberId = implode($lostMemberArr, ',');

			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$logRs = $esmworklogDao->checkExistLogUsers_d($lostMemberId, $projectId);
			if ($logRs != '0') {
				return array('val' => 4, 'member' => $logRs);
			}
		}

		//����1 - ����ִ��
		return 1;
	}

	function array_diff_fast($firstArray, $secondArray) {
		// ת���ڶ�������ļ�ֵ��ϵ
		$secondArray = array_flip($secondArray);

		// ѭ����һ������
		foreach ($firstArray as $key => $value) {
			// ����ڶ��������д��ڵ�һ�������ֵ
			if (isset($secondArray[$value])) {
				// �Ƴ���һ�������ж�Ӧ��Ԫ��
				unset($firstArray[$key]);
			}
		}

		return $firstArray;
	}

	//������ɫ��Ϣ
	function cancelRole_d($id) {
		try {
			$sql = "update " . $this->tbl_name . " set roleName = null ,roleId = null ,status = 1,isManager = 0,isCanEdit = 0 where id = " . $id;
			$this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ����id�ۼӸ���ĳһ�ֶ�
	 */
	function updateValById($id, $file, $val) {
		$this->updateVal("id = '{$id}'", $file, $val);
	}

	/**
	 * �ۼӸ���ĳһ�ֶ�
	 */
	function updateVal($condition, $file, $val) {
		$this->query("UPDATE oa_esm_project_member c SET c.{$file}= {$val} WHERE {$condition}");
	}

	/**
	 * ������
	 */
	function addOrg_d($object) {
		try {
			return parent::add_d($object, true);
		} catch (Exception $e) {
			throw $e;
		}
	}

	//������ɫ��Ϣ - ��Ŀid�ͳ�Ա����
	function cancelRoleByProjectMember_d($memberId, $projectId) {
		try {
			$sql = "update " . $this->tbl_name . " set roleName = null ,roleId = null ,isManager = 0,isCanEdit = 0,status = 1 where memberId = '" . $memberId . "' and projectId = '" . $projectId . "'";
			$this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ����޸��������Ƿ������Ŀ����,���ڷ���true
	 * @param unknown $object
	 * @return boolean
	 */
	function checkHasManager_d($object) {
		$esmroleDao = new model_engineering_role_esmrole();
		$esmroleArr = $esmroleDao->find(array('projectId' => $object['projectId'], 'isManager' => '1'), null, 'memberId');
		if (!empty($esmroleArr)) {
			$managerId = explode(',', $esmroleArr['memberId']);
			$memberId = explode(',', $object['memberId']);
			foreach ($managerId as $val) {
				if (in_array($val, $memberId)) {
					return 1;
				}
			}
		}
		return 0;
	}

	/******************* ���ʲ��� *****************/
	/**
	 * ������Ŀ��Ա�ķ���
	 */
	function updateMemberFee_d($projectIds) {
		$sql = "update
			oa_esm_project_member c
				inner join
			(
				select projectId,sum(feePerson) as feePerson,userId from oa_esm_project_personfee where projectId in ($projectIds) group by projectId,userId
			) p
				on c.projectId = p.projectId and c.memberId = p.userId
		set c.feePerson = p.feePerson
		where c.projectId in ($projectIds);";
		return $this->_db->query($sql);
	}
}