<?php

/**
 * @author Show
 * @Date 2011年12月3日 星期六 14:17:32
 * @version 1.0
 * @description:项目变更申请单Model层
 */
class model_engineering_change_esmchange extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_change_baseinfo";
        $this->sql_map = "engineering/change/esmchangeSql.php";
        parent::__construct();
    }

    /**************************** 增删改查 *****************************/
    /**
     * @param $object
     * @return bool
     */
    function add_d($object) {
        //字段添加
        $object['ExaStatus'] = WAITAUDIT;
        $object['applyId'] = $_SESSION['USER_ID'];
        $object['applyName'] = $_SESSION['USERNAME'];
        $object['applyDate'] = day_date;

        try {
            //新增
            $newId = parent::add_d($object, true);
            return $newId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 根据主键修改对象
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            //修改
            parent::edit_d($object, true);
            return $object['id'];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 获取变更id
     * @param $projectId
     * @param bool $isCreateNew
     * @return bool
     * @throws Exception
     */
    function getChangeId_d($projectId, $isCreateNew = true) {
        $changeId = $this->hasChangeProject_d($projectId);
        if ($changeId) {
            return $changeId;
        } else if ($isCreateNew) {
            //重新创建一个变更单
            try {
                $this->start_d();

                //项目基本信息部分
                $esmprojectDao = new model_engineering_project_esmproject();
                $esmprojectObj = $esmprojectDao->get_d($projectId);
                //构建变更申请数组
                $esmchangeObj = array(
                    'projectId' => $esmprojectObj['id'], 'projectCode' => $esmprojectObj['projectCode'], 'projectName' => $esmprojectObj['projectName'],
                    'orgBudgetAll' => $esmprojectObj['budgetAll'], 'orgBudgetField' => $esmprojectObj['budgetField'], 'orgBudgetOutsourcing' => $esmprojectObj['budgetOutsourcing'],
                    'orgBudgetOther' => $esmprojectObj['budgetOther'], 'orgBudgetPerson' => $esmprojectObj['budgetPerson'], 'orgBudgetPeople' => $esmprojectObj['budgetPeople'],
                    'orgBudgetDay' => $esmprojectObj['budgetDay'], 'orgBudgetEqu' => $esmprojectObj['budgetEqu'], 'newBudgetAll' => $esmprojectObj['budgetAll'],
                    'newBudgetField' => $esmprojectObj['budgetField'], 'newBudgetOutsourcing' => $esmprojectObj['budgetOutsourcing'], 'newBudgetOther' => $esmprojectObj['budgetOther'],
                    'newBudgetPerson' => $esmprojectObj['budgetPerson'], 'newBudgetPeople' => $esmprojectObj['budgetPeople'], 'newBudgetDay' => $esmprojectObj['budgetDay'],
                    'newBudgetEqu' => $esmprojectObj['budgetEqu'], 'planBeginDate' => $esmprojectObj['planBeginDate'], 'planEndDate' => $esmprojectObj['planEndDate'],
                    'expectedDuration' => $esmprojectObj['expectedDuration'], 'actBeginDate' => $esmprojectObj['actBeginDate'],
                    'actEndDate' => $esmprojectObj['actEndDate'],'orgPlanEndDate' => $esmprojectObj['planEndDate'],'newPlanEndDate' => $esmprojectObj['planEndDate'],
                    'actDuration' => $esmprojectObj['actDuration'], 'officeId' => $esmprojectObj['officeId'], 'officeName' => $esmprojectObj['officeName'],
                    'provinceId' => $esmprojectObj['provinceId'], 'city' => $esmprojectObj['city'], 'cityId' => $esmprojectObj['cityId'],
                    'country' => $esmprojectObj['country'], 'countryId' => $esmprojectObj['countryId'], 'province' => $esmprojectObj['province'],
                    'place' => $esmprojectObj['place'], 'applyDate' => day_date,
                    'applyId' => $_SESSION['USER_ID'], 'applyName' => $_SESSION['USERNAME'],
                    'ExaStatus' => '待提交'
                );
                //新增变更申请单
                $newId = parent::add_d($esmchangeObj, true);

                //项目任务部分
                $esmactivityDao = new model_engineering_activity_esmactivity();
                //更新任务节点信息
                $esmactivityDao->initChangeInfo_d($projectId);
                $esmactivityArr = $esmactivityDao->getProjectActivity_d($projectId);
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->createActivity_d($esmactivityArr, $newId, $esmprojectObj);

                //项目预算部分
                $esmbudgetDao = new model_engineering_budget_esmbudget();
                $esmbudgetArr = $esmbudgetDao->getProjectBudgetInfo_d($projectId);
                if ($esmbudgetArr) {
                    $esmchangebudDao = new model_engineering_change_esmchangebud();
                    $esmchangebudDao->createBudget_d($esmbudgetArr, $newId);
                }

                //设备预算部分
                $esmresourcesDao = new model_engineering_resources_esmresources();
                $esmresourcesArr = $esmresourcesDao->getProjectResources_d($projectId);
                if ($esmresourcesArr) {
                    $esmchangeresDao = new model_engineering_change_esmchangeres();
                    $esmchangeresDao->createResources_d($esmresourcesArr, $newId);
                }

                $this->commit_d();
                return $newId;
            } catch (Exception $e) {
                $this->rollBack();
                throw $e;
            }
        } else {
            return false;
        }
    }

    /**
     * 重写删除方法
     * @param $id
     * @return bool|void
     * @throws Exception
     */
    function deletes_d($id) {
        try {
            $this->start_d();

            //获取当前单据
            $obj = $this->get_d($id);

            //删除单据
            $this->deletes($id);

            //还原任务变更状态
            $esmactivityDao = new model_engineering_activity_esmactivity();
            $esmactivityDao->revertChangeInfo_d($obj['projectId']);

            //还原设备预算变更状态
            $esmresourcesDao = new model_engineering_resources_esmresources();
            $esmresourcesDao->revertChangeInfo_d($obj['projectId']);

            //还原项目预算变更状态
            $esmbudgetDao = new model_engineering_budget_esmbudget();
            $esmbudgetDao->revertChangeInfo_d($obj['projectId']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /*********************************业务调用方法**********************************/

    /**
     * 判断是否已存在项目变更申请
     * @param $projectId
     * @return bool
     */
    function hasChangeProject_d($projectId) {
        $this->searchArr = array('ExaStatusIn' => '待提交,部门审批,打回', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            return $rs[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * 获取项目变更申请所需信息
     * @param $id
     * @return bool|mixed
     */
    function getObjInfo_d($id) {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->get_d($id);
    }

    /**
     * 获取变更记录说明
     * @param $projectId
     * @return null|string
     */
    function getChangDescription_d($projectId) {
        $this->searchArr = array('ExaStatusNo' => '待提交', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $str = null;
            foreach ($rs as $val) {
                $str .= '申请人：' . $val['applyName'] . ' ,申请日期 ：' . $val['applyDate'] . ' ,审批状况 ：' . $val['ExaStatus'] . " \n"
                    . $val['changeDescription'] . " \n\n";;
            }
            return $str;
        } else {
            return null;
        }
    }

    /**
     * 项目审批后处理
     * @param $spid
     * @throws Exception
     */
    function dealAfterAudit_d($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $id = $folowInfo ['objId'];
        //获取变更申请单
        $object = $this->get_d($id);
        if ($object['ExaStatus'] == AUDITED) {
            $this->setChange_d($id, $object);
        }
    }

    /**
     * 项目变更处理
     * @param $id
     * @param null $object
     * @return bool
     */
    function setChange_d($id, $object = null) {
        try {
            $this->start_d();

            //如果有传入项目信息，说明是变更审批更新
            if (!$object) {
                //初始化变更申请单信息
                $object = $this->get_d($id);
                //更新当前变更申请单状态
                $this->update(array('id' => $id), array('projectStatus' => 1, 'ExaStatus' => AUDITED));
            }

            //将数据内容更新到项目中
            $esmprojectDao = new model_engineering_project_esmproject();

            //更新单据预算
            $updateArr = array(
                'id' => $object['projectId'], 'budgetAll' => $object['newBudgetAll'], 'budgetField' => $object['newBudgetField'],
                'budgetOther' => $object['newBudgetOther'], 'budgetOutsourcing' => $object['newBudgetOutsourcing'],
                'budgetPerson' => $object['newBudgetPerson'], 'budgetPeople' => $object['newBudgetPeople'],
                'budgetDay' => $object['newBudgetDay'], 'budgetEqu' => $object['newBudgetEqu'],
                'planBeginDate' => $object['planBeginDate'], 'planEndDate' => $object['planEndDate'],
                'expectedDuration' => $object['expectedDuration'], 'actDuration' => $object['actDuration'],
                'country' => $object['country'], 'countryId' => $object['countryId'],
                'province' => $object['province'], 'provinceId' => $object['provinceId'],
                'city' => $object['city'], 'cityId' => $object['cityId'], 'place' => $object['place'],
                'status' => 'GCXMZT02'
            );

            //当开始时间不为空时,设置项目实际开始时间
            if ($object['actBeginDate'] != '0000-00-00' && !empty($object['actBeginDate'])) {
                $updateArr['actBeginDate'] = $object['actBeginDate'];
            }

            //当结束时间不为空时,设置项目实际结束时间
            if ($object['actEndDate'] != '0000-00-00' && !empty($object['actEndDate'])) {
                $updateArr['actEndDate'] = $object['actEndDate'];
            }

            $esmprojectDao->edit_d($updateArr);

            //重新计算项目费用进度
            $esmprojectDao->calFeeProcess_d($object['projectId']);

            /***************** 项目任务更新 *********************/
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $allChangeActArr = $esmchangeactDao->getChangeArr_d($id);//获取全部任务
            if ($allChangeActArr) {
                $parentId = $esmchangeactDao->getChangeRoot_d($id);
                $esmchangeactArr = array();//进行了变更的数组
                $cacheArr = array($parentId => -1);//缓存任务id的表 变更id => 正式id
                foreach ($allChangeActArr as $val) {
                    if ($val['activityId']) {//设置上级ID缓存
                        $cacheArr[$val['id']] = $val['activityId'];
                    }
                    if ($val['isChanging'] == 1) {//设置发生变更的数据
                        array_push($esmchangeactArr, $val);
                    }
                }

                if ($esmchangeactArr) {//如果有变更的数据，才做变更处理
                    $esmactivityDao = new model_engineering_activity_esmactivity();
                    $esmworklogDao = new model_engineering_worklog_esmworklog();
                    $deleteArr = array();//做删除操作的任务
                    foreach ($esmchangeactArr as $val) {
                        //如果是新增
                        if ($val['changeAction'] == 'add') {

                            //切换根节点
                            $val['parentId'] = $cacheArr[$val['parentId']];

                            $addArr = $val;
                            unset($addArr['id']);
                            unset($addArr['lft']);
                            unset($addArr['rgt']);
                            $addArr['isChanging'] = 0;
                            $addArr['changeAction'] = '';
                            $activityId = $esmactivityDao->addOrg_d($addArr);

                            //缓存正式id
                            $cacheArr[$val['id']] = $activityId;

                        } elseif ($val['changeAction'] == 'edit') {
                            //切换根节点
                            $val['parentId'] = $cacheArr[$val['parentId']];
                            $updateArr = array(
                                'id' => $val['activityId'], 'activityName' => $val['activityName'], 'parentId' => $val['parentId'], 'lft' => $val['lft'], 'rgt' => $val['rgt'],
                                'workRate' => $val['workRate'], 'workload' => $val['workload'], 'workloadUnit' => $val['workloadUnit'], 'workloadUnitName' => $val['workloadUnitName'],
                                'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'], 'days' => $val['days'],
                                'workContent' => $val['workContent'], 'remark' => $val['remark'],
                                'isChanging' => 0, 'changeAction' => ''
                            );
                            $esmactivityDao->editOrg_d($updateArr);
                        } elseif ($val['changeAction'] == 'delete') {
                            array_push($deleteArr, $val['activityId']);
                        }
                    }
                    //删除部分
                    if (!empty($deleteArr)) {
                        $esmactivityDao->deletes_d(implode(',', $deleteArr));
                    }
                    //还原操作
                    $esmchangeactDao->rollBackChangeInfo_d($id);

                    //调用一个更新进度的方法
                    $esmactivityDao->recountProjectProcess_d($object['projectId']);

                    //更新日志的项目进度
                    $esmworklogDao->recountProjectProcess_d($object['projectId']);
                }
            }

            /***************** 预算明细更新 *********************/
            //实例化费用预算
            $esmchangebudDao = new model_engineering_change_esmchangebud();
            $esmchangebudArr = $esmchangebudDao->getChangeBudget_d($id, '1');
            if ($esmchangebudArr) {
                $esmbudgetDao = new model_engineering_budget_esmbudget();
                $deleteArr = array();
                foreach ($esmchangebudArr as $val) {
                    //如果是新增
                    if ($val['changeAction'] == 'add') {
                        $addArr = $val;
                        unset($addArr['id']);
                        unset($addArr['activityId']);
                        unset($addArr['activityName']);
                        $addArr['isChanging'] = 0;
                        $addArr['changeAction'] = '';
                        $esmbudgetDao->addOrg_d($addArr);
                    } elseif ($val['changeAction'] == 'edit') {
                        $updateArr = array(
                            'id' => $val['orgId'], 'price' => $val['price'], 'numberOne' => $val['numberOne'], 'numberTwo' => $val['numberTwo'],
                            'amount' => $val['amount'], 'remark' => $val['remark'], 'coefficient' => $val['coefficient'], 'budgetDay' => $val['budgetDay'],
                            'budgetPeople' => $val['budgetPeople'], 'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'],
                            'isChanging' => 0, 'changeAction' => ''
                        );
                        $esmbudgetDao->editOrg_d($updateArr);
                    } elseif ($val['changeAction'] == 'delete') {
                        array_push($deleteArr, $val['orgId']);
                    }
                }
                //删除部分
                if (!empty($deleteArr)) {
                    $esmbudgetDao->deletes(implode(',', $deleteArr));
                }
                //还原操作
                $esmchangebudDao->rollBackChangeInfo_d($id);
            }

            /**************** 设备预算更新 ********************/
            //设备预算更新
            $esmchangeresDao = new model_engineering_change_esmchangeres();
            $esmchangeresArr = $esmchangeresDao->getChangeArr_d($id, '1');
            if ($esmchangeresArr) {
                $esmresourcesDao = new model_engineering_resources_esmresources();
                $deleteArr = array();
                foreach ($esmchangeresArr as $val) {
                    //如果是新增
                    if ($val['changeAction'] == 'add') {
                        $addArr = $val;
                        unset($addArr['id']);
                        unset($addArr['activityId']);
                        unset($addArr['activityName']);
                        $addArr['isChanging'] = 0;
                        $addArr['changeAction'] = '';
                        $esmresourcesDao->addOrg_d($addArr);
                    } elseif ($val['changeAction'] == 'edit') {
                        $updateArr = array(
                            'id' => $val['orgId'], 'price' => $val['price'], 'number' => $val['number'],
                            'unit' => $val['unit'], 'amount' => $val['amount'], 'remark' => $val['remark'],
                            'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'],
                            'useDays' => $val['useDays'], 'isChanging' => 0, 'changeAction' => ''
                        );
                        $esmresourcesDao->editOrg_d($updateArr);
                    } elseif ($val['changeAction'] == 'delete') {
                        array_push($deleteArr, $val['orgId']);
                    }
                }
                //删除部分
                if (!empty($deleteArr)) {
                    $esmresourcesDao->deletes(implode(',', $deleteArr));
                }
                //还原操作
                $esmchangeresDao->rollBackChangeInfo_d($id);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 获取原数据
     * @param $projectId
     * @return bool|mixed
     */
    function getProjectForChange_d($projectId) {
        //获取项目信息
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->get_d($projectId);
    }

    /**
     * 获取查看信息
     * @param $id
     * @return bool|mixed
     */
    function getViewInfo_d($id) {
        $obj = parent::get_d($id);

        //获取变更范围
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $obj['esmactivity'] = $esmchangeactDao->initView_d($id);

        return $obj;
    }

    /**
     * 获取查看信息
     * @param $id
     * @return bool|mixed
     */
    function getEditInfo_d($id) {
        $obj = parent::get_d($id);

        //获取变更范围
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $obj['esmactivity'] = $esmchangeactDao->initEdit_d($id);

        return $obj;
    }

    /**
     * 判断当前登录人是否项目经理
     * @param $projectId
     * @return int
     */
    function isManager_d($projectId) {
        $esmproject = $this->getObjInfo_d($projectId);
        $esmManagerIdArr = explode(',', $esmproject['managerId']);
        if (in_array($_SESSION['USER_ID'], $esmManagerIdArr)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 判断变更状态
     * @param $projectId
     * @return bool|int
     */
    function hasChangeInfo_d($projectId) {
        $this->searchArr = array('ExaStatusNo' => '完成', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            if ($rs[0]['ExaStatus'] == '待提交' || $rs[0]['ExaStatus'] == '打回') {
                return $rs[0]['id'];
            } else {
                return -1;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取项目的对应范围id
     * @param $projectId
     * @return mixed
     */
    function getRangeId_d($projectId) {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->getRangeId_d($projectId);
    }

    /**
     * 更新变更申请的金额
     * @param $updateArr
     * @return bool
     */
    function updateChangeBudget_d($updateArr) {
        try {
            $this->start_d();

            //变更申请id
            $changeId = $updateArr['id'];
            unset($updateArr['id']);

            //更新单据
            $this->update(array('id' => $changeId), $updateArr);

            //计算总预算
            $this->calFeeProcess_d($changeId);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重新计算项目费用进度
     * @param $id
     * @throws Exception
     */
    function calFeeProcess_d($id) {
        try {
            return $this->_db->query("UPDATE " . $this->tbl_name . " SET newBudgetAll = newBudgetEqu + newBudgetField +
                newBudgetPerson + newBudgetOther + newBudgetOutsourcing,
                orgBudgetAll = orgBudgetEqu + orgBudgetField + orgBudgetPerson + orgBudgetOther + orgBudgetOutsourcing 
                WHERE id = " . $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取项目变更状态
     * @param $projectId
     * @return bool|mixed
     */
    function getState_d($projectId) {
        return $this->find(array('ExaStatus' => '待提交', 'projectId' => $projectId));
    }
}