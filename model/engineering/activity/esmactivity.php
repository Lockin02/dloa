<?php

/**
 * @author Administrator
 * @Date 2011年12月12日 17:06:50
 * @version 1.0
 * @description:项目范围(oa_esm_project_activity) Model层
 * status {0:正常,1:完成,2:暂停}
 */
class model_engineering_activity_esmactivity extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_esm_project_activity";
        $this->sql_map = "engineering/activity/esmactivitySql.php";
        parent::__construct();
    }

    // 数据字典字段处理
    public $datadictFieldArr = array(
        'workloadUnit'
    );

    /************************ 外部信息获取 ***********************/
    /**
     * 获取项目信息
     * @param $projectId
     * @return bool|mixed
     */
    function getObjInfo_d($projectId) {
        $projectDao = new model_engineering_project_esmproject();
        return $projectDao->get_d($projectId);
    }

    /************************* 增删改查 ************************/

    /**
     * 新增
     * @param 传入保存的树节点 $object
     * @return bool|null
     */
    function add_d($object) {
        //获取人员配置
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);

        try {
            $this->start_d();

            //判断此修改是否属于变更
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                //预算变更类
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $newId = $esmchangeactDao->add_d($object, $this);
            } else {

                //新增任务
                $newId = $this->addOrg_d($object);

                //处理任务成员
                $esmactmemberDao = new model_engineering_activity_esmactmember();
                $esmactmemberDao->batchDeal_d($esmperson, $newId, $object['activityName']);

                //新增项目人力预算
                $esmpersonDao = new model_engineering_person_esmperson();
                $esmpersonDao->dealPerson_d($esmperson, $newId, $object['activityName']);

                //更新上级任务相关信息
                $this->updateAllParent_d($newId, $object);

                //判断是否是筹备状态的项目
                $esmprojectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'status');
                if ($esmprojectArr['status'] == 'GCXMZT01') {
                    //更新项目预计开始和结束日期
                    $this->updatePlanDate_d($object['projectId']);
                }
            }

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 简单新增
     * @param $object
     * @return bool|null
     * @throws Exception
     */
    function addOrg_d($object) {
        try {
            //数据字典中文处理
            $object = $this->processDatadict($object);
            return parent::add_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        //获取任务成员
        $esmactmember = $object['esmactmember'];
        unset($object['esmactmember']);
        //获取人力预算
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);
        try {
            $this->start_d();

            if ($object['orgId'] != -1) {
                //预算变更类
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->editOrg_d($object);
            } else {
                //判断此修改是否属于变更
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //预算变更类
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->edit_d($object, $this);
                } else {
                    //调用树工具产生左右节点id
                    $this->editOrg_d($object);

                    //处理任务成员
                    $esmactmemberDao = new model_engineering_activity_esmactmember();
                    $esmactmemberDao->batchDeal_d($esmactmember);

                    //处理人力预算
                    $esmpersonDao = new model_engineering_person_esmperson();
                    $esmpersonDao->batchEdit_d($esmperson);

                    //更新上级任务相关信息
                    $this->updateAllParent_d($object['id'], $object);

                    //判断是否是筹备状态的项目
                    $esmprojectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'status');
                    if ($esmprojectArr['status'] == 'GCXMZT01') {
                        //更新项目预计开始和结束日期
                        $this->updatePlanDate_d($object['projectId']);
                    }
                }
            }

            $this->commit_d();
            return $object;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 简单编辑
     * @param $object
     * @return bool|null|传入添加的节点对象|传入移动
     * @throws Exception
     */
    function editOrg_d($object) {
        try {
            //数据字典中文处理
            $object = $this->processDatadict($object);
            return parent::edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 简单编辑
     * @param $object
     * @return bool
     */
    function editWorkloadDone_d($object) {
        try {
            //否则更新任务进度
            $process = bcmul(bcdiv($object['workloadDone'], $object['days'], 6), 100, 4);
            $updateArr = array(
                'workloadDone' => $object['workloadDone'],
                'process' => $process > 100 ? 100 : $process,
                'confirmDate' => day_date, 'confirmId' => $_SESSION['USER_ID'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmDays' => bcsub($object['workloadDone'], $object['workloadCount'])
            );
            return $this->update(array('id' => $object['id']), $updateArr);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 删除树节点及下属节点
     * @param $id
     * @param null $changeId
     * @param null $projectId
     * @return bool
     */
    function deletes_d($id, $changeId = null, $projectId = null) {
        try {
            $this->start_d();

            //如果是变更的情况
            if ($changeId) {
                //转入变更部分处理
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->deletes_d($id, $changeId);
            } else {
                //判断此修改是否属于变更
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($projectId)) {
                    //变更删除
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->deletes_d($id, null, $projectId);
                } else {
                    //获取子节点
                    $node = $this->get_d($id);
                    $childNodes = $this->getChildrenByNode($node);
                    if ($childNodes) {
                        foreach ($childNodes as $val) {
                            $this->deleteNodes($val['id']);
                            parent::deletes($val['id']);
                        }
                    }
                    parent::deletes($id);

                    //删除时重新计算项目信息
                    $esmprojectDao = new model_engineering_project_esmproject();
                    $esmprojectDao->updateProjectBudget_d($node['projectId']);

                    //更新上级任务相关信息
                    $this->updateAllParent_d($id, $node);
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
     * 转移项目
     * @param $object
     * @return bool
     */
    function move_d($object) {
        //获取任务成员
        $esmactmember = $object['esmactmember'];
        unset($object['esmactmember']);
        //获取人力预算
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);

        try {
            $this->start_d();

            //判断如果已经是变更，则特殊处理
            if ($object['changeId']) {
                //预算变更类
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->move_d($object, $this);
            } else {
                //判断此修改是否属于变更
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //实例化任务变更类
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->move_d($object, $this);
                } else {
                    //调用树工具产生左右节点id
                    $newId = $this->addOrg_d($object);

                    //处理任务成员
                    $esmactmember = util_arrayUtil::setArrayFn(array('activityId' => $newId, 'activityName' => $object['acitivityName']), $esmactmember);
                    $esmactmemberDao = new model_engineering_activity_esmactmember();
                    $esmactmemberDao->batchDeal_d($esmactmember);

                    //处理人力预算
                    $esmperson = util_arrayUtil::setArrayFn(array('activityId' => $newId, 'activityName' => $object['acitivityName']), $esmperson);
                    $esmpersonDao = new model_engineering_person_esmperson();
                    $esmpersonDao->batchEdit_d($esmperson);

                    //预算部分更新
                    $esmbudgetDao = new model_engineering_budget_esmbudget();
                    $esmbudgetDao->update(array('activityId' => $object['parentId']), array('activityId' => $newId, 'activityName' => $object['acitivityName']));

                    //原任务更新
                    $this->clearActivity_d($object['parentId']);

                    //更新上级任务相关信息
                    $this->updateAllParent_d($newId, $object);
                }
            }

            $this->commit_d();
            return $object;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 特殊清空方法，为了使数据看起来正常
     * @param $id
     */
    function clearActivity_d($id) {
        $sql = "update " . $this->tbl_name . " set
				budgetAll = null,memberId = null,memberName = null,workload = null,workloadUnit = null ,workloadUnitName = null,
				workContent = null,remark = null
			where id = " . $id;
        $this->_db->query($sql);
    }

    /************************** 业务逻辑接口 ************************/

    /**
     * 获取项目中的可用任务
     * @param $projectId
     * @return mixed
     */
    function getProjectActivity_d($projectId) {
        return $this->findAll(array('projectId' => $projectId), 'lft');
    }

    /**
     * 项目范围 - 更新上级 - 递归函数
     * @param $id
     * @param null $obj
     * @return bool
     */
    function updateAllParent_d($id, $obj = null) {
        if (empty($obj)) {
            $obj = $this->get_d($id);
        }

        // 查询范围日期上下限以及工期
        $sql = "select
				min(planBeginDate) as planBeginDate,max(planEndDate) as planEndDate,
				round((UNIX_TIMESTAMP( max(planEndDate) ) - UNIX_TIMESTAMP( min(planBeginDate) ) )/(3600 *24)) + 1 as days,
				round(sum(workRate*process/100),2) as process
			from oa_esm_project_activity where parentId = " . $obj['parentId'];
        $rs = $this->_db->getArray($sql);
        $this->update(
            array('id' => $obj['parentId']),
            array('planBeginDate' => $rs[0]['planBeginDate'], 'planEndDate' => $rs[0]['planEndDate'], 'days' => $rs[0]['days'], 'process' => $rs[0]['process'])
        );

        // 如果已经是根节点，直接返回
        if ($obj['parentId'] == PARENT_ID) {
            return true;
        } else {
            return $this->updateAllParent_d($obj['parentId']);
        }
    }

    /**
     * 验证是否存在根节点，不存在则新增
     */
    function checkParent_d() {
        $this->searchArr['id'] = -1;
        $rs = $this->list_d('select_default');
        if (is_array($rs)) {
            return true;
        } else {
            $this->create(array('id' => -1, 'activityName' => '项目', 'lft' => 1, 'rgt' => 2));
            return false;
        }
    }

    /**
     * 获取id下的所有Id,包括本节点
     * @param $id
     * @param $lft
     * @param $rgt
     * @return null|string
     */
    function getUnderTreeIds_d($id, $lft, $rgt) {
        $this->searchArr = array('biglft' => $lft, 'smallrgt' => $rgt);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $idArr = array();
            foreach ($rs as $val) {
                array_push($idArr, $val['id']);
            }
            return $ids = implode($idArr, ',');
        }
        return null;
    }

    /**
     * 获取本级内的工作量
     * @param $projectId
     * @param int $parentId
     * @param null $changeId
     * @return int
     * @throws Exception
     */
    function getWorkRateByParentId_d($projectId, $parentId = -1, $changeId = null) {
        $this->searchArr = array(
            'projectId' => $projectId,
            'parentId' => $parentId
        );
        //如果没有变更申请id,再做一次查询
        if (!$changeId) {
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($projectId, false);
        }

        if ($changeId) {
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $workRate = $esmchangeactDao->workRateCount($changeId, $parentId);
            $rs = array(array(
                'workRate' => $workRate
            ));
        } else {
            $rs = $this->list_d('sum_list');
        }

        if (is_array($rs)) {
            return $rs[0]['workRate'];
        } else {
            return 0;
        }
    }

    /**
     * 计算任务的进度 - 日志录入页面计算进度调用方法
     * @param $id
     * @param $workload
     * @param null $worklogId
     * @return array
     */
    function calTaskProcess_d($id, $workload, $worklogId = null) {
        $obj = $this->get_d($id);

        //获取日志统计信息
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $countInfo = $esmworklogDao->getWorklogCountInfo_d($id, $worklogId);

        //如果任务中含有工作量，则以工作量计算，否则以工期计算
        $newWorkLoad = bcadd($workload, $countInfo['workloadDay'], 2);
        $process = bcmul(bcdiv($newWorkLoad, $obj['workload'], 4), 100, 2);
        if ($process > 100) {
            $process = 100;
        }

        //计算所对应任务的百分比 - 从叶子往上递归
        $thisActivityProcess = bcmul(bcdiv($workload, $obj['workload'], 4), 100, 2);

        //计算所对应项目的百分比 - 从叶子往上
        $thisProjectProcess = $this->calThisProjectProcess_d($obj, $thisActivityProcess);

        return array('process' => $process, 'thisActivityProcess' => $thisActivityProcess, 'thisProjectProcess' => $thisProjectProcess);
    }

    /**
     * 递归计算任务占项目比例
     * @param $object
     * @param $thisRate
     * @return string
     */
    function calThisProjectProcess_d($object, $thisRate) {
        //如果是
        if ($object['parentId'] == PARENT_ID) {
            return bcdiv(bcmul($thisRate, $object['workRate'], 2), 100, 2);
        } else {
            $obj = $this->get_d($object['parentId']);
            $thisRate = bcdiv(bcmul($thisRate, $object['workRate'], 2), 100, 2);
            return $this->calThisProjectProcess_d($obj, $thisRate);
        }
    }

    /**
     * 计算以及更新任务进度
     * @param $id
     * @param $countInfo
     * @return mixed
     * @throws Exception
     */
    function updateTaskProcess_d($id, $countInfo) {
        $obj = $this->get_d($id);

        //如果任务中含有工作量，则以工作量计算，否则以工期计算
        $process = bcmul(bcdiv($countInfo['workloadDay'], $obj['workload'], 4), 100, 2);
        if ($process > 100) {
            $process = 100;
        }
        if ($process < 0) {
            $process = 0;
        }
        try {
            //条件数组
            $conditionArr = array('id' => $id);

            $needDay = bcsub($obj['days'], $countInfo['workDay']);
            //更新数组
            $updateArr = array(
                'process' => $process,
                'workloadDone' => $countInfo['workloadDay'],
                'actBeginDate' => $countInfo['actBeginDate'],
                'needDays' => $needDay,
                'workedDays' => $countInfo['workDay']
            );

            //更新任务
            $this->update($conditionArr, $updateArr);

            //更新上级
            $this->updateAllParent_d($id, $obj);

            return $updateArr['process'];
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * 更细任务实际完成日期已经实际工期
     * @param $id
     * @param null $actBeginDate
     * @param null $actEndDate
     * @return bool
     * @throws Exception
     */
    function updateEndDate_d($id, $actBeginDate = null, $actEndDate = null) {
        try {
            //计算实际工期
            $actDays = bcdiv(strtotime($actEndDate) - strtotime($actBeginDate), 86400) + 1;

            //更新实际结束日期
            return $this->update(array('id' => $id), array('actEndDate' => $actEndDate, 'actDays' => $actDays));
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * 获取上级进度 - 此处传入一个任务id,递归生成项目进度
     * @param $projectId
     * @return int
     */
    function getProjectProcess_d($projectId) {
        return $this->calProcessForParentId_d(PARENT_ID, $projectId);
    }

    /**
     * 计算同一层的进度
     * @param $parentId
     * @param $projectId
     * @return int
     */
    function calProcessForParentId_d($parentId, $projectId) {
        $this->searchArr = array(
            'parentId' => $parentId,
            'projectId' => $projectId
        );
        $this->groupBy = 'c.parentId,c.projectId';
        $rs = $this->list_d('parent_process');
        if ($rs[0]['process']) {
            return $rs[0]['process'];
        } else {
            return 0;
        }
    }

    /**
     * 获取项目进度信息
     * @param $projectId
     * @return array|null
     */
    function getListProcess_d($projectId) {
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $processList = $esmworklogDao->getListProcess_d($projectId);
        if ($processList) {
            $newProcessList = array();
            foreach ($processList as $val) {
                $newProcessList[$val['activityId']] = $val;
            }
            return $newProcessList;
        } else {
            return null;
        }
    }

    /*********************** 变更部分处理 *************************/

    /**
     * 变更 编辑任务时设置状态 add ,edit ,delete
     * @param $id
     * @param string $thisAction
     * @return bool
     */
    function changeInfoSet_d($id, $thisAction = 'add') {
        return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
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
     * 根据项目id计算项目任务工作占比总和
     * @param $projectId
     * @return int
     */
    function workRateCount($projectId) {
        $num = 0;
        $workRateRow = $this->findAll(array('parentId' => -1, 'projectId' => $projectId), null, 'workRate');
        $workRateRow = array_filter($workRateRow);
        if (!is_array($workRateRow) || empty($workRateRow))
            return 0;
        foreach ($workRateRow as $value) {
            $num += $value['workRate'];
        }
        return $num;
    }

    /**
     * 根据项目id计算项目任务-下级任务-工作占比总和
     * @param $projectId
     * @param $parentId
     * @param $result
     * @return array
     */
    function workRateCountNew($projectId, $parentId, $result) {
        $num = 0;
        if (empty($result)) {
            $result = array('count' => 100, 'parentName' => null);
        }
        if ($parentId == -1) {
            $idRow = $this->findAll(array('parentId' => $parentId, 'projectId' => $projectId), null, 'id');
            $idRow = array_filter($idRow);
            if (!empty($idRow)) {
                foreach ($idRow as $value) {
                    $workRateNext = $this->workRateCountNew($projectId, $value['id'], $result);
                    if ($workRateNext['count'] != 100) {
                        if (empty($workRateNext['parentName'])) {
                            $result['parentName'] = $this->getParentName($value['id']);
                        } else {
                            $result['parentName'] = $workRateNext['parentName'];
                        }
                        $result['count'] = $workRateNext['count'];
                        return $result;
                    }
                }
                return $result;
            } else {
                return $result;
            }
        } else {
            $idWorkRate = $this->getIdWorkRate($projectId, $parentId);
            if (!empty($idWorkRate)) { //检查有没有下级任务
                foreach ($idWorkRate as $v) {
                    $num += $v['workRate'];
                }
                if ($num != 100) {
                    if (empty($result['parentName'])) {
                        $result['parentName'] = $this->getParentName($idWorkRate[0]['id']);
                    }
                    $result['count'] = $num;
                    return $result;
                }

                foreach ($idWorkRate as $v) {
                    $workRateNext = $this->workRateCountNew($projectId, $v['id'], $result);
                    if ($workRateNext['count'] != 100) {
                        if (empty($workRateNext['parentName'])) {
                            $result['parentName'] = $this->getParentName($v['id']);
                        } else {
                            $result['parentName'] = $workRateNext['parentName'];
                        }
                        $result['count'] = $workRateNext['count'];
                        return $result;
                    }
                }
            }
            return $result;
        }
    }

    /**
     * 获取上级任务名称
     * @param $id
     * @return mixed
     */
    function getParentName($id) {
        $parentName = $this->findAll(array('id' => $id), null, 'parentName');
        return $parentName[0]['parentName'];
    }

    /**
     * 获取工作百分比和ID
     * @param $projectId
     * @param $parentId
     * @return array
     */
    function getIdWorkRate($projectId, $parentId) {
        $idWorkRateRow = $this->findAll(array('parentId' => $parentId, 'projectId' => $projectId), null, 'id,workRate');
        $idWorkRateRow = array_filter($idWorkRateRow);
        return $idWorkRateRow;
    }

    /**
     * 变更时，将树节点信息更新
     * @param $projectId
     * @return mixed
     */
    function initChangeInfo_d($projectId) {
        $sql = "update " . $this->tbl_name . " set changeLft = lft,changeRgt = rgt where projectId = " . $projectId;
        return $this->_db->query($sql);
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

    /**
     * 变更信息获取
     * @param $uid
     * @return bool|mixed
     */
    function getChange_d($uid) {
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $changeObj = $esmchangeactDao->get_d($uid);

        //载入一些当前任务相关字段
        $obj = $this->get_d($changeObj['activityId']);
        $changeObj['actBeginDate'] = $obj['actBeginDate'];
        $changeObj['actEndDate'] = $obj['actEndDate'];
        $changeObj['actDays'] = $obj['actDays'];
        $changeObj['workedDays'] = $obj['workedDays'];
        $changeObj['needDays'] = $obj['needDays'];
        $changeObj['process'] = $obj['process'];
        $changeObj['workloadDone'] = $obj['workloadDone'];
        $changeObj['orgId'] = $changeObj['activityId'];
        $changeObj['memberId'] = '';
        $changeObj['memberName'] = '';

        return $changeObj;
    }

    /**
     * 获取变更的根节点
     * @param $changeId
     * @return mixed
     */
    function getChangeRoot_d($changeId) {
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        return $esmchangeactDao->getChangeRoot_d($changeId);
    }

    /**
     * 获取上级任务
     * @param $id
     * @param $changeId
     * @return bool|mixed
     */
    function getParentObj_d($id, $changeId) {
        //如果传入变更单id，则认定为变更
        if ($changeId) {
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $obj = $esmchangeactDao->get_d($id);
            $obj['memberId'] = '';
            $obj['memberName'] = '';
            //初始化费用
            if (empty($obj['budgetAll'])) {
                $obj['budgetAll'] = 0;
            }
        } else {
            $obj = $this->get_d($id);
        }
        return $obj;
    }

    /**
     * 变更合计处理
     * @param $parentId
     * @return mixed
     */
    function countForChange_d($parentId) {
        $sql = "select
            sum(if(c.parentId = $parentId ,c.workRate,0)) as workRate ,
            min(c.planBeginDate) as planBeginDate,max(c.planEndDate) as planEndDate,
            round((UNIX_TIMESTAMP( max(c.planEndDate) ) - UNIX_TIMESTAMP( min(c.planBeginDate) ) )/(3600 *24)) + 1 as days,
            sum(round(if(c.parentId = $parentId,c.process,0)*if(c.parentId = $parentId,c.workRate,0)/100,2)) as process,
            sum(c.workload) as workload
        from
            oa_esm_change_activity c
        where c.changeAction <> 'delete' and c.isRoot = 0";
        return $this->listBySql($sql);
    }

    /**
     * 变更任务时，重新计算任务的进度以及项目的进度
     * @param $projectId
     * @return bool
     * @throws Exception
     */
    function recountProjectProcess_d($projectId) {
        try {
            //获取项目中的任务
            $sql = "select id,parentId from " . $this->tbl_name . " where projectId = '$projectId' and rgt - lft = 1
                AND isTrial = 0";
            $rows = $this->_db->getArray($sql);

            //用查询出来的叶子任务网上递归项目的进度
            if ($rows) {
                foreach ($rows as $key => $val) {
                    //更新自身进度
                    $myProcess = $this->getLeafProcess_d($val['id']);
                    $this->update(array('id' => $val['id']), array('process' => $myProcess));
                    //更新上级进度
                    $this->updateAllParent_d($val['id'], $val);
                }
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取当前任务进度
     * @param $id
     * @return int
     */
    function getLeafProcess_d($id) {
        $this->searchArr = array(
            'id' => $id
        );
        $rs = $this->list_d('count_process');
        if ($rs[0]['process']) {
            if ($rs[0]['process'] > 100)
                return 100;
            else
                return $rs[0]['process'];
        } else {
            return 0;
        }
    }

    /**
     * 获取即时进度
     * @param $projectId
     * @param null $beginDate
     * @param $endDate
     * @param bool $isAProject
     * @param bool $trialProcessDeal
     * @return int|string
     */
    function getActCountProcess_d($projectId, $beginDate = null, $endDate, $isAProject = false, $trialProcessDeal = false) {
        // 进度
        $process = 0;
        // 如果是A类项目，取预计进度
        if ($isAProject) {
            $rs = $this->findAll(array('projectId' => $projectId), 'lft DESC', 'id,lft,rgt,isTrial,process,planBeginDate,planEndDate,days,confirmDays,parentId,workRate');
            foreach ($rs as $k => $v) {
                if ($v['rgt'] - $v['lft'] != 1) continue; // 如果不是子任务,直接跳过
                if ($v['isTrial'] && !$trialProcessDeal) { // 如果任务是PK任务，则默认为100
                    $rs[$k]['process'] = 100;
                    continue;
                }
                if ($endDate < $v['planBeginDate']) { // 如果结束日期在任务开始之前，那么进度算为0
                    $rs[$k]['process'] = 0;
                    continue;
                }
                if ($beginDate > $v['planEndDate']) { // 如果开始日期在任务结束日期之后，那么进度为100
                    $rs[$k]['process'] = 0;
                    continue;
                }
                $calBeginDate = $beginDate ? max($beginDate, $v['planBeginDate']) : $v['planBeginDate']; // 预计开始日期
                $calEndDate = min($endDate, $v['planEndDate']); // 预计结束日期
                $rs[$k]['calDays'] = (strtotime($calEndDate) - strtotime($calBeginDate)) / 86400 + 1 + $v['confirmDays'];
                $rs[$k]['process'] = bcmul(bcdiv($rs[$k]['calDays'], $v['days'], 6), 100, 6);
            }
        } else {
            $timeSql = " and executionDate <= '$endDate'"; // 加载结束日期
            if ($beginDate) $timeSql .= " and executionDate >= '$beginDate'"; // 加载开始日期
            $sql = "select
				c.id,c.parentId,c.lft,c.rgt,c.workRate,c.isTrial,
				if(w.workloadDay > c.workload,1,w.workloadDay/c.workload)*100 as process
			from
				oa_esm_project_activity c
				left join
				(
					select sum(workloadDay) as workloadDay,activityId
					from oa_esm_worklog where projectId = '$projectId' and confirmStatus = 1 $timeSql group by activityId
				) w
				on c.id = w.activityId
			where projectId = '$projectId' order by c.lft desc";
            $rs = $this->_db->getArray($sql);
            foreach ($rs as $k => $v) {
                if ($v['isTrial']) { // 如果任务是PK任务，则默认为100
                    if ($trialProcessDeal) {
                        $calBeginDate = $beginDate ? max($beginDate, $v['planBeginDate']) : $v['planBeginDate']; // 预计开始日期
                        $calEndDate = min($endDate, $v['planEndDate']); // 预计结束日期
                        $rs[$k]['calDays'] = (strtotime($calEndDate) - strtotime($calBeginDate)) / 86400 + 1;
                        $rs[$k]['process'] = bcmul(bcdiv($rs[$k]['calDays'], $v['days'], 6), 100, 6);
                    } else {
                        $rs[$k]['process'] = 100;
                    }
                }
            }
        }
        if ($rs) {
            $processCache = array(); // 进度缓存
            foreach ($rs as $v) {
                $activityProcess = empty($v['process']) ? 0 : $v['process']; # 此处处理进度为空的情况
                $activityProcess = $activityProcess > 100 ? 100 : $activityProcess; # 此处将进度大于100的进度设置为100
                if ($v['parentId'] == -1) {
                    // 计算进度
                    $process = $v['rgt'] - $v['lft'] == 1 ? bcadd($process, bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6), 6) :
                        bcadd($process, bcmul(bcdiv($v['workRate'], 100, 6), $processCache[$v['id']], 6), 6);
                } else {
                    if ($v['rgt'] - $v['lft'] == 1) {
                        // 进度初始化
                        $processCache[$v['parentId']] = isset($processCache[$v['parentId']]) ?
                            bcadd($processCache[$v['parentId']], bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6), 6) :
                            bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6);
                    } else {
                        // 进度初始化
                        $processCache[$v['parentId']] = $processCache[$v['id']];
                    }
                }
            }
        }
        if ($process > 100) $process = 100;
        if ($process < 0) $process = 0;
        return $process;
    }

    /**
     * 项目进度获取 - 财务类
     * @param $projectInfo
     * @param null $beginDate
     * @param $endDate
     * @return int
     */
    function getActFinanceProcess_d($projectInfo, $beginDate = null, $endDate) {
        // 合同金额
        $contractMoney = $projectInfo['contractMoney'];

        // 扣款金额
        $sql = "SELECT SUM(deductMoney) AS deductMoney FROM oa_contract_deduct WHERE state = 1
            AND contractId = '" . $projectInfo['contractId'] . "' AND TO_DAYS(ExaDT) <= TO_DAYS('" . $endDate . "') ";
        if ($beginDate) {
            $sql .= "  AND TO_DAYS(ExaDT) >= TO_DAYS('" . $beginDate . "') ";
        }
        $deductRow = $this->_db->get_one($sql);
        $deductMoney = $deductRow['deductMoney'] === null ? 0 : $deductRow['deductMoney'];

        // 入账金额
        if ($projectInfo['incomeType'] == 'SRQRFS-02') {
            $sql = "SELECT SUM(IF(isRed = 0,invoiceMoney,-invoiceMoney)) AS inMoney FROM oa_finance_invoice WHERE objType = 'KPRK-12'
            AND objId = '" . $projectInfo['contractId'] . "' AND TO_DAYS(invoiceTime) <= TO_DAYS('" . $endDate . "') ";
            if ($beginDate) {
                $sql .= "  AND TO_DAYS(invoiceTime) >= TO_DAYS('" . $beginDate . "') ";
            }
        } else {
            $sql = "SELECT SUM(IF(formType = 'YFLX-TKD',-a.money,a.money)) AS inMoney
                FROM oa_finance_income c LEFT JOIN oa_finance_income_allot a ON c.id = a.incomeId
                WHERE a.objType = 'KPRK-12' AND a.objId = '" . $projectInfo['contractId'] . "'
                    AND TO_DAYS(a.allotDate) <= TO_DAYS('" . $endDate . "') ";
            if ($beginDate) {
                $sql .= "  AND TO_DAYS(a.allotDate) >= TO_DAYS('" . $beginDate . "') ";
            }
        }
        $inMoneyRow = $this->_db->get_one($sql);
        $inMoney = $inMoneyRow['inMoney'] === null ? 0 : $inMoneyRow['inMoney'];

        // 最后进度计算
        $process = round(bcmul(bcdiv($inMoney, bcsub($contractMoney, $deductMoney, 9), 9), 100, 9), 5);

        if ($process > 100) $process = 100;
        if ($process < 0) $process = 0;
        return $process;
    }

    /**
     * 根据传进来的任务id,获取它的在项目中的占比
     * @param $id
     * @return string
     */
    function getWorkRate_d($id) {
        $arr = $this->find(array('id' => $id));
        $workRate = bcdiv($arr['workRate'], 100, 4);
        if ($arr['parentId'] != -1) {
            return bcmul($workRate, $this->getWorkRate_d($arr['parentId']), 4);
        } else {
            return $workRate;
        }
    }

    /**
     * 更新预计开始和结束日期
     * @param $projectId
     * @return bool
     */
    function updatePlanDate_d($projectId) {
        // 取当前最小开始日期和最后结束日期
        $this->searchArr = array('projectId' => $projectId);
        $objArr = $this->listBySqlId('count_list');

        // 更新到项目上
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->update(
            array('id' => $projectId),
            array('planBeginDate' => $objArr[0]['planBeginDate'], 'planEndDate' => $objArr[0]['planEndDate'],
                'expectedDuration' => (strtotime($objArr[0]['planEndDate']) - strtotime($objArr[0]['planBeginDate'])) / 86400 + 1
            )
        );
    }

    /**
     * 更新试用任务的信息
     * @param $projectId
     * @return mixed
     */
    function updateTriActivity_d($projectId) {
        //更新试用项目信息
        return $this->_db->query("update oa_esm_project_activity a left join oa_esm_project p on a.triProjectId = p.id
                set a.planBeginDate = p.planBeginDate,a.planEndDate = p.planEndDate,a.days = p.expectedDuration
            where a.projectId = " . $projectId ." and a.isTrial = 1");
    }

    /**
     * 更新A类项目进度 - 此方法用于项目进度页面以及定时任务更新项目进度
     * @param $param
     * @return bool
     */
    function updateCategoryAProcess_d($param,$updateActivityOnly = false) {
        // 项目Id初始化
        $projectId = is_array($param) ? $param['id'] : $param;

        //在这里更新试用项目进度
        $this->updateTriActivity_d($projectId);

        $esmprojectDao = new model_engineering_project_esmproject();
        if (!$esmprojectDao->isCategoryAProject_d(null, $projectId)) return false; //非A类项目直接返回
        if (!$esmprojectDao->isDoing_d($projectId)) return true; // 非在建项目直接返回

        //获取常规任务
        $activityArray = $this->findAll(
            array('projectId' => $projectId, 'isTrial' => 0), 'lft DESC',
            'id,parentId,workRate,process,lft,rgt,status,planEndDate,planBeginDate,confirmDays,days'
        );
        // 需要更新进度的上级
        $parentActivity = array();
        foreach ($activityArray as $v) {
            if ($v['rgt'] - $v['lft'] != 1) continue;     // 如果不是子任务,不做处理
            if ($v['status'] == 2) continue;              // 如果是暂停状态的任务，不做更新
            if (min($v['planBeginDate'], day_date) == day_date) continue; // 还没有开始的任务，不计算进度
            //否则更新任务进度
            $workloadDone = (strtotime(min(day_date, $v['planEndDate'])) - strtotime($v['planBeginDate'])) / 86400 + 1 + $v['confirmDays'];
            $process = bcmul(bcdiv($workloadDone, $v['days'], 6), 100, 4);
            $updateArr = array(
                'workloadDone' => $workloadDone,
                'workedDays' => $workloadDone,
                'process' => $process > 100 ? 100 : $process
            );
            $this->update(array('id' => $v['id']), $updateArr);

            if ($v['parentId'] <> -1 && !in_array($v['parentId'], $parentActivity)) array_push($parentActivity, $v['parentId']); // 将上级缓存到数组中
        }

        if(!$updateActivityOnly){
            // 更新上级进度
            if (!empty($parentActivity)) $this->autoCalProcess_d($projectId, $parentActivity);

            // 匹配整包项目，如果是则直接计划项目进度
            if ($esmprojectDao->isAllOutsourcingProject_d($projectId)) {
                $esmprojectDao->updateProjectProcess_d($projectId,
                    $this->getActCountProcess_d($projectId, null, day_date, true, true));
            }
        }

        return true;
    }

    /**
     * 自动计算进度
     * @param $projectId
     * @param $parentActivity
     * @return bool
     */
    function autoCalProcess_d($projectId, $parentActivity) {
        $parentIds = implode(',', $parentActivity);
        $this->_db->query("UPDATE
            oa_esm_project_activity a
            LEFT JOIN
            (
                SELECT
                    parentId,SUM(workRate*process/100) AS processCount
                FROM
                    oa_esm_project_activity
                WHERE
                    projectId = $projectId AND parentId IN ($parentIds)
                GROUP BY
                    parentId
            ) aa
            ON a.id = aa.parentId
            SET a.process = aa.processCount
            WHERE a.projectId = $projectId AND a.id IN ($parentIds)");
        //再查询还有没有上级
        $rs = $this->_db->getArray("SELECT parentId FROM oa_esm_project_activity WHERE id IN ($parentIds)");
        if ($rs) {
            $parentActivity = array();
            foreach ($rs as $v) {
                if ($v['parentId'] != -1) array_push($parentActivity, $v['parentId']);
            }
            return empty($parentActivity) ? true : $this->autoCalProcess_d($projectId, $parentActivity);
        } else {
            return true;
        }
    }

    /**
     * 暂停任务
     * @param $id
     * @return bool
     */
    function stop_d($id) {
        return $this->update(array('id' => $id), array('status' => 2, 'stopDate' => day_date));
    }

    /**
     * 恢复任务
     * @param $id
     * @return bool
     */
    function restart_d($id) {
        return $this->update(array('id' => $id), array('status' => 0, 'stopDate' => '0000-00-00'));
    }

    /**
     * 获取可以写日志的任务
     * @param $projectId
     * @param $activityName
     * @return mixed
     */
    function getCanLogTask_d($projectId, $activityName) {
        $rs = $this->findAll(array('projectId' => $projectId, 'isTrial' => 0, 'activityName' => $activityName),
            null,
            'id,workloadUnit,planEndDate,lft,rgt');
        if ($rs) {
            foreach ($rs as $k => $v) {
                if ($v['rgt'] - $v['lft'] != 1) unset($rs[$k]);
            }
        }
        return $rs;
    }
}