<?php

/**
 * @author Show
 * @Date 2011年12月22日 星期四 9:49:51
 * @version 1.0
 * @description:项目费用预算(oa_esm_project_budget) Model层
 */
class model_engineering_budget_esmbudget extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_budget";
		$this->sql_map = "engineering/budget/esmbudgetSql.php";
		parent::__construct();
	}

	private $budgetTypeArr = array(
		'budgetField' => '现场成本',
		'budgetPerson' => '人力成本',
		'budgetOutsourcing' => '外包成本',
		'budgetOther' => '其他成本'
	);

	// 返回预算类型中文
	function rtBudgetType($thisVal) {
		return $this->budgetTypeArr[$thisVal];
	}

	/****************************获取外部信息方法***************************/

	/**
	 * 获取项目信息
	 * @param $projectId
	 * @return bool|mixed
	 */
	function getProjectInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName');
	}

	/****************接口方法******************/
	/**
	 * 获取项目中的预算
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
	 * 单次获取全部预算
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
	 * 获取项目预算信息
	 * @param $projectId
	 * @return mixed
	 */
	function getProjectBudgetInfo_d($projectId) {
		return $this->findAll(array('projectId' => $projectId));
	}

	/**
	 * 更新项目信息 - 统一调用方法
	 * @param $object
	 * @param bool $isUpdateFee
	 * @return bool
	 * @throws Exception
	 */
	function updateProject_d($object, $isUpdateFee = false) {
		$esmprojectDao = new model_engineering_project_esmproject();
		try {
			$this->start_d();

			//更新项目费用
			$updateArr = $this->getProjectBudgetOnce_d($object['projectId']);
			//如果需要更新费用
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
	 * 累加更新某一字段
	 * @param $condition
	 * @param $file
	 * @param $val
	 */
	function updateVal($condition, $file, $val) {
		$this->query("UPDATE $this->tbl_name c SET c.{$file}= {$val} WHERE {$condition}");
	}

	/**
	 * 根据id累加更新某一字段
	 * @param $id
	 * @param $file
	 * @param $val
	 */
	function updateValById($id, $file, $val) {
		$this->updateVal("id = '{$id}'", $file, $val);
	}

	/**
	 * 判断该项目是否有资源计划
	 * @param $projectId
	 * @return bool
	 */
	function checkResources_d($projectId) {
		$esmresourcesDao = new model_engineering_resources_esmresources();
		return $esmresourcesDao->find(array("projectId" => $projectId), $sort = null, $fields = null) ? true : false;
	}

	/**
	 * 判断是否有变更数据
	 * @param $projectId
	 * @param $isCreateNew
	 * @return bool
	 * @throws Exception
	 */
	function isChanging_d($projectId, $isCreateNew) {
		//判断是否已经存在变更申请
		$esmchangeDao = new model_engineering_change_esmchange();
		return $esmchangeDao->getChangeId_d($projectId, $isCreateNew);
	}

	/*************************** 内部方法 *****************************/
	/**
	 * 重写add
	 * @param $object
	 * @return bool
	 */
	public function add_d($object) {
		try {
			$this->start_d();

			//判断此修改是否属于变更
			$esmprojectDao = new model_engineering_project_esmproject();
			if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
				//预算变更类
				$esmchangebudDao = new model_engineering_change_esmchangebud();
				$esmchangebudDao->add_d($object, $this);
			} else {
				//新增费用
				parent::add_d($object, true);

				//更新项目的现场预算
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
	 * 批量新增
	 * @param $object
	 * @return bool
	 */
	function addBatch_d($object) {
		//获取从表预算信息
		$budgets = $object['budgets'];
		unset($object['budgets']);
		try {
			$this->start_d();//事务开启

			//按引入预算页面数据的数目来新增预算
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
	 * 数组合并以及筛选
	 * @param $inArr
	 * @param $objectArr
	 * @return mixed
	 */
	function setArrayFn_d($inArr, $objectArr) {
		$otherDatasDao = new model_common_otherdatas();
		$subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

		foreach ($objectArr as $key => $val) {
			//如果金额为空，跳过此数据
			if ($val['amount'] === "") {
				unset($objectArr[$key]);
				continue;
			}
			$objectArr[$key] = array_merge($val, $inArr);

			// 转换预算类型
			if (in_array($val['budgetName'], $subsidyArr)) {
				$objectArr[$key]['budgetType'] = 'budgetPerson';
			}
		}

		return $objectArr;
	}

	/**
	 * 编辑树节点
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//如果存在orgId ,则定义为修改变更的预算
			if ($object['orgId'] != -1) {
				//预算变更类
				$esmchangebudDao = new model_engineering_change_esmchangebud();
				//加入一个判断,如果是为变更的元素,那么加入变更属性值
				if (!$esmchangebudDao->budgetIsChanging_d($object['id'])) {
					$object['isChanging'] = 1;
					$object['changeAction'] = 'edit';
				}
				$esmchangebudDao->editOrg_d($object, $this);
			} else {
				//判断此修改是否属于变更
				$esmprojectDao = new model_engineering_project_esmproject();
				if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
					//预算变更类
					$esmchangebudDao = new model_engineering_change_esmchangebud();
					$esmchangebudDao->edit_d($object, $this);
				} else {
					parent::edit_d($object, true);

					//更新项目的现场预算
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
	 * 简单新增
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
	 * 简单编辑
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
	 * 删除树节点及下属节点
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

		//预算变更类
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmchangebudDao = new model_engineering_change_esmchangebud();
		try {
			$this->start_d();

			//正常记录处理
			if ($id) {
				if ($esmprojectDao->actionIsChange_d($projectId)) {
					$esmchangebudDao->deletes_d($id, $this, $projectId, $changeId);
				} else {
					//删除预算项
					parent::deletes($id);

					//更新项目的现场预算
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
	 * 变更 编辑任务时设置状态
	 * @param $id
	 * @param string $thisAction
	 * @return bool
	 */
	function changeInfoSet_d($id, $thisAction = 'add') {
		return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
	}

	/*********************** 变更部分处理 *************************/
	/**
	 * 变更信息获取
	 * @param $uid
	 * @return bool|mixed
	 */
	function getChange_d($uid) {
		$esmchangebudDao = new model_engineering_change_esmchangebud();
		return $esmchangebudDao->get_d($uid);
	}

	/**
	 * 变更状态还原
	 * @param $projectId
	 * @return bool
	 */
	function revertChangeInfo_d($projectId) {
		return $this->update(array('projectId' => $projectId), array('changeAction' => '', 'isChanging' => '0'));
	}

	/**
	 * 获取项目预算
	 * @param $projectId
	 * @return array
	 */
	function getFee_d($projectId) {
		$projectDao = new model_engineering_project_esmproject();
		$projectArr = $projectDao->find(array('id' => $projectId), null, 'projectCode');

		// 人力决算 以及初始化
		$newArr = array(
			'allCostMoney' => 0
		);

		// 费用获取
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
	 * 没有该级别则不能修改
	 * @param $obj
	 * @return mixed
	 */
	function checkLevelId_d($obj) {
		return $this->_db->getArray("select budgetId from " . $this->tbl_name . " where budgetId = '$obj'");
	}

	/**
	 * 项目预算获取 - 包括所有类型的预算
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
	 * 获取费用小类个数
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
	 * 获取预算
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

        // 配置中的补贴名称
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        if ($budgetData) {
            foreach ($budgetData as $v) {
                switch ($v['budgetType']) {
                    case 'budgetPerson' : // 人力成本
                        $budgetCache[0][] = $v;
                        break;
                    case 'budgetEqu' : // 设备成本
                        $budgetCache[2][] = $v;
                        break;
                    case 'budgetOutsourcing' : // 外包成本
                        $budgetCache[3][] = $v;
                        break;
                    case 'budgetOther' : // 其他成本
                        $budgetCache[4][] = $v;
                        break;
                    default: // 混合成本 - 主要报销支付成本 以及 混合在其中的某些其他成本
                        $key = 1; // 默认的key，指向报销支付成本

                        // 判断是否人员补贴成本
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
     * 决算拆分
     * @param $feeData
     * @return array
     */
    function feeSplit_d($feeData) {
        $feeCache = array();

        // 配置中的补贴名称
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');
		$filterArr = $otherDatasDao->getConfig('engineering_budget_expense', null, 'arr'); // 过滤的报销决算

        if ($feeData) {
            foreach ($feeData['detail'] as $k => $v) {
				// 过滤掉不要的费用类型
				if (in_array($k, $filterArr)) {
					continue;
				}

                $key = 1; // 默认的key，指向报销支付成本

                // 判断是否人员补贴成本
                if (in_array($k, $subsidyArr)) {
                    $key = 0;
                }

                $feeCache[$key][$k] = $v;
            }
        }

        return $feeCache;
    }

    /**
     * 人力成本
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     * 运算逻辑
     * 工资决算 从项目表 feePerson字段中获取
     * 已录补贴 从费用报销中获取
     * 计提补贴 从费用维护中获取
     * 这里在生成数据的时候，需要进行折叠，按大类型进行合并
     */
    function getPersonFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        // 配置中的补贴名称
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        $rowsCache = array();

        // 人力预算计数 - 工资
        $salaryCount = 0;
        $salaryBudget = 0; // 工资预算要重新计算

        if ($project['feeSubsidyImport'] > 0) {
            $rowsCache['cost'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '人力成本（计提补贴）',
                'detCount' => '', 'budgetName' => '计提补贴', 'remark' => '', 'realName' => '计提补贴',
                'amount' => 0, 'actFee' => $project['feeSubsidyImport'], 'isImport' => 0,
                'amountWait' => 0, 'actFeeWait' => 0, 'status' => 0
            );
        }

        foreach ($budget as $v) {
            // 人力预算
            if (in_array($v['budgetName'], $subsidyArr)) {
                if (!isset($rowsCache['expense'])) {
                    $rowsCache['expense'] = array(
                        'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '人力成本（已录补贴）',
                        'budgetName' => '补贴', 'remark' => '', 'realName' => $v['parentName'],
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

        // 决算处理
        $expenseFee = 0;
        foreach ($fee as $v) {
            $expenseFee = bcadd($expenseFee, $v['CostMoney'], 2);
        }

        // 报销决算叠加
        if (isset($rowsCache['expense'])) {
            $rowsCache['expense']['actFee'] = $expenseFee;
        } else if ($expenseFee > 0) {
            $rowsCache['expense'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '人力成本（已录补贴）',
                'detCount' => 1, 'budgetName' => '补贴', 'remark' => '', 'realName' => '补贴',
                'amount' => 0, 'actFee' => $expenseFee
            );
        }

        if ($salaryBudget > 0 || $project['feePerson'] > 0) {
            // 工资叠加
            $rowsCache['salary'] = array(
                'id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '人力成本（工资成本）',
                'detCount' => $salaryCount, 'budgetName' => '工资', 'remark' => '',
                'amount' => $salaryBudget, 'actFee' => $project['feePerson'], 'realName' => '人力预算',
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

        // 合计列
        if (!empty($rows)) {
            // 费用列信息生成
            array_push($rows, $this->getCountRow_d('budgetPerson', '人力成本合计', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * 报销支付成本
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getFieldFee_d($budget, $fee, $project) {
        // 初始化费用类型
        $costTypeDao = new model_finance_expense_costtype();
        $rows = array();
        $budgetAll = 0; // 预算合计
        $feeAll = 0; // 决算合计
        $budgetFormat = array(); // 已重构预算
        $feeFormat = array(); // 已重构决算

        // 构建预算部分
        if ($budget) {
            // 预算部分构建
            // 预算存在两种，一种是项目编制的预算，一种是费用维护的预算
            // 这里处理的时候进行无差别合并
            // 对应的数据里面只有费用维护有决算，所以需要特别处理
            foreach ($budget as $v) {
                // 存入业务数据以及初始数据，将相关金额全部置0，然后在下面的逻辑里面处理
                if (!isset($budgetFormat[$v['budgetName']])) {
                    $budgetFormat[$v['budgetName']] = $v;
                    $budgetFormat[$v['budgetName']]['amount'] = 0;
                    $budgetFormat[$v['budgetName']]['actFee'] = 0;
                    $budgetFormat[$v['budgetName']]['amountWait'] = 0;
                    $budgetFormat[$v['budgetName']]['actFeeWait'] = 0;
                }
                // 处理数据
                if ($v['isImport']) {
                    // 预算
                    $budgetFormat[$v['budgetName']]['amountWait'] =
                        bcadd($budgetFormat[$v['budgetName']]['amountWait'], $v['amountWait'], 2);
                    $budgetFormat[$v['budgetName']]['actFeeWait'] =
                        bcadd($budgetFormat[$v['budgetName']]['actFeeWait'], $v['actFeeWait'], 2);

                    // 决算
                    $feeFormat[$v['budgetName']]['feeCostMaintain'] = isset($feeFormat[$v['budgetName']]['feeCostMaintain']) ?
                        bcadd($feeFormat[$v['budgetName']]['feeCostMaintain'], $v['actFee'], 2) : $v['actFee'];
                }
                // 预算
                $budgetFormat[$v['budgetName']]['amount'] =
                    bcadd($budgetFormat[$v['budgetName']]['amount'], $v['amount'], 2);
            }
        }

        // 缓存上级分类
        $parentCostArr = array();

        // 报销决算部分叠加
        if ($fee) {
            foreach ($fee as $v) {
                // 决算载入
                $feeFormat[$v['CostTypeName']]['feeExpense'] = isset($feeFormat[$v['CostTypeName']]['feeExpense']) ?
                    bcadd($feeFormat[$v['CostTypeName']]['feeExpense'], $v['CostMoney'], 2) : $v['CostMoney'];

                if (!isset($parentCostArr[$v['CostTypeName']])) {
                    if ($v['ParentCostType']) {
                        $parentCostArr[$v['CostTypeName']] = $v['ParentCostType'];
                    } else {
                        $costType = $costTypeDao->get_d($v['ParentCostTypeID']);
                        $parentCostArr[$v['CostTypeName']] = $costType ? $costType['CostTypeName'] : "报销成本";
                    }
                }
            }
        }

        // 获取支付的决算
        $fieldRecordDao = new model_engineering_records_esmfieldrecord();
        $fieldRecordArr = $fieldRecordDao->feeDetail_d('payables', $project['id']);
        if ($fieldRecordArr) {
            foreach ($fieldRecordArr as $k => $v) {
                $feeFormat[$k]['feePayables'] = isset($feeFormat[$k]['feePayables']) ?
                    bcadd($feeFormat[$k]['feePayables'], $v, 2) : $v;
            }
        }

        // 租车决算处理
        if ($project['feeCar'] > 0) {
            // 配置的租车名目
            $otherDatasDao = new model_common_otherdatas();
            $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');
            $carParentItem = $otherDatasDao->getConfig('engineering_budget_rent_car_parent');
            if (!isset($parentCostArr[$carItem])) {
                $parentCostArr[$carItem] = $carParentItem;
            }
            // 决算载入
            $feeFormat[$carItem]['feeCar'] = isset($feeFormat[$carItem]['feeCar']) ?
                bcadd($feeFormat[$carItem]['feeCar'], $project['feeCar'], 2) : $project['feeCar'];
        }

        // 检查是否存在审批中的租车登记记录,并汇总相关的金额 (加上租车登记的预提金额 PMS 715)
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentalcarCostArr = $rentalcarDao->getProjectAuditingCarFee($project['id']);
        $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;
        if($auditingCarFee > 0){
            $parentCostArr['租车登记汇总预提'] = "车辆运行支出费";
            $feeFormat['租车登记汇总预提']['rentalCarAuditingCost'] = $auditingCarFee;
        }

        // 最终转换
        foreach ($budgetFormat as $v) {
            $remark = array();
            $feeAct = 0;

            // 报销决算
            if (isset($feeFormat[$v['budgetName']]['feeExpense'])) {
                $v['feeExpense'] = $feeFormat[$v['budgetName']]['feeExpense'];
                $remark[] = "报销成本：" . number_format($v['feeExpense'], 2);
                $feeAct = bcadd($feeAct, $v['feeExpense'], 2);
                unset($feeFormat[$v['budgetName']]['feeExpense']);
            }
            // 费用维护
            if (isset($feeFormat[$v['budgetName']]['feeCostMaintain'])) {
                $v['feeCostMaintain'] = $feeFormat[$v['budgetName']]['feeCostMaintain'];
                $remark[] = "费用维护：" . number_format($v['feeCostMaintain'], 2);
                $feeAct = bcadd($feeAct, $v['feeCostMaintain'], 2);
                unset($feeFormat[$v['budgetName']]['feeCostMaintain']);
            }
            // 支付决算
            if (isset($feeFormat[$v['budgetName']]['feePayables'])) {
                $v['feePayables'] = $feeFormat[$v['budgetName']]['feePayables'];
                $remark[] = "支付决算：" . number_format($v['feePayables'], 2);
                $feeAct = bcadd($feeAct, $v['feePayables'], 2);
                unset($feeFormat[$v['budgetName']]['feePayables']);
            }
            // 租车合同
            if (isset($feeFormat[$v['budgetName']]['feeCar'])) {
                $v['feeCar'] = $feeFormat[$v['budgetName']]['feeCar'];
                $remark[] = "租车合同：" . number_format($v['feeCar'], 2);
                $feeAct = bcadd($feeAct, $v['feeCar'], 2);
                unset($feeFormat[$v['budgetName']]['feeCar']);
            }
            $v['actFee'] = $feeAct;
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $feeAct);
            $v['remark'] = implode('，', $remark);
            $rows[] = $v;
            $feeAll = bcadd($feeAll, $feeAct, 2);
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
        }

        // 剩余决算处理
        foreach ($feeFormat as $k => $v) {
            if (!empty($v)) {
                $remark = array();
                $feeAct = 0;

                if (!isset($parentCostArr[$k])) {
                    $currentCostType = $costTypeDao->getIdAndParentIdByName($k);
                    if (empty($currentCostType)) {
                        $parentCostArr[$k] = '支付成本';
                    } else {
                        $costType = $costTypeDao->get_d($currentCostType['ParentCostTypeID']);
                        $parentCostArr[$k] = $costType ? $costType['CostTypeName'] : '支付成本';
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
                            $remark[] = "报销成本：" . number_format($vi, 2);
                            break;
                        case 'feeCostMaintain':
                            $row['feeCostMaintain'] = $vi;
                            $remark[] = "费用维护：" . number_format($vi, 2);
                            break;
                        case 'feePayables':
                            $row['feePayables'] = $vi;
                            $remark[] = "支付决算：" . number_format($vi, 2);
                            break;
                        case 'feeCar':
                            $row['feeCar'] = $vi;
                            $remark[] = "租车合同：" . number_format($vi, 2);
                            break;
                        case 'rentalCarAuditingCost':
                            $row['isRentalCarAuditingCost'] = 1;
                            $remark[] = "报销成本：" . number_format($vi, 2);
                            break;
                        default:
                    }
                }

                $row['actFee'] = $feeAct;
                $row['feeProcess'] = 0;
                $row['remark'] = implode('，', $remark);
                $rows[] = $row;
                $feeAll = bcadd($feeAll, $feeAct, 2);
            }
        }

        // 机票决算单独处理
        if ($project['feeFlights'] > 0) {
            $rows[] = array(
                'id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '报销支付成本', 'detCount' => '',
                'budgetName' => '机票决算', 'amount' => '0.00', 'actFee' => $project['feeFlights'],
                'feeFlights' => $project['feeFlights'],
                'feeProcess' => 0, 'remark' => '机票系统：' . number_format($project['feeFlights'], 2)
            );
            $feeAll = bcadd($feeAll, $project['feeFlights'] , 2);
        }

        // 合计列
        if (!empty($rows)) {
            // 根据大类,把小类都排到一起
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

            // 费用列信息生成
            array_push($rows, $this->getCountRow_d('budgetField', '报销支付成本合计', $budgetAll, $feeAll));
        }
        return $rows;
    }

    /**
     * 设备成本
     * @param $budget
     * @param $fee
     * @param $project
     * @return array
     */
    function getEquFee_d($budget, $fee, $project) {
        $rows = array();
        $budgetAll = 0;
        $feeAll = 0;

        //实例化项目费用维护
        $esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
        foreach ($budget as $v) {
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $v['feeEquImport'] = $v['actFee'];
            if ($v['isImport']) {
                //获取导入预算的费用维护记录数
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
            // 设备决算
            $feeEquAll = bcadd($project['feeEqu'], $esmDeviceFeeActOld, 2);
            $feeEquAll = bcadd($feeEquAll, $esmDeviceFeeActNew, 2);
            $feeAll = bcadd($feeAll, $feeEquAll, 2);
            // 设备决算信息生成
            array_push($rows, array(
                'id' => 'budgetEqu', 'budgetType' => 'budgetEqu', 'parentName' => '设备成本', 'detCount' => '',
                'budgetName' => '设备成本',
                'remark' => '初始化导入（' . $project['feeEqu'] .
                    '），资产折旧（' . number_format($esmDeviceFeeActNew, 2) .
                    '），旧设备系统（' . number_format($esmDeviceFeeActOld, 2) . '）',
                'amount' => $project['budgetEqu'], 'actFee' => $feeEquAll, 'feeEqu' => $feeEquAll,
                'feeProcess' => '0.00'
            ));
            $budgetAll = bcadd($budgetAll, $project['budgetEqu'], 2);
        }

        // 合计列
        if (!empty($rows)) {
            // 费用列信息生成
            array_push($rows, $this->getCountRow_d('budgetEqu', '设备成本合计', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * 外包成本
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
                //获取导入预算的费用维护记录数
                $v['detCount'] = "[" . $this->getOutsourcingCount_d($project['id'], $v['parentName'], $v['budgetName']) . "]";
            }
            $v['feeProcess'] = $this->countProcess_d($v['amount'], $v['actFee']);
            $v['feeOutsourcing'] = $v['actFee'];
            $rows[] = $v;
            $budgetAll = bcadd($budgetAll, $v['amount'], 2);
            $feeAll = bcadd($feeAll, $v['actFee'], 2);
        }

        // 合计列
        if (!empty($rows)) {
            // 费用列信息生成
            array_push($rows, $this->getCountRow_d('budgetOutsourcing', '外包成本合计', $budgetAll, $feeAll));
        }

        return $rows;
    }

	/**
	 * 获取外包明细数量
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
     * 其他成本
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
            // 决算载入
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
                    'id' => 'budgetOther', 'budgetType' => 'budgetOther', 'parentName' => '其他成本',
                    'budgetName' => $v['CostTypeName'], 'remark' => '',
                    'amount' => 0, 'actFee' => $v['CostMoney'], 'feeProcess' => '0.00',
                    'feeOther' => $v['CostMoney']
                );
                $feeAll = bcadd($feeAll, $v['CostMoney'], 2);
            }
        }

        //PK费用获取
        $projectDao = new model_engineering_project_esmproject();
        $triProjectInfo = $projectDao->getPKInfo_d($project['id'], $project);
        if ($triProjectInfo) {
            foreach ($triProjectInfo as $v) {
                //费用列信息生成
                array_push($rows, array(
                    'id' => 'budgetTrial', 'budgetType' => 'budgetTrial', 'parentName' => '试用项目', 'detCount' => '',
                    'budgetName' => $v['projectCode'], 'remark' => $v['projectName'], 'projectId' => $v['id'],
                    'amount' => $v['budgetAll'], 'actFee' => $v['feeAll'], 'feePK' => $v['feeAll'],
                    'feeProcess' => $v['budgetAll'] == 0 ? 0 : bcmul(bcdiv($v['feeAll'], $v['budgetAll'], 4), 100, 2)
                ));
                $budgetAll = bcadd($budgetAll, $v['budgetAll'], 2);
                $feeAll = bcadd($feeAll, $v['feeAll'], 2);
            }
        }

        // 合计列
        if (!empty($rows)) {
            // 费用列信息生成
            array_push($rows, $this->getCountRow_d('budgetOther', '其他成本合计', $budgetAll, $feeAll));
        }

        return $rows;
    }

    /**
     * 进度计算
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
     * 统计行生成
     * @param $budgetType
     * @param $budgetName
     * @param $budget
     * @param $fee
     * @return array
     */
    function getCountRow_d($budgetType, $budgetName, $budget, $fee) {
        // 费用列信息生成
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
                // 获取租车决算
                $esmprojectDao = new model_engineering_project_esmproject();
                $esmprojectObj = $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,feeCar');

				// 过滤的报销决算
				$otherDatasDao = new model_common_otherdatas();
				$filterArr = $otherDatasDao->getConfig('engineering_budget_expense', null, 'arr');
				if (!in_array($budgetName, $filterArr)) {
					// 获取报销决算
					$expenseDao = new model_finance_expense_expense();
					$expenseItem = $expenseDao->getFeeDetailGroupMonth_d($esmprojectObj['projectCode'], $budgetName);

					// 合并决算
					$result = $this->feeMerge_d($result, $expenseItem, array(
						'budgetType' => '报销成本'
					));
				}

                // 获取费用维护
                $esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
                $esmCost = $esmcostmaintainDao->getDetailGroupMonth_d($projectId, $budgetName);

                // 合并决算
                $result = $this->feeMerge_d($result, $esmCost, array(
                    'budgetType' => '费用维护'
                ));

                // 获取支付的决算
                $fieldRecordDao = new model_engineering_records_esmfieldrecord();
                $fieldRecordArr = $fieldRecordDao->getDetailGroupMonth_d('payables', $projectId, $budgetName);

                // 合并决算
                $result = $this->feeMerge_d($result, $fieldRecordArr, array(
                    'budgetType' => '支付决算'
                ));

                // 配置中的补贴ID
                $otherDatasDao = new model_common_otherdatas();
                $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');

                // 租车决算处理
                if ($carItem == $budgetName && $esmprojectObj['feeCar'] > 0) {
                    // 租车决算
                    $rentCarArr = $fieldRecordDao->getDetailGroupMonth_d('rentCar', $projectId, $budgetName);

                    // 合并决算
                    $result = $this->feeMerge_d($result, $rentCarArr, array(
                        'budgetType' => '租车合同'
                    ));
                }

                // 机票决算
                if ($budgetName == '机票决算') {
                    // 机票决算
                    $esmFlightsDao = new model_engineering_records_esmflights();
                    $flightArr = $esmFlightsDao->getDetailGroupMonth_d($projectId);

                    // 合并决算
                    $result = $this->feeMerge_d($result, $flightArr, array(
                        'budgetType' => '机票决算'
                    ));
                }


                break;
            default :
        }

        if (!empty($result)) {
            $countArr = array(
                'budgetType' => '合计',
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
     * 决算数据合并
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
	 * 把现场预算，外包预算，其他预算载入到最终数组
	 * @param $rowArr 最终数组
	 * @param $rows 搜索出的预算数组
	 * @param $rowSum 项目合计数组
	 * @param $feeArr 费用维护的数据
	 * @param $param 搜索条件
	 */
	function getNormalData_d($rowArr, $rows, $rowSum, $feeArr, $param) {
		// 配置中的补贴ID
		$otherDatasDao = new model_common_otherdatas();
		$subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');
        $carItem = $otherDatasDao->getConfig('engineering_budget_rent_car');

        // 获取租车决算
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('id' => $param['projectId']), null, 'feeCar');

        // 是否已经导入租车就算
        $isNeedImportCar = $esmprojectObj['feeCar'] != 0;

		//实例化项目费用维护
		$esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
		foreach ($rows as $key => $val) {
			//载入费用
			if ($rows[$key]['budgetType'] == 'budgetField' && $rows[$key]['isImport'] == '0'
				&& isset($feeArr['detail'][$rows[$key]['budgetName']])) {

				$rows[$key]['actFee'] = $feeArr['detail'][$rows[$key]['budgetName']]['CostMoney'];
				$rows[$key]['feeProcess'] = bcmul(bcdiv($rows[$key]['actFee'], $rows[$key]['amount'], 4), 100, 2);

				unset($feeArr['detail'][$rows[$key]['budgetName']]);
			} else {
				$rows[$key]['feeProcess'] = bcmul(bcdiv($rows[$key]['actFee'], $rows[$key]['amount'], 4), 100, 2);
			}

			//计算费用小类的个数
			if ($rows[$key]['num'] > 1) {
				$rows[$key]['detCount'] = "[" . $rows[$key]['num'] . "]";
			} elseif ($val['isImport'] == 1) {
				//获取导入预算的费用维护记录数
				$rows[$key]['detCount'] = "[" . $esmcostmaintainDao->findCount(array('projectId' => $param['projectId'],
						'parentCostType' => $val['parentName'], 'costType' => $val['budgetName'])) . "]";
			} else {
				$rows[$key]['detCount'] = "";
			}

            // 决算叠加
            if ($isNeedImportCar && $carItem == $rows[$key]['budgetName']) {
                $rows[$key]['actFee'] = bcadd($rows[$key]['actFee'], $esmprojectObj['feeCar'], 2);
                $rows[$key]['remark'] .= '（附加租车合同决算：' . number_format($esmprojectObj['feeCar'], 2) . '）';
                $isNeedImportCar = false;
            }

			//去除预算和决算都为0的数据
			if (($rows[$key]['actFee'] != 0 || $rows[$key]['amount'] != 0)) {
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $rows[$key]['actFee'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $rows[$key]['amount'], 2);
				array_push($rowArr, $rows[$key]);
			}
		}

		// 没有预算的决算项处理
		if (($param['budgetType'] == 'budgetField' || $param['budgetType'] == '') && !empty($feeArr['detail'])) {
			$rowFee = $feeArr['detail'];
			foreach ($rowFee as $k => $v) {
				// 去除补贴项部分金额
                if ($isNeedImportCar && $rowFee[$k]['CostTypeName'] == $carItem) {
                    $feeSum = bcadd($rowFee[$k]['CostMoney'], $esmprojectObj['feeCar'], 2);
                    $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '车辆运行支出费',
                        'amount' => 0, 'feeProcess' => '0.00', 'budgetName' => $rowFee[$k]['CostTypeName'], 'actFee' => $feeSum,
                        'remark' => '未在费用预算内的费用：' . $rowFee[$k]['CostMoney'] .
                            ' + （附加租车合同决算：' . number_format($esmprojectObj['feeCar'], 2) . '）');
                    $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeSum, 2);
                    array_push($rowArr, $feeArr2);
                } else if (!in_array($rowFee[$k]['CostTypeName'], $subsidyArr)) {
                    $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '现场预算',
                        'amount' => 0, 'feeProcess' => '0.00', 'remark' => '未在费用预算内的费用',
                        'budgetName' => $rowFee[$k]['CostTypeName'], 'actFee' => $rowFee[$k]['CostMoney']);
                    $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeArr2['actFee'], 2);
                    array_push($rowArr, $feeArr2);
                }
			}
		}

        // 去除补贴项部分金额
        if ($isNeedImportCar) {
            $feeArr2 = array('id' => 'budgetField', 'budgetType' => 'budgetField', 'parentName' => '车辆运行支出费',
                'amount' => 0, 'feeProcess' => '0.00', 'budgetName' => $carItem, 'actFee' => $esmprojectObj['feeCar'],
                'remark' =>  '租车合同决算：' . number_format($esmprojectObj['feeCar'], 2));
            $rowSum['actFee'] = bcadd($rowSum['actFee'], $esmprojectObj['feeCar'], 2);
            array_push($rowArr, $feeArr2);
        }

		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;
		return $result;
	}

	/**
	 * 载入人力预算
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
        // 配置配置中的补贴ID
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

		// 从现场获取的人力决算
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
                    $v['parentName'] = '人力决算(已录补贴)';
				} else {
					$v['feeProcess'] = bcmul(bcdiv($v['actFee'], $v['amount'], 4), 100, 2);
                    $v['realName'] = $v['parentName'];
                    $v['parentName'] = '人力决算(计提补贴)';
				}
				$v['detCount'] = '';
				$rowArr[] = $v;
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $v['actFee'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $v['amount'], 2);

				$projectObj['budgetPerson'] = bcsub($projectObj['budgetPerson'], $v['amount'], 2);
			}
		}

		// 初始化人力预决算
		$rowPerson = array('budgetType' => 'budgetPerson', 'parentName' => '人力决算(工资成本)', 'budgetName' => '人力预算',
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

        // 没有预算的决算项处理
        if (($param['budgetType'] == 'budgetPerson' || $param['budgetType'] == '') && !empty($feeArr['detail'])) {
            $rowFee = $feeArr['detail'];
            foreach ($rowFee as $k => $v) {
                // 去除补贴项部分金额
                if (in_array($rowFee[$k]['CostTypeName'], $subsidyArr)) {
                    $feeArr2 = array('id' => 'budgetPerson', 'budgetType' => 'budgetPerson', 'parentName' => '人力决算(已录补贴)',
                        'amount' => 0, 'feeProcess' => '0.00', 'remark' => '未在费用预算内的费用',
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
	 * 载入设备预算
	 * @param $rowArr
	 * @param $rowSum
	 * @param $param
	 * @return mixed
	 */
	function getBudgetDevice_d($rowArr, $rowSum, $param) {
		$projectDao = new model_engineering_project_esmproject();
		$projectObj = $projectDao->get_d($param['projectId']);

		//存储设备预算的数组
		$rowDevice = array('budgetType' => 'budgetDevice', 'parentName' => '设备预算', 'budgetName' => '设备预算', 'detCount' => '');
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
	 * 载入试用项目
	 * @param $rowArr
	 * @param $rowSum
	 * @param $param
	 * @param null $esmprojectObj
	 * @return mixed
	 */
	function getBudgetTrial_d($rowArr, $rowSum, $param, $esmprojectObj = null) {
		//PK费用获取
		$esmprojectDao = new model_engineering_project_esmproject();
		$triProjectInfo = $esmprojectObj ? $esmprojectDao->getPKInfo_d($param['projectId'], $esmprojectObj) : $esmprojectDao->getPKInfo_d($param['projectId']);
		if ($triProjectInfo) {
			foreach ($triProjectInfo as $val) {
				//费用列信息生成
				$rowTrial = array(
					'id' => 'budgetTrial', 'budgetType' => 'budgetTrial', 'parentName' => '试用项目', 'detCount' => '',
					'budgetName' => $val['contractCode'], 'remark' => $val['projectName'], 'projectId' => $val['id'],
					'amount' => $val['budgetAll'], 'actFee' => $val['feeAll'],
					'feeProcess' => $val['budgetAll'] == 0 ? 0 : bcmul(bcdiv($val['feeAll'], $val['budgetAll'], 4), 100, 2)
				);
				array_push($rowArr, $rowTrial);
				//把金额加载到列表合计中
				$rowSum['actFee'] = bcadd($rowSum['actFee'], $val['feeAll'], 2);
				$rowSum['amount'] = bcadd($rowSum['amount'], $val['budgetAll'], 2);
			}
		}
		$result['rowArr'] = $rowArr;
		$result['rowSum'] = $rowSum;
		return $result;
	}

	/**
	 * 根据项目id返回预算信息
	 * @param $projectId
	 * @return array
	 */
	function getBudgetForCar_d($projectId) {
		$budgetInfo = array();

		if ($projectId) {
			// 获取租车预算
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
     * 根据项目ID,更新其当前外包预提的预决算信息
     * @param $Arr
     */
    function updateCostByProjectIds($Arr) {
        foreach ($Arr as $pId){
            $rs = $this->findAll(array('projectId' => $pId, 'parentName' => "外包预算"), null , "id,budgetName,amount,actFee");
            if(!empty($rs)){
                foreach ($rs as $rsv){
                    $inArr = array("projectId"=>$pId,"parentCostType"=>"外包预算","costType"=>$rsv['budgetName']);
                    $budgetArr = $this->getCostmaintain_d($inArr);

                    $actFee = 0;
                    foreach ($budgetArr as $v) {
                        $actFee = bcadd($actFee, $v['fee'], 2);
                    }

                    // 如果预算和决算都为0的时候，直接删除预决算记录
                    if ($budgetArr[0]['budget'] == 0 && $actFee == 0) {
                        $log = "清空旧项目费用维护【" . $inArr['costType'] . "】";
                        parent::delete(array(
                            'id' => $rsv['id']
                        ));
                    } else {
                        parent::edit_d(array(
                            'id' => $rsv['id'], 'actFee' => $actFee, 'amount' => $budgetArr[0]['budget'],  'status' => 1
                        ), true);
                        $log = "更新旧项目费用维护【" . $inArr['costType'] . "】，预算【" . $budgetArr[0]['budget'] . "】，决算【" .
                            $actFee . "】";
                    }

                    //记录操作日志
                    $esmlogDao = new model_engineering_baseinfo_esmlog();
                    $esmlogDao->addLog_d($inArr['projectId'], '导入费用', $log);
                }
            }
        }
    }

	/**
	 * 经由项目费用维护导入预算
	 * @param $inArr
	 * @return bool
	 * @throws Exception
	 */
	function importByCostMainTain($inArr) {
		// 配置中的补贴名称
		$otherDatasDao = new model_common_otherdatas();
		$subsidyIdArr = $otherDatasDao->getConfig('engineering_budget_subsidy_id', null, 'arr');

		try {
			$budgetArr = $this->getCostmaintain_d($inArr);

			// 判断是进行更新还是新增操作
			$rs = $this->find(array('projectId' => $inArr['projectId'], 'parentName' => $inArr['parentCostType'],
                'budgetName' => $inArr['costType'], 'isImport' => 1), null, 'id,actFee');

			if (!empty($rs)) {
                $actFee = 0;
                foreach ($budgetArr as $v) {
                    $actFee = bcadd($actFee, $v['fee'], 2);
                }
                // 如果预算和决算都为0的时候，直接删除预决算记录
                if ($budgetArr[0]['budget'] == 0 && $actFee == 0) {
                    $log = "清空费用维护【" . $inArr['costType'] . "】";
                    parent::delete(array(
                        'id' => $rs['id']
                    ));
                } else {
                    parent::edit_d(array(
                        'id' => $rs['id'], 'actFee' => $actFee, 'amount' => $budgetArr[0]['budget'],  'status' => 1
                    ), true);
                    $log = "更新费用维护【" . $inArr['costType'] . "】，预算【" . $budgetArr[0]['budget'] . "】，决算【" .
                        $actFee . "】";
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

				// 如果输入补贴项，则纳入人力预决算
				if (in_array($inArr['costTypeId'], $subsidyIdArr)) {
					$object['budgetType'] = 'budgetPerson';
				} else {
					if($inArr['parentCostType'] == '外包预算'){
						$object['budgetType'] = 'budgetOutsourcing';//预算类型为【外包预算】
						$object['actFee'] = $budgetArr[0]['fee']; // 提取总外包的总决算
					}else if($inArr['parentCostType'] == '设备预算'){
                        $object['budgetType'] = 'budgetEqu';//预算类型为【设备预算】
                    } else {
						$object['budgetType'] = 'budgetField';//预算类型为【现场预算】
					}
				}
                $log = "导入费用维护【" . $inArr['costType'] . "】，预算【" . $budgetArr[0]['budget'] . "】，决算【" .
                    $inArr['fee'] . "】";

				parent::add_d($object, true);
			}

            // 项目预决算重计
            $this->recount_d($inArr['projectId']);

			//记录操作日志
			$esmlogDao = new model_engineering_baseinfo_esmlog();
			$esmlogDao->addLog_d($inArr['projectId'], '导入费用', $log);

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 这里加一个获取外包数据接口
	 * @param $inArr
	 * @return array|bool
	 */
	function getCostmaintain_d($inArr) {
		if ($inArr['parentCostType'] == "外包预算") {
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
			// 获取费用维护项
			$budgetArr = $this->_db->getArray($sql);
		} else {
			//实例化项目费用维护
			$esmcostmaintainDao = new model_engineering_cost_esmcostmaintain();
			// 获取最新项目费用维护的预算值budget
			$budgetArr = $esmcostmaintainDao->findAll(
				array(
					'projectId' => $inArr['projectId'], 'parentCostType' => $inArr['parentCostType'], 'costType' => $inArr['costType']
				),
				'month DESC', 'budget,fee');
		}
		return $budgetArr;
	}

    /**
     * 导入预决算更新
     * @param $projectId
     * @throws Exception
     */
    function recount_d($projectId) {
		try {
			//获取费用信息
			$feeInfo = $this->findAll("budgetType IN('budgetField','budgetOutsourcing','budgetEqu') AND projectId = ".$projectId,
				null, 'budgetType,actFee,amount,isImport,status');
            $budgetField = 0; // 现场预算
			$feeFieldImport = 0;//导入现场决算
			$feeOutsourcing = 0;//外包决算
			$budgetOutsourcing = 0;//外包预算
            $feeEquImport = 0; // 设备决算
			foreach ($feeInfo as $v) {
				if($v['budgetType'] == 'budgetField'){//现场预决算
					if($v['isImport'] == 1 && $v['status'] == 1){
						$feeFieldImport = bcadd($v['actFee'], $feeFieldImport, 2);
					}
                    $budgetField = bcadd($budgetField, $v['amount'], 2);
				} elseif($v['budgetType'] == 'budgetEqu') {
                    if($v['isImport'] == 1 && $v['status'] == 1){
                        $feeEquImport = bcadd($v['actFee'], $feeEquImport, 2);
                    }
                } else {//外包预决算
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
			//实例化工程项目
			$esmprojectDao = new model_engineering_project_esmproject();

			//更新工程项目导入现场决算,现场预算,外包预决算
			$esmprojectDao->update(array('id' => $projectId), array(
                'feeFieldImport' => $feeFieldImport, 'feeOutsourcing' => $feeOutsourcing, 'budgetField' => $budgetField,
				'budgetOutsourcing' => $budgetOutsourcing, 'feeEquImport' => $feeEquImport
            ));

			//重新计算项目总预算
			$esmprojectDao->updateBudgetAll_d($projectId);
            //计算决算金额
            $esmprojectDao->calProjectFee_d(null, $projectId);
            //计算决算进度
            $esmprojectDao->calFeeProcess_d($projectId);
		} catch (Exception $e) {
			throw $e;
		}
	}

    //做一个对象缓存 -- 因为导入的时候耗费的内容太多，所以要优化
    static $initObjCache = array();

    //获取对象缓存的方法
    static function getObjCache($className) {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }
	
	/**
	 * 获取导出数据
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

                    $dataCache = array(); // 初始化

                    // 这里取出项目 - 主要是获取某些固化在项目表中的成本数据
                    $project = $projectDao->get_d($projectId);

                    // 获取预算
                    $budgetData  = $this->getBudgetData_d(array('projectId' => $projectId));

                    // 预算切分
                    $budgetCache = $this->budgetSplit_d($budgetData);

                    // 获取决算
                    $feeData = $this->getFee_d($projectId);

                    // 决算切分
                    $feeCache = $this->feeSplit_d($feeData);

                    // 人力成本
                    $dataCache[0] = $this->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

                    // 报销支付成本
                    $dataCache[1] = $this->getFieldFee_d($budgetCache[1], $feeCache[1], $project);

                    // 设备成本
                    $dataCache[2] = $this->getEquFee_d($budgetCache[2], $feeCache[2], $project);

                    // 外包成本
                    $dataCache[3] = $this->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

                    // 其他成本
                    $dataCache[4] = $this->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

                    // 如果查询到数据，则塞入数据数据中
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