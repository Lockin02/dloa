<?php

/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:01:27
 * @version 1.0
 * @description:工作日志(oa_esm_worklog) Model层 每日进展
 */
class model_engineering_worklog_esmworklog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_worklog";
        $this->sql_map = "engineering/worklog/esmworklogSql.php";
        parent::__construct();
    }

    //数据字典字段处理
    public $datadictFieldArr = array(
        'workloadUnit'
    );

    //默认工作量单位
    public $workloadUnitDefault = 'GCGZLDW-00';

    //审核结果值定义
    public $assessResultArr = array(
        '0' => '',
        '1' => '优',
        '2' => '良',
        '3' => '中',
        '4' => '差',
        '5' => '打回'
    );

    //做一个对象缓存 -- 因为导入的时候耗费的内容太多，所以要优化
    static $initObjCache = array();

    //获取对象缓存的方法
    static function getObjCache($className)
    {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }

    /******************** 增删改查*****************************/

    /**
     * 新增方法
     * @param $object
     * @return bool|void
     * @throws $e
     */
    function add_d($object)
    {
        # 如果填入的工作站比大于100,新增失败
        if ($object['inWorkRate'] > 100) return false;
        # 如果日期格式错误，返回
        if ($object['executionDate'] == '0000-00-00') return false;

        # 如果当日已填,新增失败
        $condition = array('executionDate' => $object['executionDate'], 'createId' => $_SESSION['USER_ID'],
            'activityId' => $object['activityId']);
        if ($this->find($condition, null, 'id')) return false;

        //取费用信息的数据
        if (isset($object['esmcostdetail'])) {
            $esmcostdetail = $object['esmcostdetail'];
            unset($object['esmcostdetail']);
        }

        try {
            $this->start_d();
            //判断时间段内是否存在周报，有则返回周报id,无则新增周报然后返回id
            $weeklogDao = self::getObjCache('model_engineering_worklog_esmweeklog');
            $weeklogId = $weeklogDao->checkIsWeeklog($object);

            //查询在执行日期之后是否存在日志，有则显示 -- ，无则显示正常进度
            if (!empty($object['activityId']) && $this->hasBelowLog_d($object['executionDate'], $object['activityId'])) {
                $object['workProcess'] = -1;
            }

            //新增日志内容
            $object ['weekId'] = $weeklogId;
            $object ['status'] = 'WTJ';
            //增加部门
            $object['deptName'] = $_SESSION['DEPT_NAME'];
            $object['deptId'] = $_SESSION['DEPT_ID'];
            $object['executionTimes'] = strtotime($object['executionDate']);

            $object = $this->processDatadict($object);
            $workLogId = parent::add_d($object, true);

            //如果真有费用
            if (isset($esmcostdetail)) {
                $esmcostdetail['worklog'] = array(
                    'worklogId' => $workLogId,
                    'activityId' => $object['activityId'],
                    'activityName' => $object['activityName'],
                    'projectId' => $object['projectId'],
                    'projectCode' => $object['projectCode'],
                    'projectName' => $object['projectName'],
                    'executionDate' => $object['executionDate']
                );
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $esmcostdetailDao->addBatch_d($esmcostdetail);
            }

            //更新关联信息
            $this->updateSourceInfo_d($object);

            $this->commit_d();
            return $workLogId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 重写edit_d方法
     * @param $object
     * @param null $invoiceStatus
     * @return bool
     */
    function edit_d($object, $invoiceStatus = null)
    {
        # 如果工作量大于100，返回
        if ($object['inWorkRate'] > 100) return false;
        # 如果日期格式错误，返回
        if ($object['executionDate'] == '0000-00-00') return false;

        //取费用信息的数据
        if (isset($object['esmcostdetail'])) {
            $esmcostdetail = $object['esmcostdetail'];
            unset($object['esmcostdetail']);
        }
        //判断是否为excel导入费用日志
        if (isset($object['isExcel'])) {
            $isExcel = $object['isExcel'];
            unset($object['isExcel']);
        }

        try {
            $this->start_d();
            //编辑
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //如果真有费用
            if (isset($esmcostdetail)) {
                $esmcostdetail['worklog'] = array(
                    'worklogId' => $object['id'],
                    'activityId' => $object['activityId'],
                    'activityName' => $object['activityName'],
                    'projectId' => $object['projectId'],
                    'projectCode' => $object['projectCode'],
                    'projectName' => $object['projectName'],
                    'executionDate' => $object['executionDate']
                );
                if (isset($isExcel) && $isExcel == 1) {
                    $esmcostdetail['worklog']['isExcel'] = 1;
                    $esmcostdetail['worklog']['costMoney'] = $object['costMoney'];
                    unset($object['costMoney']);
                }
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $idsArr = $esmcostdetailDao->editBatch_d($esmcostdetail, $invoiceStatus);
            }

            //更新关联信息
            $this->updateSourceInfo_d($object);

            $this->commit_d();
            if (isset($idsArr) && $idsArr) {
                return $idsArr;
            }
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 无业务edit_d
     * @param $object
     * @return bool
     * @throws Exception
     */
    function editOrg_d($object)
    {
        try {
            //编辑
            $object = $this->processDatadict($object);
            return parent::edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 删除操作
     * @param $id
     * @return bool
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $object = $this->get_d($id);

            $this->deletes($id);

            //更新业务信息
            $this->updateSourceInfo_d($object);

            //计算人员的项目费用
            if ($object['projectId']) {
                //获取当前项目的费用
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId']);

                //更新人员费用信息
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']),
                    $projectCountArr
                );
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
    function batchAdd_d($object)
    {
        # 日期校验
        if (strtotime($object['executionDate']) > strtotime(date('Y-m-d'))) {
            return '日志还未到填写时间，请稍后再试！';
        }

        try {
            $this->start_d();

            $executionTimes = strtotime($object['executionDate']);

            //载入字段，然后新增
            $object['detail'] = util_arrayUtil::setArrayFn(array(
                'executionDate' => $object['executionDate'],
                'executionTimes' => $executionTimes,
                'workStatus' => $object['workStatus']
            ), $object['detail']);

            //保存
            $this->saveDelBatch($object['detail']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 更新日志的费用信息
     * @param $id
     * @param $costMoney
     * @return bool
     * @throws Exception
     */
    function updateCostMoney_d($id, $costMoney)
    {
        //加入判断
        if (!is_array($costMoney)) {
            $costMoney = array('costMoney' => $costMoney);
        }

        try {
            $this->update(array('id' => $id), $costMoney);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新日志状态
     * @param $weekId
     * @param string $status
     * @param null $confirmStatus
     * @return bool
     * @throws Exception
     */
    function updateStatus_d($weekId, $status = 'YTJ', $confirmStatus = null)
    {
        try {
            //提交数组
            $conditionArr = array('weekId' => $weekId);

            $updateArr = array('status' => $status);
            $updateArr = $this->addUpdateInfo($updateArr);

            if ($confirmStatus !== null) {
                $updateArr['confirmStatus'] = $confirmStatus;
            }

            $this->update($conditionArr, $updateArr);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /********************** 2013-7-3调整部分呢 ***********************/
    /**
     * 日志审核
     * @param $id
     * @param $assessResult
     * @param $feedBack
     * @return bool
     */
    function auditLog_d($id, $assessResult, $feedBack)
    {
        //获取当前日志
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //加载考核结果分数计算系数
            $assessResultDao = self::getObjCache('model_engineering_assess_esmassresult');
            $assessResultScore = $assessResultDao->getValScore_d($assessResult);

            //日志评价
            $object = array(
                'id' => $id,
                'assessResult' => $assessResult,
                'assessResultName' => $this->assessResultArr[$assessResult],
                'feedBack' => $feedBack,
                'confirmDate' => date('Y-m-d H:i:s'),
                'confirmMoney' => $obj['costMoney'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'],
                'confirmStatus' => 1,
                'processCoefficient' => bcmul($assessResultScore, $obj['thisProjectProcess'], 2)
            );

            //如果不是项目经理,直接计算工作系数,否则在项目周报考核完再计算
            $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
            if (!$esmmemberDao->isManager_d($obj['projectId'], $obj['createId'])) {
                $object['workCoefficient'] = bcdiv(bcmul($assessResultScore, $obj['inWorkRate'], 4), 100, 2);
            } else {
                //如果对应的项目周报已经完成审核,则更新当前日志的工作系数
                if ($obj['projectId']) {
                    $statusreportDao = self::getObjCache('model_engineering_project_statusreport');
                    $score = $statusreportDao->checkLogScored_d($obj['projectId'], $obj['executionDate']);
                    if ($score !== false) {
                        $object['workCoefficient'] = bcdiv(bcmul($score, $obj['inWorkRate'], 4), 1000, 2);
                    }
                }
            }

            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //更新日志下的费用状态
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            $esmcostdetailDao->auditFee_d($id);

            //确认任务进度
            if ($obj['activityId']) {
                //加入项目类型判断，如果是A类，则不更新任务进度
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $obj['projectId'])) {
                    //项目范围对象
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //获取任务的日志统计信息
                    $countInfo = $this->getWorklogCountInfo_d($obj['activityId'], null, true);

                    //更新任务日志信息 edit by kuangzw on 2013-07-04 暂停使用，新日志审核功能与此冲突
                    $process = $activityDao->updateTaskProcess_d($obj['activityId'], $countInfo);

                    if ($process == 100) {
                        //获取任务数据
                        $activityArr = $activityDao->find(array('id' => $obj['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //更新任务实际结束日期
                        $activityDao->updateEndDate_d($obj['activityId'], $activityArr['actBeginDate'], $obj['executionDate']);
                    }
                }
            }

            //计算人员的项目费用
            if ($obj['projectId']) {
                //获取当前项目的费用
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //更新人员费用信息
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 打回日志
     * @param $id
     * @param $assessResult
     * @param $feedBack
     * @return bool
     */
    function backLog_d($id, $assessResult, $feedBack)
    {
        //获取当前日志
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //日志评价
            $object = array(
                'id' => $id,
                'assessResult' => $assessResult,
                'assessResultName' => $this->assessResultArr[$assessResult],
                'feedBack' => $feedBack,
                'confirmDate' => date('Y-m-d H:i:s'),
                'backMoney' => $obj['costMoney'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'],
                'confirmStatus' => 0
            );
            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //更新日志下的费用状态
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            //如果是打回，则传入打回参数
            $esmcostdetailDao->auditFee_d($id, 1);

            //计算人员的项目费用
            if ($obj['projectId']) {
                //获取当前项目的费用
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //更新人员费用信息
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 反审核日志
     * @param $id
     * @return bool|string
     */
    function unauditLog_d($id)
    {
        //获取当前日志
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //日志评价
            $object = array(
                'id' => $id,
                'assessResult' => 0,
                'assessResultName' => '',
                'feedBack' => '',
                'confirmDate' => '0000-00-00 00:00:00',
                'confirmMoney' => 0,
                'confirmName' => '',
                'confirmId' => '',
                'confirmStatus' => 0,
                'processCoefficient' => 0,
                'workCoefficient' => 0
            );
            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //更新日志下的费用状态
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            $esmcostdetailDao->unauditFee_d($id);

            //重新计算任务进度
            if ($obj['activityId']) {
                //加入项目类型判断，如果是A类，则不更新任务进度
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $obj['projectId'])) {
                    //项目范围对象
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //获取任务的日志统计信息
                    $countInfo = $this->getWorklogCountInfo_d($obj['activityId'], null, true);

                    //更新任务日志信息 edit by kuangzw on 2013-07-04 暂停使用，新日志审核功能与此冲突
                    $process = $activityDao->updateTaskProcess_d($obj['activityId'], $countInfo);

                    if ($process == 100) {
                        //获取任务数据
                        $activityArr = $activityDao->find(array('id' => $obj['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //更新任务实际结束日期
                        $activityDao->updateEndDate_d($obj['activityId'], $activityArr['actBeginDate'], $obj['executionDate']);
                    }
                }
            }

            //重新计算项目进度
            if ($obj['projectId']) {
                //获取当前项目的费用
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //更新人员费用信息
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return $e->getMessage();
        }
    }

    /**
     * 日志审核 - 批量
     * @param $object
     * @return bool
     */
    function auditLogForPerson_d($object)
    {
        //实例化成员
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
        try {
            $this->start_d();

            //加载考核结果分数计算系数
            $assessResultDao = self::getObjCache('model_engineering_assess_esmassresult');
            $assessResultScore = $assessResultDao->getValScore_d($object['assessResults']);

            //查询要审核的日志
            $objArr = $this->findAll(array(
                'projectId' => $object['projectId'],
                'activityId' => $object['activityId'],
                'createId' => $object['createId'],
                'confirmStatus' => $object['confirmStatus']
            ), null, 'id,costMoney,thisProjectProcess,inWorkRate,executionDate');

            //更新日志下的费用状态
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');

            //如果有查到日志，则循环进行审核
            if ($objArr) {
                //如果不是项目经理,直接计算工作系数,否则在项目周报考核完再计算
                $isManager = $esmmemberDao->isManager_d($object['projectId'], $object['createId']) ? true : false;

                # 记录审核时间
                $thisTime = date('Y-m-d H:i:s');
                $maxDate = '';

                $statusreportDao = self::getObjCache('model_engineering_project_statusreport');

                foreach ($objArr as $val) {
                    //日志评价
                    $newObj = array(
                        'id' => $val['id'],
                        'assessResult' => $object['assessResults'],
                        'assessResultName' => $this->assessResultArr[$object['assessResults']],
                        'confirmDate' => $thisTime,
                        'confirmMoney' => $val['costMoney'],
                        'confirmName' => $_SESSION['USERNAME'],
                        'confirmId' => $_SESSION['USER_ID'],
                        'confirmStatus' => 1,
                        'processCoefficient' => bcmul($assessResultScore, $val['thisProjectProcess'], 2)
                    );

                    //如果不是项目经理,即时计算
                    if (!$isManager) {
                        $newObj['workCoefficient'] = bcdiv(bcmul($assessResultScore, $val['inWorkRate'], 4), 100, 2);
                    } else {
                        if ($object['projectId']) {
                            //如果对应的项目周报已经完成审核,则更新当前日志的工作系数
                            $score = $statusreportDao->checkLogScored_d($object['projectId'], $val['executionDate']);
                            if ($score !== false) {
                                $newObj['workCoefficient'] = bcdiv(bcmul($score, $val['inWorkRate'], 4), 1000, 2);
                            }
                        }
                    }

                    $newObj = $this->addUpdateInfo($newObj);
                    $this->updateById($newObj);

                    $esmcostdetailDao->auditFee_d($val['id']);

                    $maxDate = max($maxDate, $val['executionDate']);
                }
            }

            //确认任务进度
            if ($object['activityId'] && $objArr) {
                //加入项目类型判断，如果是A类，则不更新任务进度
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $object['projectId'])) {
                    //项目范围对象
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //获取任务的日志统计信息
                    $countInfo = $this->getWorklogCountInfo_d($object['activityId'], null, true);

                    //更新任务日志信息 edit by kuangzw on 2013-07-04
                    $process = $activityDao->updateTaskProcess_d($object['activityId'], $countInfo);

                    if ($process == 100) {
                        //获取任务数据
                        $activityArr = $activityDao->find(array('id' => $object['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //更新任务实际结束日期
                        $activityDao->updateEndDate_d($object['activityId'], $activityArr['actBeginDate'], $maxDate);
                    }
                }
            }

            //计算人员的项目费用
            if ($object['projectId'] && $objArr) {
                //获取当前项目的费用
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['createId']);

                //更新人员费用信息
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $object['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 批量打回日志
     * @param $object
     * @return bool
     */
    function backLogForPerson_d($object)
    {
        try {
            $this->start_d();

            //查询要审核的日志
            $objArr = $this->findAll(array(
                'projectId' => $object['projectId'],
                'activityId' => $object['activityId'],
                'createId' => $object['createId'],
                'confirmStatus' => $object['confirmStatus']
            ), null, 'id,costMoney,thisProjectProcess,inWorkRate,executionDate');

            //更新日志下的费用状态
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');

            //如果有查到日志，则循环进行审核
            if ($objArr) {
                foreach ($objArr as $val) {
                    //日志评价
                    $newObj = array(
                        'id' => $val['id'],
                        'assessResult' => $object['assessResults'],
                        'assessResultName' => $this->assessResultArr[$object['assessResults']],
                        'confirmDate' => date('Y-m-d H:i:s'),
                        'confirmName' => $_SESSION['USERNAME'],
                        'confirmId' => $_SESSION['USER_ID'],
                        'backMoney' => $val['costMoney']
                    );

                    $newObj = $this->addUpdateInfo($newObj);
                    $this->updateById($newObj);
                    $esmcostdetailDao->auditFee_d($val['id'], 1);
                }
            }

            //计算人员的项目费用
            if ($object['projectId'] && $objArr) {
                //获取当前项目的费用
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['createId']);

                //更新人员费用信息
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $object['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /************************ 业务逻辑处理 *************************/
    /**
     * 更新 日志 上级业务信息
     * @param $object
     * @throws Exception
     * @return true
     */
    function updateSourceInfo_d($object)
    {
        //项目范围对象
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

        //实例化项目对象
        $esmprojectDao = self::getObjCache('model_engineering_project_esmproject');

        //实例化项目成员
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');

        try {
            $this->start_d();

            /*************** 任务部分业务更新 ********************/
            if ($object['activityId']) {
                //获取任务数据
                $activityArr = $activityDao->find(array('id' => $object['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                //------------更新任务开始日期
                if ($activityArr['actBeginDate'] == '0000-00-00' || empty($activityArr['actBeginDate']) || strtotime($activityArr['actBeginDate']) > strtotime($object['executionDate'])) {
                    $activityDao->update(array('id' => $object['activityId']), array('actBeginDate' => $object['executionDate']));
                }
            }

            /***************** 项目部分数据更新 ******************/
            if ($object['projectId']) {
                //获取任务-个人日志的统计信息
                $thisCount = $this->getProjectMemberCountInfo_d($object['projectId'], $_SESSION['USER_ID']);
                //更新任务成员工作量
                $esmmemberDao->updateDayInfo_d($object['projectId'], $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                /*------------ 项目成员更新部分 -----------------*/
                $esmmemberArr = $esmmemberDao->find(array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']), null, 'beginDate');
                if ($esmmemberArr['beginDate'] == '0000-00-00' || empty($esmmemberArr['beginDate']) || strtotime($esmmemberArr['beginDate']) > strtotime($object['executionDate'])) {
                    $esmmemberDao->update(
                        array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']),
                        array('beginDate' => $object['executionDate'])
                    );
                }

                //获取项目数据
                $projectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'id,projectName,projectCode,planEndDate,actBeginDate');

                //------------更新项目开始日期
                if ($projectArr['actBeginDate'] == '0000-00-00' || empty($projectArr['actBeginDate']) || strtotime($projectArr['actBeginDate']) > strtotime($object['executionDate'])) {
                    $esmprojectDao->update(array('id' => $object['projectId']), array('actBeginDate' => $object['executionDate']));
                }

                //获取项目的总费用
                $projectPersonFee = $esmmemberDao->getFeePerson_d($object['projectId']);
                $esmprojectDao->updateFeePerson_d($object['projectId'], $projectPersonFee);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 更新 日志 上级业务信息
     * @param $esmprojectDao
     * @param $activityDao
     * @param $esmmemberDao
     * @param $projectId
     * @param $activityId
     * @param $createId
     * @param $executionDate
     * @return bool
     * @throws Exception
     */
    function updateLogSource_d($esmprojectDao, $activityDao, $esmmemberDao, $projectId,
                               $activityId, $createId, $executionDate)
    {

        try {
            $this->start_d();

            /*************** 任务部分业务更新 ********************/
            if ($activityId) {
                //获取任务数据
                $activityArr = $activityDao->find(array('id' => $activityId), null, 'id,activityName,actBeginDate,planEndDate');

                //------------更新任务开始日期
                if ($activityArr['actBeginDate'] == '0000-00-00' || empty($activityArr['actBeginDate']) || strtotime($activityArr['actBeginDate']) > strtotime($executionDate)) {
                    $activityDao->update(array('id' => $activityId), array('actBeginDate' => $executionDate));
                }
            }

            /***************** 项目部分数据更新 ******************/
            if ($projectId) {
                //获取任务-个人日志的统计信息
                $thisCount = $this->getProjectMemberCountInfo_d($projectId, $createId);
                //更新任务成员工作量
                $esmmemberDao->updateDayInfo_d($projectId, $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                /*------------ 项目成员更新部分 -----------------*/
                $esmmemberArr = $esmmemberDao->find(array('projectId' => $projectId, 'memberId' => $createId), null, 'beginDate');
                if ($esmmemberArr['beginDate'] == '0000-00-00' || empty($esmmemberArr['beginDate']) || strtotime($esmmemberArr['beginDate']) > strtotime($executionDate)) {
                    $esmmemberDao->update(
                        array('projectId' => $projectId, 'memberId' => $_SESSION['USER_ID']),
                        array('beginDate' => $executionDate)
                    );
                }

                //获取项目数据
                $projectArr = $esmprojectDao->find(array('id' => $projectId), null, 'id,projectName,projectCode,planEndDate,actBeginDate');

                //------------更新项目开始日期
                if ($projectArr['actBeginDate'] == '0000-00-00' || empty($projectArr['actBeginDate']) || strtotime($projectArr['actBeginDate']) > strtotime($executionDate)) {
                    $esmprojectDao->update(array('id' => $projectId), array('actBeginDate' => $executionDate));
                }

                //获取项目的总费用
                $projectPersonFee = $esmmemberDao->getFeePerson_d($projectId);
                $esmprojectDao->updateFeePerson_d($projectId, $projectPersonFee);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 获取上一次创建日志的信息
     * @return array|bool|mixed
     */
    function getLastInfo_d()
    {
        $sort = 'createTime DESC';
        $serviceesmworklog = $this->find(array('createId' => $_SESSION ['USER_ID']), $sort);
        if (is_array($serviceesmworklog)) {
            return $serviceesmworklog;
        } else {
            return array(
                'countryId' => 1,
                'provinceId' => null,
                'cityId' => null
            );
        }
    }

    /**
     * 判断某个人在某项目中是否填写了日志
     * @param $userId
     * @param $projectId
     * @return bool
     */
    function checkExistLogPro($userId, $projectId)
    {
        return $this->find(array('projectId' => $projectId, 'createId' => $userId), null) ? true : false;
    }

    /**
     * 判断人员是否有日志
     * @param $userId
     * @param $projectId
     * @return int|string
     */
    function checkExistLogUsers_d($userId, $projectId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'createIdArr' => $userId
        );
        $this->groupBy = 'c.createId';
        $rs = $this->list_d();
        if ($rs) {
            $createNameArr = array();
            foreach ($rs as $val) {
                array_push($createNameArr, $val['createName']);
            }
            return implode(',', $createNameArr);
        } else {
            return 0;
        }
    }

    /**
     * 根据任务id获取日志统计信息
     * 传入id时，取系统原统计值时不计算本日志的信息
     * @param $activityId
     * @param null $id
     * @param bool $done
     * @return array
     */
    function getWorklogCountInfo_d($activityId, $id = null, $done = false)
    {
        $this->searchArr = array(
            'activityId' => $activityId
        );
        if ($id) {
            $this->searchArr['noId'] = $id;
        }
        if ($done) {
            $this->searchArr['confirmStatus'] = 1;
        }
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * 根据任务id获取日志统计信息
     * 传入id时，取系统原统计值时不计算本日志的信息
     * @param $activityId
     * @param $memberId
     * @return array
     */
    function getActMemberCountInfo_d($activityId, $memberId)
    {
        $this->searchArr = array(
            'activityId' => $activityId,
            'createId' => $memberId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * 根据项目id获取日志统计信息
     * 传入id时，取系统原统计值时不计算本日志的信息
     * @param $projectId
     * @param $memberId
     * @return array
     */
    function getProjectMemberCountInfo_d($projectId, $memberId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'createId' => $memberId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'inWorkDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * 获取已填工作量
     * @param $thisDate
     * @param null $userId
     * @return array
     */
    function getDayUserCountInfo_d($thisDate, $userId = null)
    {
        //如果没传入用于id，则去登录人
        if (empty($userId)) {
            $userId = $_SESSION['USER_ID'];
        }
        $this->searchArr = array(
            'executionDate' => $thisDate,
            'createId' => $userId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0,
                'inWorkRate' => 0
            );
        }
    }

    /**
     * 获取项目时间范围内的日志
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getSearchData_d($projectId, $beginDate, $endDate)
    {
        $rows = $this->getAssessData_d(array(
            'projectId' => $projectId, 'beginDate' => $beginDate, 'endDate' => $endDate
        ));

        // 时间戳转换
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);

        // 请休假处理
        foreach ($rows as $k => $v) {
            if (isset($this->lastHols[$v['createId']])) {
                $actHolsDays = 0;
                foreach ($this->lastHols[$v['createId']] as $ki => $vi) {
                    $holsTime = strtotime($ki);
                    if ($holsTime <= $end && $holsTime >= $begin) {
                        $actHolsDays += $vi;
                    }
                }
                $rows[$k]['actHolsDays'] = $actHolsDays;
            }
        }

        return $rows;
    }

    /**
     * 获取缺觉日志天数
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @param $needLogs
     * @return mixed
     */
    function getWarningLogDays_d($projectId, $beginDate, $endDate, $needLogs)
    {
        // 明细数据获取
        $rows = $this->getAssessment_d(array(
            'projectId' => $projectId, 'beginDate' => $beginDate, 'endDate' => $endDate
        ), false, true);

        // 时间戳转换
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);

        // 请休假处理
        $logNum = 0;
        foreach ($rows as $k => $v) {
            // 如果检查人当天需要填写日期且没有请休假，则判定为
            if (in_array($v['executionDate'], $needLogs[$v['createId']])) {
                $logDay = bcdiv($v['inWorkRate'], 100, 2);
                $logNum = bcadd($logNum, $logDay, 2);
            }
        }
        // 需要填写的日期，检验对应天是否存在请休假，如果存在，算入到已填
        foreach ($needLogs as $k => $v) {
            foreach ($v as $vi) {
                $holsTime = strtotime($vi);
                if ($holsTime <= $end && $holsTime >= $begin && $this->lastHols[$k][$vi]) {
                    $logNum += $this->lastHols[$k][$vi];
                }
            }
        }
        return $logNum;
    }

    /**
     * 获取日志进度列表
     * @param $projectId
     * @return mixed
     */
    function getListProcess_d($projectId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'confirmStatus' => 0,
            'assessResultsNo' => '5'
        );
        $this->groupBy = 'c.activityId';
        return $this->list_d('getListProcess');
    }

    /**
     * 获取时间段内项目的统计信息
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return bool
     */
    function getProjectCountInfo_d($projectId, $beginDate, $endDate)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'confirmStatus' => 1,
            'beginDateThan' => $beginDate,
            'endDateThan' => $endDate
        );
        $this->groupBy = 'c.createId';
        $rs = $this->list_d('search_json');
        if ($rs[0]['createId']) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * 获取日志天数 - 传入开始、结束日期
     * 外包管理调用
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getTimeLog_d($beginDate, $endDate)
    {
        //获取预定义的外包部门id
        include(WEB_TOR . "includes/config.php");
        $deptIds = isset($defaultOutsourcingDept) ? implode(',', array_keys($defaultOutsourcingDept)) : '';

        $sql = "select
				c.projectId ,p.projectCode,p.projectName,p.officeId,p.officeName,p.provinceId,p.province,p.managerName,
				p.managerId,c.createName as userName,c.createId as userId,count(*) as totalDay,min(c.executionDate) as beginDate,
				max(c.executionDate) as endDate,round(sum(if(c.inWorkRate > 1,1,c.inWorkRate)),2) as feeDay,group_concat(c.ids) as ids
			from
				(
					select
						createId,createName,executionDate,projectId,sum(inWorkRate)/100 as inWorkRate,group_concat(CAST(id AS char)) as ids
					from
						oa_esm_worklog
					where projectId <> 0 and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' and isCount = 0
						 and deptId in ($deptIds)
					group by createId,executionDate,projectId
				) c
					left join
				oa_esm_project p on c.projectId = p.id
					left join
				user u on c.createId = u.USER_ID
			group by c.createId,projectId
			order by c.projectId";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取日志天数 - 传入开始、结束日期
     * 外包管理调用 - 新 create on 2014-01-14
     * @param $beginDate
     * @param $endDate
     * @param null $userId
     * @return null
     */
    function getTimeLogForSupp_d($beginDate, $endDate, $userId = null)
    {
        $user = null ? $userId : $_SESSION['USER_ID'];
        //调用外包供应商接口取数
        $personnelDao = self::getObjCache('model_outsourcing_supplier_personnel');
        $userStr = $personnelDao->getPersonIdList($user);
        //空的时候直接返回
        if (empty($userStr)) return null;
        $userStr = util_jsonUtil::strBuild($userStr);
        $sql = "select
                c.projectId ,p.projectCode,p.projectName,p.officeId,p.officeName,p.provinceId,p.province,p.managerName,
                p.managerId,c.createName as userName,c.createId as userId,count(*) as totalDay,min(c.executionDate) as beginDate,
                max(c.executionDate) as endDate,round(sum(if(c.inWorkRate > 1,1,c.inWorkRate)),2) as feeDay,group_concat(c.ids) as ids
            from
                (
                    select
                        createId,createName,executionDate,projectId,sum(inWorkRate)/100 as inWorkRate,group_concat(CAST(id AS char)) as ids
                    from
                        oa_esm_worklog
                    where projectId <> 0 and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' and isCount = 0
                         and createId in ($userStr)
                    group by createId,executionDate,projectId
                ) c
                    left join
                oa_esm_project p on c.projectId = p.id
                    left join
                user u on c.createId = u.USER_ID
            group by c.createId,projectId
            order by c.projectId";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取日期内的统计信息
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    function getCountByDates_d($projectId, $beginDate, $endDate)
    {
        $sql = "select
				activityId,sum(workloadDay) as workloadDay,sum(thisActivityProcess) as thisActivityProcess
			from
				oa_esm_worklog where projectId = '$projectId' and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' GROUP BY activityId";
        $arr = $this->_db->getArray($sql);
        $newArr = array();
        if ($arr) {//专程哈希数组
            foreach ($arr as $val) {
                $newArr[$val['activityId']] = $val;
            }
        }
        return $newArr;
    }

    /**
     * 根据年月获取日志统计数据
     * @param $thisYear
     * @param $thisMonth
     * @return array|bool
     */
    function getLogData_d($thisYear, $thisMonth)
    {
        $sql = "SELECT
                projectId,projectCode,projectName,createId,createName,SUM(inWorkRate)/100 AS inWorkRate,
                $thisYear AS thisYear,$thisMonth AS thisMonth
            FROM
                oa_esm_worklog
            WHERE
                YEAR(executionDate) = $thisYear AND MONTH(executionDate) = $thisMonth AND confirmStatus = 1
            GROUP BY projectId,createId";
        return $this->_db->getArray($sql);
    }

    /**
     * 验证项目日志填写未到截止日期
     * @param $projectIds
     * @param $executionDate
     * @return bool
     */
    function checkProjectWithoutDeadline_d($projectIds, $executionDate)
    {
        // 获取
        $esmDeadlineDao = new model_engineering_baseinfo_esmdeadline();
        $deadlineInfo = $esmDeadlineDao->getDeadlineInfo_d();

        // 如果没有限制，则直接通过验证
        if (empty($deadlineInfo) || empty($deadlineInfo['useRangeId'])) {
            return true;
        }

        $projectDao = new model_engineering_project_esmproject();
        $projectList = $projectDao->getListByIdsAndAttribute_d($projectIds,
            $deadlineInfo['useRangeId']);

        if (!empty($projectList) && strtotime($executionDate) < strtotime($deadlineInfo['date'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检测任务当前是否已填写日志
     * @param $userId
     * @param $activityId
     * @param $executionDate
     * @param null $searchType
     * @return bool
     */
    function checkActivityLog_d($userId, $activityId, $executionDate, $searchType = null)
    {
        //验证当前日期对应的周报是否可写
        $esmweeklogDao = self::getObjCache('model_engineering_worklog_esmweeklog');
        $weekLogRs = $esmweeklogDao->isSetWeekLog_d($userId, $executionDate);
        if ($weekLogRs == 1) {
            return '对应周报正在审核，如果需要填写日志，请先联系审核人打回周报！';
        } elseif ($weekLogRs == 2) {
            return '对应周报已经完成确认，不能继续填写！';
        }

        //如果是混合查询，则返回中文结果
        if ($searchType == 'mul') {
            $this->searchArr = array(
                'activityIds' => $activityId,
                'executionDate' => $executionDate,
                'createId' => $userId
            );
            $rs = $this->list_d();

            if ($rs) {
                //当日一天日志的任务
                $activityNameArr = array();
                //构建返回语句
                foreach ($rs as $val) {
                    array_push($activityNameArr, $val['activityName']);
                }
                return '任务' . implode(',', $activityNameArr) . '的日志已经填写！';
            } else {
                return false;
            }

        } else {
            $rs = $this->find(array('createId' => $userId, 'activityId' => $activityId, 'executionDate' => $executionDate), null, 'id');
            if (is_array($rs)) {
                return $rs['id'];
            } else {
                return false;
            }
        }
    }

    /**
     * 检查在执行日期之后对应的任务时候包含日志
     * @param $executionDate
     * @param $activity
     * @return bool
     */
    function hasBelowLog_d($executionDate, $activity)
    {
        $this->searchArr = array(
            'activityId' => $activity,
            'biggerDate' => $executionDate,
            'createId' => $_SESSION['USER_ID']
        );
        return $this->list_d() ? true : false;
    }

    /**
     * 判断周报还有未审核的费用
     * @param $weekId
     * @return bool
     */
    function checkCostAllAudit_d($weekId)
    {
        $this->searchArr = array(
            'weekId' => $weekId,
            'confirmStatus' => '0',
            'costMoneyNotEqu' => '0'
        );
        return $this->list_d() ? false : true;
    }

    /**
     * 查询人员 日期内 的日志信息
     * @param $userIds
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getWorklogInPeriod_d($userIds, $projectId, $beginDate, $endDate)
    {
        $this->searchArr = array(
            'userIds' => $userIds,
            'beginDateThan' => $beginDate,
            'endDateThan' => $endDate,
            'projectId' => $projectId
        );
        return $this->list_d();
    }

    /**
     * 日志数组拼装
     * @param $worklogArr
     * @return array
     */
    function logArrDeal_d($worklogArr)
    {
        $newLogArr = array();
        if ($worklogArr) {
            foreach ($worklogArr as $val) {
                //显示记录合计金额处理
                $thisCostMoney = $val['confirmStatus'] == 1 ? $val['confirmMoney'] : $val['costMoney'];
                if (!isset($newLogArr[$val['createId']]['costMoney'])) {
                    $newLogArr[$val['createId']]['costMoney'] = $thisCostMoney;
                } else {
                    $newLogArr[$val['createId']]['costMoney'] = bcadd($newLogArr[$val['createId']]['costMoney'], $thisCostMoney, 2);
                }

                $newLogArr[$val['createId']]['logStatus'] = $val['status'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['worklogId'] = $val['id'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['costMoney'] = $val['costMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['confirmMoney'] = $val['confirmMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['backMoney'] = $val['backMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['unconfirmMoney'] = $val['unconfirmMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['confirmStatus'] = $val['confirmStatus'];
            }
        }
        return $newLogArr;
    }

    /**
     * 根据周报id获取指向项目id
     * @param $weekId
     * @return bool
     */
    function getAuditProjectId_d($weekId)
    {
        $this->searchArr = array(
            'weekId' => $weekId
        );
        $this->groupBy = 'c.createId,c.projectId';
        $this->sort = 'worklogNum';
        $rs = $this->list_d('index_project');
        return $rs[0]['projectId'] ? $rs[0]['projectId'] : false;
    }

    /**
     * 费用信息 - 编辑页面
     * @param $id
     * @return null|string
     */
    function initCost_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempEdit_d($id);
    }

    /**
     * 费用信息 - 查看页面
     * @param $id
     * @return null|string
     */
    function initCostView_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempView_d($id);
    }

    /**
     * 费用信息 - 确认页面
     * @param $id
     * @return null|string
     */
    function initCostConfirm_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempConfirm_d($id);
    }

    /**
     * 费用信息 - 重新编辑
     * @param $id
     * @return null|string
     */
    function initCostReedit_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempReedit_d($id);
    }

    /**
     * 费用信息 - 票据整理编辑
     * @param $id
     * @param $costdetailId
     * @return null|string
     */
    function initCheckEdit_d($id, $costdetailId)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initCheckEdit_d($id, $costdetailId);
    }

    /**
     * 整理列表费用调整
     * 此方法相当复杂，别乱动
     * @param $object
     * @return bool
     */
    function editCheck_d($object)
    {
        $expenseId = $object['expenseId'];
        unset($object['expenseId']);
        try {
            $this->start_d();

            //先更新日志内容
            $idsArr = $this->edit_d($object, 3);

            //重新获取报销单明细，然后进行更新
            $expenseDao = new model_finance_expense_expense();
            $expenseObj = $expenseDao->find(array('id' => $expenseId));
            $ecostdetailIdArr = explode(',', $expenseObj['esmCostdetailId']);

            //实例化报销单明细类
            $expensedetailDao = new model_finance_expense_expensedetail();
            $expenseinvDao = new model_finance_expense_expenseinv();

            //去除无效的值
            if ($idsArr['delIds']) {
                foreach ($ecostdetailIdArr as $key => $val) {
                    if (in_array($val, $idsArr['delIds'])) {
                        unset($ecostdetailIdArr[$key]);
                    }
                }
            }
            $newArr = array_merge($ecostdetailIdArr, $idsArr['addIds']);

            //用新的费用名目去更新报销单
            $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
            //获取新的发票明细
            $esminvoicedetailDao = new model_engineering_cost_esminvoicedetail();

            //删除内容处理
            $this->delCostDetail_d($object, $idsArr['delIds'], $expenseObj, $expensedetailDao, $expenseinvDao, $esminvoicedetailDao);

            //获取报销单新内容
            $newIds = implode(',', $newArr);
            $newCostdetailArr = $esmcostdetailDao->getCostByIds_d($newIds);
            //循环构建发票条目以及更新费用类型
            foreach ($newCostdetailArr as $key => $val) {
                //用报销类型查询报销单明细
                $rs = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,AssID');
                //获取assId
                if (!isset($assId)) {
                    if (empty($assId)) {
                        $assArr = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo']), null, 'AssID');
                        $assId = $assArr['AssID'];
                    } else {
                        $assId = $rs['AssID'];
                    }
                }
                //重新计算修改的项
                if ($rs) {//如果已存在类型，更新内容
                    $expensedetailDao->update(array('id' => $rs['id']), array('CostMoney' => $val['costMoney'], 'esmCostdetailId' => $val['costIds']));
                } else {
                    $newId = $expensedetailDao->create(
                        array(
                            'CostMoney' => $val['costMoney'],
                            'RNo' => 1,
                            'days' => 1,
                            'esmCostdetailId' => $val['costIds'],
                            'CostTypeID' => $val['costTypeId'],
                            'HeadID' => $expenseId,
                            'BillNo' => $expenseObj['BillNo'],
                            'AssID' => $assId,
                            'MainType' => $val['parentCostType'],
                            'MainTypeId' => $val['parentCostTypeId'],
                            'Remark' => $val['remark']
                        )
                    );
                    $rs = array(
                        'id' => $newId
                    );
                }

                //循环更新发票信息
                $newCostdetailArr[$key]['expenseinv'] = $esminvoicedetailDao->getInvoice_d($val['costIds'], 3);

                foreach ($newCostdetailArr[$key]['expenseinv'] as $v) {
                    //查询对应发票
                    $irs = $expenseinvDao->find(array('BillDetailID' => $rs['id'], 'BillTypeID' => $v['invoiceTypeId']), null, 'id');
                    //重新计算发票类型项
                    if ($irs) {
                        $expenseinvDao->update(array('id' => $irs['id']), array('Amount' => $v['invoiceMoney'], 'invoiceNumber' => $v['invoiceNumber']));
                    } elseif (empty($irs)) {
                        $expenseinvDao->create(
                            array(
                                'Amount' => $v['invoiceMoney'],
                                'invoiceNumber' => $v['invoiceNumber'],
                                'BillTypeID' => $v['invoiceTypeId'],
                                'BillDetailID' => $rs['id'],
                                'BillNo' => $expenseObj['BillNo'],
                                'BillAssID' => $assId
                            )
                        );
                    }
                }
            }

            //重新计算报销单的总金额
            $expenseDao->recountExpense_d($expenseId, $expensedetailDao, $expenseinvDao);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 删除内容处理
     * @param $object
     * @param $delArr
     * @param $expenseObj
     * @param null $expensedetailDao
     * @param null $expenseinvDao
     * @param null $esminvoicedetailDao
     * @throws Exception
     */
    function delCostDetail_d($object, $delArr, $expenseObj, $expensedetailDao = null, $expenseinvDao = null, $esminvoicedetailDao = null)
    {
        //判断是否需求实例化费用明细
        if (!$expensedetailDao) {
            $expensedetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
        }
        //判断是否需求实例化费用明细
        if (!$expenseinvDao) {
            $expenseinvDao = new model_finance_expense_expenseinv();
        }
        //判断是否需求实例化费用明细
        if (!$esminvoicedetailDao) {
            $esminvoicedetailDao = new model_engineering_cost_esminvoicedetail();
        }

        //对应报销单所有缓存
        try {
            $this->start_d();

            foreach ($object['esmcostdetail'] as $key => $val) {
                //如果是需要删除的项，则进行处理
                if (isset($val['isDelTag']) && $val['isDelTag'] == 1 && isset($val['id'])) {
                    $rs = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,esmCostDetailId');
                    if (is_numeric($rs['esmCostDetailId']) && in_array($rs['esmCostDetailId'], $delArr)) {
                        //删除对应的费用信息
                        $expensedetailDao->delete(array('id' => $rs['id']));
                        //删除对应的发票信息
                        $expenseinvDao->delete(array('BillDetailID' => $rs['id']));
                    }
                } elseif (isset($val['id'])) {
                    $rs2 = null;
                    //循环发票信息，获取需要删除的发票
                    foreach ($val['invoiceDetail'] as $k => $v) {
                        //如果查询到删除的数据，才实例化对应的信息
                        if (isset($v['isDelTag']) && $v['isDelTag'] == 1 && isset($v['id'])) {
                            //判断当前发票的情况
                            //情况一：相关发票只存在本记录中
                            //情况二：一日志一费用中含有多条相同发票
                            //情况三：一日之不同费用中含相同发票
                            if (empty($rs2)) {
                                $rs2 = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,esmCostDetailId');
                            }
                            $irs = $esminvoicedetailDao->checkInvoiceExist_d($v['id'], $v['invoiceTypeId'], $rs2['esmCostDetailId']);
                            if (!$irs) {
                                //删除对应的发票信息
                                $expenseinvDao->delete(array('BillDetailID' => $rs2['id'], 'BillNo' => $expenseObj['BillNo'], 'BillTypeID' => $v['invoiceTypeId']));
                            }
                        }
                    }
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
     * 判断任务 以及子任务下 是否存在日志
     * @param $activityId
     * @return mixed
     */
    function checkActAndParentLog_d($activityId)
    {
        //先查询任务及其子节点
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $activityObj = $activityDao->find(array('id' => $activityId), null, 'lft,rgt');
        $activityIds = $activityDao->getUnderTreeIds_d($activityId, $activityObj['lft'], $activityObj['rgt']);

        //查询日志
        $this->searchArr = array('activityIds' => $activityIds);
        return $this->list_d();
    }

    /**
     * 处理人员的入离职信息
     * @param $data
     * @param $userId
     * @param $info
     * @return mixed
     */
    function dealEntryQuit_d($data, $userId = '', $info = array())
    {
        // 获取人员的入职、离职信息
        $userInfo = $userId ? $this->getPersonnelInfo_d($userId) : $info;

        // 加入入离职数据
        foreach ($data as $k => $v) {
            if ($userInfo['entryTime'] && $userInfo['entryTime'] > $v['executionTimes']) {
                $data[$k]['notEntry'] = 1;
                $data[$k]['noNeed'] = 1;
                continue;
            }
            if ($userInfo['quitTime'] && $userInfo['quitTime'] < $v['executionTimes']) {
                $data[$k]['isLeave'] = 1;
                $data[$k]['noNeed'] = 1;
                continue;
            }
        }

        return $data;
    }

    /**
     * 处理人员请休假
     * @param $data
     * @param $beginDate
     * @param $endDate
     * @param $userId
     * @param $info
     * @return mixed
     */
    function dealHolds_d($data, $beginDate = '', $endDate = '', $userId = '', $info = array())
    {
        // 获取请休假
        $hols = $beginDate ? $this->getHolsHash_d(strtotime($beginDate), strtotime($endDate), $userId) : $info;

        foreach ($data as $k => $v) {
            if ($hols[$userId][$v['executionDate']]) {
                $data[$k]['hols'] = $hols[$userId][$v['executionDate']];
                if ($hols[$userId][$v['executionDate']] == 1) {
                    $data[$k]['noNeed'] = 1;
                } else {
                    $data[$k]['noNeed'] = 1;
                    $data[$k]['need'] = bcsub(1, $hols[$userId][$v['executionDate']], 2);
                }
            }
        }
        return $data;
    }

    /**
     * 开始处理日志
     * @param $data
     * @param $projectId
     * @param $logs
     * @return mixed
     */
    function dealLog_d($data, $projectId, $logs)
    {
        $logMapping = array();

        foreach ($logs as $v) {
            if (!isset($logMapping[$v['executionTimes']])) {
                $logMapping[$v['executionTimes']] = array();
            }
            $logMapping[$v['executionTimes']][] = $v;
        }

        $rst = array();
        foreach ($data as $k => $v) {
            if (isset($logMapping[$v['executionTimes']])) {
                foreach ($logMapping[$v['executionTimes']] as $vi) {
                    if ($projectId == $vi['projectId']) {
                        $vi['monthScore'] = $vi['accessScore'];
                        $vi['inWorkRate'] = round(bcdiv($vi['inWorkRate'], 100, 4), 2);
//                        $vi['noNeed'] = 1;
                        $rst[] = $vi;
                    } else {
                        $v['noNeed'] = 1;
                        $v['otherProject'] = 1;
                        $v['projectCode'] = $vi['projectCode'];
                        $rst[] = $v;
                    }
                }
            } else {
                $rst[] = $v;
            }
        }

        return $rst;
    }

    /**
     * 处理未填写的周报
     * @param $row
     * @param $beginDate
     * @param $endDate
     * @param $projectId
     * @return array
     */
    function dealUnLog_d($row, $beginDate, $endDate, $projectId)
    {
        //计算日期
        $days = (strtotime($endDate) - strtotime($beginDate)) / 86400 + 1;
        //新数组
        $newRows = array();
        //头部信息渲染
        $thisDate = $endDate;
        for ($i = $days; $i > 0; $i--) {
            $hasLog = false;
            if ($i != $days) {
                $thisDate = date('Y-m-d', strtotime($thisDate) - 86400);
            }

            foreach ($row as $val) {
                if ($thisDate == $val['executionDate']) {
                    if ($projectId == $val['projectId']) {
                        $newRows[] = $val;
                    } else {
                        $newRows[] = array(
                            'id' => $thisDate,
                            'executionDate' => $thisDate,
                            'confirmStatus' => 3
                        );
                    }
                    $hasLog = true;
                }
            }

            if ($hasLog == false) {
                $newRows[] = array(
                    'id' => $thisDate,
                    'executionDate' => $thisDate
                );
            }
        }
        return array_reverse($newRows);
    }

    /**
     * 未填写日志
     * @param $obj
     * @return array
     */
    function warnView_d($obj)
    {
        $condition = '';
        $deptCondition = '';
        $objArr = array();//没写日志
        $otherDataDao = new model_common_otherdatas();

        if (isset($obj['k'])) {
            // 如果存在创建人，则增加过滤
            if ($obj['createId']) {
                $condition .= " AND c.createId = '" . $obj['createId'] . "'";
                $deptCondition .= " AND c.userAccount = '" . $obj['createId'] . "'";
            }
            $beginDateThan = strtotime($obj['year'] . '-' . $obj['month'] . '-01');
            $endDateThan = strtotime(date('Y-m-t', $beginDateThan));
            $condition .= " AND c.executionTimes >= '" . $beginDateThan . "'";
            $condition .= " AND c.executionTimes <= '" . $endDateThan . "'";
            $deptCondition .= " AND c.belongDeptId in (" . $obj['deptId'] . ")";
        } else {
            $beginDateThan = strtotime($obj['beginDateThan']);
            $endDateThan = strtotime($obj['endDateThan']);
            if ($beginDateThan) $condition .= " AND c.executionTimes >= '" . $beginDateThan . "'";
            if ($endDateThan) $condition .= " AND c.executionTimes <= '" . $endDateThan . "'";
            if ($obj['deptId']) $deptCondition = " AND c.belongDeptId in (" . $obj['deptId'] . ")";

            //加入权限
            $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            $deptLimit = $sysLimit['部门权限'];
            if (strpos($deptLimit, ';;') === false) {
                $deptCondition .= empty($deptLimit) ?
                    " AND c.belongDeptId in (" . $_SESSION['DEPT_ID'] . ")" :
                    " AND c.belongDeptId in (" . $deptLimit . ',' . $_SESSION['DEPT_ID'] . ")";
            }
        }

        // 如果存在过滤的职位，则拼接相关处理
        $filterJobNameStr = "";
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('RZGJJOBFILTER');
        if(count($matchConfigItem) > 0){
            foreach ($matchConfigItem as $configItem){
                if($configItem['config_item1'] != ""){
                    $filterJobNameStr .= ($filterJobNameStr == "")?
                        trim($configItem['config_item1'],",") :
                        ",".trim($configItem['config_item1'],",");
                }
            }
            $deptCondition .= " AND c.jobName NOT IN(" . util_jsonUtil::strBuild($filterJobNameStr) . ")";
        }
//        $filterJobName = $otherDataDao->getConfig('engineering_worklog_warning_filter_jobName');
//        if ($filterJobName) {
//            $deptCondition .= " AND c.jobName NOT IN(" . util_jsonUtil::strBuild($filterJobName) . ")";
//        }

        $sql = <<<MARK
    		SELECT c.userAccount AS createId,c.userName AS createName,c.belongDeptName,c.jobName,w.writeLog,
    		    if(c.entryDate = '0000-00-00', '', c.entryDate) AS entryDate,
    		    if(c.quitDate = '0000-00-00', '', c.quitDate) AS quitDate,
    		    w.confirmStatus
            FROM
                oa_hr_personnel c
                LEFT JOIN
                (
                    SELECT GROUP_CONCAT(CAST(c.executionDate AS char)) AS writeLog,c.createId,
                        GROUP_CONCAT(CAST(c.confirmStatus AS char)) AS confirmStatus FROM
                        (
                            SELECT
                                c.executionDate,c.createId,c.confirmStatus
                            FROM oa_esm_worklog c WHERE 1 $condition
                            GROUP BY c.executionDate,c.createId
                        ) c GROUP BY c.createId
                ) w ON c.userAccount = w.createId
            WHERE c.employeesState = 'YGZTZZ' $deptCondition
MARK;
        $rows = $this->findSql($sql);

        // 生成日期
        $num = ($endDateThan - $beginDateThan) / 86400; // 天数
        $nextStamp = $beginDateThan;
        $speDate = array(date('Y-m-d', $nextStamp));//查询的时间段
        for ($i = 1; $i <= $num; $i++) { // 查询日期间的所有日期
            $nextStamp = $nextStamp + 86400;
            array_push($speDate, date('Y-m-d', $nextStamp));
        }

        //查找未写日志时间
        if (!empty($rows) && !empty($speDate)) {

            // 请休假
            $holsInfo = $this->getHolsHash_d($beginDateThan, $endDateThan);

            foreach ($rows as $v) {
                // 生成 日期 =》 确认状态 的数组
                $logArr = array_combine(explode(',', $v['writeLog']), explode(',', $v['confirmStatus']));

                // 开始匹配查询时间
                foreach ($speDate as $time) {
                    $thisTime = strtotime($time);
                    // 条件如下：日志未提交 / 不存在请休假 / 大于等于入职日期 / 小于等于离职日期
                    if (!isset($logArr[$time]) && !isset($holsInfo[$v['createId']][$time])
                        && (empty($v['entryDate']) || $thisTime >= strtotime($v['entryDate']))
                        && (empty($v['quitDate']) || $thisTime <= strtotime($v['quitDate']))
                    ) {
                        $msg = '未填报';
                    } else if ($logArr[$time] == '0') {
                        $msg = '未完成';
                    } else {
                        continue;
                    }
                    array_push($objArr, array(
                        'executionDate' => $time,
                        'createId' => $v['createId'],
                        'createName' => $v['createName'],
                        'belongDeptName' => $v['belongDeptName'],
                        'jobName' => $v['jobName'],
                        'msg' => $msg
                    ));
                }
            }
        }
        return $objArr;
    }

    /**
     * 未填写日志汇总
     * @param $obj
     * @return array
     */
    function warnSummary_d($obj)
    {
        // 获取告警数据
        $warningData = $this->warnView_d($obj);

        // 汇总数据
        $warningSummary = array();
        if (!empty($warningData)) {
            foreach ($warningData as $v) {
                if (!isset($warningSummary[$v['createId']])) {
                    $warningSummary[$v['createId']] = $v;
                    $warningSummary[$v['createId']]['warningNum'] = 1;
                } else {
                    $warningSummary[$v['createId']]['warningNum']++;
                }
            }
            $warningSummary = array_values($warningSummary);
        }

        return $warningSummary;
    }
    /******************************** 日志导入部分 ***********************/
    /**
     * 日志导入
     */
    function excelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        $datadictArr = array();//数据字典数组
        $datadictDao = self::getObjCache('model_system_datadict_datadict');

        //项目缓存数组
        $esmprojectArr = array();
        $esmprojectDao = self::getObjCache('model_engineering_project_esmproject');
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');

        //城市省份缓存
        $cityArr = array();
        $cityDao = self::getObjCache('model_system_procity_city');
        $provinceArr = array();
        $provinceDao = self::getObjCache('model_system_procity_province');

        //缓存日志数组
        $esmworklogArr = array();

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //判断日志日期是否存在请休假记录
                $holsStr = $this->isInHols_d($excelData);
                if (!empty($holsStr)) {
                    $tempArr['docCode'] = '【' . $holsStr . '】存在请休假记录，工作状态不能为工作或待命';
                    $tempArr['result'] = '导入失败';
                    array_push($resultArr, $tempArr);
                    return $resultArr;
                }

                // 获取入职日期
                $personnelInfo = $this->getPersonnelInfo_d();
                $entryTime = strtotime($personnelInfo['entryDate']);

                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $val[2] = strtoupper(trim($val[2]));
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $inArr = array();
                        //执行日期
                        if (!empty($val[0]) && $val[0] != '0000-00-00' && trim($val[0]) != '') {
                            $val[0] = trim($val[0]);
                            // 获取日期
                            $inArr['executionDate'] = !is_numeric($val[0]) ? $val[0] : util_excelUtil::exceltimtetophp($val[0]);

                            //不能提前填日志
                            $executionTime = strtotime($inArr['executionDate']);
                            if (strtotime(day_date) < $executionTime) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不能提前填日志';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else if ($executionTime < $entryTime) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不能填写入职日期之前的日志';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写执行日期';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //工作状态
                        if (!empty($val[1])) {
                            $val[1] = trim($val[1]);
                            if (!isset($datadictArr[$val[1]])) {
                                $rs = $datadictDao->getCodeByName('GXRYZT', $val[1]);
                                if (!empty($rs)) {
                                    $inArr['workStatus'] = $datadictArr[$val[1]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的工作状态';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['workStatus'] = $datadictArr[$val[1]]['code'];
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写工作状态';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //项目编号
                        if (!empty($val[2]) && !empty($val[3])) {
                            //项目缓存处理
                            if (!isset($esmprojectArr[$val[2]])) {
                                $esmprojectObj = $esmprojectDao->find(array('projectCode' => $val[2]), null, 'id,projectName,maxLogDay,planEndDate,status,attribute');
                                if ($esmprojectObj) {
                                    $esmprojectArr[$val[2]] = $esmprojectObj;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!没有查询到相关的项目';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            // 非后台项目需要验证项目成员
                            if ($esmprojectArr[$val[2]]['attribute'] != "GCXMSS-04") {
                                // 项目成员缓存
                                if (!isset($esmprojectArr[$val[2]]['isMember'])) {
                                    $esmmemberObj = $esmmemberDao->find(
                                        array('projectCode' => $val[2], 'memberId' => $_SESSION['USER_ID'], 'status' => 0),
                                        null, 'id');
                                    $esmprojectArr[$val[2]]['isMember'] = $esmmemberObj ? true : false;
                                }

                                // 项目成员验证
                                if (!$esmprojectArr[$val[2]]['isMember']) {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!非项目成员或者已经离开的人员不能导入日志';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            //项目状态判断
                            if ($esmprojectArr[$val[2]]['status'] != 'GCXMZT02') {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!项目不是在建状态';
                                array_push($resultArr, $tempArr);
                                continue;
                            }

                            //日期判断
                            if ($esmprojectArr[$val[2]]['maxLogDay'] != 0 &&
                                round((strtotime(day_date) - strtotime($inArr['executionDate'])) / 86400) > $esmprojectArr[$val[2]]['maxLogDay']
                            ) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!日志已超过填报期限';
                                array_push($resultArr, $tempArr);
                                continue;
                            }

                            //任务缓存处理
                            if (!isset($esmprojectArr[$val[2]][$val[3]])) {
                                $esmactivityObjs = $activityDao->getCanLogTask_d($esmprojectArr[$val[2]]['id'], $val[3]);
                                if ($esmactivityObjs && count($esmactivityObjs) == 1) {
                                    //弹出数组
                                    $esmprojectArr[$val[2]][$val[3]] = array_pop($esmactivityObjs);
                                } elseif ($esmactivityObjs && count($esmactivityObjs) > 1) {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!项目下存在多个同名任务，不能导入日志';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!项目中没有查询到相关的任务';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            //数据载入
                            $inArr['projectId'] = $esmprojectArr[$val[2]]['id'];
                            $inArr['projectCode'] = $val[2];
                            $inArr['projectName'] = $esmprojectArr[$val[2]]['projectName'];
                            $inArr['projectEndDate'] = $esmprojectArr[$val[2]]['planEndDate'];
                            $inArr['activityId'] = $esmprojectArr[$val[2]][$val[3]]['id'];
                            $inArr['activityName'] = $val[3];
                            $inArr['activityEndDate'] = $esmprojectArr[$val[2]][$val[3]]['planEndDate'];
                            $inArr['workloadUnit'] = $esmprojectArr[$val[2]][$val[3]]['workloadUnit'];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!项目和任务必须填写';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //省份
                        if (!empty($val[4])) {
                            $val[4] = trim($val[4]);
                            if (!isset($provinceArr[$val[4]])) {
                                $rs = $provinceDao->find(array('provinceName' => $val[4]), null, 'id');
                                if (!empty($rs)) {
                                    $inArr['provinceId'] = $provinceArr[$val[4]]['id'] = $rs['id'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的省份';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['provinceId'] = $provinceArr[$val[4]]['id'];
                            }
                            $inArr['province'] = $val[4];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写省份';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //城市
                        if (!empty($val[5])) {
                            $val[5] = trim($val[5]);
                            if (!isset($cityArr[$val[5]])) {
                                $rs = $cityDao->find(array('cityName' => $val[5]), null, 'id');
                                if (!empty($rs)) {
                                    $inArr['cityId'] = $cityArr[$val[5]]['id'] = $rs['id'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的城市';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['cityId'] = $cityArr[$val[5]]['id'];
                            }
                            $inArr['city'] = $val[5];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写城市';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //完成量
                        $workloadDay = $val[6] === '' ? 'NONE' : sprintf("%f", trim($val[6]));
                        if ($workloadDay != 'NONE') {
                            $inArr['workloadDay'] = $workloadDay;
                        }

                        //投入工作量比例
                        $inWorkRate = $val[7] === '' ? 'NONE' : sprintf("%f", abs(trim($val[7])));
                        if ($inWorkRate != 'NONE') {
                            if ($inWorkRate < 0) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!投入工作比例不能小于0';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else {
                                $inArr['inWorkRate'] = $inWorkRate * 100;
                            }
                        }

                        //工作描述
                        if (!empty($val[8])) {
                            $inArr['description'] = $val[8];
                        }

                        //备注
                        if (!empty($val[9])) {
                            $inArr['problem'] = $val[9];
                        }

                        //默认载入国家信息
                        $inArr['country'] = '中国';
                        $inArr['countryId'] = '1';

                        //判断日志是否存在
                        if (isset($esmworklogArr[$inArr['executionDate']][$inArr['activityId']])) {
                            $inArr['id'] = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'];
                            //临时换存在投入工作占比，如不符合，则还原
                            $tempInWorkRate = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'];
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $inArr['inWorkRate'];
                        } else {
                            //先去库里面找
                            $esmworklogObjs = $this->findAll(array('executionDate' => $inArr['executionDate'], 'createId' => $_SESSION['USER_ID']));
                            if ($esmworklogObjs) {
                                foreach ($esmworklogObjs as $v) {
                                    $esmworklogArr[$inArr['executionDate']][$v['activityId']] = $v;
                                }
                            }
                            //如果已经存在，则赋值
                            if ($esmworklogArr[$inArr['executionDate']][$inArr['activityId']]) {
                                $inArr['id'] = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'];
                                //临时缓存在投入工作占比，如不符合，则还原
                                $tempInWorkRate = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'];
                                $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $inArr['inWorkRate'];
                            } else {
                                $tempInWorkRate = 0;
                                $esmworklogArr[$inArr['executionDate']][$inArr['activityId']] = $inArr;
                            }
                        }

                        //判断日志是否审批
                        if ($inArr['id'] && $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['confirmStatus'] == 1) {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!日志已审核，不能进行更新';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        // 如果是新增，并且存在项目id, 则开始验证日志填报期限
                        if (!isset($inArr['id']) && $inArr['projectId']) {
                            if (!$this->checkProjectWithoutDeadline_d($inArr['projectId'], $inArr['executionDate'])) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!项目超过截止填报日期';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //判断投入工作占比是否满足
                        if ($this->calInWorkRateExcel_d($esmworklogArr[$inArr['executionDate']])) {
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $tempInWorkRate;
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!投入工作比例已超出范围';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //获取进度
                        if (isset($inArr['id'])) {
                            $processInfo = $activityDao->calTaskProcess_d($inArr['activityId'], $inArr['workloadDay'], $inArr['id']);
                        } else {
                            $processInfo = $activityDao->calTaskProcess_d($inArr['activityId'], $inArr['workloadDay'], null);
                        }
                        //计算进度
                        $inArr['workProcess'] = $processInfo['process'];
                        $inArr['thisActivityProcess'] = $processInfo['thisActivityProcess'];
                        $inArr['thisProjectProcess'] = $processInfo['thisProjectProcess'];

                        //根据id 判断是做变更处理还是新增处理
                        if (isset($inArr['id'])) {
                            // 更新修改了日志数据后,初始化确认数据
                            $inArr['confirmDate'] = null;
                            $inArr['confirmId'] = '';
                            $inArr['confirmName'] = '';
                            $inArr['confirmStatus'] = 0;
                            $inArr['assessResult'] = 0;
                            $inArr['assessResultName'] = '';
                            $inArr['feedBack'] = '';

                            $this->edit_d($inArr);
                            $tempArr['result'] = '更新成功';
                        } else {
                            $newId = $this->add_d($inArr);
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'] = $newId;
                            $tempArr['result'] = '新增成功';
                        }

                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 计算日期内的工作站比是否超标 - excel导入用
     * @param $esmworklogArr
     * @return bool
     */
    function calInWorkRateExcel_d($esmworklogArr)
    {
        $inWorkRate = 0;
        foreach ($esmworklogArr as $val) {
            $inWorkRate = bcadd($inWorkRate, $val['inWorkRate'], 2);
        }
        return $inWorkRate > 100 || $inWorkRate < 0 ? true : false;
    }

    /**
     * 获取查询列表的数据
     * @param $object
     * @return string
     */
    function getSearchList($object)
    {
        $condition = '';
        $workStatusSql = '';
        $projectIdSql = '';
        if (!empty($object['workStatusArr'])) {
            $workStatus = util_jsonUtil::strBuild($object['workStatusArr']);
            $workStatusSql .= " c.workStatus in($workStatus)";
        }
        if (!empty($object['projectId'])) {
            $projectIdSql .= " AND c.projectId = " . $object['projectId'];
        }
        $work = " c.workStatus = 'GXRYZT-01' ";
        $sql = "
            select
                c.createId,c.createName,c.projectId,c.projectCode,c.projectName,
                round(sum(if($workStatusSql,c.inWorkRate/100,0)),2) as inWorkRate,
                round(sum(if($workStatusSql,c.workCoefficient/c.inWorkRate * 100,0)),2) as auditScore,
                round(sum(if($workStatusSql,c.workCoefficient,0))/sum(if($workStatusSql,c.inWorkRate/100,0)),2) as perAuditScore,
                m.monthAuditScore,
                sum(c.costMoney) as costMoney,
                sum(c.thisProjectProcess) as thisProjectProcess,
                sum(c.processCoefficient) as processCoefficient,
                round(sum(if($work,c.workCoefficient/c.inWorkRate * 100,0)),2) as workCoefficient
            from
                oa_esm_worklog c
                LEFT JOIN
                (
                SELECT createId,round(sum(if($workStatusSql,c.workCoefficient,0))/sum(if($workStatusSql,c.inWorkRate/100,0)),2) as monthAuditScore
                FROM oa_esm_worklog c
                WHERE
                c.confirmStatus ='{$object['confirmStatus']}' and c.executionDate >= '{$object['beginDateThan']}'
                and c.executionDate <= '{$object['endDateThan']}'
                GROUP BY createId
                ) m ON c.createId = m.createId
            where c.confirmStatus ='{$object['confirmStatus']}' and c.executionDate >= '{$object['beginDateThan']}' and c.executionDate <= '{$object['endDateThan']}' $projectIdSql
            $condition
            group by c.projectId,c.createId";
        return $this->_db->getArray($sql);
    }

    /**
     * 工作量统计
     * @param $object
     * @param bool $getSql
     * @return string
     */
    function getSearchDeptList_d($object, $getSql = false)
    {
        $condition = '';
        $projectIdSql = '';
        $workStatusSql = " c.workStatus in('GXRYZT-01','GXRYZT-02','GXRYZT-04')";
        if (!empty($object['projectId'])) {
            $projectIdSql .= " AND c.projectId = " . $object['projectId'];
        }
        $work = " c.workStatus = 'GXRYZT-01' ";
        $sql = "
            SELECT
                c.createId,c.createName,u.belongDeptName,c.projectId,c.projectCode,c.projectName,
                round(SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS inWorkRate,
                round(SUM(if($workStatusSql,c.workCoefficient/c.inWorkRate * 100,0)),2) AS auditScore,
                round(SUM(if($workStatusSql,c.workCoefficient,0))/SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS perAuditScore,
                m.monthAuditScore,
                SUM(c.costMoney) AS costMoney,
                SUM(c.thisProjectProcess) AS thisProjectProcess,
                SUM(c.processCoefficient) AS processCoefficient,
                round(SUM(if($work,c.workCoefficient/c.inWorkRate * 100,0)),2) AS workCoefficient
            FROM
                oa_esm_worklog c
                LEFT JOIN
                (
                SELECT createId,round(SUM(if($workStatusSql,c.workCoefficient,0))/SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS monthAuditScore
                FROM oa_esm_worklog c
                WHERE
                c.confirmStatus = 1 AND c.executionDate >= '{$object['beginDate']}'
                and c.executionDate <= '{$object['endDate']}'
                GROUP BY createId
                ) m ON c.createId = m.createId
				LEFT JOIN
					oa_hr_personnel u ON u.userAccount = c.createId
            WHERE c.confirmStatus = 1 AND c.executionDate >= '{$object['beginDate']}'
            	AND c.executionDate <= '{$object['endDate']}' $projectIdSql
            $condition
            group by c.projectId,c.createId";
        return $getSql ? $sql : $this->_db->getArray($sql);
    }

    /**
     * 加载用户等级
     * @param $rows
     * @param $object
     * @return bool
     */
    function appendLogInfo_d($rows, $object)
    {
        $deptId = is_array($object) ? $object['deptId'] : $object;
        if ($rows) {
            $deptIdArr = array();
            // 加入部门数据过滤
            if ($deptId) {
                $deptIdArr = explode(',', $deptId);
            }

            // 获取用户信息
            $personArr = $this->_db->getArray('SELECT userAccount,personLevel,userNo,belongDeptId,belongDeptName FROM oa_hr_personnel');
            $personLevelInfo = array(); // 缓存人员等级数组
            foreach ($personArr as $v) {
                $personLevelInfo[$v['userAccount']] = $v;
            }
            unset($personArr); // 在这里unset掉

            // 开始过滤部门信息
            foreach ($rows as $k => $v) {
                if (!empty($deptIdArr) && !in_array($personLevelInfo[$v['createId']]['belongDeptId'], $deptIdArr)) {
                    unset($rows[$k]);
                    continue;
                }
                $rows[$k]['personLevel'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['personLevel'] : '';
                $rows[$k]['userNo'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['userNo'] : '';
                $rows[$k]['deptName'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['belongDeptName'] : '';
            }
            unset($personLevelInfo); // 在这里unset掉
            return $rows;
        }
        return false;
    }

    /**
     * 查询项目成员
     * @param $projectId
     * @return array
     */
    function findMember_d($projectId)
    {
        $memberDao = new model_engineering_member_esmmember();
        $data = $memberDao->getMemberInProject_d($projectId);
        foreach ($data as $k => $v) {
            $data[$k]['createId'] = $v['memberId'];
            $data[$k]['createName'] = $v['memberName'];
        }
        return $data;
    }

    /**
     * 工作量统计 - 合计部分
     * @param $rows
     * @return array
     */
    function getSearchDeptCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'costMoney' => 0,
            'thisProjectProcess' => 0,
            'processCoefficient' => 0,
            'workCoefficient' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['costMoney'] = bcadd($rtOjb['costMoney'], $v['costMoney'], 2);
            $rtOjb['thisProjectProcess'] = bcadd($rtOjb['thisProjectProcess'], $v['thisProjectProcess'], 2);
            $rtOjb['processCoefficient'] = bcadd($rtOjb['processCoefficient'], $v['processCoefficient'], 2);
            $rtOjb['auditScore'] = bcadd($rtOjb['auditScore'], $v['auditScore'], 2);
            $rtOjb['workCoefficient'] = bcadd($rtOjb['workCoefficient'], $v['workCoefficient'], 2);
        }
        return $rtOjb;
    }

    /**
     * 工作量统计 - 合计部分
     * @param $rows
     * @return array
     */
    function getNewSearchDeptCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'monthScore' => 0,
            'workloadDay' => 0,
            'actHolsDays' => 0,
            'countDay' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['monthScore'] = bcadd($rtOjb['monthScore'], $v['monthScore'], 2);
            $rtOjb['workloadDay'] = bcadd($rtOjb['workloadDay'], $v['workloadDay'], 2);
            $rtOjb['actHolsDays'] = bcadd($rtOjb['actHolsDays'], $v['actHolsDays'], 2);
            $rtOjb['countDay'] = bcadd($rtOjb['countDay'], $v['countDay'], 2);
        }
        return $rtOjb;
    }

    /**
     * 输出html
     * @param $rows
     * @return string
     */
    function searchDeptHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>序号</th><th>姓名</th><th>部门名称</th><th>人员等级</th>' .
                '<th>员工编号</th><th>项目名称</th><th>考核天数</th><th>考核系数</th><th>平均考核系数</th><th>月考核系数</th><th>工作系数</th><th>项目完成量</th>' .
                '<th>进展系数</th><th>费用</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[createName]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[createName]</a></td>";
                $html .= "<td>$v[deptName]</td><td>$v[personLevel]</td><td>$v[userNo]</td>" .
                    "<td>$v[projectName]</td><td>$v[inWorkRate]</td><td>$v[auditScore]</td><td>$v[perAuditScore]</td><td>$v[monthAuditScore]</td>" .
                    "<td>$v[workCoefficient]</td><td>$v[thisProjectProcess] %</td><td>$v[processCoefficient]</td><td>$v[costMoney]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return '没有查询到数据';
        }
    }

    /**
     * 输出html
     * @param $rows
     * @return string
     */
    function newSearchDeptHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>序号</th>' .
                '<th>姓名</th><th>员工编号</th><th>所属部门</th><th>项目编号</th><th>考勤投入</th>' .
                '<th>考核得分</th><th>统计天数</th><th>出勤系数</th><th>考核系数</th><th>请休假天数</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[createName]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[createName]</a></td>";
                $html .= "<td>$v[userNo]</td><td>$v[deptName]</td><td>$v[projectCode]</td>" .
                    "<td>$v[inWorkRate]</td><td>$v[monthScore]</td><td>$v[countDay]</td>" .
                    "<td>$v[attendance]</td><td>$v[assess]</td><td>$v[hols]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return '没有查询到数据';
        }
    }

    /**
     * 获取周数据信息
     * @param $projectId
     * @return mixed
     */
    function getWeekStatus_d($projectId)
    {
        $this->searchArr = array('projectId' => $projectId);
        $this->groupBy = "date_format(executionDate,'%Y%w'),activityId";
        $this->sort = "date_format(executionDate,'%Y%w') desc,activityId";
        return $this->list_d('select_weekstatus');
    }

    /**
     * 获取项目日志的开始、结束日期
     * @param $projectId
     * @return array
     */
    function getDates_d($projectId)
    {
        if ($projectId) {
            $beginDate = $this->get_table_fields($this->tbl_name, "projectId = $projectId AND executionDate <> '0000-00-00'", "min(executionDate)");
            $endDate = $this->get_table_fields($this->tbl_name, "projectId = $projectId AND executionDate <> '0000-00-00'", "max(executionDate)");
            return array(
                'beginDate' => $beginDate,
                'endDate' => $endDate
            );
        } else {
            return array(
                'beginDate' => '',
                'endDate' => ''
            );
        }
    }

    /**
     * 更新项目经理的工作系数
     * @param $projectId
     * @param $weekNo
     * @param $score
     * @return mixed
     */
    function updateWorkCoefficient_d($projectId, $weekNo, $score)
    {
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $dateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);
        // 评分过载处理
        $score = $score > 10 ? 10 : $score;
        // 获取项目经理
        $esmmemberDao = new model_engineering_member_esmmember();
        $esmmemberArr = $esmmemberDao->getManagerInProject_d($projectId);
        if ($esmmemberArr) {
            $memberIdArr = array();
            foreach ($esmmemberArr as $v) {
                array_push($memberIdArr, $v['memberId']);
            }
            $memberIds = "'" . implode("','", $memberIdArr) . "'";
            $sql = "update oa_esm_worklog
						set workCoefficient = round(inWorkRate/100 * $score / 10,2)
					where
						createId in ($memberIds) and projectId = '$projectId' and executionDate >= '{$dateInfo['beginDate']}' and executionDate <= '{$dateInfo['endDate']}'";
            return $this->_db->query($sql);
        }
    }

    /**
     * 重新计算项目进度
     * @param $projectId
     */
    function recountProjectProcess_d($projectId)
    {
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $activityArr = $activityDao->findAll(array('projectId' => $projectId));

        foreach ($activityArr as $val) {
            if ($val['workload']) {
                $workRate = $activityDao->getWorkRate_d($val['id']);
                $sql = "update oa_esm_worklog c set c.thisActivityProcess = round((c.workloadDay/" . $val['workload'] . ")*100,2) , c.thisProjectProcess = "
                    . "round((c.workloadDay/" . $val['workload'] . ")*" . $workRate . "*100,2) where activityId = " . $val['id'];
                $this->_db->query($sql);
            }
        }
    }

    /******************************** 费用日志导入部分 ***********************/
    /**
     * 费用日志导入
     */
    function costExcelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//结果数组


        $tempArr = array();

        $costTypeDao = new model_finance_expense_costtype();

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);//执行日期
                    $val[1] = trim($val[1]);//填报人员
                    $val[2] = trim($val[2]);//任务名称
                    $val[3] = trim($val[3]);//费用大类
                    $val[4] = trim($val[4]);//费用小类
                    $val[5] = trim($val[5]);//发票类型
                    $val[6] = trim($val[6]);//发票金额
                    $val[7] = trim($val[7]);//发票数量
                    $val[8] = trim($val[8]);//备注
                    $actNum = $key + 2;
                    $inArr = array();
                    $esmcostdetailArr = array();
                    $invoiceDetailArr = array();
                    //执行日期
                    if (!empty($val[0]) && $val[0] != '0000-00-00' && $val[0] != '') {
                        if (!is_numeric($val[0])) {
                            $inArr['executionDate'] = $val[0];
                        } else {
                            $inArr['executionDate'] = util_excelUtil::exceltimtetophp($val[0]);
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写执行日期';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //填报人员
                    if (empty($val[1]) && $val[1] == '') {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写填报人员';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //任务名称
                    if (!empty($val[2]) && $val[2] != '') {
                        $inArr['activityName'] = $val[2];
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        $tempArr['result'] = '导入失败!没有填写任务名称';
                        array_push($resultArr, $tempArr);
                        continue;
                    }

                    //根据执行日期，填报人员，任务名称判断该工作日志是否存在，存在则返回其它所需值，不存在不导入该费用日志
                    if (!empty($val[0]) && !empty($val[1]) && !empty($val[2])) {
                        //获取工作日志信息
                        $workLogObj = $this->findAll(array('executionDate' => $inArr['executionDate'], 'createName' => $val[1], 'activityName' => $val[2]), null, 'id,projectId,projectCode,projectName,activityId,confirmStatus,costMoney,feeRegular,invoiceMoney,invoiceNumber');
                        if (is_array($workLogObj)) {
                            //若查到不只一条日志记录，则报错
                            if (count($workLogObj) > 1) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!查询到重复的工作日志';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $inArr['id'] = $workLogObj['0']['id'];
                            $inArr['projectId'] = $workLogObj['0']['projectId'];
                            $inArr['projectCode'] = $workLogObj['0']['projectCode'];
                            $inArr['projectName'] = $workLogObj['0']['projectName'];
                            $inArr['activityId'] = $workLogObj['0']['activityId'];
                            $inArr['costMoney'] = $workLogObj['0']['costMoney'];
                            $inArr['feeRegular'] = $workLogObj['0']['feeRegular'];
                            $inArr['invoiceMoney'] = $workLogObj['0']['invoiceMoney'];
                            $inArr['invoiceNumber'] = $workLogObj['0']['invoiceNumber'];
                            $inArr['isExcel'] = 1;
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有查询到相关的工作日志';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //费用大类
                        if (!empty($val[3]) && $val[3] != '') {
                            //根据费用类型名称获取相应的id
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[3]);
                            if (!empty($costTypeInfo)) {
                                $esmcostdetailArr['parentCostType'] = $val[3];
                                $esmcostdetailArr['parentCostTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!该费用大类不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写费用大类';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //发票类型id
                        if (!empty($val[5]) && $val[5] != '') {
                            //根据发票类型名称获取相应的id
                            $sql = "select ID from bill_type where Name='" . $val[5] . "'";
                            $rs = $this->_db->get_one($sql);
                            $invoiceTypeId = $rs['ID'];
                            if (!empty($invoiceTypeId)) {
                                $invoiceDetailArr['0']['invoiceTypeId'] = $invoiceTypeId;
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!该发票类型不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写发票类型';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //发票金额
                        if (!empty($val[6]) && $val[6] != '') {
                            $invoiceDetailArr['0']['invoiceMoney'] = $val[6];
                            //费用金额数值与发票金额一致
                            $esmcostdetailArr['costMoney'] = $val[6];
                            //更新工作日志的合计金额，常规费用，发票金额
                            $inArr['costMoney'] += $val[6];
                            $inArr['feeRegular'] += $val[6];
                            $inArr['invoiceMoney'] += $val[6];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写发票金额';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //发票数量
                        if (!empty($val[7]) && $val[7] != '') {
                            $invoiceDetailArr['0']['invoiceNumber'] = $val[7];
                            $inArr['invoiceNumber'] += $val[7];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写发票数量';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //备注
                        if (!empty($val[8]) && $val[8] != '') {
                            $esmcostdetailArr['remark'] = $val[8];
                        }
                        //费用小类
                        if (!empty($val[4]) && $val[4] != '') {
                            //根据费用类型名称获取相应的id
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[4]);
                            if (!empty($costTypeInfo)) {
                                if ($costTypeInfo['ParentCostTypeID'] != $esmcostdetailArr['parentCostTypeId']) {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!费用大类与费用小类不匹配';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                //该费用若存在，获取其id,并更新费用金额
                                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                                $esmcostdetailInfo = $esmcostdetailDao->findAll(array('projectId' => $inArr['projectId'], 'worklogId' => $inArr['id'], 'executionDate' => $inArr['executionDate'], 'createName' => $val[1], 'activityName' => $val[2], 'parentCostType' => $val[3], 'costType' => $val[4]), null, 'id,costMoney');
                                if (!empty($esmcostdetailInfo)) {
                                    if (count($esmcostdetailInfo) > 1) {
                                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                                        $tempArr['result'] = '导入失败!查询到重复的工作日志';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                    $esmcostdetailArr['id'] = $esmcostdetailInfo['0']['id'];
                                    $esmcostdetailArr['costMoney'] = $esmcostdetailInfo['0']['costMoney'] + $val[6];
                                }
                                $esmcostdetailArr['costType'] = $val[4];
                                $esmcostdetailArr['costTypeId'] = $costTypeInfo['CostTypeID'];
                                $esmcostdetailArr['invoiceDetail'] = $invoiceDetailArr;
                                $inArr['esmcostdetail'][$costTypeInfo['CostTypeID']] = $esmcostdetailArr;
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!该费用小类不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有填写费用小类';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //判断日志是否审批
                        if ($workLogObj['0']['confirmStatus'] == 1) {
                            $tempArr['result'] = '导入失败!费用日志已审核，不能进行更新';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //导入开始执行
                        try {
                            $this->start_d();

                            $this->edit_d($inArr);
                            if (!empty($esmcostdetailArr['id'])) {
                                $tempArr['result'] = '更新成功';
                            } else {
                                $tempArr['result'] = '新增成功';
                            }

                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '导入失败';
                        }
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 日志记录查询 - 条数
     * @param $beginDate
     * @param $endDate
     * @return string
     */
    function searchLog_d($beginDate, $endDate)
    {
        $condition = 'confirmStatus = 0 and createId = "' . $_SESSION['USER_ID'] . '" and executionDate between "' . $beginDate . '" and "' . $endDate . '"';
        return $this->get_table_fields($this->tbl_name, $condition, 'COUNT(*)');
    }

    /**
     * 删除日志
     * @param $beginDate
     * @param $endDate
     * @return bool
     */
    function deleteLog_d($beginDate, $endDate)
    {
        //查询所有日志
        $this->searchArr = array('createId' => $_SESSION['USER_ID'], 'confirmStatus' => 0, 'beginDateThan' => $beginDate, 'endDateThan' > $endDate);
        $logArr = $this->list_d();
        if (empty($logArr)) return true; // 如果没有查询到日志，则直接返回

        try {
            $this->start_d();

            //循环查处相关信息
            $idArr = array();# id
            $projectIdArr = array();# 项目id
            foreach ($logArr as $v) {
                array_push($idArr, $v['id']);
                if ($v['projectId'] && !in_array($v['projectId'], $projectIdArr)) array_push($projectIdArr, $v['projectId']);
            }

            //删除日志
            parent::deletes(implode(',', $idArr));

            //更新项目信息
            if (!empty($projectIdArr)) {
                $esmprojectDao = new model_engineering_project_esmproject();//实例化项目
                $esmmemberDao = new model_engineering_member_esmmember();//实例化成员
                $esmcostdetailDao = new model_engineering_cost_esmcostdetail();//实例化费用信息

                //批量更新项目内容
                foreach ($projectIdArr as $v) {
                    //获取任务-个人日志的统计信息
                    $thisCount = $this->getProjectMemberCountInfo_d($v, $_SESSION['USER_ID']);

                    //更新任务成员工作量
                    $esmmemberDao->updateDayInfo_d($v, $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                    //获取项目的总费用
                    $projectPersonFee = $esmmemberDao->getFeePerson_d($v);
                    $esmprojectDao->updateFeePerson_d($v, $projectPersonFee);

                    //获取当前项目的费用
                    $projectCountArr = $esmcostdetailDao->getCostFormMember_d($v);

                    //更新人员费用信息
                    $esmmemberDao->update(
                        array('projectId' => $v, 'memberId' => $_SESSION['USER_ID']),
                        $projectCountArr
                    );
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /********************************考核系数查询部分 ***********************/
    /**
     * 考核系数查询-获取列表数据
     * @param $object
     * @param bool $getSql
     * @return string
     */
    function getSearchCoefficienList_d($object, $getSql = false)
    {
        $condition = '';
        $workStatusSql = '';
        if (!empty($object['userName'])) {
            $condition = " and c.createName = '" . $object['userName'] . "'";
        }
        if (!empty($object['workStatusArr'])) {
            $workStatus = util_jsonUtil::strBuild($object['workStatusArr']);
            $workStatusSql = " and c.workStatus in($workStatus)";
        }
        $sql = "
	    	SELECT
	    		c.createId,c.createName,c.deptName,p.userNo,c.projectId,c.projectCode,c.projectName,
				round(sum(c.inWorkRate / 100), 2) AS inWorkRate,
				sum(c.workCoefficient) AS workCoefficient,
				round(sum(c.workCoefficient) / sum(c.inWorkRate / 100),2) AS workCoefficientAvg
			FROM
				oa_esm_worklog c LEFT JOIN oa_hr_personnel p ON c.createId = p.userAccount
			WHERE
				c.confirmStatus = '{$object['confirmStatus']}'
				AND
				c.executionDate >= '{$object['beginDateThan']}' and c.executionDate <= '{$object['endDateThan']}'
				$condition
				$workStatusSql
			GROUP BY
    			c.projectId,c.createId";
        return $getSql ? $sql : $this->_db->getArray($sql);
    }

    /**
     * 考核系数查询-合计部分
     * @param $rows
     * @return array
     */
    function getSearchCoefficienCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'workCoefficient' => 0,
            'workCoefficientAvg' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['workCoefficient'] = bcadd($rtOjb['workCoefficient'], $v['workCoefficient'], 2);
        }
        $rtOjb['workCoefficientAvg'] = bcdiv($rtOjb['workCoefficient'], $rtOjb['inWorkRate'], 2);
        return $rtOjb;
    }

    /**
     * 考核系数查询-输出html
     * @param $rows
     * @return string
     */
    function searchCoefficientHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>序号</th><th>姓名</th><th>部门名称</th><th>员工编号</th>' .
                '<th>项目编号</th><th>项目名称</th><th>考核天数</th><th>考核系数</th><th>平均考核系数</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td><td>$v[createName]</td><td>$v[deptName]</td><td>$v[userNo]</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[projectCode]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[projectCode]</a></td>";
                $html .= "<td>$v[projectName]</td><td>$v[inWorkRate]</td><td>$v[workCoefficient]</td>" .
                    "<td>$v[workCoefficientAvg]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return '没有查询到数据';
        }
    }

    /**
     * 判断日志日期是否存在请休假记录
     * @param $rows ,可以是单独一个日期,也可以是日期数组
     * @return string
     */
    function isInHols_d($rows)
    {
        $sql = "SELECT BeginDT,EndDT FROM hols WHERE UserId = '" . $_SESSION['USER_ID'] . "'";
        $rs = $this->_db->getArray($sql);
        if (empty($rs)) {
            return '';
        } else {
            $str = '';
            if (is_array($rows)) {//日期数组
                $arr = array();
                foreach ($rows as $val) {
                    if ($val[1] == '工作' || $val[1] == '待命') {
                        $executionDate = trim($val[0]);
                        // 获取日期
                        $executionDate = !is_numeric($executionDate) ? $executionDate : util_excelUtil::exceltimtetophp($executionDate);
                        foreach ($rs as $v) {
                            if ($executionDate >= $v['BeginDT'] && $executionDate <= $v['EndDT']) {
                                array_push($arr, $executionDate);
                            }
                        }
                    }
                }
                if (!empty($arr)) {
                    $str = implode(',', array_unique($arr));
                }
            } else {//单个日期
                foreach ($rs as $v) {
                    if ($rows >= $v['BeginDT'] && $rows <= $v['EndDT']) {
                        $str = $rows;
                    }
                }
            }
        }
        return $str;
    }

    // 剩余请假信息
    public $lastHols = array();

    /**
     * 根绝年月获取人员的请休假信息
     * @param $begin
     * @param $end
     * @param $userIds
     * @return array
     */
    function getHolsHash_d($begin, $end, $userIds = '')
    {
        // 最后返回的结果
        $hash = array();

        // 查询脚本
        $sql = "SELECT UserId, UNIX_TIMESTAMP(begindt) AS bt, UNIX_TIMESTAMP(enddt) AS et, DTA, beginHalf
            FROM hols WHERE ExaStatus IN('完成','部门审批')
            AND UNIX_TIMESTAMP(enddt) >= " . $begin . " and UNIX_TIMESTAMP(begindt) <= " . $end;

        $sql .= $userIds ? " AND UserId IN(" . util_jsonUtil::strBuild($userIds) . ")" : "";

        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            foreach ($data as $v) {
                // 如果是只请半天的状态，则将这半天放入缓存
                if ($v['DTA'] < 1) {
                    $d = date('Y-m-d', $v['bt']);
                    $hash[$v['UserId']][$d] = $v['DTA'];
                } else {
                    // 是否初始状态
                    $isInitState = true;

                    // 天数计算
                    $dt = ($v['et'] - $v['bt']) / 86400 + 1;

                    // 如果是半天开始的，减掉0.5
                    if ($v['beginHalf']) {
                        $dt = $dt - 0.5;
                    }

                    for ($bt = $v['bt']; $bt <= $v['et']; $bt = $bt + 86400) {
                        $d = date('Y-m-d', $bt);

                        // 如果请当天已经有请休假了，则不处理
                        if (isset($hash[$v['UserId']][$d])) {
                            continue;
                        } else {
                            if ($dt >= 1) {
                                if ($isInitState && $v['beginHalf']) {
                                    // 如果请休假从下午开始，则第一天取摩
                                    $hash[$v['UserId']][$d] = fmod($dt, 1);
                                    $isInitState = false;
                                } else {
                                    // 如果请休假从下午开始，则第一天取摩
                                    $hash[$v['UserId']][$d] = 1;
                                }

                            } else {
                                $hash[$v['UserId']][$d] = $dt > 0 ? $dt : 0;
                            }
                            // 结算剩余请假天数
                            $dt = $dt - $hash[$v['UserId']][$d];
                        }
                    }
                }
            }
        }
        $this->lastHols = $hash;

        return $hash;
    }

    /**
     * 获取考核结果 - 用于人员补贴
     * @param $year
     * @param $month
     * @return array
     */
    function getAssessInfo_d($year, $month)
    {
        $begin = $year . '-' . $month . '-01';
        $end = date('Y-m-t', strtotime($begin));

        return $this->getAssessData_d(array(
            'beginDate' => $begin,
            'endDate' => $end
        ), false, true);
    }

    /**
     * 获取考核结果 - 用于人员补贴
     * @param $begin string 开始日期(YYYY-mm-dd)
     * @param $end string 结束日期(YYYY-mm-dd)
     * @param $userId string 用户ID
     * @return array
     */
    function getAssessInfoByCond_d($begin, $end, $userId) {
        return $this->getAssessData_d(array(
            'beginDate' => $begin,
            'endDate' => $end,
            'userId' => $userId
        ), false, true);
    }

    /**
     * 获取项目考核数据
     * @param $condition
     * @param bool $noHols
     * @param bool $allDept
     * @return array
     */
    function getAssessData_d($condition, $noHols = true, $allDept = false)
    {
        // 明细数据获取
        $assessData = $this->getAssessment_d($condition, $noHols, $allDept);

        // 返回的数据
        $returnData = array();

        if (!empty($assessData)) {
            // 中间数据
            $tempData = array();

            // 考勤天数
            $countDay = (strtotime($condition['endDate']) - strtotime($condition['beginDate'])) / 86400 + 1;

            foreach ($assessData as $v) {
                // 如果是离职日志，不取值
                if (isset($v['isLeave']) && $v['isLeave']) {
                    continue;
                }
                // 如果是未入职日志，不取值
                if (isset($v['unEntry']) && $v['unEntry']) {
                    continue;
                }

                // 如果日志被打回或者未审核，则不统计
                if ($v['confirmStatus'] != 1) {
                    continue;
                }

                if (isset($tempData[$v['createId']][$v['projectCode']])) {
                    $tempData[$v['createId']][$v['projectCode']]['inWorkRate'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['inWorkRate'], $v['inWorkRate']);
                    $tempData[$v['createId']][$v['projectCode']]['accessScore'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['accessScore'], $v['accessScore'], 2);
                    $tempData[$v['createId']][$v['projectCode']]['workloadDay'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['workloadDay'], $v['workloadDay'], 2);
                    $tempData[$v['createId']][$v['projectCode']]['hols'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['hols'], $v['hols'], 1);
                } else {
                    $tempData[$v['createId']][$v['projectCode']] = array(
                        'projectId' => $v['projectId'],
                        'createName' => $v['createName'],
                        'inWorkRate' => $v['inWorkRate'],
                        'accessScore' => $v['accessScore'],
                        'workloadDay' => $v['workloadDay'],
                        'hols' => $v['hols']
                    );
                }
            }

            // 转化为正式输出的数据
            foreach ($tempData as $k => $v) {
                foreach ($v as $ki => $vi) {
                    $vi['createId'] = $k;
                    $vi['projectCode'] = $ki;
                    $vi['monthScore'] = $vi['accessScore'];
                    $vi['inWorkRate'] = bcdiv($vi['inWorkRate'], 100, 2);
                    $vi['countDay'] = $countDay;
                    $vi['attendance'] = round(bcdiv($vi['inWorkRate'], $countDay, 3), 2);
                    $vi['assess'] = round(bcdiv($vi['monthScore'], $vi['inWorkRate'], 3), 2);
                    $vi['hols'] = isset($vi['hols']) ? $vi['hols'] : '0.0';
                    $returnData[] = $vi;
                }
            }
        }

        return $returnData;
    }

    /**
     * 获取工作量统计数据
     * @param $condition
     * @param bool $noHols 控制是否需要处理请休假，true的时候不处理
     * @param bool $allDept 控制是否启动部门权限
     * @return mixed
     */
    function getAssessment_d($condition, $noHols = true, $allDept = false)
    {
        // 获取工作量统计
        $data = $this->getAssessmentLog_d($condition, $noHols, $allDept);

        // 请休假处理
        if ($data) {
            $begin = strtotime($condition['beginDate']);
            $end = strtotime($condition['endDate']);

            // 请休假
            $holsInfo = $this->getHolsHash_d($begin, $end);

            // 考核结果
            $assResultDao = new model_engineering_assess_esmassresult();
            $assResultMap = $assResultDao->getResultMap_d();

            // 考核明细处理
            $data = $this->assessmentDetail_d($data, $holsInfo, $assResultMap);
        }

        return $data;
    }

    /**
     * 返回日志 - key => logs
     * @param $beginDate
     * @param $endDate
     * @param $userIds
     * @return array
     */
    function getAssessmentLogMap_d($beginDate, $endDate, $userIds) {
        // 获取日志
        $logs = $this->getAssessmentLog_d(array('beginDate' => $beginDate, 'endDate' => $endDate, 'userIds' => $userIds),
            false, true, false);

        // 考核结果
        $assResultDao = new model_engineering_assess_esmassresult();
        $assResultMap = $assResultDao->getResultMap_d();

        // 考核明细处理
        $logs = $this->assessmentDetail_d($logs, array(), $assResultMap);

        $data = array();

        foreach ($logs as $v) {
            // 过滤掉执行日志为 0000-00-00的数据
            if ($v['executionDate'] == '0000-00-00') {
                continue;
            }
            if (!$data[$v['createId']]) {
                $data[$v['createId']] = array();
            }
            $data[$v['createId']][] = $v;
        }

        return $data;
    }

    /**
     * 获取工作量
     * @param $condition
     * @param bool|true $noHols
     * @param bool|false $allDept
     * @param bool|true $onlyNormalProject
     * @return array|bool
     */
    function getAssessmentLog_d($condition, $noHols = true, $allDept = false, $onlyNormalProject = true)
    {
        $begin = strtotime($condition['beginDate']);
        $end = strtotime($condition['endDate']);

        $userSql = "";
        if (isset($condition['userName'])) {
            $userName = util_jsonUtil::is_utf8($condition['userName']) ? util_jsonUtil::iconvUTF2GB($condition['userName'])
                : $condition['userName'];
            $userSql = " AND c.createName = '" . $userName . "'";
        }
        if (isset($condition['userId'])) {
            $userSql = " AND c.createId = '" . $condition['userId'] . "'";
        }
        if (isset($condition['userIds'])) {
            $userSql = " AND c.createId IN(" . util_jsonUtil::strBuild($condition['userIds']) . ")";
        }

        // 如果有传入项目id，则以项目ID为准，否则仅对合同、试用项目做查询
        if (isset($condition['projectId'])) {
            $projectIdSql = " AND c.projectId = " . $condition['projectId'];
        } else if ($onlyNormalProject) {
            // 获取一般项目的id,用于过滤
            $projectDao = new model_engineering_project_esmproject();
            $projectIds = $projectDao->getNormalProjectIds_d();
            $projectIdSql = "";
            if ($projectIds) {
                $projectIdSql = "AND c.projectId IN(" . $projectIds . ") ";
            }
        }

        // 部门权限处理
        $userTbSql = "";
        $deptLimitSql = "";
        $deptLimit = $this->getDeptLimit_d();
        if (!$allDept && $noHols && $deptLimit != 'all' && $deptLimit != '') {
            $userTbSql = "LEFT JOIN user u ON c.createId = u.USER_ID";
            $deptLimitSql = " AND u.DEPT_ID IN(" . $deptLimit . ")";
        }

        $sql = "SELECT
                c.id, c.createId, c.createName, c.executionDate, c.executionTimes, c.projectId, c.projectCode,
                c.province, c.city, c.inWorkRate, c.assessResultName, c.assessResult,
                c.workStatus, c.workloadDay, c.workloadUnitName, c.createTime, c.confirmDate,
                ROUND(s.score) AS score, c.confirmStatus
            FROM
                oa_esm_worklog c
                $userTbSql
                LEFT JOIN
                (
                    SELECT projectId, beginTimes, endTimes, score
                    FROM oa_esm_project_statusreport c
                    WHERE
                        ExaStatus = '完成' $projectIdSql AND endTimes >= " . $begin . "
                        AND beginTimes <= " . $end . "
                ) s
                ON c.projectId = s.projectId AND
                    c.executionTimes >= s.beginTimes AND
                    c.executionTimes <= s.endTimes
            WHERE
                1 $userSql $deptLimitSql $projectIdSql AND
                c.executionTimes BETWEEN " . $begin . " AND " . $end . " AND c.executionDate <> '0000-00-00'
            ORDER BY c.executionTimes";
        return $this->_db->getArray($sql);
    }

    /**
     * 考核明细处理
     * @param $data
     * @param $holsInfo
     * @param $assResultMap
     * @return mixed
     */
    function assessmentDetail_d($data, $holsInfo, $assResultMap)
    {
        // 获取离职日期
        $personnelInfo = $this->getPersonnelInfoMap_d();

        foreach ($data as $k => $v) {
            // 错误日志剔除
            if ($v['executionDate'] == '0000-00-00') {
                unset($data[$k]);
                continue;
            }
            // 离职处理
            if (isset($personnelInfo[$v['createId']]) && $personnelInfo[$v['createId']]['quitTime']
                && $v['executionTimes'] > $personnelInfo[$v['createId']]['quitTime']
            ) {
                $data[$k]['isLeave'] = '1';
            }
            // 入职处理
            if (isset($personnelInfo[$v['createId']]) && $personnelInfo[$v['createId']]['entryTime']
                && $v['executionTimes'] < $personnelInfo[$v['createId']]['entryTime']
            ) {
                $data[$k]['unEntry'] = '1';
            }

            // 初始工作投入
            $inWorkRate = bcdiv($v['inWorkRate'], 100, 2);

            // 如果存在请休假，则扣除请假天数
            if (isset($holsInfo[$v['createId']]) && isset($holsInfo[$v['createId']][$v['executionDate']])) {
                // 如果请假天数1，则当前全扣
                if ($holsInfo[$v['createId']][$v['executionDate']] == 1) {
                    $inWorkRate = 0;
                    // 如果当天填报了100%，并且请了半天假的时候，扣除请假工作量
                } else if ($inWorkRate == 1 && $holsInfo[$v['createId']][$v['executionDate']] < 1) {
                    $inWorkRate = $inWorkRate - $holsInfo[$v['createId']][$v['executionDate']];
                }
                // 考核得分计算
                $data[$k]['hols'] = $holsInfo[$v['createId']][$v['executionDate']];

                // 如果日志当天存在请假，则剔除这一天的请假量
                $this->lastHols[$v['createId']][$v['executionDate']] = $this->lastHols[$v['createId']][$v['executionDate']] - $inWorkRate;
                if ($this->lastHols[$v['createId']][$v['executionDate']] == 0) {
                    unset($this->lastHols[$v['createId']][$v['executionDate']]);
                }
            }

            // 投入比例赋值
            $data[$k]['inWorkRate'] = $inWorkRate * 100;

            // 没有提交周报按0算
            $v['score'] = $v['score'] === null ? 0 : $v['score'];

            // 得分坐标计算
            $scoreIndex = 'score_' . $v['score'];

            // 考核得分计算
            $data[$k]['accessScore'] = $assResultMap[$v['assessResult']][$scoreIndex];

            // 考核分数
            $data[$k]['accessScore'] = bcmul($data[$k]['accessScore'], $inWorkRate, 2);
        }

        return $data;
    }

    /**
     * 返回权限内的部门，all所有，ids部分部门，空字符串，无权限
     * @return array
     */
    function getDeptLimit_d()
    {
        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        if (isset($sysLimit['部门权限'])) {
            if (empty($sysLimit['部门权限'])) {
                return '';
            } else if (strpos($sysLimit['部门权限'], ';;') !== false) {
                return 'all';
            } else {
                return $sysLimit['部门权限'];
            }
        } else {
            return '';
        }
    }

    /**
     * 获取用户信息
     * @param string $userId
     * @return array
     */
    function getPersonnelInfo_d($userId = "")
    {
        $userId = $userId ? $userId : $_SESSION['USER_ID'];
        // 获取入职日期
        $personInfo = $this->_db->get_one("SELECT entryDate, quitDate FROM oa_hr_personnel WHERE userAccount = '" .
            $userId . "'");
        if ($personInfo['entryDate'] && $personInfo['entryDate'] != "" && $personInfo['entryDate'] != "0000-00-00") {
            $personInfo['entryTime'] = strtotime($personInfo['entryDate']);
        }
        if ($personInfo['quitDate'] && $personInfo['quitDate'] != "" && $personInfo['quitDate'] != "0000-00-00") {
            $personInfo['quitTime'] = strtotime($personInfo['quitDate']);
        }
        return $personInfo;
    }

    /**
     * 获取用户信息 - map
     * @param string $userIds
     * @return array
     */
    function getPersonnelInfoMap_d($userIds = '')
    {
        $sql = "SELECT userAccount, entryDate, quitDate FROM oa_hr_personnel";
        if ($userIds) {
            $sql .= " WHERE userAccount IN(" . util_jsonUtil::strBuild($userIds) . ")";
        }
        // 获取入职日期
        $data = $this->_db->getArray($sql);

        $result = array();
        if ($data) {
            foreach ($data as $v) {
                $result[$v['userAccount']] = array(
                    'entryTime' => $v['entryDate'] && $v['entryDate'] != '0000-00-00' ? strtotime($v['entryDate']) : 0,
                    'quitTime' => $v['quitDate'] && $v['quitDate'] != '0000-00-00' ? strtotime($v['quitDate']) : 0
                );
            }
        }
        return $result;
    }
}