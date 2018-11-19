<?php

/**
 * @author Show
 * @Date 2011��12��22�� ������ 9:49:51
 * @version 1.0
 * @description:��Ŀ����Ԥ��(oa_esm_project_budget) Model��
 */
class model_engineering_budget_esmbudget extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_budget";
		$this->sql_map = "engineering/budget/esmbudgetSql.php";
		parent::__construct();
	}

	private $budgetTypeArr = array(
		'budgetField' => '�ֳ��ɱ�',
		'budgetPerson' => '�����ɱ�',
		'budgetOutsourcing' => '����ɱ�',
		'budgetOther' => '�����ɱ�'
	);

	// ����Ԥ����������
	function rtBudgetType($thisVal) {
		return $this->budgetTypeArr[$thisVal];
	}

	/****************************��ȡ�ⲿ��Ϣ����***************************/

	/**
	 * ��ȡ��Ŀ��Ϣ
	 * @param $projectId
	 * @return bool|mixed
	 */
	function getProjectInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName');
	}

	/****************�ӿڷ���******************/
	/**
	 * ��ȡ��Ŀ�е�Ԥ��
	 * @param $projectId
	 * @param string $budgetType
	 * @return int
	 */
	function getProjectBudget_d($projectId, $budgetType = 'budgetField') {
		$this->searchArr = array('projectId' => $projectId, 'budgetType' => $budgetType);
		$this->groupBy = 'c.projectId';
		$rs = $this->listBySqlId('count_all');
		if ($rs[0]['amount']) {
			return $rs[0]['amount'];
		} else {
			return 0;
		}
	}

	/**
	 * ���λ�ȡȫ��Ԥ��
	 * @param $projectId
	 * @return array
	 */
	function getProjectBudgetOnce_d($projectId) {
		$sql = "select
				sum(if(c.budgetType = 'budgetField',c.amount,0)) as budgetField,
				sum(if(c.budgetType = 'budgetOutsourcing',c.amount,0)) as budgetOutsourcing,
				sum(if(c.budgetType = 'budgetPerson',c.amount,0)) as budgetPerson,
				sum(if(c.budgetType = 'budgetPerson',c.budgetDay,0)) as budgetDay,
				sum(if(c.budgetType = 'budgetPerson',c.budgetPeople,0)) as budgetPeople,
				sum(if(c.budgetType = 'budgetOther',c.amount,0)) as budgetOther,
				sum(if(c.budgetType = 'budgetOther',c.actFee,0)) as feeOther,
				sum(if(c.budgetType = 'budgetOutsourcing',c.actFee,0)) as feeOutsourcing
			from
				oa_esm_project_budget c
			where
				c.projectId = '$projectId' group by c.projectId";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['budgetField'] === null) {
			return array(
				'budgetField' => 0,
				'budgetOutsourcing' => 0,
				'budgetPerson' => 0,
				'budgetDay' => 0,
				'budgetPeople' => 0,
				'budgetOther' => 0
			);
		} else {
			return $rs[0];
		}
	}

	/**
	 * ��ȡ��ĿԤ����Ϣ
	 * @param $projectId
	 * @return mixed
	 */
	function getProjectBudgetInfo_d($projectId) {
		return $this->findAll(array('projectId' => $projectId));
	}

	/**
	 * ������Ŀ��Ϣ - ͳһ���÷���
	 * @param $object
	 * @param bool $isUpdateFee
	 * @return bool
	 * @throws Exception
	 */
	function updateProject_d($object, $isUpdateFee = false) {
		$esmprojectDao = new model_engineering_project_esmproject();
		try {
			$this->start_d();

			//������Ŀ����
			$updateArr = $this->getProjectBudgetOnce_d($object['projectId']);
			//�����Ҫ���·���
			if (!$isUpdateFee) {
				unset($updateArr['feeOther']);
				unset($updateArr['feeOutsourcing']);
			}
			$esmprojectDao->updateProject_d($object['projectId'], $updateArr);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �ۼӸ���ĳһ�ֶ�
	 * @param $condition
	 * @param $file
	 * @param $val
	 */
	function updateVal($condition, $file, $val) {
		$this->query("UPDATE $this->tbl_name c SET c.{$file}= {$val} WHERE {$condition}");
	}

	/**
	 * ����id�ۼӸ���ĳһ�ֶ�
	 * @param $id
	 * @param $file
	 * @param $val
	 */
	function updateValById($id, $file, $val) {
		$this->updateVal("id = '{$id}'", $file, $val);
	}

	/**
	 * �жϸ���Ŀ�Ƿ�����Դ�ƻ�
	 * @param $projectId
	 * @return bool
	 */
	function checkResources_d($projectId) {
		$esmresourcesDao = new model_engineering_resources_esmresources();
		return $esmresourcesDao->find(array("projectId" => $projectId), $sort = null, $fields = null) ? true : false;
	}

	/**
	 * �ж��Ƿ��б������
	 * @param $projectId
	 * @param $isCreateNew
	 * @return bool
	 * @throws Exception
	 */
	function isChanging_d($projectId, $isCreateNew) {
		//�ж��Ƿ��Ѿ����ڱ������
		$esmchangeDao = new model_engineering_change_esmchange();
		return $esmchangeDao->getChangeId_d($projectId, $isCreateNew);
	}

	/*************************** �ڲ����� *****************************/
	/**
	 * ��дadd
	 * @param $object
	 * @return bool
	 */
	public function add_d($object) {
		try {
			$this->start_d();

			//�жϴ��޸��Ƿ����ڱ��
			$esmprojectDao = new model_engineering_project_esmproject();
			if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
				//Ԥ������
				$esmchangebudDao = new model_engineering_change_esmchangebud();
				$esmchangebudDao->add_d($object, $this);
			} else {
				//��������
				parent::add_d($object, true);

				//������Ŀ���ֳ�Ԥ��
				$this->updateProject_d($object);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��������
	 * @param $object
	 * @return bool
	 */
	function addBatch_d($object) {
		//��ȡ�ӱ�Ԥ����Ϣ
		$budgets = $object['budgets'];
		unset($object['budgets']);
		try {
			$this->start_d();//������

			//������Ԥ��ҳ�����ݵ���Ŀ������Ԥ��
			$budgets = $this->setArrayFn_d($object, $budgets);
			$this->saveDelBatch($budgets);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ϲ��Լ�ɸѡ
	 * @param $inArr
	 * @param $objectArr
	 * @return mixed
	 */
	function setArrayFn_d($inArr, $objectArr) {
		$otherDatasDao = new model_common_otherdatas();
		$subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

		foreach ($objectArr as $key => $val) {
			//������Ϊ�գ�����������
			if ($val['amount'] === "") {
				unset($objectArr[$key]);
				continue;
			}
			$objectArr[$key] = array_merge($val, $inArr);

			// ת��Ԥ������
			if (in_array($val['budgetName'], $subsidyArr)) {
				$objectArr[$key]['budgetType'] = 'budgetPerson';
			}
		}

		return $objectArr;
	}

	/**
	 * �༭���ڵ�
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//�������orgId ,����Ϊ�޸ı����Ԥ��
			if ($object['orgId'] != -1) {
				//Ԥ������
				$esmchangebudDao = new model_engineering_change_esmchangebud();
				//����һ���ж�,�����Ϊ�����Ԫ��,��ô����������ֵ
				if (!$esmchangebudDao->budgetIsChanging_d($object['id'])) {
					$object['isChanging'] = 1;
					$object['changeAction'] = 'edit';
				}
				$esmchangebudDao->editOrg_d($object, $this);
			} else {
				//�жϴ��޸��Ƿ����ڱ��
				$esmprojectDao = new model_engineering_project_esmproject();
				if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
					//Ԥ������
					$esmchangebudDao = new model_engineering_change_esmchangebud();
					$esmchangebudDao->edit_d($object, $this);
				} else {
					parent::edit_d($object, true);

					//������Ŀ���ֳ�Ԥ��
					$this->updateProject_d($object);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function addOrg_d($object) {
		try {
			return parent::add_d($object, true);
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �򵥱༭
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function editOrg_d($object) {
		try {
			return parent::edit_d($object, true);
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ɾ�����ڵ㼰�����ڵ�
	 * @param $id
	 * @param $projectId
	 * @param $changeId
	 * @return bool
	 * @throws Exception
	 */
	function deletes_d($id, $projectId, $changeId) {
		if (empty($id)) {
			return false;
		}

		//Ԥ������
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmchangebudDao = new model_engineering_change_esmchangebud();
		try {
			$this->start_d();

			//������¼����
			if ($id) {
				if ($esmprojectDao->actionIsChange_d($projectId)) {
					$esmchangebudDao->deletes_d($id, $this, $projectId, $changeId);
				} else {
					//ɾ��Ԥ����
					parent::deletes($id);

					//������Ŀ���ֳ�Ԥ��
					$this->updateProject_d(array('projectId' => $projectId));
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ��� �༭����ʱ����״̬
	 * @param $id
	 * @param string $thisAction
	 * @return bool
	 */
	function changeInfoSet_d($id, $thisAction = 'add') {
		return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
	}

	/*********************** ������ִ��� *************************/
	/**
	 * �����Ϣ��ȡ
	 * @param $uid
	 * @return bool|mixed
	 */
	function getChange_d($uid) {
		$esmchangebudDao = new model_engineering_change_esmchangebud();
		return $esmchangebudDao->get_d($uid);
	}

	/**
	 * ���״̬��ԭ
	 * @param $projectId
	 * @return bool
	 */
	function revertChangeInfo_d($projectId) {
		return $this->update(array('projectId' => $projectId), array('changeAction' => '', 'isChanging' => '0'));
	}

	/**
	 * ��ȡ��ĿԤ��
	 * @param $projectId
	 * @return array
	 */
	function getFee_d($projectId) {
		$projectDao = new model_engineering_project_esmproject();
		$projectArr = $projectDao->find(array('id' => $projectId), null, 'projectCode');

		// �������� �Լ���ʼ��
		$newArr = array(
			'allCostMoney' => 0
		);

		// ���û�ȡ
		$expenseDao = new model_finance_expense_expense();
		$expenseArr = $expenseDao->getFeeDetail_d($projectArr['projectCode']);
		$fieldArr = array();
		if ($expenseArr) {
			$allCostMoney = 0;
			foreach ($expenseArr as $v) {
				$fieldArr[$v['CostTypeName']] = $v;
				$allCostMoney = bcadd($allCostMoney, $v['CostMoney'], 2);
			}
			$newArr['allCostMoney'] = bcadd($allCostMoney, $newArr['allCostMoney'], 2);
			$newArr['detail'] = $fieldArr;
		}
		return $newArr;
	}

	/**
	 * û�иü��������޸�
	 * @param $obj
	 * @return mixed
	 */
	function checkLevelId_d($obj) {
		return $this->_db->getArray("select budgetId from " . $this->tbl_name . " where budgetId = '$obj'");
	}

	/**
	 * ��ĿԤ���ȡ - �����������͵�Ԥ��
	 * @param $projectId
	 * @return mixed
	 */
	function getAllBudgetDetail_d($projectId) {
		return $this->_db->getArray("SELECT
                budgetId,budgetName,parentId,parentName,numberOne,numberTwo,price,amount,budgetType
            FROM
                oa_esm_project_budget
            WHERE
                projectId = '$projectId'
            UNION
            SELECT
                resourceId,resourceName,resourceTypeId,resourceTypeName,number,1 AS number,price,amount,'budgetEqu'
            FROM
                oa_esm_project_resources
            WHERE
                projectId = '$projectId'");
	}

	/**
	 * ��ȡ����С�����
	 * @param $projectId
	 * @param $parentName
	 * @param $budgetName
	 * @return mixed
	 */
	function getDetCount_d($projectId, $parentName, $budgetName) {
		$query = $this->_db->query("select count(*) as count  from oa_esm_project_budget where projectId = '" .
            $projectId . "' and parentName= '" . $parentName . "' and budgetName='" . $budgetName . "'");
		$rs = $this->_db->fetch_array($query);
		return $rs['count'];
	}

	/**
	 * ��ȡԤ��
	 * @param $param
	 * @return mixed
	 */
	function getBudgetData_d($param) {
		$this->getParam($param);
		$this->asc = false;
		$this->groupBy = 'c.isImport,c.budgetType,c.budgetName';
		$this->sort = 'c.budgetType,c.budgetName,c.isImport';
		return $this->list_d('search_json');
	}

    /**
     * @param $budgetData TODO
     * @return array
     */
    function budgetSplit_d($budgetData) {
        $budgetCache = array();

        // �����еĲ�������
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        if ($budgetData) {
            foreach ($budgetData as $v) {
                switch ($v['budgetType']) {
                    case 'budgetPerson' : // �����ɱ�
                        $budgetCache[0][] = $v;
                        break;
                    case 'budgetEqu' : // �豸�ɱ�
                        $budgetCache[2][] = $v;
                        break;
                    case 'budgetOutsourcing' : // ����ɱ�
                        $budgetCache[3][] = $v;
                        break;
                    case 'budgetOther' : // �����ɱ�
                        $budgetCache[4][] = $v;
                        break;
                    default: // ��ϳɱ� - ��Ҫ����֧���ɱ� �Լ� ��������е�ĳЩ�����ɱ�
                        $key = 1; // Ĭ�ϵ�key��ָ����֧���ɱ�

                        // �ж��Ƿ���Ա�����ɱ�
                        if (in_array($v['budgetName'], $subsidyArr)) {
                            $key = 0;
                        }
                        $budgetCache[$key][] = $v;
                }
            }
        }
        return $budgetCache;
    }

    /**
     * ������
     * @param $feeData
     * @return array
     */
    function feeSplit_d($feeData) {
        $feeCache = array();

        // �����еĲ�������
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');
		$filterArr = $otherDatasDao->getConfig('engineering_budget_expense', null, 'arr'); // ���˵ı�������

        if ($feeData) {
            foreach ($feeData['detail'] as $k => $v) {
				// ���˵���Ҫ�ķ�������
				if (in_array($k, $filterArr)) {
					continue;
				}

                $key = 1; // Ĭ�ϵ�key��ָ����֧���ɱ�

                // �ж��Ƿ���Ա�����ɱ�
                if (in_array($k, $subsidyArr)) {
                    $key = 0;
                }

                $feeCache[$key][$k] = $v;
            }
        }

        return $feeCache;
    }

    /**
     * �����ɱ�
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     * �����߼�
     * ���ʾ��� ����Ŀ�� feePerson�ֶ��л�ȡ
     * ��¼���� �ӷ��ñ����л�ȡ
     * ���Ჹ�� �ӷ���ά���л�ȡ
     * �������������ݵ�ʱ����Ҫ�����۵����������ͽ��кϲ�
     */
    function getPersonFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        // �����еĲ�������
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        $rowsCache = array();

        // ����Ԥ����� - ����
        $salaryCount = 0;
        $salaryBudget = 0; // ����Ԥ��Ҫ���¼���

        if ($project['feeSubsidyImport'] > 0) {
            $rowsCache['cost'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '�����ɱ������Ჹ����',
                'detCount' => '', 'budgetName' => '���Ჹ��', 'remark' => '', 'realName' => '���Ჹ��',
                'amount' => 0, 'actFee' => $project['feeSubsidyImport'], 'isImport' => 0,
                'amountWait' => 0, 'actFeeWait' => 0, 'status' => 0
            );
        }

        foreach ($budget as $v) {
            // ����Ԥ��
            if (in_array($v['budgetName'], $subsidyArr)) {
                if (!isset($rowsCache['expense'])) {
                    $rowsCache['expense'] = array(
                        'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '�����ɱ�����¼������',
                        'budgetName' => '����', 'remark' => '', 'realName' => $v['parentName'],
                        'amount' => $v['amount']
                    );
                } else {
                    $rowsCache['expense']['amount'] = bcadd($rowsCache['expense']['amount'], $v['amount'], 2);
                }
            } else {
                $salaryCount++;
                $salaryBudget = bcadd($salaryBudget, $v['amount'], 2);
            }
        }

        // ���㴦��
        $expenseFee = 0;
        foreach ($fee as $v) {
            $expenseFee = bcadd($expenseFee, $v['CostMoney'], 2);
        }

        // �����������
        if (isset($rowsCache['expense'])) {
            $rowsCache['expense']['actFee'] = $expenseFee;
        } else if ($expenseFee > 0) {
            $rowsCache['expense'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '�����ɱ�����¼������',
                'detCount' => 1, 'budgetName' => '����', 'remark' => '', 'realName' => '����',
                'amount' => 0, 'actFee' => $expenseFee
            );
        }

        if ($salaryBudget > 0 || $project['feePerson'] > 0) {
            // ���ʵ���
            $rowsCache['salary'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '�����ɱ������ʳɱ���',
                'detCount' => $salaryCount, 'budgetName' => '����', 'remark' => '',
                'amount' => $salaryBudget, 'actFee' => $project['feePerson'], 'realName' => '����Ԥ��',
                'feePerson' => $project['feePerson']
            );
        }

        foreach ($rowsCache as $k => $v) {
            switch ($k) {
                case 'salary' :
                    $v['feePerson'] = $v['actFee'];
                    break;
                case 'expense' :
                    $v['feeSubsidy'] = $v['actFee'];
                    break;
                case 'cost' :
                    $v['feeSubsidyImport'] = $v['actFee'];
                    break;
            }
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $rows[] = $v;
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
            $feeAll = bcadd($feeAll, $v['actFee'], 2);
        }

        // �ϼ���
        if (!empty($rows)) {
            // ��������Ϣ����
            array_push($rows, $this->getCountRow_d('budgetPerson', '�����ɱ��ϼ�', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * ����֧���ɱ�
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getFieldFee_d($budget, $fee, $project) {
        // ��ʼ����������
        $costTypeDao = new model_finance_expense_costtype();
        $rows = array();
        $budgetAll = 0; // Ԥ��ϼ�
        $feeAll = 0; // ����ϼ�
        $budgetFormat = array(); // ���ع�Ԥ��
        $feeFormat = array(); // ���ع�����

        // ����Ԥ�㲿��
        if ($budget) {
            // Ԥ�㲿�ֹ���
            // Ԥ��������֣�һ������Ŀ���Ƶ�Ԥ�㣬һ���Ƿ���ά����Ԥ��
            // ���ﴦ���ʱ������޲��ϲ�
            // ��Ӧ����������ֻ�з���ά���о��㣬������Ҫ�ر���
            foreach ($budget as $v) {
                // ����ҵ�������Լ���ʼ���ݣ�����ؽ��ȫ����0��Ȼ����������߼����洦��
                if (!isset($budgetFormat[$v['budgetName']])) {
                    $budgetFormat[$v['budgetName']] = $v;
                    $budgetFormat[$v['budgetName']]['amount'] = 0;
                    $budgetFormat[$v['budgetName']]['actFee'] = 0;
                    $budgetFormat[$v['budgetName']]['amountWait'] = 0;
                    $budgetFormat[$v['budgetName']]['actFeeWait'] = 0;
                }
                // ��������
                if ($v['isImport']) {
                    // Ԥ��
                    $budgetFormat[$v['budgetName']]['amountWait'] =
                        bcadd($budgetFormat[$v['budgetName']]['amountWait'], $v['amountWait'], 2);
                    $budgetFormat[$v['budgetName']]['actFeeWait'] =
                        bcadd($budgetFormat[$v['budgetName']]['actFeeWait'], $v['actFeeWait'], 2);

                    // ����
                    $feeFormat[$v['budgetName']]['feeCostMaintain'] = isset($feeFormat[$v['budgetName']]['feeCostMaintain']) ?
                        bcadd($feeFormat[$v['budgetName']]['feeCostMaintain'], $v['actFee'], 2) : $v['actFee'];
                }
                // Ԥ��
                $budgetFormat[$v['budgetName']]['amount'] =
                    bcadd($budgetFormat[$v['budgetName']]['amount'], $v['amount'], 2);
            }
        }

        // �����ϼ�����
        $parentCostArr = array();

        // �������㲿�ֵ���
        if ($fee) {
            foreach ($fee as $v) {
                // ��������
                $feeFormat[$v['CostTypeName']]['feeExpense'] = isset($feeFormat[$v['CostTypeName']]['feeExpense']) ?
                    bcadd($feeFormat[$v['CostTypeName']]['feeExpense'], $v['CostMoney'], 2) : $v['CostMoney'];

                if (!isset($parentCostArr[$v['CostTypeName']])) {
                    if ($v['ParentCostType']) {
                        $parentCostArr[$v['CostTypeName']] = $v['ParentCostType'];
                    } else {
                        $costType = $costTypeDao->get_d($v['ParentCostTypeID']);
                        $parentCostArr[$v['CostTypeName']] = $costType ? $costType['CostTypeName'] : "�����ɱ�";
                    }
                }
            }
        }

        // ��ȡ֧���ľ���
        $fieldRecordDao = new model_engineering_records_esmfieldrecord();
        $fieldRecordArr = $fieldRecordDao->feeDetail_d('payables', $project['id']);
        if ($fieldRecordArr) {
            foreach ($fieldRecordArr as $k => $v) {
                $feeFormat[$k]['feePayables'] = isset($feeFormat[$k]['feePayables']) ?
                    bcadd($feeFormat[$k]['feePayables'], $v, 2) : $v;
            }
        }

        // �⳵���㴦��
        if ($project['feeCar'] > 0) {
            // ���õ��⳵��Ŀ
            $otherDatasDao = new model_common_otherdatas();
            $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');
            $carParentItem = $otherDatasDao->getConfig('engineering_budget_rent_car_parent');
            if (!isset($parentCostArr[$carItem])) {
                $parentCostArr[$carItem] = $carParentItem;
            }
            // ��������
            $feeFormat[$carItem]['feeCar'] = isset($feeFormat[$carItem]['feeCar']) ?
                bcadd($feeFormat[$carItem]['feeCar'], $project['feeCar'], 2) : $project['feeCar'];
        }

        // ����Ƿ���������е��⳵�ǼǼ�¼,��������صĽ�� (�����⳵�Ǽǵ�Ԥ���� PMS 715)
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentalcarCostArr = $rentalcarDao->getProjectAuditingCarFee($project['id']);
        $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;
        if($auditingCarFee > 0){
            $parentCostArr['�⳵�Ǽǻ���Ԥ��'] = "��������֧����";
            $feeFormat['�⳵�Ǽǻ���Ԥ��']['rentalCarAuditingCost'] = $auditingCarFee;
        }

        // ����ת��
        foreach ($budgetFormat as $v) {
            $remark = array();
            $feeAct = 0;

            // ��������
            if (isset($feeFormat[$v['budgetName']]['feeExpense'])) {
                $v['feeExpense'] = $feeFormat[$v['budgetName']]['feeExpense'];
                $remark[] = "�����ɱ���" . number_format($v['feeExpense'], 2);
                $feeAct = bcadd($feeAct, $v['feeExpense'], 2);
                unset($feeFormat[$v['budgetName']]['feeExpense']);
            }
            // ����ά��
            if (isset($feeFormat[$v['budgetName']]['feeCostMaintain'])) {
                $v['feeCostMaintain'] = $feeFormat[$v['budgetName']]['feeCostMaintain'];
                $remark[] = "����ά����" . number_format($v['feeCostMaintain'], 2);
                $feeAct = bcadd($feeAct, $v['feeCostMaintain'], 2);
                unset($feeFormat[$v['budgetName']]['feeCostMaintain']);
            }
            // ֧������
            if (isset($feeFormat[$v['budgetName']]['feePayables'])) {
                $v['feePayables'] = $feeFormat[$v['budgetName']]['feePayables'];
                $remark[] = "֧�����㣺" . number_format($v['feePayables'], 2);
                $feeAct = bcadd($feeAct, $v['feePayables'], 2);
                unset($feeFormat[$v['budgetName']]['feePayables']);
            }
            // �⳵��ͬ
            if (isset($feeFormat[$v['budgetName']]['feeCar'])) {
                $v['feeCar'] = $feeFormat[$v['budgetName']]['feeCar'];
                $remark[] = "�⳵��ͬ��" . number_format($v['feeCar'], 2);
                $feeAct = bcadd($feeAct, $v['feeCar'], 2);
                unset($feeFormat[$v['budgetName']]['feeCar']);
            }
            $v['actFee'] = $feeAct;
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $feeAct);
            $v['remark'] = implode('��', $remark);
            $rows[] = $v;
            $feeAll = bcadd($feeAll, $feeAct, 2);
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
        }

        // ʣ����㴦��
        foreach ($feeFormat as $k => $v) {
            if (!empty($v)) {
                $remark = array();
                $feeAct = 0;

                if (!isset($parentCostArr[$k])) {
                    $currentCostType = $costTypeDao->getIdAndParentIdByName($k);
                    if (empty($currentCostType)) {
                        $parentCostArr[$k] = '֧���ɱ�';
                    } else {
                        $costType = $costTypeDao->get_d($currentCostType['ParentCostTypeID']);
                        $parentCostArr[$k] = $costType ? $costType['CostTypeName'] : '֧���ɱ�';
                    }
                }

                $row = array(
                    'id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => $parentCostArr[$k],
                    'detCount' => '', 'budgetName' => $k, 'amount' => '0.00'
                );

                foreach ($v as $ki => $vi) {
                    $feeAct = bcadd($feeAct, $vi, 2);
                    switch ($ki) {
                        case 'feeExpense':
                            $row['feeExpense'] = $vi;
                            $remark[] = "�����ɱ���" . number_format($vi, 2);
                            break;
                        case 'feeCostMaintain':
                            $row['feeCostMaintain'] = $vi;
                            $remark[] = "����ά����" . number_format($vi, 2);
                            break;
                        case 'feePayables':
                            $row['feePayables'] = $vi;
                            $remark[] = "֧�����㣺" . number_format($vi, 2);
                            break;
                        case 'feeCar':
                            $row['feeCar'] = $vi;
                            $remark[] = "�⳵��ͬ��" . number_format($vi, 2);
                            break;
                        case 'rentalCarAuditingCost':
                            $row['isRentalCarAuditingCost'] = 1;
                            $remark[] = "�����ɱ���" . number_format($vi, 2);
                            break;
                        default:
                    }
                }

                $row['actFee'] = $feeAct;
                $row['feeProcess'] = 0;
                $row['remark'] = implode('��', $remark);
                $rows[] = $row;
                $feeAll = bcadd($feeAll, $feeAct, 2);
            }
        }

        // ��Ʊ���㵥������
        if ($project['feeFlights'] > 0) {
            $rows[] = array(
                'id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '����֧���ɱ�', 'detCount' => '',
                'budgetName' => '��Ʊ����', 'amount' => '0.00', 'actFee' => $project['feeFlights'],
                'feeFlights' => $project['feeFlights'],
                'feeProcess' => 0, 'remark' => '��Ʊϵͳ��' . number_format($project['feeFlights'], 2)
            );
            $feeAll = bcadd($feeAll, $project['feeFlights'] , 2);
        }

        // �ϼ���
        if (!empty($rows)) {
            // ���ݴ���,��С�඼�ŵ�һ��
            $catchArr = array();
            foreach ($rows as $row){
                if(!isset($catchArr[$row['parentName']])){
                    $catchArr[$row['parentName']] = array();
                }
                $catchArr[$row['parentName']][] = $row;
            }
            $rows = array();
            foreach ($catchArr as $cRow){
                $rows = array_merge($rows,$cRow);
            }

            // ��������Ϣ����
            array_push($rows, $this->getCountRow_d('budgetField', '����֧���ɱ��ϼ�', $budgetAll, $feeAll));
        }
        return $rows;
    }

    /**
     * �豸�ɱ�
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getEquFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        //ʵ������Ŀ����ά��
        $esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
        foreach ($budget as $v) {
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $v['feeEquImport'] = $v['actFee'];
            if ($v['isImport']) {
                //��ȡ����Ԥ��ķ���ά����¼��
                $v['detCount'] = "[" . $esmcostmaintainDao->findCount(array('projectId' => $project['id'],
                        'parentCostType' => $v['parentName'], 'costType' => $v['budgetName'])) . "]";
            }
            $rows[] = $v;
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
            $feeAll = bcadd($feeAll, $v['actFee'], 2);
        }

        $esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
        $esmDeviceFeeActOld = $esmDeviceFeeDao->getDeviceFee_d($project['id'], 1);
        $esmDeviceFeeActNew = $esmDeviceFeeDao->getDeviceFee_d($project['id'], 2);

        if ($project['budgetEqu'] > 0 || $project['feeEqu'] > 0 || $esmDeviceFeeActOld || $esmDeviceFeeActNew) {
            // �豸����
            $feeEquAll = bcadd($project['feeEqu'], $esmDeviceFeeActOld, 2);
            $feeEquAll = bcadd($feeEquAll, $esmDeviceFeeActNew, 2);
            $feeAll = bcadd($feeAll, $feeEquAll, 2);
            // �豸������Ϣ����
            array_push($rows, array(
                'id' => 'budgetEqu', 'budgetType' => 'budgetEqu', 'parentName' => '�豸�ɱ�', 'detCount' => '',
                'budgetName' => '�豸�ɱ�',
                'remark' => '��ʼ�����루' . $project['feeEqu'] .
                    '�����ʲ��۾ɣ�' . number_format($esmDeviceFeeActNew, 2) .
                    '�������豸ϵͳ��' . number_format($esmDeviceFeeActOld, 2) . '��',
                'amount' => $project['budgetEqu'], 'actFee' => $feeEquAll, 'feeEqu' => $feeEquAll,
                'feeProcess' => '0.00'
            ));
            $budgetAll = bcadd($budgetAll, $project['budgetEqu'], 2);
        }

        // �ϼ���
        if (!empty($rows)) {
            // ��������Ϣ����
            array_push($rows, $this->getCountRow_d('budgetEqu', '�豸�ɱ��ϼ�', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * ����ɱ�
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getOutsourcingFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        foreach ($budget as $v) {
            if ($v['isImport']) {
                //��ȡ����Ԥ��ķ���ά����¼��
                $v['detCount'] = "[" . $this->getOutsourcingCount_d($project['id'], $v['parentName'], $v['budgetName']) . "]";
            }
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $v['feeOutsourcing'] = $v['actFee'];
            $rows[] = $v;
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
            $feeAll = bcadd($feeAll, $v['actFee'], 2);
        }

        // �ϼ���
        if (!empty($rows)) {
            // ��������Ϣ����
            array_push($rows, $this->getCountRow_d('budgetOutsourcing', '����ɱ��ϼ�', $budgetAll, $feeAll));
        }

        return $rows;
    }

	/**
	 * ��ȡ�����ϸ����
	 * @param $projectId
	 * @param $parentCostType
	 * @param $costType
	 * @return int
	 */
	function getOutsourcingCount_d($projectId, $parentCostType, $costType) {
		$sql = "SELECT *
			FROM
			(
				SELECT budget,fee FROM oa_esm_costmaintain WHERE
					projectId = " . $projectId . " AND parentCostType = '" . $parentCostType . "' AND costType = '" . $costType . "'
				UNION ALL
				SELECT 0 AS budget, i.prepaid AS fee
				FROM
					oa_outsourcing_nprepaid c
					INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
				WHERE c.projectId = " . $projectId . " AND c.businessType = '" . $costType . "'
			) c";
		$data = $this->_db->getArray($sql);

		return empty($data)? 0 : count($data);
	}

    /**
     * �����ɱ�
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getOtherFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        foreach ($budget as $v) {
            // ��������
            if (isset($fee[$v['budgetName']])) {
                $v['actFee'] = bcadd($fee[$v['budgetName']]['CostMoney'], $v['actFee'], 2);
                unset($fee[$v['budgetName']]);
            }
            $v['feeOther'] = $v['actFee'];
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $rows[] = $v;
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
            $feeAll = bcadd($feeAll, $v['actFee'], 2);
        }

        if (!empty($fee)) {
            foreach ($fee as $v) {
                $rows[] = array(
                    'id' => 'budgetOther', 'budgetType' => 'budgetOther', 'parentName' => '�����ɱ�',
                    'budgetName' => $v['CostTypeName'], 'remark' => '',
                    'amount' => 0, 'actFee' => $v['CostMoney'], 'feeProcess' => '0.00',
                    'feeOther' => $v['CostMoney']
                );
                $feeAll = bcadd($feeAll, $v['CostMoney'], 2);
            }
        }

        //PK���û�ȡ
        $projectDao = new model_engineering_project_esmproject();
        $triProjectInfo = $projectDao->getPKInfo_d($project['id'], $project);
        if ($triProjectInfo) {
            foreach ($triProjectInfo as $v) {
                //��������Ϣ����
                array_push($rows, array(
                    'id' => 'budgetTrial', 'budgetType' => 'budgetTrial', 'parentName' => '������Ŀ', 'detCount' => '',
                    'budgetName' => $v['projectCode'], 'remark' => $v['projectName'], 'projectId' => $v['id'],
                    'amount' => $v['budgetAll'], 'actFee' => $v['feeAll'], 'feePK' => $v['feeAll'],
                    'feeProcess' => $v['budgetAll'] == 0 ? 0 : bcmul(bcdiv($v['feeAll'], $v['budgetAll'], 4), 100, 2)
                ));
                $budgetAll = bcadd($budgetAll, $v['budgetAll'], 2);
                $feeAll = bcadd($feeAll, $v['feeAll'], 2);
            }
        }

        // �ϼ���
        if (!empty($rows)) {
            // ��������Ϣ����
            array_push($rows, $this->getCountRow_d('budgetOther', '�����ɱ��ϼ�', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * ���ȼ���
     * @param $budget
     * @param $fee
     * @return string
     */
    function countProcess_d($budget, $fee) {
        if ($budget && $budget != 0.00 && $fee && $fee != 0.00) {
            return round(bcmul(bcdiv($fee, $budget, 6), 100, 4), 2);
        } else {
            return 0;
        }
    }

    /**
     * ͳ��������
     * @param $budgetType
     * @param $budgetName
     * @param $budget
     * @param $fee
     * @return array
     */
    function getCountRow_d($budgetType, $budgetName, $budget, $fee) {
        // ��������Ϣ����
        return array(
            'id' => 'noId', 'budgetType' => $budgetType, 'parentName' => $budgetName,
            'detCount' => '','budgetName' => '', 'remark' => '',
            'amount' => $budget, 'actFee' => $fee,
            'feeProcess' => $this->countProcess_d($budget, $fee)
        );
    }

    /**
     * @param $projectId
     * @param $budgetName
     * @param $budgetType
     * @return array
     */
    function feeDetail_d($projectId, $budgetName, $budgetType) {
        $result = array();

        switch ($budgetType) {
            case 'budgetField' :
                // ��ȡ�⳵����
                $esmprojectDao = new model_engineering_project_esmproject();
                $esmprojectObj = $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,feeCar');

				// ���˵ı�������
				$otherDatasDao = new model_common_otherdatas();
				$filterArr = $otherDatasDao->getConfig('engineering_budget_expense', null, 'arr');
				if (!in_array($budgetName, $filterArr)) {
					// ��ȡ��������
					$expenseDao = new model_finance_expense_expense();
					$expenseItem = $expenseDao->getFeeDetailGroupMonth_d($esmprojectObj['projectCode'], $budgetName);

					// �ϲ�����
					$result = $this->feeMerge_d($result, $expenseItem, array(
						'budgetType' => '�����ɱ�'
					));
				}

                // ��ȡ����ά��
                $esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
                $esmCost = $esmcostmaintainDao->getDetailGroupMonth_d($projectId, $budgetName);

                // �ϲ�����
                $result = $this->feeMerge_d($result, $esmCost, array(
                    'budgetType' => '����ά��'
                ));

                // ��ȡ֧���ľ���
                $fieldRecordDao = new model_engineering_records_esmfieldrecord();
                $fieldRecordArr = $fieldRecordDao->getDetailGroupMonth_d('payables', $projectId, $budgetName);

                // �ϲ�����
                $result = $this->feeMerge_d($result, $fieldRecordArr, array(
                    'budgetType' => '֧������'
                ));

                // �����еĲ���ID
                $otherDatasDao = new model_common_otherdatas();
                $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');

                // �⳵���㴦��
                if ($carItem == $budgetName && $esmprojectObj['feeCar'] > 0) {
                    // �⳵����
                    $rentCarArr = $fieldRecordDao->getDetailGroupMonth_d('rentCar', $projectId, $budgetName);

                    // �ϲ�����
                    $result = $this->feeMerge_d($result, $rentCarArr, array(
                        'budgetType' => '�⳵��ͬ'
                    ));
                }

                // ��Ʊ����
                if ($budgetName == '��Ʊ����') {
                    // ��Ʊ����
                    $esmFlightsDao = new model_engineering_records_esmflights();
                    $flightArr = $esmFlightsDao->getDetailGroupMonth_d($projectId);

                    // �ϲ�����
                    $result = $this->feeMerge_d($result, $flightArr, array(
                        'budgetType' => '��Ʊ����'
                    ));
                }


                break;
            default :
        }

        if (!empty($result)) {
            $countArr = array(
                'budgetType' => '�ϼ�',
                'actFee' => 0
            );
            foreach ($result as $v) {
                $countArr['actFee'] = bcadd($countArr['actFee'], $v['actFee'], 2);
            }
            $result[] = $countArr;
        }

        return $result;
    }

    /**
     * �������ݺϲ�
     * @param $orgData
     * @param $appendData
     * @param array $otherAppend
     * @return array
     */
    function feeMerge_d($orgData, $appendData, $otherAppend = array()) {
        if (!empty($appendData)) {
            foreach ($appendData as $v) {
                if (!empty($otherAppend)) {
                    foreach ($otherAppend as $ki => $vi) {
                        $v[$ki] = $vi;
                    }
                }
                $orgData[] = $v;
            }
        }
        return $orgData;
    }

	/**
	 * ���ֳ�Ԥ�㣬���Ԥ�㣬����Ԥ�����뵽��������
	 * @param $rowArr ��������
	 * @param $rows ��������Ԥ������
	 * @param $rowSum ��Ŀ�ϼ�����
	 * @param $feeArr ����ά��������
	 * @param $param ��������
	 */
	function getNormalData_d($rowArr, $rows, $rowSum, $feeArr, $param) {
		// �����еĲ���ID
		$otherDatasDao = new model_common_otherdatas();
		$subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');
        $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');

        // ��ȡ�⳵����
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('id' => $param['projectId']), null, 'feeCar');

        // �Ƿ��Ѿ������⳵����
        $isNeedImportCar = $esmprojectObj['feeCar'] != 0;

		//ʵ������Ŀ����ά��
		$esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
		foreach ($rows as $key => $val) {
			//�������
			if ($rows[$key]['budgetType'] == 'budgetField' && $rows[$key]['isImport'] == '0'
				&& isset($feeArr['detail'][$rows[$key]['budgetName']])) {

				$rows[$key]['actFee'] = $feeArr['detail'][$rows[$key]['budgetName']]['CostMoney'];
				$rows[$key]['feeProcess'] = bcmul(bcdiv($rows[$key]['actFee'], $rows[$key]['amount'], 4), 100, 2);

				unset($feeArr['detail'][$rows[$key]['budgetName']]);
			} else {
				$rows[$key]['feeProcess'] = bcmul(bcdiv($rows[$key]['actFee'], $rows[$key]['amount'], 4), 100, 2);
			}

			//�������С��ĸ���
			if ($rows[$key]['num'] > 1) {
				$rows[$key]['detCount'] = "[" . $rows[$key]['num'] . "]";
			} elseif ($val['isImport'] == 1) {
				//��ȡ����Ԥ��ķ���ά����¼��
				$rows[$key]['detCount'] = "[" . $esmcostmaintainDao->findCount(array('projectId' => $param['projectId'],
						'parentCostType' => $val['parentName'], 'costType' => $val['budgetName'])) . "]";
			} else {
				$rows[$key]['detCount'] = "";
			}

            // �������
            if ($isNeedImportCar && $carItem == $rows[$key]['budgetName']) {
                $rows[$key]['actFee'] = bcadd($rows[$key]['actFee'], $esmprojectObj['feeCar'], 2);
                $rows[$key]['remark'] .= '�������⳵��ͬ���㣺' . number_format($esmprojectObj['feeCar'], 2) . '��';
                $isNeedImportCar = false;
            }

			//ȥ��Ԥ��;��㶼Ϊ0������
			if (($rows[$key]['actFee'] != 0 || $rows[$key]['amount'] != 0)) {
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $rows[$key]['actFee'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $rows[$key]['amount'], 2);
				array_push($rowArr, $rows[$key]);
			}
		}

		// û��Ԥ��ľ������
		if (($param['budgetType'] == 'budgetField' || $param['budgetType'] == '') && !empty($feeArr['detail'])) {
			$rowFee = $feeArr['detail'];
			foreach ($rowFee as $k => $v) {
				// ȥ��������ֽ��
                if ($isNeedImportCar && $rowFee[$k]['CostTypeName'] == $carItem) {
                    $feeSum = bcadd($rowFee[$k]['CostMoney'], $esmprojectObj['feeCar'], 2);
                    $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '��������֧����',
                        'amount' => 0, 'feeProcess' => '0.00', 'budgetName' => $rowFee[$k]['CostTypeName'], 'actFee' => $feeSum,
                        'remark' => 'δ�ڷ���Ԥ���ڵķ��ã�' . $rowFee[$k]['CostMoney'] .
                            ' + �������⳵��ͬ���㣺' . number_format($esmprojectObj['feeCar'], 2) . '��');
                    $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeSum, 2);
                    array_push($rowArr, $feeArr2);
                } else if (!in_array($rowFee[$k]['CostTypeName'], $subsidyArr)) {
                    $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '�ֳ�Ԥ��',
                        'amount' => 0, 'feeProcess' => '0.00', 'remark' => 'δ�ڷ���Ԥ���ڵķ���',
                        'budgetName' => $rowFee[$k]['CostTypeName'], 'actFee' => $rowFee[$k]['CostMoney']);
                    $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeArr2['actFee'], 2);
                    array_push($rowArr, $feeArr2);
                }
			}
		}

        // ȥ��������ֽ��
        if ($isNeedImportCar) {
            $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '��������֧����',
                'amount' => 0, 'feeProcess' => '0.00', 'budgetName' => $carItem, 'actFee' => $esmprojectObj['feeCar'],
                'remark' =>  '�⳵��ͬ���㣺' . number_format($esmprojectObj['feeCar'], 2));
            $rowSum['actFee'] = bcadd($rowSum['actFee'], $esmprojectObj['feeCar'], 2);
            array_push($rowArr, $feeArr2);
        }

		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;
		return $result;
	}

	/**
	 * ��������Ԥ��
	 * @param $rowArr
	 * @param $rowSum
	 * @param $param
	 * @param $personCount
	 * @param $feeArr
	 * @param $personRows
	 * @return mixed
	 */
	function getBudgetPerson_d($rowArr, $rowSum, $param, $personCount, $feeArr, $personRows) {
		$projectDao = new model_engineering_project_esmproject();
		$projectObj = $projectDao->get_d($param['projectId']);
        // ���������еĲ���ID
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

		// ���ֳ���ȡ����������
		if (!empty($personRows)) {
			foreach ($personRows as $v) {
				if (!$v['isImport']) {
					if (isset($feeArr['detail'][$v['budgetName']])) {
						$v['actFee'] = $feeArr['detail'][$v['budgetName']]['CostMoney'];
						$v['feeProcess'] = bcmul(bcdiv($v['actFee'], $v['amount'], 4), 100, 2);
						unset($feeArr['detail'][$v['budgetName']]);
					} else {
						$v['actFee'] = 0;
						$v['feeProcess'] = 0;
					}
                    $v['parentName'] = '��������(��¼����)';
				} else {
					$v['feeProcess'] = bcmul(bcdiv($v['actFee'], $v['amount'], 4), 100, 2);
                    $v['realName'] = $v['parentName'];
                    $v['parentName'] = '��������(���Ჹ��)';
				}
				$v['detCount'] = '';
				$rowArr[] = $v;
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $v['actFee'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $v['amount'], 2);

				$projectObj['budgetPerson'] = bcsub($projectObj['budgetPerson'], $v['amount'], 2);
			}
		}

		// ��ʼ������Ԥ����
		$rowPerson = array('budgetType' => 'budgetPerson', 'parentName' => '��������(���ʳɱ�)', 'budgetName' => '����Ԥ��',
			'actFee' => $projectObj['feePerson'], 'amount' => $projectObj['budgetPerson'],
			'feeProcess' => bcmul(bcdiv($projectObj['feePerson'], $projectObj['budgetPerson'], 4), 100, 2));

		if ($rowPerson['amount'] != 0 || $rowPerson['actFee'] != 0) {
			if ($personCount > 1) {
				$rowPerson['detCount'] = $personCount;
			} else if ($personCount == 0) {
				$rowPerson['id'] = 'budgetPerson';
				$rowPerson['detCount'] = '';
			} else {
				$rowPerson['detCount'] = '';
			}
			$rowSum['actFee'] = bcadd($rowSum['actFee'], $rowPerson['actFee'], 2);
			$rowSum['amount'] = bcadd($rowSum['amount'], $rowPerson['amount'], 2);
			array_push($rowArr, $rowPerson);
		}

        // û��Ԥ��ľ������
        if (($param['budgetType'] == 'budgetPerson' || $param['budgetType'] == '') && !empty($feeArr['detail'])) {
            $rowFee = $feeArr['detail'];
            foreach ($rowFee as $k => $v) {
                // ȥ��������ֽ��
                if (in_array($rowFee[$k]['CostTypeName'], $subsidyArr)) {
                    $feeArr2 = array('id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '��������(��¼����)',
                        'amount' => 0, 'feeProcess' => '0.00', 'remark' => 'δ�ڷ���Ԥ���ڵķ���',
                        'budgetName' => $rowFee[$k]['CostTypeName'], 'actFee' => $rowFee[$k]['CostMoney']);
                    $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeArr2['actFee'], 2);
                    array_push($rowArr, $feeArr2);
                }
            }
        }

		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;
		return $result;
	}

	/**
	 * �����豸Ԥ��
	 * @param $rowArr
	 * @param $rowSum
	 * @param $param
	 * @return mixed
	 */
	function getBudgetDevice_d($rowArr, $rowSum, $param) {
		$projectDao = new model_engineering_project_esmproject();
		$projectObj = $projectDao->get_d($param['projectId']);

		//�洢�豸Ԥ�������
		$rowDevice = array('budgetType' => 'budgetDevice', 'parentName' => '�豸Ԥ��', 'budgetName' => '�豸Ԥ��', 'detCount' => '');
		$rowDevice['amount'] = $projectObj['budgetEqu'];
		$esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
		$rowDevice['actFee'] = bcadd($projectObj['feeEqu'], $esmDeviceFeeDao->getDeviceFee_d($projectObj['id']), 2);

		if ($rowDevice['amount'] != 0 || $rowDevice['actFee'] != 0) {
			$rowDevice['feeProcess'] = bcmul(bcdiv($rowDevice['actFee'], $rowDevice['amount'], 4), 100, 2);
			$rowSum['actFee'] = bcadd($rowSum['actFee'], $rowDevice['actFee'], 2);
			$rowSum['amount'] = bcadd($rowSum['amount'], $rowDevice['amount'], 2);
			array_push($rowArr, $rowDevice);
		}
		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;

		return $result;
	}

	/**
	 * ����������Ŀ
	 * @param $rowArr
	 * @param $rowSum
	 * @param $param
	 * @param null $esmprojectObj
	 * @return mixed
	 */
	function getBudgetTrial_d($rowArr, $rowSum, $param, $esmprojectObj = null) {
		//PK���û�ȡ
		$esmprojectDao = new model_engineering_project_esmproject();
		$triProjectInfo = $esmprojectObj ? $esmprojectDao->getPKInfo_d($param['projectId'], $esmprojectObj) : $esmprojectDao->getPKInfo_d($param['projectId']);
		if ($triProjectInfo) {
			foreach ($triProjectInfo as $val) {
				//��������Ϣ����
				$rowTrial = array(
					'id' => 'budgetTrial', 'budgetType' => 'budgetTrial', 'parentName' => '������Ŀ', 'detCount' => '',
					'budgetName' => $val['contractCode'], 'remark' => $val['projectName'], 'projectId' => $val['id'],
					'amount' => $val['budgetAll'], 'actFee' => $val['feeAll'],
					'feeProcess' => $val['budgetAll'] == 0 ? 0 : bcmul(bcdiv($val['feeAll'], $val['budgetAll'], 4), 100, 2)
				);
				array_push($rowArr, $rowTrial);
				//�ѽ����ص��б�ϼ���
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $val['feeAll'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $val['budgetAll'], 2);
			}
		}
		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;
		return $result;
	}

	/**
	 * ������Ŀid����Ԥ����Ϣ
	 * @param $projectId
	 * @return array
	 */
	function getBudgetForCar_d($projectId) {
		$budgetInfo = array();

		if ($projectId) {
			// ��ȡ�⳵Ԥ��
			$sql = "SELECT c.CostTypeID,c.budgetType,b.amount
			FROM
				cost_type c LEFT JOIN oa_esm_project_budget b ON c.CostTypeID = b.budgetId
			WHERE
				projectId = $projectId AND c.budgetType IN('YSLX-02', 'YSLX-03', 'YSLX-04', 'YSLX-08', 'YSLX-05', 'YSLX-06')";
			$data = $this->_db->getArray($sql);

			if ($data) {
				foreach ($data as $v) {
					$budgetInfo[$v['budgetType']] = $v['amount'];
				}
			}
		}

		return $budgetInfo;
	}

    /**
     * ������ĿID,�����䵱ǰ���Ԥ���Ԥ������Ϣ
     * @param $Arr
     */
    function updateCostByProjectIds($Arr) {
        foreach ($Arr as $pId){
            $rs = $this->findAll(array('projectId' => $pId, 'parentName' => "���Ԥ��"), null , "id,budgetName,amount,actFee");
            if(!empty($rs)){
                foreach ($rs as $rsv){
                    $inArr = array("projectId"=>$pId,"parentCostType"=>"���Ԥ��","costType"=>$rsv['budgetName']);
                    $budgetArr = $this->getCostmaintain_d($inArr);

                    $actFee = 0;
                    foreach ($budgetArr as $v) {
                        $actFee = bcadd($actFee, $v['fee'], 2);
                    }

                    // ���Ԥ��;��㶼Ϊ0��ʱ��ֱ��ɾ��Ԥ�����¼
                    if ($budgetArr[0]['budget'] == 0 && $actFee == 0) {
                        $log = "��վ���Ŀ����ά����" . $inArr['costType'] . "��";
                        parent::delete(array(
                            'id' => $rsv['id']
                        ));
                    } else {
                        parent::edit_d(array(
                            'id' => $rsv['id'], 'actFee' => $actFee, 'amount' => $budgetArr[0]['budget'],  'status' => 1
                        ), true);
                        $log = "���¾���Ŀ����ά����" . $inArr['costType'] . "����Ԥ�㡾" . $budgetArr[0]['budget'] . "�������㡾" .
                            $actFee . "��";
                    }

                    //��¼������־
                    $esmlogDao = new model_engineering_baseinfo_esmlog();
                    $esmlogDao->addLog_d($inArr['projectId'], '�������', $log);
                }
            }
        }
    }

	/**
	 * ������Ŀ����ά������Ԥ��
	 * @param $inArr
	 * @return bool
	 * @throws Exception
	 */
	function importByCostMainTain($inArr) {
		// �����еĲ�������
		$otherDatasDao = new model_common_otherdatas();
		$subsidyIdArr = $otherDatasDao->getConfig('engineering_budget_subsidy_id', null, 'arr');

		try {
			$budgetArr = $this->getCostmaintain_d($inArr);

			// �ж��ǽ��и��»�����������
			$rs = $this->find(array('projectId' => $inArr['projectId'], 'parentName' => $inArr['parentCostType'],
                'budgetName' => $inArr['costType'], 'isImport' => 1), null, 'id,actFee');

			if (!empty($rs)) {
                $actFee = 0;
                foreach ($budgetArr as $v) {
                    $actFee = bcadd($actFee, $v['fee'], 2);
                }
                // ���Ԥ��;��㶼Ϊ0��ʱ��ֱ��ɾ��Ԥ�����¼
                if ($budgetArr[0]['budget'] == 0 && $actFee == 0) {
                    $log = "��շ���ά����" . $inArr['costType'] . "��";
                    parent::delete(array(
                        'id' => $rs['id']
                    ));
                } else {
                    parent::edit_d(array(
                        'id' => $rs['id'], 'actFee' => $actFee, 'amount' => $budgetArr[0]['budget'],  'status' => 1
                    ), true);
                    $log = "���·���ά����" . $inArr['costType'] . "����Ԥ�㡾" . $budgetArr[0]['budget'] . "�������㡾" .
                        $actFee . "��";
                }
			} else {
                $object = array(
                    'amount' => $budgetArr[0]['budget'], 'status' => 1, 'projectId' => $inArr['projectId'],
                    'projectCode' => $inArr['projectCode'], 'projectName' => $inArr['projectName'],
                    'planBeginDate' => $inArr['planBeginDate'], 'planEndDate' => $inArr['planEndDate'],
                    'parentName' => $inArr['parentCostType'], 'parentId' => $inArr['parentCostTypeId'],
                    'budgetName' => $inArr['costType'], 'budgetId' => $inArr['costTypeId'],
                    'actFee' => $inArr['fee'], 'price' => $budgetArr[0]['budget'], 'isImport' => 1
                );

				// ������벹�������������Ԥ����
				if (in_array($inArr['costTypeId'], $subsidyIdArr)) {
					$object['budgetType'] = 'budgetPerson';
				} else {
					if($inArr['parentCostType'] == '���Ԥ��'){
						$object['budgetType'] = 'budgetOutsourcing';//Ԥ������Ϊ�����Ԥ�㡿
						$object['actFee'] = $budgetArr[0]['fee']; // ��ȡ��������ܾ���
					}else if($inArr['parentCostType'] == '�豸Ԥ��'){
                        $object['budgetType'] = 'budgetEqu';//Ԥ������Ϊ���豸Ԥ�㡿
                    } else {
						$object['budgetType'] = 'budgetField';//Ԥ������Ϊ���ֳ�Ԥ�㡿
					}
				}
                $log = "�������ά����" . $inArr['costType'] . "����Ԥ�㡾" . $budgetArr[0]['budget'] . "�������㡾" .
                    $inArr['fee'] . "��";

				parent::add_d($object, true);
			}

            // ��ĿԤ�����ؼ�
            $this->recount_d($inArr['projectId']);

			//��¼������־
			$esmlogDao = new model_engineering_baseinfo_esmlog();
			$esmlogDao->addLog_d($inArr['projectId'], '�������', $log);

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �����һ����ȡ������ݽӿ�
	 * @param $inArr
	 * @return array|bool
	 */
	function getCostmaintain_d($inArr) {
		if ($inArr['parentCostType'] == "���Ԥ��") {
			$sql = "SELECT SUM(budget) AS budget, SUM(fee) AS fee
				FROM
				(
					SELECT budget,fee FROM oa_esm_costmaintain
					WHERE projectId = " . $inArr['projectId'] . " AND parentCostType = '" . $inArr['parentCostType'] . "' AND costType = '" . $inArr['costType'] . "'
					UNION ALL
					SELECT 0 AS budget, i.prepaid AS fee
					FROM
						oa_outsourcing_nprepaid c
						INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
					WHERE c.projectId = " . $inArr['projectId'] . " AND c.businessType = '" . $inArr['costType'] . "'
				) c";
			// ��ȡ����ά����
			$budgetArr = $this->_db->getArray($sql);
		} else {
			//ʵ������Ŀ����ά��
			$esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
			// ��ȡ������Ŀ����ά����Ԥ��ֵbudget
			$budgetArr = $esmcostmaintainDao->findAll(
				array(
					'projectId' => $inArr['projectId'], 'parentCostType' => $inArr['parentCostType'], 'costType' => $inArr['costType']
				),
				'month DESC', 'budget,fee');
		}
		return $budgetArr;
	}

    /**
     * ����Ԥ�������
     * @param $projectId
     * @throws Exception
     */
    function recount_d($projectId) {
		try {
			//��ȡ������Ϣ
			$feeInfo = $this->findAll("budgetType IN('budgetField','budgetOutsourcing','budgetEqu') AND projectId = ".$projectId,
				null, 'budgetType,actFee,amount,isImport,status');
            $budgetField = 0; // �ֳ�Ԥ��
			$feeFieldImport = 0;//�����ֳ�����
			$feeOutsourcing = 0;//�������
			$budgetOutsourcing = 0;//���Ԥ��
            $feeEquImport = 0; // �豸����
			foreach ($feeInfo as $v) {
				if($v['budgetType'] == 'budgetField'){//�ֳ�Ԥ����
					if($v['isImport'] == 1 && $v['status'] == 1){
						$feeFieldImport = bcadd($v['actFee'], $feeFieldImport, 2);
					}
                    $budgetField = bcadd($budgetField, $v['amount'], 2);
				} elseif($v['budgetType'] == 'budgetEqu') {
                    if($v['isImport'] == 1 && $v['status'] == 1){
                        $feeEquImport = bcadd($v['actFee'], $feeEquImport, 2);
                    }
                } else {//���Ԥ����
					if($v['isImport'] == 1){
						if($v['status'] == 1){
							$feeOutsourcing = bcadd($v['actFee'], $feeOutsourcing, 2);
							$budgetOutsourcing = bcadd($v['amount'], $budgetOutsourcing, 2);
						}
					}else{
						$feeOutsourcing = bcadd($v['actFee'], $feeOutsourcing, 2);
						$budgetOutsourcing = bcadd($v['amount'], $budgetOutsourcing, 2);
					}
				}
			}
			//ʵ����������Ŀ
			$esmprojectDao = new model_engineering_project_esmproject();

			//���¹�����Ŀ�����ֳ�����,�ֳ�Ԥ��,���Ԥ����
			$esmprojectDao->update(array('id' => $projectId), array(
                'feeFieldImport' => $feeFieldImport, 'feeOutsourcing' => $feeOutsourcing, 'budgetField' => $budgetField,
				'budgetOutsourcing' => $budgetOutsourcing, 'feeEquImport' => $feeEquImport
            ));

			//���¼�����Ŀ��Ԥ��
			$esmprojectDao->updateBudgetAll_d($projectId);
            //���������
            $esmprojectDao->calProjectFee_d(null, $projectId);
            //����������
            $esmprojectDao->calFeeProcess_d($projectId);
		} catch (Exception $e) {
			throw $e;
		}
	}

    //��һ�����󻺴� -- ��Ϊ�����ʱ��ķѵ�����̫�࣬����Ҫ�Ż�
    static $initObjCache = array();

    //��ȡ���󻺴�ķ���
    static function getObjCache($className) {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }
	
	/**
	 * ��ȡ��������
	 * @param $sql
	 * @return array
	 */
	function getExcelData_d($sql) {
		set_time_limit(0);
		$rs = $this->_db->getArray($sql);
		if(!empty($rs)){
			$data = array();
            $projectDao = self::getObjCache('model_engineering_project_esmproject');

			foreach ($rs as $v){
                if ($v['pType'] == 'esm') {
                    $projectId = $v['id'];
                    $projectCode = $v['projectCode'];
                    $statusName = $v['statusName'];

                    $dataCache = array(); // ��ʼ��

                    // ����ȡ����Ŀ - ��Ҫ�ǻ�ȡĳЩ�̻�����Ŀ���еĳɱ�����
                    $project = $projectDao->get_d($projectId);

                    // ��ȡԤ��
                    $budgetData  = $this->getBudgetData_d(array('projectId' => $projectId));

                    // Ԥ���з�
                    $budgetCache = $this->budgetSplit_d($budgetData);

                    // ��ȡ����
                    $feeData = $this->getFee_d($projectId);

                    // �����з�
                    $feeCache = $this->feeSplit_d($feeData);

                    // �����ɱ�
                    $dataCache[0] = $this->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

                    // ����֧���ɱ�
                    $dataCache[1] = $this->getFieldFee_d($budgetCache[1], $feeCache[1], $project);

                    // �豸�ɱ�
                    $dataCache[2] = $this->getEquFee_d($budgetCache[2], $feeCache[2], $project);

                    // ����ɱ�
                    $dataCache[3] = $this->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

                    // �����ɱ�
                    $dataCache[4] = $this->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

                    // �����ѯ�����ݣ�����������������
                    if (!empty($dataCache)) {
                        foreach ($dataCache as $vi) {
                            foreach ($vi as $vii) {
                                if ($vii['id'] != 'noId') {
                                    $vii['projectCode'] = $projectCode;
                                    $vii['statusName'] = $statusName;
                                    $data[] = $vii;
                                }
                            }
                        }
                    }
                }
			}
			return $data;
		}else{
			return '';
		}
	}
}