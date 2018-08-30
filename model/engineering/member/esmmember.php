<?php

/**
 * @author Show
 * @Date 2011年12月20日 星期二 15:23:55
 * @version 1.0
 * @description:项目成员(oa_esm_project_member) Model层 成员类型
 * 0 内部
 * 1 外部
 *
 * 成员状态
 * 0 项目中
 * 1 已离开
 */
class model_engineering_member_esmmember extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_member";
		$this->sql_map = "engineering/member/esmmemberSql.php";
		parent:: __construct();
	}

	/********************** 获取外部信息接口 *****************************/
	/**
	 * 获取项目成员所需信息
	 */
	function getObjInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		return $esmprojectDao->find(array('id' => $projectId), null, 'id,projectCode,projectName,actBeginDate');
	}

	/*********************** 内部信息处理 *********************************/

	/**
	 * 新增保存
	 * 项目章程使用
	 */
	function addAlong_d($object) {
		try {
			$this->start_d();

			//新增
			$newId = parent::add_d($object, true);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//如果roleId为空，unset
			if (empty($object['roleId'])) {
				unset($object['roleId']);
				unset($object['roleName']);
			}

			//新增
			$newId = parent::add_d($object, true);

			//更新项目的人数
			$this->updateProject_d($object['projectId']);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 重写编辑
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//如果roleId为空，unset
			if (empty($object['roleId'])) {
				unset($object['roleId']);
				unset($object['roleName']);
			}

			//新增
			parent::edit_d($object, true);

			//更新项目的人数
			if (isset($object['projectId'])) {
				$this->updateProject_d($object['projectId']);
			}

			if (!empty($object['endDate'])) {
				//如果对应人员有实际离开日期时，清空该成员的档案信息中的项目缓存
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
	 * 更新 - 根据memberId
	 */
	function editByMemberId_d($object) {
		//条件数组
		$conditionArr = array('memberId' => $object['memberId']);

		//如果roleId为空，unset
		if (empty($object['roleId'])) {
			unset($object['roleId']);
			unset($object['roleName']);
		}
		$object = $this->addUpdateInfo($object);

		return $this->update($conditionArr, $object);
	}

	/**
	 * 批量处理方法 - 人员增删改
	 */
	function dealMember_d($object, $activityId, $activityName) {
		if (!is_array($object)) {
			throw new Exception ("传入对象不是数组！");
		}
		$returnObjs = array();
		foreach ($object as $key => $val) {
			//加载信息
			$val['activityId'] = $activityId;
			$val['activityName'] = $activityName;
			$val = $this->addCreateInfo($val);

			//进去增删改流程
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
	 * 更新项目成员信息
	 * object 中要包含
	 * projectId 项目id
	 * memberId 成员id
	 */
	function updateMemberInfo_d($object, $esmactmemberDao) {
		try {
			//获取当前项目当前人员的所有任务
			$allTaskRow = $esmactmemberDao->getAllTask_d($object['projectId'], $object['memberId']);

			//查找当前人员信息
			$memberArr = $this->find(array('projectId' => $object['projectId'], 'memberId' => $object['memberId']));

			//如果存在人员信息，则更新，否则新增一条，不做删除操作
			if ($memberArr) {
				//有任务则更新，否则直接清空任务信息
				if ($allTaskRow) {
					//任务id
					$activityIdArr = array();
					//任务名称
					$activityNameArr = array();

					foreach ($allTaskRow as $val) {
						array_push($activityNameArr, $val['activityName']);
						array_push($activityIdArr, $val['activityId']);
					}
					$memberArr['activityName'] = implode($activityNameArr, ',');
					$memberArr['activityId'] = implode($activityIdArr, ',');

					parent::edit_d($memberArr, true);
				} else {
					//重新构造一下数组
					$memberArr = array(
						'memberId' => $object['memberId'],
						'projectId' => $object['projectId'],
						'activityId' => '',
						'activityName' => ''
					);
					$this->editByMemberId_d($memberArr);
				}

			} else {
				//任务id
				$activityIdArr = array();
				//任务名称
				$activityNameArr = array();
				foreach ($allTaskRow as $key => $val) {
					array_push($activityNameArr, $val['activityName']);
					array_push($activityIdArr, $val['activityId']);
				}
				//重新构造一下数组
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
	 * 更新项目信息
	 */
	function updateProject_d($projectId) {
		//实例化项目
		$esmprojectDao = new model_engineering_project_esmproject();
		//更新鼎利和外包的人数
		$memberIds = $this->findAll(array('projectId' => $projectId, 'status' => 0), null, 'memberId');
		$memberArr = array();
		foreach ($memberIds as $k => $v) {
			if ($memberIds[$k]['memberId'] == 'SYSTEM') {
				continue;
			} else {
				array_push($memberArr, $memberIds[$k]['memberId']);
			}
		}
		//计算鼎利人数与外包人数
		$userDao = new model_deptuser_user_user();
		$memberIdstr = implode(',', $memberArr);
		$outPeople = $userDao->getOutPeople_d($memberIdstr);//外包人员
		$dlPeople = count($memberArr) - $outPeople;//鼎利人员

		try {
			$this->start_d();
			$updateArr = array('dlPeople' => $dlPeople, 'outsourcingPeople' => $outPeople);
			//更新
			$esmprojectDao->updateProjectPeople_d($projectId, $updateArr);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 对项目成员的更新
	 */
	function updateMember_d($roleObj, $esmroleDao = null) {
		if ($roleObj['memberId'] || $roleObj['orgMemberId']) {
			$memberIdArr = explode(',', $roleObj['memberId']); //人员id数组
			$memberNameArr = explode(',', $roleObj['memberName']); //人员名称数组

			$orgMemberIdArr = explode(',', $roleObj['orgMemberId']); //人员id数组

			//减去的人员
			$lostMemberArr = $this->array_diff_fast($orgMemberIdArr, $memberIdArr);

			//人事信息
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
							//如果项目成员已经是项目组里的人，则抹掉相关角色信息
							$esmroleDao->clearRoleMember_d($memberObj['roleId'], $val);
						}
						//更新成员角色
						$this->update($condition, array(
							'roleId' => $roleObj['roleId'],
							'roleName' => $roleObj['roleName'],
							'fixedRate' => $roleObj['fixedRate'],
							'status' => 0
						));
					} else {
						//获取人员等级
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

				//如果减去的人员数组不为空，则进行删除操作
				if (!empty($lostMemberArr)) {
					$esmworklogDao = new model_engineering_worklog_esmworklog();
					foreach ($lostMemberArr as $key => $val) {
						if (empty($val)) continue;
						//判断人员是否已经填写日志，未填写则删除，已填写则清空角色信息
						if ($esmworklogDao->checkExistLogUsers_d($val, $roleObj['projectId'])) {
							//取消角色关联
							$this->cancelRoleByProjectMember_d($val, $roleObj['projectId']);
						} else {
							$this->delete(array('projectId' => $roleObj['projectId'], 'memberId' => $val));
						}
					}
				}

				//更新项目的人员
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

	/************************** 逻辑处理以及判断 *******************************/

	//更新任务成员的实际工作量
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
	 * 项目中成员唯一判断
	 */
	function isUnique_d($projectId, $userName) {
		return $this->find(array('projectId' => $projectId, 'memberName' => $userName));
	}

	/**
	 * 判断项目中是否已经存在改成员
	 */
	function isExistedInProject_d($projectId, $userId) {
		return $this->find(array('projectId' => $projectId, 'memberId' => $userId)) ? true : false;
	}

	/**
	 * 项目中成员唯一判断
	 */
	function isManager_d($projectId, $userId) {
		return $this->find(array('projectId' => $projectId, 'memberId' => $userId, 'isManager' => 1), null, 'id');
	}

	/**
	 * 获取项目的人力决算信息
	 */
	function getFeePerson_d($projectId) {
		$this->searchArr = array('projectId' => $projectId);
		$rs = $this->listBySqlId('count_all');
		if (is_array($rs)) {
			//加上无统计预设值
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
	 * 判断项目中是否还没未离开的项目成员
	 */
	function checkMemberAllLeave_d($projectId) {
		$this->searchArr = array('projectId' => $projectId, 'noEndDate' => 1, 'memberIdNot' => 'SYSTEM');
		return $this->listBySqlId();
	}

	/**
	 * 获取项目中的项目成员名单
	 */
	function getMemberInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId), "status", 'memberId,memberName,status,endDate');
	}

	/**
	 * 获取项目中的项目成员名单
	 */
	function getExistMemberInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId, 'status' => 0), null, 'memberId,memberName');
	}

	/**
	 * 获取某段时间内项目中的项目成员等级
	 */
	function memberCurrent_d($projectId, $beginDate, $endDate, $condition) {
		$groupSql =
			" GROUP BY
				  CASE
				  WHEN personLevel IS NULL THEN
				  '无'
				  WHEN personLevel = '' THEN
			      '无'
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
	 * 获取某段时间内项目中的项目成员列表
	 */
	function memberListJson_d($projectId, $beginDate, $endDate, $condition) {
		//获取项目编号
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
							AND STATUS <> '打回'
						)
						OR (
							isNew = '1'
							AND STATUS <> '打回'
							AND STATUS <> '编辑'
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
					AND STATUS <> '编辑'
					AND STATUS <> '打回'
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
	 * 获取项目经理
	 */
	function getManagerInProject_d($projectId) {
		return $this->findAll(array('projectId' => $projectId, 'isManager' => 1), null, 'memberId,memberName');
	}

	/**
	 * 判断项目中是否已存在对象
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
	 * 判断项目成员是否可设置
	 */
	function memberCanSet_d($projectId, $roleId, $memberId, $orgMemberId) {
		//判断本次选择的人员是否已经有其他角色
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

		//丢失成员和新增成员获取
		$memberIdArr = explode(',', $memberId);
		$orgMemberIdArr = explode(',', $orgMemberId);
		$lostMemberArr[0] = $this->array_diff_fast($memberIdArr, $orgMemberIdArr);
		$lostMemberArr[1] = $this->array_diff_fast($orgMemberIdArr, $memberIdArr);
		$lostMemberArr = array_merge($lostMemberArr[0], $lostMemberArr[1]);

		//任务成员判断
		if (!empty($lostMemberArr)) {
			$lostMemberId = implode($lostMemberArr, ',');
			$esmactmemberDao = new model_engineering_activity_esmactmember();
			$memberRs = $esmactmemberDao->isExsistActivity_d($projectId, $lostMemberId);
			if ($memberRs != '0') {
				return array('val' => 3, 'member' => $memberRs);
			}
		}

		//日志部分判断
		if (!empty($lostMemberArr)) {
			$lostMemberId = implode($lostMemberArr, ',');

			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$logRs = $esmworklogDao->checkExistLogUsers_d($lostMemberId, $projectId);
			if ($logRs != '0') {
				return array('val' => 4, 'member' => $logRs);
			}
		}

		//返回1 - 即可执行
		return 1;
	}

	function array_diff_fast($firstArray, $secondArray) {
		// 转换第二个数组的键值关系
		$secondArray = array_flip($secondArray);

		// 循环第一个数组
		foreach ($firstArray as $key => $value) {
			// 如果第二个数组中存在第一个数组的值
			if (isset($secondArray[$value])) {
				// 移除第一个数组中对应的元素
				unset($firstArray[$key]);
			}
		}

		return $firstArray;
	}

	//撤销角色信息
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
	 * 根据id累加更新某一字段
	 */
	function updateValById($id, $file, $val) {
		$this->updateVal("id = '{$id}'", $file, $val);
	}

	/**
	 * 累加更新某一字段
	 */
	function updateVal($condition, $file, $val) {
		$this->query("UPDATE oa_esm_project_member c SET c.{$file}= {$val} WHERE {$condition}");
	}

	/**
	 * 简单新增
	 */
	function addOrg_d($object) {
		try {
			return parent::add_d($object, true);
		} catch (Exception $e) {
			throw $e;
		}
	}

	//撤销角色信息 - 项目id和成员名称
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
	 * 检查修改数组里是否存在项目经理,存在返回true
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

	/******************* 工资部分 *****************/
	/**
	 * 更新项目人员的费用
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