<?php

/**
 * @author Show
 * @Date 2011年11月24日 星期四 17:20:15
 * @version 1.0
 * @description:工程项目(oa_esm_project) Model层
 *
 * 关于合同相关信息说明
 * contractId (源单id)
 * contractCode (源单编号)
 * contractType (源单类型)
 * rObjCode (业务编号)
 *
 */
class model_engineering_project_esmproject extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project";
        $this->sql_map = "engineering/project/esmprojectSql.php";
        parent::__construct();
    }

    //做一个对象缓存 -- 因为导入的时候耗费的内容太多，所以要优化
    public static $initObjCache = array();

    //获取对象缓存的方法
    public static function getObjCache($className)
    {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }

    //数据字典字段处理
    public $datadictFieldArr = array(
        'attribute', 'contractType', 'outsourcingType',
        'nature2', 'nature', 'cycle', 'category', 'platform', 'status',
        'net', 'createType', 'outsourcing', 'signType', 'customerType',
        'newProLine', 'productLine'
    );

    /********************** 策略部分 **********************/

    // 已实例数组
    public $initContractType = array(
        'GCXMYD-01', 'GCXMYD-04'
    );

    //不同类型入库申请策略类,根据需要在这里进行追加
    private $relatedStrategyArr = array(
        'GCXMYD-01' => 'model_engineering_project_strategy_sContract', //鼎利合同
        'GCXMYD-04' => 'model_engineering_project_strategy_sTrialproject' //试用项目申请
    );

    /**
     * 根据数据类型返回类
     * @param $objType
     * @return string
     */
    public function getClass($objType)
    {
        return $this->relatedStrategyArr[$objType];
    }

    //对应业务代码
    private $relatedCode = array(
        'GCXMYD-01' => 'contract', //鼎利合同
        'GCXMYD-04' => 'trialproject' //试用项目
    );

    /**
     * 根据类型返回业务名称
     * @param $objType
     * @return string
     */
    public function getBusinessCode($objType)
    {
        return $this->relatedCode[$objType];
    }

    /**
     * 源单对应合同属性配置
     */
    private $relatedSourceAttribute = array(
        'GCXMYD-01' => 'GCXMSS-02', //鼎利合同
        'GCXMYD-04' => 'GCXMSS-01' //试用项目
    );

    /**
     * 根据源单返回项目属性
     * @param $objType
     * @return string
     */
    public function getAttributeCode($objType)
    {
        return $this->relatedSourceAttribute[$objType];
    }

    /**
     * 获取数据信息
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, iesmproject $strategy)
    {
        return $strategy->getObjInfo_i($obj);
    }

    /**
     * 获取产线金额 - 暂时只作用于合同
     * @param $contractId
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getContractMoney_d($contractId, iesmproject $strategy)
    {
        return $strategy->getContractMoney_i($contractId);
    }

    /**
     * 获取原始数据信息
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getRawObjInfo_d($obj, iesmproject $strategy)
    {
        return $strategy->getRawInfo_i($obj['contractId']);
    }

    /**
     * 新增方法业务处理
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessAdd_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessAdd_i($obj, $this) : true;
    }

    /**
     * 单据确认业务处理方法
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessConfirm_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessConfirm_i($obj, $this) : true;
    }

    /**
     * 删除方法业务处理
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessDelete_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessDelete_i($obj, $this) : true;
    }

    /**
     * 关闭方法业务处理
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessClose_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessClose_i($obj, $this) : true;
    }

    /**
     * 获取用于项目章程的合同信息
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessForCharter_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessForCharter_i($obj, $this) : true;
    }

    /**
     * 合同业务处理
     * 传入项目信息
     * @param $object
     * @return mixed
     */
    function businessDeal_d($object)
    {
        try {
            $this->start_d();

            //获取最新的一个项目的状态
            $rs = $this->find(array('id' => $object['id']), 'createTime', 'status');
            if (!is_array($rs)) {
                $rs = array(
                    'status' => 'GCXMZT00'
                );
            }

            $contractDao = new model_contract_contract_contract();
            $contractDao->update(array('objCode' => $object['rObjCode']), array('projectStatus' => $rs['status']));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /********************** 策略部分 **********************/

    /****************************业务方法***************************/

    /**
     * 重写新增方法
     * @param $object
     * @return mixed
     */
    function add_d($object)
    {
        //数据字典
        $object = $this->processDatadict($object);
        return parent::add_d($object, true);
    }

    /**
     * 项目新增方法 - 包含业务处理
     * @param $object
     * @param boolean $isTransaction
     * @return mixed
     * @throws $e
     */
    function addProject_d($object, $isTransaction = false)
    {
        //创建项目经理角色数据
        $roleDao = new model_engineering_role_esmrole();
        try {
            if ($isTransaction == true) {
                $this->start_d();
            }
            //设定初始信息
            $object['ExaStatus'] = WAITAUDIT;
            $object['status'] = 'GCXMZT01';

            //数据字典
            $object = $this->processDatadict($object);

            //根据源单省份自动查询服务经理
            if (empty($object['areaManagerId']) && !empty($object['province'])) {
                $managerDao = new model_engineering_officeinfo_manager();
                $provinceObj = $managerDao->getManager_d($object['province'], $object['businessBelong']);
                $object['areaManagerId'] = $provinceObj['areaManagerId'];
                $object['areaManager'] = $provinceObj['areaManager'];
            }

            //新增项目
            $newId = $this->add_d($object);
            $object['id'] = $newId;
            $managerRole = array(
                'projectCode' => $object['projectCode'],
                'projectName' => $object['projectName'],
                'projectId' => $newId,
                'memberId' => $object['managerId'],
                'memberName' => $object['managerName'],
                'roleName' => '项目经理',
                'isManager' => '1',
                'parentId' => -1
            );
            $roleDao->addRoleAndMember_d($managerRole);

            //如果合同有关联的试用项目，新增一个项目任务
            if ($object['contractType'] == 'GCXMYD-01') {
                $trialproject = new model_projectmanagent_trialproject_trialproject();
                $trialStr = $trialproject->getTrialIdByconId($object['contractId']);

                if ($trialStr != null) {
                    $this->getParam(array('trialStr' => $trialStr, 'contractType' => 'GCXMYD-04', 'newProLine' => $object['newProLine']));
                    $trialInfo = $this->list_d();
                    //如果存在试用项目，则新增
                    if ($trialInfo) {
                        $esmactivityDao = new model_engineering_activity_esmactivity(); // 实例化试用项目对象
                        $esmmappingDao = new model_engineering_project_esmmapping(); // 实例化PK项目映射对象
                        foreach ($trialInfo as $v) {
                            // 新增项目任务
                            $esmactivityDao->addOrg_d(array('projectId' => $newId, 'projectCode' => $object['projectCode'],
                                'projectName' => $object['projectName'], 'activityName' => $v['projectName'],
                                'workRate' => 0.00, 'parentId' => -1, 'isTrial' => 1, 'triProjectId' => $v['id'],
                                'workContent' => '合同关联PK项目（' . $v['projectCode'] . '）', 'planBeginDate' => $v['planBeginDate'],
                                'planEndDate' => $v['planEndDate'], 'process' => 100, 'days' => $v['expectedDuration'],
                                'workLoadUnit' => 'GCGZLDW-00', 'workloadUnitName' => '天'
                            ));

                            // 新增映射关系
                            $esmmappingDao->add_d(array('projectId' => $newId, 'pkProjectId' => $v['id']));
                        }
                    }
                }
            }

            //新增后业务处理
            //调用策略
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessAdd_d($object, $initObj);
                }
            }

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($newId, '新建');

            //同步project_info的项目
            $this->updateProjectInfo_p($object);
            if ($isTransaction == true) {
                $this->commit_d();
            }
            return $newId;
        } catch (Exception $e) {
            if ($isTransaction == true) {
                $this->rollBack();
            }
            throw $e;
        }
    }

    /**
     * 重写修改方法
     * @param $object
     * @return mixed
     */
    function edit_d($object)
    {
        $object = $this->processDatadict($object);//数据字典
        return parent::edit_d($object, true);
    }

    /**
     * 特殊编辑方法
     * @param $object
     * @return mixed
     */
    function editRight_d($object)
    {
        //数据字典
        $object = $this->processDatadict($object);

        // 差异呈现字段
        $keyArr = array('projectName' => '项目名称', 'managerName' => '项目经理', 'productLineName' => '执行区域',
            'outsourcingName' => '外包类型', 'officeName' => '归属区域', 'deptName' => '归属部门',
            'categoryName' => '项目类别', 'customerLinkMan' => '客户姓名', 'country' => '国家',
            'province' => '省份', 'city' => '城市', 'projectObjectives' => '项目目标', 'description' => '项目实施要求',
            'workDescription' => '项目工作量描述', 'statusName' => '项目状态');

        // 项目原型
        $orgObject = $this->find(array('id' => $object['id']), null,
            implode(',', array_keys($keyArr)));

        try {
            $this->start_d();

            //项目经理部分处理 如果修改了项目经理，则对项目成员列表中的项目经理进行修改
            if (isset($object['orgManagerId']) && $object['orgManagerId'] != $object['managerId']) {
                //变更项目经理
                $esmroleDao = new model_engineering_role_esmrole();
                $esmroleDao->changeManager_d($object);

                //调用变更项目经理的方法
                $this->updateProjectInfo_p($object);
            }

            unset($object['orgManagerId']);
            unset($object['orgManagerName']);

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '管理人员修改', $esmlogDao->showDiff_d($keyArr, $orgObject, $object));

            //编辑
            parent::edit_d($object, true);

            //若已经设置初始化，则调用策略
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //调用策略
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            // 重新更新关联同一源单的项目的概算
            if(isset($object['contractId']) && isset($object['contractType']) && $object['contractType'] == 'GCXMYD-01'){
                $this->updateContractMoney_d($object['contractId']);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 批量删除对象
     * @param $id
     * @return bool
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $obj = $this->find(array('id' => $id), null, 'contractId,contractType,rObjCode,projectCode');

            $this->deletes($id);

            //若已经设置初始化，则调用策略
            if (in_array($obj['contractType'], $this->initContractType)) {
                //调用策略 - 删除后操作方法
                $newClass = $this->getClass($obj['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $obj = $this->businessDelete_d($obj, $initObj);
                }
            }
            //删除项目章程
            $esmcharterDao = new model_engineering_charter_esmcharter();
            $esmcharterDao->delete(array('projectCode' => $obj['projectCode']));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 暂停项目
     * @param $object
     * @return mixed
     */
    function stop_d($object)
    {
        $mailInfo = $object;
        try {
            $this->start_d();
            //数据字典处理
            $object = $this->processDatadict($object);

            //编辑项目
            unset($object['description']);
            parent::edit_d($object, true);

            //同步项目
            $this->updateProjectInfo_p($object['projectCode']);

            //若已经设置初始化，则调用策略
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //调用策略
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            //邮件处理
            if ($object['email']['issend'] == 'y') {
                $this->mailDeal_d('esmprojectStop', $object['mail']['TO_ID'], $mailInfo);
            }

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '暂停', $mailInfo['description']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 取消暂停
     * @param $object
     * @return mixed
     */
    function cancelStop_d($object)
    {
        $mailInfo = $object;
        try {
            $this->start_d();
            //数据字典处理
            $object = $this->processDatadict($object);

            //编辑项目
            unset($object['description']);
            parent::edit_d($object, true);

            //同步项目
            $this->updateProjectInfo_p($object['projectCode']);

            //若已经设置初始化，则调用策略
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //调用策略
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            //邮件处理
            if ($object['email']['issend'] == 'y') {
                $this->mailDeal_d('esmprojectCancelStop', $object['email']['TO_ID'], $mailInfo);
            }

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '取消暂停', $mailInfo['description']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 关闭项目方法
     * @param $object
     * @return mixed
     */
    function close_d($object)
    {
        try {
            $this->start_d();

            //数据字典处理
            $object = $this->processDatadict($object);

            //关闭项目时，自动将项目进度更新为100
            $object['projectProcess'] = 100;

            //编辑项目
            parent::edit_d($object, true);

            //同步项目
            $this->updateProjectInfo_p($object['projectCode']);

            /**** ------------ 关闭后业务处理 ------------- ****/

            // 调用策略
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($object, $initObj);
                }
            }

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '关闭', $object['closeDesc']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重新计算项目的决算信息
     * @param null $projectCode
     * @param null $projectIds
     * @return mixed
     * @throws Exception
     */
    function calProjectFee_d($projectCode = null, $projectIds = null)
    {
        $sql = "UPDATE " . $this->tbl_name . " c LEFT JOIN (
                SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e ON c.id = e.projectId
                SET c.feeAll = c.feeField + c.feeFieldImport + c.feePerson + c.feeSubsidy + c.feeSubsidyImport +
                    if(e.feeEqu IS NULL, 0, e.feeEqu) + c.feeEqu + c.feeEquImport + c.feeOutsourcing +
                    c.feeOther + c.feePK + c.feeFlights + c.feePayables + c.feeCar";

        if ($projectCode) $sql .= " WHERE projectCode = '$projectCode'";
        if ($projectIds) $sql .= " WHERE id IN($projectIds)";

        try {
            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 重新计算项目费用进度
     * @param null $id
     * @param null $projectIds
     * @return mixed
     * @throws $e
     */
    function calFeeProcess_d($id = null, $projectIds = null)
    {
        try {
            $sql = "UPDATE " . $this->tbl_name . " SET
                feeAllProcess = IF(budgetAll = 0,0,ROUND(feeAll/budgetAll*100,2)) ,
                feeFieldProcess = IF(budgetField = 0,0,ROUND(feeField/budgetField*100,2))";

            if ($id) $sql .= " WHERE id = " . $id;
            if ($projectIds) $sql .= " WHERE id IN($projectIds)";

            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 重新计算项目费用进度
     * @param $projectCode
     * @param $type
     * @return mixed
     * @throws $e
     */
    function calFeeProcessByCode_d($projectCode, $type = 1)
    {
        if ($type == 1) {
            $sql = "update " . $this->tbl_name . " set feeAllProcess = if(budgetAll = 0,0,round(feeAll/budgetAll*100,2)) ,
			feeFieldProcess = if(budgetField = 0,0,round(feeField/budgetField*100,2)) where projectCode = '" . $projectCode . "'";
        } else {
            $sql = "update " . $this->tbl_name . " set feeAllProcess = if(budgetAll = 0,0,round(feeAll/budgetAll*100,2)) ,
			feeFieldProcess = if(budgetField = 0,0,round(feeField/budgetField*100,2)),
			budgetAll = budgetOther + budgetField + budgetOutsourcing + budgetEqu + budgetPerson where projectCode = '" . $projectCode . "'";
        }
        try {
            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取个人办事处id
     * @return string
     */
    function getOfficeIds_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
        return $officeInfoDao->getOfficeIds_d();
    }

    /**
     * 获取个人负责省份
     * @return string
     */
    function getProvinces_d()
    {
        $managerDao = new model_engineering_officeinfo_manager();
        return $managerDao->getProvinces_d();
    }

    /**
     * 获取服务经理负责省份与产品线
     * @return string
     */
    function getProvincesAndLines_d()
    {
        $managerDao = new model_engineering_officeinfo_manager();
        return $managerDao->getProvincesAndLines_d();
    }

    /**
     * 获取负责人或服务总监负责的省份与产品线
     * @return string
     */
    function getOfficeNameAndLines_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
        return $officeInfoDao->getOfficeNameAndLines_d();
    }

    /**
     * 获取项目的对应范围id
     * @param $id
     * @return mixed
     */
    function getRangeId_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'provinceId,productLine,officeId');
        $esmrangeDao = new model_engineering_officeinfo_range();
        return $esmrangeDao->getRangeId_d($obj['provinceId'], $obj['productLine'], $obj['officeId']);
    }

    /**
     * 获取项目的对应范围信息
     * @param $id
     * @return mixed
     */
    function getRangeInfo_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'provinceId,productLine,officeId');
        $esmrangeDao = new model_engineering_officeinfo_range();
        return $esmrangeDao->getRangeInfo_d($obj['provinceId'], $obj['productLine'], $obj['officeId']);
    }

    /**
     * 获取项目现场费用
     * @param $projectCode
     * @return int
     */
    function getFeeNow_d($projectCode)
    {
        $expenseDao = self::getObjCache('model_finance_expense_expense');
        return $expenseDao->getFeeSum_d($projectCode);
    }

    /**
     * 获取项目的某些现场决算
     * @param $projectCode
     * @param $costTypeIds
     * @return mixed
     */
    function getSomeFeeSum_d($projectCode, $costTypeIds)
    {
        $expenseDao = self::getObjCache('model_finance_expense_expense');
        return $expenseDao->getSomeFeeSum_d($projectCode, $costTypeIds);
    }

    /**
     * 获取人力决算信息
     * @param $id
     * @return int
     */
    function getFeePerson_d($id)
    {
        $esmmemberDao = new model_engineering_member_esmmember();
        return $esmmemberDao->getFeePerson_d($id);
    }

    /**
     * 根据项目编号获取项目信息
     * @param $projectCode
     * @return bool|mixed
     */
    function getProjectInfo_d($projectCode)
    {
        return $this->find(array('projectCode' => $projectCode));
    }

    /**
     * 项目章程获取项目信息
     * @param $object
     * @return bool|mixed
     */
    function getProjectForCharter_d($object)
    {
        if (!isset($object['contractType'])) {
            $object['contractType'] = 'GCXMYD-01';
        }
        //调用策略 - 删除后操作方法
        $newClass = $this->getClass($object['contractType']);
        if ($newClass) {
            $initObj = new $newClass();
            return $this->businessForCharter_d($object, $initObj);
        } else {
            return false;
        }
    }

    /**
     * 更新项目进度
     * create on 2012-12-3
     * create by show
     * @param $id
     * @param $projectProcess
     * @throws $e
     */
    function updateProjectProcess_d($id, $projectProcess)
    {
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        try {
            $this->start_d();

            //更新进度
            //$this->update(array('id' => $id), array('projectProcess' => $projectProcess));
            $this->update(" id = '{$id}' and incomeType not in ('SRQRFS-02','SRQRFS-03')", array('projectProcess' => $projectProcess));//更新项目进度 PMS 521 要求取消项目周报的进度更新

            //更新进度相关业务
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 更新项目人力费用
     * @param $id
     * @param $feePerson
     * @throws $e
     */
    function updateFeePerson_d($id, $feePerson)
    {
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        $feePerson['id'] = $id;
        try {
            $this->start_d();

            $this->edit_d($feePerson);

            //更新进度相关业务
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }

            //更新项目费用
            $this->calFeeProcess_d($id);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 根据合同编号更新项目付款分摊费用
     * @param $projectCode
     * @param $feePayables
     * @return mixed
     * @throws Exception
     */
    function updateFeePayables_d($projectCode, $feePayables)
    {
        try {
            return $this->update(array('projectCode' => $projectCode), array('feePayables' => $feePayables));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据合同编号更新项目付款分摊费用
     * @param $projectId
     * @param $feeFlights
     * @return mixed
     * @throws Exception
     */
    function updateFeeFlights_d($projectId, $feeFlights)
    {
        try {
            return $this->update(array('id' => $projectId), array('feeFlights' => $feeFlights));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /******************      服务合同专用处理      ******************/

    /**
     * 获取合同总工作占比 - 根据合同类型和id
     * @param $contractId
     * @param $contractType
     * @param $newProLine
     * @return int
     */
    function getAllWorkRateByType_d($contractId, $contractType, $newProLine)
    {
        $this->searchArr = array('contractId' => $contractId, 'contractType' => $contractType, 'newProLine' => $newProLine);
        $this->groupBy = 'c.contractId,c.contractType,newProLine';
        $rs = $this->list_d('sumWorkRate');

        if (is_array($rs)) {
            return $rs[0]['workRate'];
        } else {
            return 0;
        }
    }

    /**
     * 获取项目数量
     * @param $contractId
     * @param $contractType
     * @return int
     */
    function getProjectNum_d($contractId, $contractType)
    {
        $projectInfo = $this->findAll(
            array('contractId' => $contractId, 'contractType' => $contractType),
            null, 'id');
        return $projectInfo ? count($projectInfo) : 0;
    }

    /**
     * 根据项目进度获取合同进度 - 根据合同类型和id
     * @param $contractId
     * @param $contractType
     * @return int
     */
    function getAllProcessByType_d($contractId, $contractType)
    {
        $this->searchArr = array('contractId' => $contractId, 'contractType' => $contractType);
        $this->groupBy = 'c.contractId,c.contractType';
        $rs = $this->list_d('sumProcess');

        if (is_array($rs)) {
            return $rs[0]['allProcess'];
        } else {
            return 0;
        }
    }

    /**
     * 根据项目和类型获取项目列表
     * @param $projectIds
     * @param $attributes
     * @return mixed
     */
    function getListByIdsAndAttribute_d($projectIds, $attributes)
    {
        $this->searchArr = array('idArr' => $projectIds, 'attributes' => $attributes);
        return $this->list_d();
    }

    /**
     * 根据项目id获取项目详细映射表
     * @param $projectIds
     * @return array
     */
    function getMapByIds_d($projectIds)
    {
        $this->searchArr = array('ids' => $projectIds);
        $data = $this->list_d();

        $rst = array();
        if (!empty($data)) {
            foreach ($data as $v) {
                $v = $this->feeDeal($v);
                $v = $this->contractDeal($v);
                $rst[$v['id']] = $v;
            }
        }

        return $rst;
    }

    /**********************导入导出部分********************************/
    /**
     * 匹配excel字段
     * @param $datas
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($datas, $titleRow)
    {
        // 已定义标题
        $definedTitle = array(
            '产品线' => 'newProLineName', '区域' => 'officeName', '项目名称' => 'projectName', '项目编号' => 'projectCode',
            '状态' => 'statusName', '总预算' => 'budgetAll', '现场预算' => 'budgetField', '人力预算' => 'budgetPerson',
            '设备预算' => 'budgetEqu', '外包预算' => 'budgetOutsourcing', '其他预算' => 'budgetOther',
            '人力决算' => 'feePerson', '外包决算' => 'feeOutsourcing', '支付及其他' => 'feeOther', '机票决算' => 'feeFlights',
            '工程进度' => 'projectProcess', '所属省份' => 'province', '预计启动日期' => 'planBeginDate',
            '预计结束日期' => 'planEndDate', '实际开始日期' => 'actBeginDate', '实际完成日期' => 'actEndDate',
            '项目经理' => 'managerName', '人数' => 'peopleNumber', '外包类型' => 'outsourcingName',
            '项目类别' => 'categoryName', '网络' => 'netName', '工作占比' => 'workRate', '项目概算' => 'estimates'
        );
        // 日期验证的标题
        $dateTitle = array(
            '预计启动日期' => 'planBeginDate', '预计结束日期' => 'planEndDate', '实际开始日期' => 'actBeginDate',
            '实际完成日期' => 'actEndDate'
        );
        // 更新记录数组
        $logArr = array();

        // 构建新的数组
        foreach ($titleRow as $k => $v) {
            // 如果数据为空，则删除
            if (trim($datas[$k]) === '') {
                unset($datas[$k]);
                continue;
            }
            // 如果存在已定义内容，则进行键值替换
            if (isset($definedTitle[$v])) {
                // 时间数据处理
                if (isset($dateTitle[$v]) && is_numeric(trim($datas[$k]))) {
                    $datas[$k] = date('Y-m-d', (mktime(0, 0, 0, 1, $datas[$k] - 1, 1900)));
                }

                // 格式化更新数组
                $datas[$definedTitle[$v]] = trim($datas[$k]);
                // 构建日志数组
                $logArr[$v] = trim($datas[$k]);
            }
            // 处理完成后，删除该项
            unset($datas[$k]);
        }
        // 有更新数组时，将更新日志一并返回
        if (!empty($datas)) {
            $datas['logs'] = $logArr;
        }
        return $datas;
    }

    /**
     * 项目导入功能
     */
    function addExecelData_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        $contArr = array();//合同信息数组
        $contDao = new model_contract_contract_contract();
        $otherDataDao = new model_common_otherdatas();
        $datadictArr = array();//数据字典数组
        $datadictDao = new model_system_datadict_datadict();
        $officeDao = new model_engineering_officeinfo_officeinfo();
        $projectArr = array();//项目缓存数组
        $strategyArr = array();//策略类缓存
        //创建项目经理角色数据
        $memberDao = new model_engineering_member_esmmember();
        $esmroleDao = new model_engineering_role_esmrole();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5]) && empty($val[6])) {
                        continue;
                    } else {
                        //新增或者是更新数据
                        //1为新增
                        //0为更新
                        $addOrUpdate = 1;
                        $newId = 0; // 初始化新增记录id

                        //合同处理
                        if (!empty($val[0])) {
                            $contractCode = $val[0];

                            //产生合同缓存数组
                            $contArr[$contractCode] = isset($contArr[$contractCode]) ? $contArr[$contractCode] : $contDao->getContractInfoByCode($contractCode, 'main');
                            if (is_array($contArr[$contractCode])) {
                                $contractId = $contArr[$contractCode]['id'];
                                $rObjCode = $contArr[$contractCode]['objCode'];
                                $customerId = $contArr[$contractCode]['customerId'];
                                $customerName = $contArr[$contractCode]['customerName'];
                                $contractCountry = $contArr[$contractCode]['contractCountry'];
                                $contractCountryId = $contArr[$contractCode]['contractCountryId'];
                                $contractProvince = $contArr[$contractCode]['contractProvince'];
                                $contractProvinceId = $contArr[$contractCode]['contractProvinceId'];
                                $contractCity = $contArr[$contractCode]['contractCity'];
                                $contractCityId = $contArr[$contractCode]['contractCityId'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '行数据';
                                $tempArr['result'] = '导入失败!不存在的合同号';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $contractId = 0;
                            $rObjCode = "";
                            $customerId = "";
                            $customerName = "";
                            $contractCode = "";
                            $contractCountry = '';
                            $contractCountryId = '';
                            $contractProvince = '';
                            $contractProvinceId = '';
                            $contractCity = '';
                            $contractCityId = '';
                        }

                        //项目名称
                        $projectName = trim($val[1]);
                        if (empty($projectName)) {
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                            $tempArr['result'] = '导入失败!没有项目名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //项目编号
                        $projectCode = trim($val[2]);
                        if (!empty($projectCode)) {
                            if (isset($projectArr[$projectCode])) {
                                $addOrUpdate = 0;
                            } else {
                                //判断时候已经存在项目
                                $rs = $this->find(array('projectCode' => $projectCode), null, 'id,contractId,managerId,managerName');
                                if (is_array($rs)) {
                                    $projectArr[$projectCode] = $rs;
                                    $addOrUpdate = 0;
                                }
                            }

                            if (!$contractCode) {
                                if ($contractId != $projectArr[$projectCode]['contractId']) {
                                    $tempArr['docCode'] = '第' . $actNum . '行数据';
                                    $tempArr['result'] = '导入失败!项目原关联合同与当前关联合同不相同,请删除该项目后再进行导入';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }

                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                            $tempArr['result'] = '导入失败!没有项目编号';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //项目状态
                        if (!empty($val[3])) {
                            $val[3] = trim($val[3]);
                            if (!isset($datadictArr[$val[3]])) {
                                $rs = $datadictDao->getCodeByName('GCXMZT', $val[3]);
                                if (!empty($rs)) {
                                    $status = $datadictArr[$val[3]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的项目状态';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $status = $datadictArr[$val[3]]['code'];
                            }
                            if ($val[3] == '筹备') {
                                $ExaStatus = WAITAUDIT;
                            } else {
                                $ExaStatus = AUDITED;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '导入失败!没有项目状态';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //项目进度
                        if (empty($val[4])) {
                            $projectProcess = 0;
                        } else {
                            $projectProcess = $val[4];
                        }

                        //项目经理
                        if (!empty($val[5])) {
                            $val[5] = trim($val[5]);
                            if (!isset($userArr[$val[5]])) {
                                $rs = $otherDataDao->getUserInfo($val[5]);
                                if (!empty($rs)) {
                                    $userArr[$val[5]] = $rs;
                                    $managerId = $userArr[$val[5]]['USER_ID'];
                                    $deptId = $userArr[$val[5]]['DEPT_ID'];
                                    $deptName = $userArr[$val[5]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '行数据';
                                    $tempArr['result'] = '更新失败!不存在的项目经理名称';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $managerId = $userArr[$val[5]]['USER_ID'];
                                $deptId = $userArr[$val[5]]['DEPT_ID'];
                                $deptName = $userArr[$val[5]]['DEPT_NAME'];
                            }
                            $managerName = $val[5];
                        } else {
                            $managerId = "";
                            $managerName = "";
                            $deptId = "";
                            $deptName = "";
                        }

                        // 公司处理，如果没有传入，则以当前登录人的所属公司为标准
//                        if (!empty($val[8])) {
//                            $businessBelongName = trim($val[8]);
//                            $branchObj = $branchDao->find(array('NameCN' => $businessBelongName));
//                            $businessBelong = $branchObj['NamePT'];
//                            $formBelong = $branchObj['NamePT'];
//                            $formBelongName = $branchObj['NameCN'];
//                        } else {
//                            $businessBelongName = $formBelongName = $_SESSION['USER_COM_NAME'];
//                            $businessBelong = $formBelong = $_SESSION['USER_COM'];
//                        }

                        //办事处
//						if (!empty($val[6]) && !empty($val[7]) && !empty($val[8])) {
//							$productLineName = trim($val[7]);
//							if (!isset($datadictArr['GC'.$productLineName])) {
//								$rs = $datadictDao->getCodeByName('GCSCX', $productLineName);
//								if (!empty($rs)) {
//									$productLine = $datadictArr['GC'.$productLineName]['code'] = $rs;
//								} else {
//									$tempArr['docCode'] = '第' . $actNum . '条数据';
//									$tempArr['result'] = '导入失败!不存在的工程产品线';
//									array_push($resultArr, $tempArr);
//									continue;
//								}
//							} else {
//								$productLine = $datadictArr['GC'.$productLineName]['code'];
//							}
//							$officeName = trim($val[6]);
//							if (!isset($officeArr[$officeName])) {
//								$officeId = $officeDao->getIdByName($officeName, $productLineName, $businessBelongName);
//								if (!empty($officeId)) {
//									$officeId = $officeArr[$officeName]['id'] = $officeId;
//								} else {
//									$tempArr['docCode'] = '第' . $actNum . '条数据';
//									$tempArr['result'] = '导入失败!没有匹配到【' . $businessBelongName .
//                                        '（公司）】的【' . $productLineName . '（产品线）】的对应工程区域';
//									array_push($resultArr, $tempArr);
//									continue;
//								}
//							} else {
//								$officeId = $officeArr[$officeName]['id'];
//							}
//							$setOffice = true;
//						} else {
//							$setOffice = false;
//							$officeName = $officeId = $productLine = $productLineName = '';
//						}

                        // 项目区域
                        if (!empty($val[6])) {
                            $officeName = trim($val[6]);
                            $officeArr = $officeDao->getIdByOfficeName($officeName);
                            if (!empty($officeArr)) {
                                $businessBelongName = $officeArr['businessBelongName'];
                                $businessBelong = $officeArr['businessBelong'];
                                $formBelong = $officeArr['businessBelong'];
                                $formBelongName = $officeArr['businessBelongName'];
                                $productLine = $officeArr['productLine'];
                                $productLineName = $officeArr['productLineName'];
                                $officeId = $officeArr['id'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不存在的项目区域【' . $officeName . '】';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $setOffice = true;
                        } else {
                            $setOffice = false;
                        }

                        // 新产线
                        if (!empty($val[7])) {
                            $newProLineName = trim($val[7]);
                            if (!isset($datadictArr['HT' . $newProLineName])) {
                                $rs = $datadictDao->getCodeByName('HTCPX', $newProLineName);
                                if (!empty($rs)) {
                                    $newProLine = $datadictArr['HT' . $newProLineName]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的合同产品线';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $newProLine = $datadictArr['HT' . $newProLineName]['code'];
                            }
                        } else {
                            $newProLine = "";
                            $newProLineName = "";
                        }

                        // 所属板块
                        $moduleCode = $moduleName = "";
                        if (!empty($val[9])) {
                            $moduleArr = $this->_db->get_one("select dataName,dataCode from oa_system_datadict where parentCode = 'HTBK' and dataName = '{$val[9]}';");
                            if($moduleArr && isset($moduleArr['dataName'])){
                                $moduleCode = $moduleArr['dataCode'];
                                $moduleName = $moduleArr['dataName'];
                            }else{
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不存在的板块【' . $val[9] . '】';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        // 归属公司
                        if (!empty($val[10])) {
                            $companyArr = $this->_db->get_one("select NameCN,NamePT from branch_info where NameCN = '{$val[10]}';");
                            if($companyArr && isset($companyArr['NameCN'])){
                                $businessBelongName = $companyArr['NameCN'];
                                $businessBelong = $companyArr['NamePT'];
                                $formBelong = $companyArr['NamePT'];
                                $formBelongName = $companyArr['NameCN'];
                            }else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不存在的归属公司【' . $val[10] . '】';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //结果数组拼装
                        $inArr = array(
                            'contractId' => $contractId,
                            'contractCode' => $contractCode,
                            'rObjCode' => $rObjCode,
                            'customerId' => $customerId,
                            'customerName' => $customerName,
                            'country' => $contractCountry,
                            'countryId' => $contractCountryId,
                            'contractCountry' => $contractCountry,
                            'province' => $contractProvince,
                            'provinceId' => $contractProvinceId,
                            'contractProvince' => $contractProvince,
                            'city' => $contractCity,
                            'cityId' => $contractCityId,
                            'contractCity' => $contractCity,
                            'projectName' => $projectName,
                            'projectCode' => $projectCode,
                            'projectProcess' => $projectProcess,
                            'managerName' => $managerName,
                            'managerId' => $managerId,
                            'deptId' => $deptId,
                            'deptName' => $deptName,
                            'status' => $status,
                            'ExaStatus' => $ExaStatus,
                            'ExaDT' => day_date,
                            'remark' => '系统导入数据',
                            'newProLine' => $newProLine,
                            'newProLineName' => $newProLineName,
                            'estimates' => $val[8],
                            'module' => $moduleCode,
                            'moduleName' => $moduleName
                        );

                        if ($setOffice) {
                            $inArr['officeName'] = $officeName;
                            $inArr['officeId'] = $officeId;
                            $inArr['productLine'] = $productLine;
                            $inArr['productLineName'] = $productLineName;
                            $inArr['businessBelongName'] = $businessBelongName;
                            $inArr['businessBelong'] = $businessBelong;
                            $inArr['formBelongName'] = $formBelongName;
                            $inArr['formBelong'] = $formBelong;
                        }

                        if ($contractCode) {
                            //项目属性特殊处理
                            $inArr['attribute'] = 'GCXMSS-02';
                            $inArr['contractType'] = 'GCXMYD-01';
                        }
                        //项目占比
                        if ($addOrUpdate == 1) {
                            $inArr['workRate'] = 100;
                        }
                        try {
                            $this->start_d();
                            if ($addOrUpdate == 1) {
                                //新增项目数据
                                $newId = $this->add_d($inArr);

                                //项目经理数据处理
                                $managerRole = array(
                                    'projectCode' => $projectCode,
                                    'projectName' => $projectName,
                                    'projectId' => $newId,
                                    'memberId' => $managerId,
                                    'memberName' => $managerName,
                                    'roleName' => '项目经理',
                                    'isManager' => '1',
                                    'parentId' => -1
                                );
                                $esmroleDao->addRoleAndMember_d($managerRole);
                            } else {
                                //更新项目数据
                                $this->update(array('projectCode' => $projectCode), $inArr);
                                //项目经理部分处理 如果修改了项目经理，则对项目成员列表中的项目经理进行修改
                                if ($projectArr[$projectCode]['managerId'] != $managerId) {
                                    $conditionArr = array('projectCode' => $projectCode, 'memberId' => $projectArr[$projectCode]['managerId']);
                                    $memberArr = array('memberId' => $managerId, 'memberName' => $managerName);
                                    $memberDao->update($conditionArr, $memberArr);

                                    //项目角色列表调整
                                    $roleCon = array('projectCode' => $projectCode, 'isManager' => 1);
                                    $roleArr = array('memberId' => $managerId, 'memberName' => $managerName);
                                    $esmroleDao->update($roleCon, $roleArr);
                                }
                            }
                            //调用策略
                            if (isset($inArr['contractType']) && in_array($inArr['contractType'], $this->initContractType)) {
                                $projectInfo = array('contractType' => $inArr['contractType'], 'contractId' => $contractId);
                                //调用策略 - 确认后操作方法
                                if (!isset($strategyArr[$inArr['contractType']])) {
                                    $newClass = $this->getClass($inArr['contractType']);
                                    if ($newClass) {
                                        $strategyArr[$inArr['contractType']] = new $newClass();
                                    }
                                }
                                //如果是新增
                                if ($addOrUpdate == 1) {
                                    //新增时处理
                                    $this->businessAdd_d($projectInfo, $strategyArr[$inArr['contractType']]);
                                }

                                //如果已有项目则更新 加上id
                                if(!$addOrUpdate){
                                    $projectInfo['id'] = $projectArr[$projectCode]['id'];
                                }
                                //处理确认
                                $this->businessConfirm_d($projectInfo, $strategyArr[$inArr['contractType']]);
                                //处理关闭
                                $this->businessClose_d($projectInfo, $strategyArr[$inArr['contractType']]);
                            }
                            //项目信息同步
                            $this->updateProjectInfo_p($inArr);

                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                        }
                        if ($addOrUpdate == 1) {
                            if ($newId) {
                                $tempArr['result'] = '导入成功';
                                $tempArr['docCode'] = '第' . $actNum . '行数据';
                            } else {
                                $tempArr['result'] = '导入失败';
                                $tempArr['docCode'] = '第' . $actNum . '行数据';
                            }
                        } else {
                            $tempArr['result'] = '更新数据成功';
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                        }

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
     * 财务部分更新
     */
    function updateProjectFee_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //项目编号
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写项目编号';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (empty($val[1])) {
                            $feeAll = 0;
                        } else {
                            $feeAll = $val[1];
                        }

                        $conditionArr = array(
                            'projectCode' => $projectCode
                        );

                        $updateRows = array(
                            'projectCode' => $projectCode,
                            'feeAll' => $feeAll,
                            'updateId' => $_SESSION['USER_ID'],
                            'updateName' => $_SESSION['USERNAME'],
                            'updateTime' => date("Y-m-d H:i:s")
                        );

                        try {
                            $this->start_d();
                            //更新费用
                            $this->update($conditionArr, $updateRows);

                            if ($this->_db->affected_rows() == 0) {
                                $tempArr['result'] = '更新成功';
                            } else {
                                $tempArr['result'] = '更新成功';
                                //重新计算项目费用进度
                                $this->calFeeProcessByCode_d($projectCode);
                            }
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     * 导入更新项目数据
     */
    function updateProjectInfo_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        $otherDataDao = new model_common_otherdatas();
        $datadictArr = array();//数据字典数组
        $datadictDao = new model_system_datadict_datadict();
        $provinceArr = array();//省份数组
        $provinceDao = new model_system_procity_province();
        $officeArr = array();//办事处id
        $officeDao = new model_engineering_officeinfo_officeinfo();
        // 项目操作日志处理
        $logArr = array();
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $changeWorkRateProjectCodeArray = array(); // 是否需要更新项目金额
        // 缓存策略
        $strategyArr = array();
        // 判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");

            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;

                    if (empty($val[3])) {
                        continue;
                    } else {
                        // 格式化数组
                        $val = $this->formatArray_d($val, $titleRow);

                        //项目编号
                        if (isset($val['projectCode'])) {
                            $conditionArr = array('projectCode' => $val['projectCode']);
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写项目编号';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //办事处
                        if (isset($val['officeName'])) {
                            if (!isset($officeArr[$val['officeName']])) {
                                $officeId = $officeDao->getIdByOfficeName($val['officeName']);
                                if (!empty($officeId)) {
                                    $deptName = explode(",", $officeId['feeDeptName']);
                                    $deptName = $deptName[0];
                                    $deptId = explode(",", $officeId['feeDeptId']);
                                    $deptId = $deptId[0];
                                    $officeId = $officeArr[$val['officeName']]['id'] = $officeId['id'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!没有对应的办事处';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $officeId = $officeArr[$val['officeName']]['id'];
                                $deptName = $officeArr[$val['officeName']]['feeDeptName'];
                                $deptId = $officeArr[$val['officeName']]['feeDeptId'];
                            }
                            $val['officeId'] = $officeId;
                            $val['deptName'] = $deptName;
                            $val['deptId'] = $deptId;
                        }

                        //项目状态
                        if (isset($val['statusName'])) {
                            if (!isset($datadictArr[$val['statusName']])) {
                                $rs = $datadictDao->getCodeByName('GCXMZT', $val['statusName']);
                                if (!empty($rs)) {
                                    $status = $datadictArr[$val['statusName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的项目状态';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $status = $datadictArr[$val['statusName']]['code'];
                            }
                            $val['status'] = $status;
                            $val['ExaStatus'] = $val['statusName'] == '筹备' ? WAITAUDIT : AUDITED;
                        }

                        //工程进度
                        if (isset($val['projectProcess'])) {
                            $val['projectProcess'] = $val['projectProcess'] * 100;
                        }

                        //工作站比
                        if (isset($val['workRate'])) {
                            $val['workRate'] = $val['workRate'] * 100;
                        }

                        //省份
                        if (isset($val['province'])) {
                            if (!isset($provinceArr[$val['province']])) {
                                $provinceCode = $provinceDao->getCodeByName($val['province']);
                                if (!empty($provinceCode)) {
                                    $provinceCode = $provinceArr[$val['province']]['provinceCode'] = $provinceCode;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!没有对应的省份';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $provinceCode = $provinceArr[$val['province']]['provinceCode'];
                            }
                            $val['proCode'] = $provinceCode;
                        }

                        //项目经理
                        if (!empty($val['managerName'])) {
                            if (!isset($userArr[$val['managerName']])) {
                                $rs = $otherDataDao->getUserInfo($val['managerName']);
                                if (!empty($rs)) {
                                    $userArr[$val['managerName']] = $rs;
                                    $managerId = $userArr[$val['managerName']]['USER_ID'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '行数据';
                                    $tempArr['result'] = '更新失败!不存在的项目经理名称';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $managerId = $userArr[$val['managerName']]['USER_ID'];
                            }
                            $val['managerId'] = $managerId;
                        }

                        //项目外包
                        if (!empty($val['outsourcingName'])) {
                            $val['outsourcingName'] = trim($val['outsourcingName']);
                            if (!isset($datadictArr[$val['outsourcingName']])) {
                                $rs = $datadictDao->getCodeByName('WBLX', $val['outsourcingName']);
                                if (!empty($rs)) {
                                    $outsourcing = $datadictArr[$val['outsourcingName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!不存在的外包类型';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $outsourcing = $datadictArr[$val['outsourcingName']]['code'];
                            }
                            $val['outsourcing'] = $outsourcing;
                        }

                        //项目类别
                        if (!empty($val['categoryName'])) {
                            $val['categoryName'] = trim($val['categoryName']);
                            if (!isset($datadictArr[$val['categoryName']])) {
                                $rs = $datadictDao->getCodeByName('XMLB', $val['categoryName']);
                                if (!empty($rs)) {
                                    $category = $datadictArr[$val['categoryName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!项目类别配置不正确';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $category = $datadictArr[$val['categoryName']]['code'];
                            }
                            $val['category'] = $category;
                        }

                        //网络
                        if (!empty($val['netName'])) {
                            $val['netName'] = trim($val['netName']);
                            if (!isset($datadictArr[$val['netName']])) {
                                $rs = $datadictDao->getCodeByName('WLLX', $val['netName']);
                                if (!empty($rs)) {
                                    $net = $datadictArr[$val['netName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!网络配置不正确';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $net = $datadictArr[$val['netName']]['code'];
                            }
                            $val['net'] = $net;
                        }

                        //产品线
                        if (!empty($val['newProLineName'])) {
                            $val['newProLineName'] = trim($val['newProLineName']);
                            if (!isset($datadictArr[$val['newProLineName']])) {
                                $rs = $datadictDao->getCodeByName('HTCPX', $val['newProLineName']);
                                if (!empty($rs)) {
                                    $newProLine = $datadictArr[$val['newProLineName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!产品线配置不正确';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $newProLine = $datadictArr[$val['newProLineName']]['code'];
                            }
                            $val['newProLine'] = $newProLine;
                        }

                        //导入特殊处理 - 变成合同项目
                        $val = $this->processDatadict($val);

                        $val['updateTime'] = date('Y-m-d h:i:s');
                        $val['updateId'] = $_SESSION['USER_ID'];
                        $val['updateName'] = $_SESSION['USERNAME'];

//						echo "<pre>";print_r($val);die();

                        try {
                            $this->start_d();
                            // 查询项目信息
                            $projectInfo = $this->find(array('projectCode' => $val['projectCode']), null, 'id,contractId,contractType,projectProcess,status');
                            // 操作日志处理
                            $val['logs']['projectId'] = $projectInfo['id'];
                            array_push($logArr, $val['logs']);
                            unset($val['logs']);

                            //更新费用
                            $this->update($conditionArr, $val);

                            //判断是否有更新进度
                            if (isset($val['projectProcess']) || isset($val['status'])) {
                                //若已经设置初始化，则调用策略
                                if (in_array($projectInfo['contractType'], $this->initContractType)) {
                                    //调用策略 - 确认后操作方法
                                    if (!isset($strategyArr[$projectInfo['contractType']])) {
                                        $newClass = $this->getClass($projectInfo['contractType']);
                                        if ($newClass) {
                                            $strategyArr[$projectInfo['contractType']] = new $newClass();
                                        }
                                    }
                                    // 如果有进度，则使用本次更新的进度
                                    $projectInfo['projectProcess'] = isset($val['projectProcess']) ? $val['projectProcess'] : $projectInfo['projectProcess'];
                                    $projectInfo['status'] = isset($val['status']) ? $val['status'] : $projectInfo['status'];
                                    //处理确认
                                    $this->businessConfirm_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                                    //处理关闭
                                    $projectInfo['contractType'] == 'GCXMYD-01' && $this->businessClose_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                                }
                            }

                            // 如果有更新工作站比，则重新计算项目金额
                            if (isset($val['workRate'])) array_push($changeWorkRateProjectCodeArray, $val['projectCode']);

                            //同步项目
                            $this->updateProjectInfo_p($val['projectCode']);

                            $tempArr['result'] = '更新成功';
                            //重新计算项目费用进度
                            $this->calFeeProcessByCode_d($val['projectCode'], 2);
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
                        }

                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        array_push($resultArr, $tempArr);
                    }
                }

                // 如果存在变更占比的项目，则重新计算合同金额
                if ($changeWorkRateProjectCodeArray) $this->updateContractMoneyByProjectCode_d($changeWorkRateProjectCodeArray);

                // 这里做日志处理
                $esmlogDao->addLogBatch_d($logArr, '导入更新', 'projectId');

                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 更新合同工作量
     * @param $projectCodeArr
     */
    function updateContractProcess_d($projectCodeArr)
    {
        //缓存项目数组
        $projectCache = array();
        //缓存策略
        $strategyArr = array();

        foreach ($projectCodeArr as $val) {
            if (!isset($projectCache[$val])) {
                //获取项目数组
                $projectCache[$val] = $this->find(array('projectCode' => $val), null, 'contractId,contractType');//若已经设置初始化，则调用策略
                //判断对象是否需要调用策略
                if (in_array($projectCache[$val]['contractType'], $this->initContractType)) {
                    //调用策略 - 确认后操作方法
                    if (!isset($strategyArr[$projectCache[$val]['contractType']])) {
                        $newClass = $this->getClass($projectCache[$val]['contractType']);
                        $strategyArr[$projectCache[$val]['contractType']] = new $newClass();
                    }

                    $this->businessConfirm_d($projectCache[$val], $strategyArr[$projectCache[$val]['contractType']]);
                }
            }
        }
    }

    /**
     * 更新项目决算费用
     */
    function updateProjectOtherFee_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //项目编号
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写项目编号';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $conditionArr = array(
                            'projectCode' => $projectCode
                        );

                        $updateRows = array(
                            'projectCode' => $projectCode,
                            'updateId' => $_SESSION['USER_ID'],
                            'updateName' => $_SESSION['USERNAME'],
                            'updateTime' => date("Y-m-d H:i:s")
                        );

                        //人力决算费用更新
                        $feePerson = $val[1] === '' ? 'NONE' : sprintf("%f", abs(trim($val[1])));
                        if ($feePerson != 'NONE') {
                            $updateRows['feePerson'] = $feePerson;
                        }

                        //人力决算费用更新
                        $feePeople = $val[2] === '' ? 'NONE' : sprintf("%f", abs(trim($val[2])));
                        if ($feePeople != 'NONE') {
                            $updateRows['feePeople'] = $feePeople;
                        }

                        //人力决算费用更新
                        $feeDay = $val[3] === '' ? 'NONE' : sprintf("%f", abs(trim($val[3])));
                        if ($feeDay != 'NONE') {
                            $updateRows['feeDay'] = $feeDay;
                        }

                        //设备决算费用更新
                        $feeEqu = $val[4] === '' ? 'NONE' : sprintf("%f", abs(trim($val[4])));
                        if ($feeEqu != 'NONE') {
                            $updateRows['feeEqu'] = $feeEqu;
                        }

                        //外包决算费用更新
                        $feeOutsourcing = $val[5] === '' ? 'NONE' : sprintf("%f", abs(trim($val[5])));
                        if ($feeOutsourcing != 'NONE') {
                            $updateRows['feeOutsourcing'] = $feeOutsourcing;
                        }

                        //其他决算费用更新
                        $feeOther = $val[6] === '' ? 'NONE' : sprintf("%f", abs(trim($val[6])));
                        if ($feeOther != 'NONE') {
                            $updateRows['feeOther'] = $feeOther;
                        }

                        try {
                            $this->start_d();
                            //更新费用
                            $this->update($conditionArr, $updateRows);

                            if ($this->_db->affected_rows() == 0) {
                                $tempArr['result'] = '更新成功';
                            } else {
                                $tempArr['result'] = '更新成功';
                                //重新计算项目费用进度
                                $this->calFeeProcessByCode_d($projectCode);
                            }
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     * 导入项目预算(其他合同和外包合同)
     * @param string $budgetType
     * @return array
     */
    function updateProjectOtherBudget_d($budgetType = 'budgetOther')
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        //实例化预算部分
        $esmbudgetDao = new model_engineering_budget_esmbudget();

        //项目缓存数组
        $projectCache = array();

        $budgetTypeArr = array(
            'budgetField' => '现场预算',
            'budgetPerson' => '人力预算',
            'budgetOutsourcing' => '外包预算',
            'budgetOther' => '其他预算'
        );
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //项目编号
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                            if (!isset($projectCache[$projectCode])) {
                                $projectObj = $this->find(array('projectCode' => $projectCode), null, 'id,projectName');
                                if ($projectObj) {
                                    $projectCache[$projectCode] = $projectObj;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '更新失败!不存在的项目编号';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写项目编号';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //预算名称
                        if (!empty($val[1])) {
                            $budgetName = $val[1];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写预算名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //预算查询条件
                        $budgetCondition = array(
                            'projectCode' => $projectCode,
                            'budgetName' => $budgetName,
                            'budgetType' => $budgetType
                        );

                        //单价
                        $price = $val[2] === '' ? 'NONE' : sprintf("%f", trim($val[2]));
                        if ($price != 'NONE') {
                            $updateRows['price'] = $price;
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写单价';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //数量1
                        $numberOne = $val[3] === '' ? 'NONE' : sprintf("%f", abs(trim($val[3])));
                        if ($numberOne != 'NONE') {
                            $updateRows['numberOne'] = $numberOne;
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写数量1';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //数量2
                        $numberTwo = $val[4] === '' ? 'NONE' : sprintf("%f", abs(trim($val[4])));
                        if ($numberTwo != 'NONE') {
                            $updateRows['numberTwo'] = $numberTwo;
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写数量2';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //计算预算
                        $updateRows['amount'] = bcmul($numberTwo, bcmul($numberOne, $price, 2), 2);
                        //决算,允许录入负值
                        $actual = $val[6] === '' ? 'NONE' : sprintf("%f", trim($val[6]));
                        if ($actual != 'NONE') {
                            if (is_numeric($actual)) {
                                $updateRows['actFee'] = $actual;
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '更新失败!<决算>请填写数字';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //备注
                        $updateRows['remark'] = $val[7] ? $val[7] : '';

                        try {
                            $this->start_d();

                            //判断预算是否存在
                            $esmbudgetObj = $esmbudgetDao->find($budgetCondition, null, 'id');
                            if ($esmbudgetObj) {
                                if (!empty($actual)) {  //判断是否存在决算  add chenrf
                                    $esmbudgetDao->updateValById($esmbudgetObj['id'], 'actFee', $actual);
                                    unset($updateRows['actFee']);
                                }
                                $esmbudgetDao->update(array('id' => $esmbudgetObj['id']), $updateRows);
                            } else {
                                //加载一些信息
                                $updateRows['budgetId'] = '';
                                $updateRows['budgetName'] = $budgetName;
                                $updateRows['budgetType'] = $budgetType;
                                $updateRows['parentId'] = '-1';
                                $updateRows['parentName'] = $budgetTypeArr[$budgetType];
                                $updateRows['projectCode'] = $projectCode;
                                $updateRows['projectId'] = $projectCache[$projectCode]['id'];
                                $updateRows['projectName'] = $projectCache[$projectCode]['projectName'];
                                $esmbudgetDao->addOrg_d($updateRows);
                            }

                            $tempArr['result'] = '更新成功';
                            //更新项目的项目预算,决算
                            $projectInfo = array(
                                'projectId' => $projectCache[$projectCode]['id']
                            );
                            $esmbudgetDao->updateProject_d($projectInfo, true);

                            // 更新项目决算
                            $this->calProjectFee_d($projectCode);

                            //重新计算项目费用进度
                            $this->calFeeProcessByCode_d($projectCode, 2);

                            unset($updateRows);
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     * 导入人力决算
     */
    function updateProjectPersonBudget_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        //实例化预算部分
        $esmmemberDao = new model_engineering_member_esmmember();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //项目编号
                        if (!empty($projectCode)) {
                            $proCodeInfo = $this->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '更新失败!无此<项目编号>';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else {
                                $projectId = $proCodeInfo['id'];
                                $memberId = 'SYSTEM';
                                $memberName = '系统补录';
                                $projectName = $proCodeInfo['projectName'];
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<项目编号>不能为空';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //人力成本
                        $feePerson = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feePerson != 'NONE') {
                            $updateRows['feePerson'] = $feePerson;
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<决算>请填写数字';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //查询条件
                        $budgetCondition = array(
                            'projectCode' => $projectCode,
                            'memberId' => $memberId,
                            'memberName' => $memberName
                        );
                        try {
                            $this->start_d();
                            //判断是否存在
                            $esmmemberObj = $esmmemberDao->find($budgetCondition, null, 'id');
                            if ($esmmemberObj) {
                                if ($feePerson) {  //判断是否存在决算  add chenrf
                                    $esmmemberDao->updateValById($esmmemberObj['id'], 'feePerson', $feePerson);
                                    unset($updateRows['feePerson']);
                                }
                                $esmmemberDao->update(array('id' => $esmmemberObj['id']), $updateRows);
                            } else {
                                //加载一些信息
                                $updateRows['projectCode'] = $projectCode;
                                $updateRows['projectId'] = $projectId;
                                $updateRows['projectName'] = $projectName;
                                $updateRows['feePerson'] = $feePerson;
                                $updateRows['memberId'] = $memberId;
                                $updateRows['memberName'] = $memberName;
                                $esmmemberDao->addOrg_d($updateRows);
                            }
                            $tempArr['result'] = '更新成功';

                            //获取项目的总费用
                            $projectPersonFee = $esmmemberDao->getFeePerson_d($projectId);
                            $this->updateFeePerson_d($projectId, $projectPersonFee);

                            //重新计算项目费用进度
                            $this->calFeeProcessByCode_d($projectId, 2);

                            unset($updateRows);
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     * 导入设备决算
     */
    function updateProjectFeeEqu_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //项目编号
                        if (!empty($projectCode)) {
                            $proCodeInfo = $this->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '更新失败!无此<项目编号>';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<项目编号>不能为空';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        $feeEqu = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feeEqu != 'NONE') {
                            $updateRows['feeEqu'] = $val[1];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<决算>请填写数字';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //查询条件
                        $budgetCondition = array(
                            'projectCode' => $projectCode
                        );
                        try {
                            $this->start_d();
                            $this->update($budgetCondition, $updateRows);
                            $tempArr['result'] = '更新成功';

                            unset($updateRows);
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     * 发货成本
     * @param $type 0-发货成本 1-其他成本
     * @param bool $isAddBefore true-单月累加 false-覆盖更新
     * @return array
     */
    function updateProjectShipCost_d($type,$isAddBefore = false)
    {
        set_time_limit(0);
        ini_set("memory_limit", "1000M");
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        if ($type == 0) {
            $tableName = "oa_esm_project_shipcost";
        }
        if ($type == 1) {
            $tableName = "oa_esm_project_othercost";
        }

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //项目编号
                        if (!empty($projectCode)) {
                            $proDao = new model_contract_conproject_conproject();
                            $proCodeInfo = $proDao->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '更新失败!无此<项目编号>';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<项目编号>不能为空';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        $feeEqu = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feeEqu != 'NONE') {
                            $isSet = $this->get_table_fields($tableName, "projectCode='$projectCode'", "id");
                            if (!empty($isSet)) {
                                if($type == 0 && $isAddBefore){// 导入发货成本(单月累加)
                                    $dataInfo = $this->_db->get_one("select id,cost from {$tableName} where projectCode = '" . $projectCode . "';");
                                    $dataInfoCost = (isset($dataInfo['cost']) && $dataInfo['cost'] > 0)? $dataInfo['cost'] : 0;
                                    $feeEqu = round(bcadd($feeEqu,$dataInfoCost,4),2);
                                }
                                //update
                                $sql = "update $tableName set cost='$feeEqu' where projectCode='$projectCode'";
                            } else {
                                //insert
                                $sql = "insert into $tableName(projectCode,cost) values ('$projectCode','$feeEqu')";
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!<成本>请填写数字';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        try {
                            $this->start_d();
                            $this->_db->query($sql);
                            $tempArr['result'] = '更新成功';

                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '更新失败';
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
     *  合同项目导入
     */
    function conprojectExcel()
    {

        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $dao = new model_contract_conproject_importConprojectUtil ();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $conProDao = new model_contract_conproject_conproject();
            $resultArr = $conProDao->importProInfo_d($excelData);
            if ($resultArr)
                echo util_excelUtil:: finalceResult($resultArr, "导入结果", array(
                    "合同编号",
                    "结果"
                ));
            else
                echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove();</script>";
        }

    }

    /***************************导入导出部分*****************************/

    /**
     * 更新项目预算
     * edit on 2013-05-31 by kuangzw 将人力预算和其他预算都放入项目预算中处理
     * @param $id
     * @return bool
     * @throws Exception
     */
    function updateProjectBudget_d($id)
    {
        $object['id'] = $id;
        try {
            $this->start_d();

            //获取项目现场预算
            $esmbudgetDao = new model_engineering_budget_esmbudget();
            $budgetArr = $esmbudgetDao->getProjectBudgetOnce_d($id);
            $object = array_merge($object, $budgetArr);

            //获取项目设备预算
            $esmresourcesDao = new model_engineering_resources_esmresources();
            $object['budgetEqu'] = $esmresourcesDao->getProjectBudget_d($id);

            //更新项目
            $this->edit_d($object);

            $this->updateBudgetAll_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 更新项目信息
     * @param $id
     * @param $updateArr
     * @return bool
     * @throws Exception
     */
    function updateProject_d($id, $updateArr)
    {
        $object = $updateArr;
        $object['id'] = $id;
        try {
            $this->start_d();

            //更新项目
            $this->edit_d($object);

            $this->updateBudgetAll_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 重计算项目总预算
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function updateBudgetAll_d($id)
    {
        try {
            return $this->_db->query("update $this->tbl_name set budgetAll = budgetField + budgetOutsourcing +
				budgetEqu + budgetPerson + budgetOther where id in ($id)");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新项目人数
     * @param $id
     * @param $updateArr
     * @return bool
     * @throws Exception
     */
    function updateProjectPeople_d($id, $updateArr)
    {
        $object = $updateArr;
        $object['id'] = $id;
        try {
            $this->start_d();

            //更新项目
            $this->edit_d($object);

            $this->updatePeople_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 计算项目人数
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function updatePeople_d($id)
    {
        try {
            return $this->_db->query("update " . $this->tbl_name . " set peopleNumber = dlPeople + outsourcingPeople where id = " . $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /************************ 审批完成后处理 *******************/
    /**
     * 审批完成后处理
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);

        $id = $folowInfo['objId'];
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        //若已经设置初始化，则调用策略
        if (!empty($obj['contractType']) && in_array($obj['contractType'], $this->initContractType)) {
            //调用策略
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }
        }

        //项目执行轨迹-在建记录,记录操作日志
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $esmlogDao->addLog_d($id, '在建', null);
        return true;
    }

    /**
     * 完工审批完成后处理
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterCompleteAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);

        $id = $folowInfo['objId'];
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        //若已经设置初始化，则调用策略
        if (!empty($obj['contractType']) && in_array($obj['contractType'], $this->initContractType)) {
            //调用策略
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessClose_d($obj, $initObj);
            }
        }

        //项目执行轨迹-在建记录,记录操作日志
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $esmlogDao->addLog_d($id, '完工', null);
        return true;
    }

    /**
     * 更新方法 - 用于源单发生金额变更时，项目中的源单金额也进行变更
     * @param $contractId
     * @param string $contractType
     * @return mixed
     * @throws $e
     */
    function updateContractMoney_d($contractId, $contractType = 'GCXMYD-01')
    {
        if (in_array($contractType, $this->initContractType)) {
            $newClass = $this->getClass($contractType);
            if ($newClass) {
                $initObj = new $newClass();
                $contractMoney = $this->getContractMoney_d($contractId, $initObj);

                try {
                    foreach ($contractMoney as $k => $v) {
                        $updateSql = ($contractType == 'GCXMYD-01')? "UPDATE oa_esm_project SET contractMoney = estimatesRate * " . $v['contractMoney'] . "/100 ," .
                            " estimates = estimatesRate * " . $v['estimates'] . "/100  WHERE contractId = " . $contractId .
                            " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'" :
                            "UPDATE oa_esm_project SET contractMoney = workRate * " . $v['contractMoney'] . "/100 ," .
                            " estimates = workRate * " . $v['estimates'] . "/100  WHERE contractId = " . $contractId .
                            " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'";
                        $this->_db->query($updateSql);
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }

        return true;
    }

    /**
     * 根据项目编号统计 - 根据单据号
     * @param $projectCodeArr
     * @param string $contractType
     * @return bool
     * @throws Exception
     */
    function updateContractMoneyByProjectCode_d($projectCodeArr, $contractType = 'GCXMYD-01')
    {
        if (in_array($contractType, $this->initContractType)) {
            $newClass = $this->getClass($contractType);
            if ($newClass) {
                $initObj = new $newClass();
                try {
                    foreach ($projectCodeArr as $vi) {
                        $obj = $this->find(array('projectCode' => $vi), null, 'contractId');
                        $contractMoney = $this->getContractMoney_d($obj['contractId'], $initObj);

                        foreach ($contractMoney as $k => $v) {
                            $updateSql = ($contractType == 'GCXMYD-01')? "UPDATE oa_esm_project SET contractMoney = estimatesRate * " . $v['contractMoney'] . "/100 ," .
                                " estimates = estimatesRate * " . $v['estimates'] . "/100  WHERE contractId = " . $obj['contractId'] .
                                " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'" :
                                "UPDATE oa_esm_project SET contractMoney = workRate * " . $v['contractMoney'] . "/100 ," .
                                " estimates = workRate * " . $v['estimates'] . "/100  WHERE contractId = " . $obj['contractId'] .
                                " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'";
                            $this->_db->query($updateSql);
                        }
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        return true;
    }

    /**
     * 判断项目当前动作是否是变更
     * @param $projectId
     * @return bool
     */
    function actionIsChange_d($projectId)
    {
        if (!$projectId) {
            return false;
        }
        $obj = $this->get_d($projectId);
        if ($obj['ExaStatus'] == '待提交' || $obj['ExaStatus'] == '打回') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取合同项目进度
     * @param $contractId
     * @return int|string
     */
    function getContractProjectProcess_d($contractId)
    {
        // 查询当前存在项目
        $data = $this->findAll(array('contractId' => $contractId, 'contractType' => 'GCXMYD-01'));

        if (empty($data)) {
            return 0;
        } else {
            // 产品获取
            $productDao = self::getObjCache('model_contract_contract_product');
            $productLines = $productDao->getProductLineDetails_d($contractId);

            // 数据拆分
            $dataSplit = $this->splitProjectByProductLine_d($data);

            $esmRate = 0; // 服务占比
            $process = 0; // 服务产线总进度
            foreach ($productLines as $k => $v) {
                if ($k == 17) {
                    foreach ($v as $ki => $vi) {
                        // 当前产线金额
                        $proLineProcess = 0;

                        // 产线进度获取
                        foreach ($dataSplit[$vi['productLine']] as $vii) {
                            $proLineProcess = round(bcadd(bcmul($vii['projectProcess'], bcdiv($vii['workRate'], 100, 4), 4), $proLineProcess, 4), 2);
                        }

                        // 服务占比叠加
                        $esmRate = bcadd($esmRate, $vi['productLineRate'], 2);

                        // 进度叠加
                        $process = bcadd($process, round(bcmul($proLineProcess, bcdiv($vi['productLineRate'], 100, 4), 4), 2), 2);
                    }
                }
            }
            return round(bcmul(bcdiv($process, $esmRate, 4), 100, 4), 4);
        }
    }

    /**
     * 根据产线拆分项目信息
     * @param $data
     * @return array
     */
    function splitProjectByProductLine_d($data)
    {
        $rst = array();
        foreach ($data as $v) {
            if (!isset($rst[$v['newProLine']])) {
                $rst[$v['newProLine']] = array();
            }
            $rst[$v['newProLine']][] = $v;
        }
        return $rst;
    }

    /**
     * 查看金额相关信息加载
     * @param $obj
     * @return mixed
     */
    function feeDeal($obj)
    {
        // 配置配置中的补贴ID
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy_id');
        $filterArr = $otherDatasDao->getConfig('engineering_budget_expense_id');
        // 报销系统费用获取
        $feeNow = $this->getFeeNow_d($obj['projectCode']);

        // 报销系统中禁用报销项
        $feeFilterNow = $this->getSomeFeeSum_d($obj['projectCode'], $filterArr);

        // 报销系统中的补贴费用获取
        $feeSubsidyNow = $this->getSomeFeeSum_d($obj['projectCode'], $subsidyArr);

        // 现场决算计算 = 报销费用 - 补贴费用 - 禁用报销项
        $feeField = bcsub($feeNow, $feeSubsidyNow, 2);
        $feeField = bcsub($feeField, $feeFilterNow, 2);

//        if ($obj['feePayables'] == 0) {// 如果支付的决算为0则实时读取当前的支付决算
            $feePayables = 0;
            $fieldRecordDao = self::getObjCache('model_engineering_records_esmfieldrecord');
            $fieldRecordArr = $fieldRecordDao->feeDetail_d('payables', $obj['id']);

            foreach ($fieldRecordArr as $costVal) {
                $feePayables = bcadd($feePayables, $costVal, 3);
            }
            $obj['feePayables'] = $feePayables;
//        }
        // 加上租车登记的预提金额 PMS 715
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentalcarCostArr = $rentalcarDao->getProjectAuditingCarFee($obj['id']);
        $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;

        //实时费用设置，现场决算 = 报销费用+费用分摊费用+费用维护+机票决算
        $obj['feeFieldClean'] = bcadd($feeField, $auditingCarFee, 2);  // 报销决算
        $feeField = bcadd($feeField, $obj['feePayables'], 2);  //费用分摊费用
        $feeField = bcadd($feeField, $obj['feeFieldImport'], 2);  //费用维护
        $feeField = bcadd($feeField, $obj['feeCar'], 2);  //租车
        $feeField = bcadd($feeField, $auditingCarFee, 2);  //租车登记预提
        $feeField = bcadd($feeField, $obj['feeFlights'], 2);
        $obj['feeField'] = $feeField;  //其他决算
        $obj['feeFieldProcess'] = $this->countProcess_d($obj['budgetField'], $feeField); // 现场费用进度

        // PK并入其他决算
        $projectList = $this->PKFeeDeal_d(array($obj));
        $obj['budgetPK'] = $projectList[0]['budgetPK']; // PK预算
        $obj['feePK'] = $projectList[0]['feePK']; // PK决算
        $obj['budgetOther'] = bcadd($obj['budgetOther'], $obj['budgetPK'], 2);
        $obj['feeOther'] = bcadd($obj['feeOther'], $obj['feePK'], 2);
        $obj['feeOtherProcess'] = $this->countProcess_d($obj['budgetOther'], $obj['feeOther']); // 其他

        // 预算重算
        $obj['budgetAllClean'] = $obj['budgetAll'];
        $obj['budgetAll'] = bcadd($obj['budgetAll'], $projectList[0]['budgetPK'], 2);

        // 人力决算 = 人力决算（从工资系统取） + 补贴决算
        $obj['feePersonClean'] = $obj['feePerson'];
        $obj['feeSubsidy'] = $feeSubsidyNow;
        $obj['feePerson'] = bcadd($obj['feePerson'], $feeSubsidyNow, 2);
        $obj['feePerson'] = bcadd($obj['feePerson'], $obj['feeSubsidyImport'], 2); //人力成本合计
        $obj['feePersonProcess'] = $this->countProcess_d($obj['budgetPerson'], $obj['feePerson']); // 设备进度

        // 获取设备决算
        $esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
        $obj['feeEquClean'] = $obj['feeEqu'];
        $obj['feeEquDepr'] = bcadd($esmDeviceFeeDao->getDeviceFee_d($obj['id'], '2'),
            $esmDeviceFeeDao->getDeviceFee_d($obj['id'], '1'), 2); // 从设备系统获取的决算
        $obj['feeEqu'] = bcadd($obj['feeEquClean'], $obj['feeEquDepr'], 2);
        $obj['feeEqu'] = bcadd($obj['feeEqu'], $obj['feeEquImport'], 2);  //设备成本合计
        $obj['feeEquProcess'] = $this->countProcess_d($obj['budgetEqu'], $obj['feeEqu']); // 设备进度

        $obj['feeOutsourcingProcess'] = $this->countProcess_d($obj['budgetOutsourcing'], $obj['feeOutsourcing']); // 外包进度

        // 总费用设置
        $feeAll = bcadd($obj['feePerson'], $obj['feeEqu'], 2);
        $feeAll = bcadd($feeAll, $obj['feeOther'], 2);
        $feeAll = bcadd($feeAll, $obj['feeOutsourcing'], 2);
        $feeAll = bcadd($feeAll, $obj['feeField'], 2);

        $obj['feeAll'] = $feeAll;  //总决算

        $obj['feeAllProcess'] = $this->countProcess_d($obj['budgetAll'], $feeAll);  //总费用进度

        $loanDao = new model_loan_loan_loan();
        $obj['inLoanAmount'] = $loanDao->getInLoanAmountByProjId($obj['id']);  //项目借款
        $obj['noPayLoanAmount'] = $loanDao->getNoPayLoanAmountByProjId($obj['id']);  //借款余额

        return $obj;
    }

    /**
     * 合同信息加载
     * @param $obj
     * @return mixed
     */
    function contractDeal($obj)
    {
        /****************** 通用处理 - 结束 ***********************/
        //已实施天数,当前日期-预计开始日期,如果还未开始,则为0,若实际工期不为0,则为实际工期
        $obj['workedDays'] = $obj['actBeginDate'] && $obj['actBeginDate'] != '0000-00-00' && $obj['actEndDate'] && $obj['actEndDate'] != '0000-00-00' ?
            round((strtotime($obj['actEndDate']) - strtotime($obj['actBeginDate'])) / 86400) + 1 : "";

        $obj['remainingDuration'] = time() < strtotime($obj['planEndDate']) ?
            round((strtotime($obj['planEndDate']) - time()) / 3600 / 24) + 1 : 0;
        // CPI
        $obj['CPI'] = round(bcdiv(bcmul(bcdiv($obj['projectProcess'], 100, 4), $obj['budgetAll'], 6), $obj['feeAll'], 6), 2);
        // SPI
        $thisTime = strtotime($obj['planEndDate']) < strtotime(day_date) ? strtotime($obj['planEndDate']) : strtotime(day_date);
        $obj['planProcess'] = round(bcmul(bcdiv(($thisTime - strtotime($obj['planBeginDate'])) / 86400 + 1, $obj['expectedDuration'], 6), 100, 4), 2);
        if ($obj['planProcess'] > 100) {
            $obj['planProcess'] = 100;
        }
        $obj['SPI'] = round(bcdiv($obj['projectProcess'], $obj['planProcess'], 6), 2);

        // 获取预警数量
        $statusreportDao = self::getObjCache('model_engineering_project_statusreport');
        $obj['warningNum'] = $statusreportDao->getNewestWarningNum_d($obj['id']);

        /****************** 通用处理 - 结束 ***********************/

        // 合同类项目
        if ($obj['contractType'] == 'GCXMYD-01') {
            //合同税点(取综合税率)
            $conDao = self::getObjCache('model_contract_contract_contract');
            $conArr = $conDao->get_d($obj['contractId']);
            $conprojectDao = self::getObjCache('model_contract_conproject_conproject');

            // 事业部/板块
            $obj['moduleName'] = $conArr['moduleName'];
            $obj['module'] = $conArr['module'];
            $obj['areaCode'] = $conArr['areaCode'];
            $obj['areaName'] = $conArr['areaName'];
            $obj['contractStatus'] = $conArr['state'];
            // 合同综合税率
            $contractRate = $conprojectDao->getTxaRate($conArr);
            $obj['contractRate'] = $contractRate > 0 ? round(bcmul($contractRate, 100, 3), 2) : 0;
            // 项目占比
            $newProLineMoney = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9, 1);
            $proportion = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9);
            $obj['lineRate'] = $proportion;
            $obj['projectRate'] = bcmul($proportion, bcdiv($obj['workRate'], 100, 9), 9);

            // 合同扣款加坏账
            $deductAndBad = $conArr['deductMoney'];//bcadd($conArr['deductMoney'], $conArr['badMoney'], 2);
            // 项目扣款金额
            $obj['deductMoney'] = bcmul($deductAndBad, bcdiv($obj['projectRate'], 100, 9), 9);
            $obj['contractDeduct'] = $deductAndBad; // 合同扣款金额
            $obj['contractMoney'] = $conArr['contractMoney']; // 合同金额
            $obj['uninvoiceMoney'] = $conArr['uninvoiceMoney']; // 不开票金额
            $obj['contractMoneyDeduct'] = bcsub($conArr['contractMoney'],
                bcadd($deductAndBad, $conArr['uninvoiceMoney'], 9), 9); // 合同金额（已经减去扣款同不开票金额）

            $obj['projectMoneyWithTax'] = bcmul($newProLineMoney, bcdiv($obj['workRate'], 100, 9), 9); // 税前 - 含税
            $obj['projectMoney'] = bcdiv($obj['projectMoneyWithTax'], bcadd(1, $contractRate, 9), 9); // 税后 - 不含税
            // 开票
            $invoiceDao = self::getObjCache('model_finance_invoice_invoice');
            $invoiceInfo = $invoiceDao->getInvoiceAllMoney_d(array(
                'objId' => $obj['contractId'],
                'objType' => 'KPRK-12'
            ));
            $obj['invoiceMoney'] = $invoiceInfo['invoiceMoney'] ? $invoiceInfo['invoiceMoney'] : '0.00';
            $obj['invoiceProcess'] = bcmul(bcdiv($obj['invoiceMoney'], $obj['contractMoneyDeduct'], 9), 100, 9);
            // 到款
            $incomeAllotDao = self::getObjCache('model_finance_income_incomeAllot');
            $incomeMoney = $incomeAllotDao->getIncomeMoney_d(array(
                'objId' => $obj['contractId'],
                'objType' => 'KPRK-12'
            ));
            $obj['incomeMoney'] = $incomeMoney ? $incomeMoney : '0.00';
            $obj['incomeProcess'] = bcmul(bcdiv($obj['incomeMoney'], $obj['contractMoneyDeduct'], 9), 100, 9);
            // 营收预留
            if ($obj['projectProcess'] <= 98 || $obj['invoiceProcess'] >= 100) {
                $reserveRate = 0;
            } else {
                $reserveRate = $obj['invoiceProcess'] < 98 ?
                    0.02 : bcsub(1, bcdiv(min($obj['projectProcess'], $obj['invoiceProcess']), 100, 9), 9);
            }
            $obj['reserveEarnings'] = $reserveRate > 0 ?
                round(
                    bcmul(bcsub($obj['projectMoney'], bcdiv($obj['deductMoney'], bcadd(1, $contractRate, 9), 9), 9), $reserveRate, 9),
                    2)
                : '0.00';
            // 概算毛利率
            $obj['estimatesExgross'] = bcmul(bcsub(1, bcdiv($obj['estimates'], $obj['projectMoney'], 9), 9), 100, 9);
            // 预计毛利、预计毛利率
            $obj['budgetProfit'] = round(bcsub($obj['projectMoney'], $obj['budgetAll'], 9), 2);
            $obj['budgetExgross'] = round(bcmul(bcsub(1, bcdiv($obj['budgetAll'], $obj['projectMoney'], 9), 9), 100, 9), 2);

            // 项目营收
            switch ($obj['incomeType']) {
                case 'SRQRFS-01' : // 进度
                    // 按进度确认 = 税后合同额 * 项目进度 - 项目扣款/（1 + 税点） - 营收预留
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['projectProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], $obj['reserveEarnings'], 9);
                    break;
                case 'SRQRFS-02' : // 开票
                    // 按开票确认 = 开篇金额 * 合同额
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['invoiceProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['reserveEarnings'] = 0;
                    break;
                case 'SRQRFS-03' : // 到款
                    // 按到款确认 = 到款金额 * 合同额
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['incomeProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['reserveEarnings'] = 0;
                    break;
                default :
                    $obj['curIncome'] = 0;
            }

            // 项目收入处理 - 如果合同状态是异常关闭，那么项目营收为0
            if ($obj['contractStatus'] == '7') {
                $obj['curIncome'] = 0;
            }

            //服务产品:读取合同系统-合同产品清单-产品名称，如有多项用/隔开
            $productDao = self::getObjCache('model_contract_contract_product');
            $rs = $productDao->findAll(array('contractId' => $obj['contractId'], 'newProLineCode' => $obj['newProLine']), null, 'conProductName');
            if (!empty($rs)) {
                $arr = array();
                foreach ($rs as $v) {
                    array_push($arr, $v['conProductName']);
                }
                $obj['conProductName'] = implode('/', $arr);
            } else {
                $obj['conProductName'] = "";
            }

            // 小数位格式化
            $obj['projectMoneyWithTax'] = round($obj['projectMoneyWithTax'], 2);
            $obj['projectMoney'] = round($obj['projectMoney'], 2);
            $obj['curIncome'] = round($obj['curIncome'], 2);
            $obj['deductMoney'] = round($obj['deductMoney'], 2);
            $obj['invoiceProcess'] = round($obj['invoiceProcess'], 2);
            $obj['incomeProcess'] = round($obj['incomeProcess'], 2);
            $obj['estimatesExgross'] = round($obj['estimatesExgross'], 2); // 概算毛利率
        } else { // 字段补齐
            $obj['projectMoneyWithTax'] = $obj['contractRate'] = $obj['projectMoney'];
            $obj['estimatesExgross'] = $obj['budgetExgross'] = 0;
            $obj['projectRate'] = $obj['workRate'];
            $obj['curIncome'] = $obj['uninvoiceMoney'] = 0;
            $obj['invoiceMoney'] = $obj['invoiceProcess'] = '0';
            $obj['incomeMoney'] = $obj['incomeProcess'] = '0';
            $obj['reserveEarnings'] = $obj['deductMoney'] = '0';
            $obj['conProductName'] = $obj['projectObjectives'] = '';

            // 如果是PK项目，获取对应的概算以及区域、板块等信息
            if ($obj['contractType'] == 'GCXMYD-04') {
                $trialprojectDao = self::getObjCache('model_projectmanagent_trialproject_trialproject');
                $trialprojectObj = $trialprojectDao->get_d($obj['contractId']);
                $obj['estimates'] = $trialprojectObj['affirmMoney'];

                // 事业部/板块
                $obj['moduleName'] = $trialprojectObj['moduleName'];
                $obj['module'] = $trialprojectObj['module'];
                $obj['areaCode'] = $trialprojectObj['areaCode'];
                $obj['areaName'] = $trialprojectObj['areaName'];
            }
        }

        // 项目毛利
        $obj['curIncome'] = sprintf("%.2f", $obj['curIncome']);
        $obj['grossProfit'] = bcsub($obj['curIncome'], $obj['feeAll'], 2);
        // 当前毛利率
        $obj['exgross'] = ($obj['curIncome'] == 0) ? '-' : round(bcmul(bcsub(1, bcdiv($obj['feeAll'], $obj['curIncome'], 9), 9), 100, 9), 2);

        $obj['projectRate'] = round($obj['projectRate'], 2);
        $obj['workRate'] = round($obj['workRate'], 2);

        return $obj;
    }

    /**
     * 获取项目占比
     * @param $id
     * @return string
     */
    function getProjectRate_d($id)
    {
        // 项目信息获取
        $obj = $this->find(array('id' => $id), null, 'contractId,newProLine,workRate');

        // 开始计算项目占比
        $conprojectDao = self::getObjCache('model_contract_conproject_conproject');
        $proportion = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9);
        $proportion = $proportion ? $proportion : 0;
        return bcmul($proportion, bcdiv($obj['workRate'], 100, 9), 9);
    }

    /**
     * 进度计算
     * @param $budget
     * @param $fee
     * @return string
     */
    function countProcess_d($budget, $fee)
    {
        if ($budget && $budget != 0.00 && $fee && $fee != 0.00) {
            return round(bcmul(bcdiv($fee, $budget, 6), 100, 4), 2);
        } else {
            return "0.00";
        }
    }

    /**
     * 更新合同
     * @param $keyArr 查询信息数组
     * @param string $keyType 查询项目用的信息
     * @return bool
     * @throws Exception
     */
    function updateContractInfo_d($keyArr, $keyType = 'id')
    {
        try {
            $this->start_d();

            foreach ($keyArr as $val) {
                $projectInfo = $this->find(array($keyType => $val), null, 'id,contractId,contractType');
                if (in_array($projectInfo['contractType'], $this->initContractType)) {
                    //调用策略 - 确认后操作方法
                    if (!isset($strategyArr[$projectInfo['contractType']])) {
                        $newClass = $this->getClass($projectInfo['contractType']);
                        if ($newClass) {
                            $strategyArr[$projectInfo['contractType']] = new $newClass();
                        }
                    }
                    //处理确认
                    $this->businessConfirm_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /******************** 同步project_info,xm_lx表的操作***************/
    /**
     * 同步项目
     * 同步project_info表中的信息
     * project_info 字段 deptId,name,number,manager,status,rand_key,flag
     * project_info中 flag 1 为在建/筹备 ,2 为项目关闭
     * 同步xm_lx表中的信息
     * xm_lx 字段 SID,Name,ProjectNo,DeptNo,Manager,flag,FlagClose
     * xm_lx中 FlagClose 0 为在建/筹备 ,1 为项目关闭
     * @param $object 需要包含 项目经理 、 项目编号 、 项目状态
     * @return bool
     */
    function updateProjectInfo_p($object)
    {
        if (!is_array($object)) {
            $object = $this->find(array('projectCode' => $object), null, 'id,projectName,projectCode,deptId,managerId,status');
        }
        if (!$object) return true;
        if (!isset($object['id'])) {
            $temp = $this->find(array('projectCode' => $object['projectCode']), null, 'id');
            $object['id'] = $temp['id'];
        }
        //项目的状态 -- 默认可用
        if (isset($object['status']) && $object['status'] == 'GCXMZT03') {
            $flag = 2;
            $flagClose = 1;
        } else {
            $flag = 1;
            $flagClose = 0;
        }
        //判断项目经理数量,取第一个
        $managerIdArr = explode(',', $object['managerId']);
        if (count($managerIdArr) > 1) {
            $managerId = array_pop($managerIdArr);
        } else {
            $managerId = $object['managerId'];
        }

        //鼎利项目表数据接口
        //同步project_info
        $projectInfo = $this->_db->getArray("select id from project_info where number = '" . $object['projectCode'] . "'");
        if (!$projectInfo) {
            $inSql = "insert into project_info (dept_id,name,number,manager,status,flag,rand_key,projectId) " .
                "value " . "('" . $object['deptId'] . "','" . $object['projectName'] . "','" . $object['projectCode'] .
                "','" . $managerId . "',1,$flag,MD5('" . $object['projectCode'] . "')," . $object['id'] . ")";
        } else {
            $inSql = "UPDATE project_info SET name = '" . $object['projectName'] .
                "',manager = '$managerId',flag = '$flag',projectId = " . $object['id'] .
                " WHERE number = '" . $object['projectCode'] . "'";
        }
        $this->_db->query($inSql);
        //同步xm_lx
        $projectInfo = $this->_db->getArray("select ID from xm_lx where ProjectNo = '" . $object['projectCode'] . "'");
        if (!$projectInfo) {
            $inSql = "insert into xm_lx (SID,Name,ProjectNo,DeptNo,Manager,flag,FlagClose) " .
                "value " . "(1,'" . $object['projectName'] . "','" . $object['projectCode'] . "','" . $object['deptId'] .
                "','" . $managerId . "','1','" . $flagClose . "')";
        } else {
            $inSql = "UPDATE xm_lx SET Name = '" . $object['projectName'] .
                "',Manager = '$managerId',FlagClose = '$flagClose',DeptNo = '" . $object['deptId'] .
                "' WHERE ProjectNo = '" . $object['projectCode'] . "'";
        }
        $this->_db->query($inSql);

        // 更新项目的项目概算
        $this->updateContractMoneyByProjectCode_d(array($object['projectCode']));
    }

    /**
     * 根据新项目id获取旧项目id
     * @param $projectId
     * @return string
     */
    function getOldProjectId_d($projectId)
    {
        $projectInfo = $this->find(array('id' => $projectId), null, 'projectCode');
        //获取另外一张表的id
        return $this->get_table_fields('project_info', ' number = "' . $projectInfo['projectCode'] . '"', 'id');
    }

    /**
     * 更新毛利率
     * @param $object
     * @return bool
     */
    function updateExgross_d($object)
    {
        set_time_limit(0); // 处理超时
        try {
            //获取项目信息
            $this->searchArr = array('contractType' => 'GCXMYD-01', 'ExaStatus' => AUDITED);
            if ($object['projectCode']) $this->searchArr['projectCodeArr'] = $object['projectCode'];
            if ($object['status']) $this->searchArr['statusArr'] = $object['status'];
            $rows = $this->list_d();

            $conDao = new model_contract_contract_contract(); // 合同
            $conprojectDao = new model_contract_conproject_conproject(); // 合同项目

            foreach ($rows as $v) {
                $conArr = $conDao->get_d($v['contractId']);
                $contractRate = $conprojectDao->getTxaRate($conArr);
                $contractRate = bcmul($contractRate, 100);
                // 税后项目金额
                $v['projectMoney'] = bcdiv($v['contractMoney'], bcadd(1, $contractRate, 2), 2);
                // 当前收入
                $v['curIncome'] = bcmul($v['projectMoney'], bcdiv($v['projectProcess'], 100, 4), 2);
                // 当前毛利率
                $v['exgross'] = round(bcmul(bcsub(1, bcdiv($v['feeAll'], $v['curIncome'], 6), 6), 100, 4), 2);

                // 构建更新的数据，并且附件更新信息
                $updateProject = array('id' => $v['id'], 'exgross' => $v['exgross']);
                $this->addUpdateInfo($updateProject);

                // 执行更新
                $this->updateById($updateProject);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 根据合同id和合同类型获取项目信息
     * @param array $contractIdArr
     * @param string $contractType
     * @return bool|array
     */
    function getProjectList_d(array $contractIdArr, $contractType = 'GCXMYD-01')
    {
        if (!is_array($contractIdArr) || empty($contractIdArr)) return false;

        // 列表内容获取
        $this->searchArr = array('contractIdArr' => implode(',', $contractIdArr), 'contractType' => $contractType);
        $rows = $this->list_d('select_defaultAndFee');

        // 试用预算，试用决算
        $rows = $this->PKFeeDeal_d($rows);

        // 转义成hash数组
        $hashRows = array();
        foreach ($rows as $v) {
            // 设置项目类型，如果该值没有传入，则默认工程项目
            $pType = isset($v['pType']) ? $v['pType'] : 'esm';
            // 只有服务项目才加入这些处理
            if ($pType == 'esm') {
                $v = $this->contractDeal($v);
            }
            $hashRows[$v['id']] = $v;
        }

        return $hashRows;
    }

    /**
     * 根据合同编号获取项目信息，目前这里只用到项目金额，其他附属数据没拿
     * @param $contractCode
     * @return array|bool
     */
    function getProjectListByContractCode_d($contractCode)
    {
        if (empty($contractCode)) return false;

        // 列表内容获取
        $this->searchArr = array('contractCode' => $contractCode);
        $rows = $this->list_d('select_defaultAndFee');

        // 合同项目类调用
        $conProjectDao = new model_contract_conproject_conproject();
        foreach ($rows as $k => $v) {
            // 设置项目类型，如果该值没有传入，则默认工程项目
            $pType = isset($v['pType']) ? $v['pType'] : 'esm';
            // 只有服务项目才加入这些处理
            if ($pType == 'esm') {
                $rows[$k] = $this->contractDeal($v);
            } else {
                $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //项目合同额
            }
        }

        return $rows;
    }

    /**
     * 获取一般项目的id（特指合同类和试用类）
     * @return string
     */
    function getNormalProjectIds_d()
    {
        $this->searchArr = array(
            'contractTypes' => 'GCXMYD-01,GCXMYD-04'
        );
        $rows = $this->list_d();

        if (!empty($rows)) {
            $idArr = array();
            foreach ($rows as $v) {
                $idArr[] = $v['id'];
            }
            return implode(",", $idArr);
        } else {
            return "";
        }
    }

    /**
     * 获取销售区域负责人所负责的省份与客户类型
     */
    function getProvincesAndCustomerType_d()
    {
        $salepersonDao = new model_system_saleperson_saleperson();
        $rs = $salepersonDao->getSaleArea($_SESSION['USER_ID']);
        if (is_array($rs)) {
            $pcArr = array();
            foreach ($rs as $val) {
                if ($val['isUse'] == 0) {
                    array_push($pcArr, array('province' => $val['province'], 'customerType' => $val['customerType']));
                }
            }
            return $pcArr;
        } else {
            return '';
        }
    }

    /**
     * 完成项目
     * @param $object
     * @return bool
     */
    function finish_d($object)
    {
        $email = $object['email'];
        unset($object['email']);

        try {
            $this->start_d();

            // 设置完成的状态
            $object['status'] = "GCXMZT04";
            $this->edit_d($object);

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '完成', null);

            if ($email['issend'] == 'y') {
                // 邮件调用
                $this->mailDeal_d('esmProjectFinish', $email['TO_ID'], array('id' => $object['id']));
            }

            //更新合同状态
            $row = $this->get_d($object['id']);
            if (in_array($row['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($row['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($row, $initObj);
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
     * 完成项目 - 自动
     * @param $object
     * @return bool
     */
    function autoFinish_d($object)
    {

        try {
            $this->start_d();

            $object['status'] = 'GCXMZT04';
            $object['actEndDate'] = day_date;
            $this->edit_d($object);

            //更新合同状态
            $row = $this->get_d($object['id']);
            if (in_array($row['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($row['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($row, $initObj);
                }
            }

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '完成', null);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }

        // 邮件通知处理
        $rangeInfo = $this->getRangeInfo_d($object['id']);

        // 合并人员数组
        $userIdArray = array();
        if ($object['managerId']) $userIdArray[] = $object['managerId'];
        if ($rangeInfo['mainManagerId']) $userIdArray[] = $rangeInfo['mainManagerId'];
        if ($rangeInfo['managerId']) $userIdArray[] = $rangeInfo['managerId'];
        if ($rangeInfo['headId']) $userIdArray[] = $rangeInfo['headId'];
        if ($rangeInfo['assistantId']) $userIdArray[] = $rangeInfo['assistantId'];

        $userIdArray = array_unique(explode(',', implode(',', $userIdArray)));
        $userIds = implode(',', $userIdArray);

        // 邮件调用
        $this->mailDeal_d('esmProjectFinish', $userIds, array('id' => $object['id']));

        return true;
    }

    /**
     * @param $object
     * @return bool
     */
    function openClose_d($object)
    {
        try {
            $this->start_d();

            // 数据字典处理
            $object = $this->processDatadict($object);

            // 项目更新
            $this->edit_d($object);

            // 操作状态
            $actionType = $object['status'] == "GCXMZT02" ? "开" : "关";

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '开关项目-' . $actionType, $object['remark']);

            //更新合同状态
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();

                    // 不同状态执行不同操作
                    if ($object['status'] == "GCXMZT02") {
                        $this->businessConfirm_d($object, $initObj);
                    } else {
                        $this->businessClose_d($object, $initObj);
                    }
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
     * 判断项目是否A类
     * @param null $object
     * @param null $id
     * @return bool
     */
    function isCategoryAProject_d($object = null, $id = null)
    {
        //如果传有项目信息,直接判断
        if (isset($object['category']) && $object['category'] == 'XMLBA') {
            return true;
        }
        //如果只传id，还需要做一次查询
        if ($id) {
            return $this->find(array('id' => $id, 'category' => 'XMLBA'), null, 'id') ? true : false;
        }
        return false;
    }

    /**
     * 判断项目是否全外包
     * @param $id
     * @return bool
     */
    function isAllOutsourcingProject_d($id)
    {
        //如果只传id，还需要做一次查询
        if ($id) {
            return $this->find(array('id' => $id, 'outsourcing' => 'WBLXB'), null, 'id') ? true : false;
        }
    }

    /**
     * 判断项目是否在建
     * @param $id
     * @return bool|mixed
     */
    function isDoing_d($id)
    {
        return $this->find(array('id' => $id, 'status' => 'GCXMZT02'), NULL, 'id');
    }

    /**
     * 判断项目时候还有业务未完成
     * @param $id
     * @return bool
     */
    function isNotDone_d($id)
    {
        $statusreportDao = new model_engineering_project_statusreport();
        if ($statusreportDao->hasSubmitedReport_d($id)) {
            return true;
        }

        $esmchangeDao = new model_engineering_change_esmchange();
        if ($esmchangeDao->hasChangeProject_d($id)) {
            return true;
        }
        return false;
    }

    /**
     * 判断项目是否已关闭
     * @param $id
     * @return mixed
     */
    function isClose_d($id)
    {
        return $this->find(array('id' => $id, 'status' => 'GCXMZT03'));
    }

    /**
     * 判断合同处理已处理完成 - 根据合同类型和id
     * @param $contractId
     * @param $contractType
     * @param $productLine
     * @return bool
     */
    function isAllDealByType_d($contractId, $contractType, $productLine)
    {
        //调用获取工作比方法
        $allWorkRate = $this->getAllWorkRateByType_d($contractId, $contractType, $productLine);
        if ($allWorkRate >= 100) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据业务编号获取项目状态
     * @param $rObjCode
     * @param bool $checkClose
     * @return int 0:执行中|1已完成|2没有项目|3已关闭
     */
    function checkIsCloseByRobjcode_d($rObjCode, $checkClose = false)
    {
        $this->searchArr = array(
            'rObjCode' => $rObjCode
        );
        $rs = $this->list_d();
        if (is_array($rs)) {
            // 产线匹配
            $proLineArr = array();
            // 状态处理
            $statusArr = array();

            // 查询产线
            foreach ($rs as $v) {
                // 如果项目状态非关闭或者完成或者项目进度不为100，则返回项目执行中
//                if ($v['projectProcess'] < 100 || !in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                // PMS 2859 （合同更新状态为完成时,不考虑项目的进度, 项目完工时，进度不一定要达到100%。）如果项目状态非关闭或者完成，则返回项目执行中,
                if (!in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                    return 0;
                }
                $proLineArr[$v['productLine']] = isset($proLineArr[$v['productLine']]) ?
                    $proLineArr[$v['productLine']] + $v['workRate'] : $v['workRate'];

                // 状态处理
                if (!in_array($v['status'], $statusArr)) {
                    $statusArr[] = $v['status'];
                }
            }

            foreach ($proLineArr as $v) {
                if ($v < 100) {
                    return 0;
                }
            }

            // 如果检验项目关闭，才执行以下判定
            // 如果项目状态有且仅为已关闭，则项目判定为全部关闭
            if ($checkClose && (count($statusArr) == 1 && $statusArr[0] == 'GCXMZT03')) {
                return 3;
            }

            // 返回项目已完成
            return 1;
        } else {
            $chkEsmProductSql = "select p.id from oa_contract_product p left join oa_contract_contract c on c.id = p.contractId where c.objCode = '{$rObjCode}' and c.isTemp = 0 and p.proTypeId in (17,18);";
            $chkEsmProduct = $this->_db->getArray($chkEsmProductSql);
            
            //返回2代表没有项目
            return ($chkEsmProduct)? 0 : 2;
        }
    }

    /**
     * 提交验证
     * @param $id
     * @return array
     */
    function submitCheck_d($id)
    {
        // 项目信息
        $obj = $this->get_d($id);
        //金额设置
        $obj = $this->feeDeal($obj);

        // PK 项目验证周期项目周期合法性
        if ($obj['contractType'] == 'GCXMYD-04') {
            //调用策略
            $newClass = $this->getClass($obj['contractType']);
            $initObj = new $newClass();
            //获取对应业务信息
            $businessObj = $this->getRawObjInfo_d($obj, $initObj);
            if (strtotime($obj['planBeginDate']) < strtotime($businessObj['beginDate']) ||
                strtotime($obj['planEndDate']) > strtotime($businessObj['closeDate'])
            ) {

                return array('pass' => 0, 'msg' => 'PK项目实施周期超过申请周期');
            } elseif ($obj['budgetAll'] > $businessObj['affirmMoney']) {

                return array('pass' => 0, 'msg' => 'PK项目预算超过申请确认额度');
            }
        } else if ($obj['contractType'] == 'GCXMYD-01') {
            // 合同项目验证概算值
            if ($obj['budgetAll'] > $obj['estimates']) {
                return array('pass' => 0, 'msg' => '项目预算不能大于项目概算，请修改后再提交审批!');
            }

            // 验证是否还用PK项目在执行
            if ($this->isPKProcessing_d($id)) {
                return array('pass' => 0, 'msg' => '该项目存在未关闭或未完成的关联PK项目，无法提交审批!');
            }
        }

        // 项目计划验证
        $esmActivityDao = new model_engineering_activity_esmactivity();

        // 项目根任务验证
        $workRate = $esmActivityDao->workRateCount($id);
        if ($workRate != 100) {
            return array('pass' => 0, 'msg' => '错误!\n项目任务-工作占比总和' . $workRate . '%，请修改为100%后再提交审批');
        }

        // 下级任务验证
        $activityCheckInfo = $esmActivityDao->workRateCountNew($id, -1, null);
        if ($activityCheckInfo['count'] != 100) {
            return array('pass' => 0, 'msg' => '错误!\n项目任务-下级任务中 ' . $activityCheckInfo['parentName'] .
                ' 工作占比总和' . $activityCheckInfo['count'] . '%,超过100%');
        }

        // 项目关闭申请
        $esmcloseDao = new model_engineering_close_esmclose();
        if ($esmcloseDao->hasProcessingApply_d($id)) {
            return array('pass' => 0, 'msg' => '错误!项目存在审批中的关闭申请');
        }

        return $errorMsg = array('pass' => 1, 'msg' => '', 'rangeId' => $this->getRangeId_d($id));
    }

    /****************************** PK项目相关方法 ******************************/
    /**
     * 根据使用项目id 关闭 所在项目
     * @param $contractId
     * @param string $contractType
     * @return bool|mixed
     */
    function closeProjectByContractId_d($contractId, $contractType = 'GCXMYD-04')
    {
        try {
            $this->start_d();

            //更新项目为关闭
            $updateArr = array(
                'status' => 'GCXMZT03', 'closeDate' => day_date,
                'closeDesc' => '试用项目转合同，系统自动关闭试用项目名下的工程项目'
            );
            $updateArr = $this->addUpdateInfo($updateArr);
            $this->update(
                array('contractId' => $contractId, 'contractType' => $contractType),
                $updateArr
            );

            //返回项目信息
            $obj = $this->find(array('contractId' => $contractId, 'contractType' => $contractType));

            $this->commit_d();
            return $obj;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 获取PK项目信息
     * @param null $projectId
     * @param null $esmprojectObj
     * @return mixed
     */
    function getPKInfo_d($projectId = null, $esmprojectObj = null,$noRate = false)
    {
        $trialInfo = array();
        //获取本项目
        $esmprojectObj = empty($esmprojectObj) ? $this->find(array('id' => $projectId), null, 'id,contractId,contractType,workRate,productLine') : $esmprojectObj;
        if ($esmprojectObj['contractType'] == 'GCXMYD-01') {
            //如果单个项目占比不足100(即视为合同下面,还存在其他项目)，那么查询相关合同下的所有项目，计算总占比
//            if ($esmprojectObj['workRate'] != 100) {
//                $esmprojectArr = $this->findAll(
//                    array('contractId' => $esmprojectObj['contractId'], 'contractType' => $esmprojectObj['contractType'], 'productLine' => $esmprojectObj['productLine']),
//                    null,
//                    'workRate'
//                );
//                //获取改产品线分配的全部占比
//                $allThisProduceLintRate = 0;
//                foreach ($esmprojectArr as $v) {
//                    $allThisProduceLintRate = bcadd($allThisProduceLintRate, $v['workRate'], 4);
//                }
//            } else {
//                $allThisProduceLintRate = 100;
//            }

            //获取关联试用项目
            $esmmappingDao = self::getObjCache('model_engineering_project_esmmapping'); // 实例化项目映射表
            $ids = $esmmappingDao->getProjectString_d($esmprojectObj['id']);
            if ($ids != null) {
                $this->getParam(array('idArr' => $ids, 'contractType' => 'GCXMYD-04', 'productLine' => $esmprojectObj['productLine']));
                $trialInfo = $this->list_d('select_defaultAndFee');
                if (!empty($trialInfo)) {
                    $pkEstimatesRate = 1;
                    if(isset($esmprojectObj['pkEstimatesRate'])){
                        $pkEstimatesRate = bcdiv($esmprojectObj['pkEstimatesRate'],100,4);
                    }else{
                        $esmObj = $this->find(array('id' => $projectId), null, 'id,contractId,contractType,pkEstimatesRate');
                        $pkEstimatesRate = (isset($esmObj['pkEstimatesRate']))? bcdiv($esmObj['pkEstimatesRate'],100,4) : $pkEstimatesRate;
                    }
                    $pkEstimatesRate = ($noRate)? 1 : $pkEstimatesRate;
                    foreach ($trialInfo as $key => $val) {
                        //计算同产品线占比
//                        $trialProjectWorkRate = bcdiv($esmprojectObj['workRate'], $allThisProduceLintRate, 4);
//                        $trialInfo[$key]['budgetAll'] = $trialInfo[$key]['feeAll'] = $val['feeAll'] == 0 ? 0 : round(bcmul($val['feeAll'], $trialProjectWorkRate, 4), 2);

                        // 根据PK成本占比, 获取对应的成本比例
                        $trialInfo[$key]['budgetAll'] = ($noRate)? $trialInfo[$key]['feeAll'] : round(bcmul($trialInfo[$key]['feeAll'],$pkEstimatesRate,3),2);
                        $trialInfo[$key]['feeAll'] = ($noRate)? $trialInfo[$key]['feeAll'] : round(bcmul($trialInfo[$key]['feeAll'],$pkEstimatesRate,3),2);
                    }
                }
            }
        }
        return $trialInfo;
    }

    /**
     * 匹配历史决算数据
     * @param $object
     * @param $feeBeginDate
     * @param $feeEndDate
     * @return mixed
     */
    function historyFeeDeal_d($object, $feeBeginDate, $feeEndDate)
    {
        // 缓存项目ID
        $projectIds = array();

        foreach ($object as $v) {
            $projectIds[] = $v['id'];
        }

        // 获取版本明细
        $esmfielddetailDao = new model_engineering_records_esmfielddetail();
        $esmfielddetailArr = $esmfielddetailDao->getHistory_d($feeBeginDate, $feeEndDate, implode(',', $projectIds));

        // 空决算数组
        $emptyArr = array(
            'feeAll' => 0, 'feePayables' => 0, 'feeFlights' => 0, 'feeCar' => 0,
            'feeCostMaintain' => 0, 'feeEqu' => 0, 'feeEquImport' => 0, 'feeExpense' => 0,
            'feeOther' => 0, 'feeOutsourcing' => 0, 'feePerson' => 0, 'feePK' => 0,
            'feeSubsidy' => 0, 'feeSubsidyImport' => 0
        );

        // 决算处理
        foreach ($object as $k => $v) {
            if (isset($esmfielddetailArr[$v['id']])) {
                $object[$k] = array_merge($object[$k], $esmfielddetailArr[$v['id']]);
            } else {
                $object[$k] = array_merge($object[$k], $emptyArr);
            }
        }

        return $object;
    }

    /**
     * 匹配历史收入
     * @param $object
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function historyIncomeDeal_d($object, $beginDate, $endDate)
    {
        // 缓存项目ID
        $projectIds = array();

        foreach ($object as $v) {
            $projectIds[] = $v['id'];
        }

        // 获取版本明细
        $esmincomeDao = new model_engineering_records_esmincome();
        $esmincomeArr = $esmincomeDao->getHistory_d($beginDate, $endDate, implode(',', $projectIds));

        // 决算处理
        foreach ($object as $k => $v) {
            if (isset($esmincomeArr[$v['id']])) {
                $object[$k]['curIncome'] = $esmincomeArr[$v['id']]['curIncome'];
            } else {
                $object[$k]['curIncome'] = 0;
            }
            //当前毛利率
            $object[$k]['exgross'] = round(bcmul(bcsub(1, bcdiv($v['feeAll'], $object[$k]['curIncome'], 6), 6), 100, 4), 2);
        }

        return $object;
    }

    /**
     * 批量PK费用处理
     * @param $object
     * @return mixed
     */
    function PKFeeDeal_d($object,$needPkRate = false)
    {
        foreach ($object as $k => $v) {

            // 设置项目类型，如果该值没有传入，则默认工程项目
            if ($v['contractType'] == 'GCXMYD-01' && (!isset($v['pType']) || $v['pType'] == 'esm')) {
                $pkEstimatesRate = 1;
                $esmObj = $this->find(array('id' => $v['id']), null, 'id,pkEstimatesRate');
                $pkEstimatesRate = (isset($esmObj['pkEstimatesRate']))? bcdiv($esmObj['pkEstimatesRate'],100,4) : $pkEstimatesRate;
                //获取当前行的试用项目
                $fee = 0;
                $thisRowPKProject = $this->getPKInfo_d(null, $v);
                if ($thisRowPKProject) {
                    foreach ($thisRowPKProject as $val) {
                        $pkFeeAll = ($needPkRate)? round(bcmul($val['feeAll'],$pkEstimatesRate,4),2) : $val['feeAll'];
                        $fee = bcadd($fee, $pkFeeAll, 2);
                    }
                    $object[$k]['budgetAll'] = bcadd(bcsub($v['budgetAll'], $v['budgetPK'], 2), $fee, 2); // 总预算
                    $object[$k]['feeAll'] = bcadd(bcsub($v['feeAll'], $v['feePK'], 2), $fee, 2); //总 决 算(实时)
                }
                $object[$k]['budgetPK'] = $fee;    //PK项目预算
                $object[$k]['feePK'] = $fee;    //PK项目决算
            }
        }
        return $object;
    }

    /**
     * 验证是否有未完成或未关闭的关联PK项目
     * @param $projectId
     * @return bool
     */
    function isPKProcessing_d($projectId)
    {
        $trialInfo = $this->getPKInfo_d($projectId);
        if ($trialInfo) {
            foreach ($trialInfo as $v) {
                //GCXMZT03状态为关闭，GCXMZT04状态为完成, GCXMZT00为逾期未关闭
                if (!in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 验证项目是否为PK项目
     * @param $projectId
     * @return bool
     */
    function isPK_d($projectId)
    {
        $rs = $this->find(array('id' => $projectId), null, 'contractType');
        if ($rs['contractType'] == 'GCXMYD-04') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检验项目是否可以免录日志
     * @param $id
     * @return string
     */
    function checkCanWithoutLog_d($id)
    {
        // 项目匹配
        $object = $this->get_d($id);
        if ($object['ExaStatus'] != AUDITED) {
            return '项目没有审批完成，不允许设置为免录日志';
        }

        // 状态要求在建
        if ($object['status'] != 'GCXMZT02') {
            return '项目状态处于在建状态，不允许设置为免录日志';
        }

        // 项目计划验证
        $esmactivityDao = new model_engineering_activity_esmactivity();
        $projectActivities = $esmactivityDao->getProjectActivity_d($id);

        // 任务长度
        $activitiesLength = count($projectActivities);

        // 要求任务长度有且唯一
        if ($activitiesLength == 0) {
            return '需要没有项目计划，不允许设置为免录日志';
        }
        if ($activitiesLength > 1) {
            return '免录功能只支持一个项目计划的项目';
        }

        // 项目成员
        $esmmemberDao = new model_engineering_member_esmmember();
        $projectMembers = $esmmemberDao->checkMemberAllLeave_d($id);

        // 成员数量
        $membersLength = count($projectMembers);

        // 成员数量不能为空
        if ($membersLength == 0) {
            return '没有还在项目中的成员，不允许设置为免录日志';
        }

        return 'ok';
    }

    /**
     * 自动日志录入 - 免录日志
     * @param $project
     * @return bool
     */
    function autoFillLog_d($project)
    {

        // 如果有查到项目，则开始处理
        if (!empty($project)) {
            // 工作状态
            $work = 'GXRYZT-01';
            $rest = 'GXRYZT-04';

            // 时间
            $now = date('Y-m-d H:i:s');

            // 获取已经填写了的日志 - 人员，项目
            $sql = "SELECT projectId, executionDate FROM oa_esm_worklog c WHERE c.projectId IN(" .
                $project['projectId'] . ") AND c.createId = '" . $project['memberId'] . "'";
            $logDone = $this->_db->getArray($sql);

            // 已录日志缓存
            $logDoneMap = array();

            // 如果有已填记录，则转化为映射表
            if ($logDone) {
                foreach ($logDone as $v) {
                    if (!in_array($v['executionDate'], $logDoneMap)) {
                        $logDoneMap[] = $v['executionDate'];
                    }
                }
            }

            // 获取预计可以填写的日志
            $sql = "SELECT
					c.id AS projectId, c.projectCode, c.projectName, c.country, c.countryId, c.province, c.provinceId,
					c.provinceId, c.city, c.cityId, c.planEndDate AS projectEndDate,
					a.id AS activityId, a.activityName, a.workloadUnit, a.workloadUnitName,
					a.planEndDate AS activityEndDate, a.planBeginDate, a.planEndDate,
					m.memberId AS createId, m.memberName AS createName,'" . $now . "' AS createTime,
					-1 AS workProcess,'WTJ' AS status, 1 AS assessResult, '优' AS assessResultName,
					'免录日志-系统自动生成' AS description, 1 AS workCoefficient,
					1 AS confirmStatus, '" . $now . "' AS confirmTime, 'SYSTEM' AS confirmId, '系统' AS confirmName
				FROM
					oa_esm_project c
					LEFT JOIN
					oa_esm_project_activity a ON c.id = a.projectId
					LEFT JOIN
					oa_esm_project_member m ON c.id = m.projectId
				WHERE c.id IN(" . $project['projectId'] . ") AND m.memberId = '" . $project['memberId'] . "'";
            $logBaseData = $this->_db->getArray($sql);

            // 获取用户部门信息
            $sql = "SELECT d.DEPT_NAME,d.DEPT_ID FROM department d LEFT JOIN user u ON d.DEPT_ID = u.DEPT_ID
                WHERE u.USER_ID = '" . $project['memberId'] . "'";
            $deptInfo = $this->_db->get_one($sql);

            // 日志缓存
            $logCache = array();

            foreach ($logBaseData as $k => $v) {
                // 开始日期以及结束日期
                $begin = $v['planBeginDate'];
                $end = strtotime($v['planEndDate']) > strtotime(day_date) ?
                    day_date : $v['planEndDate'];

                // 任务周期
                $weekDao = new model_engineering_baseinfo_week();
                $projectPeriod = $weekDao->getAllDays($begin, $end);

                foreach ($projectPeriod as $vi) {
                    $logCache[$vi][] = $k;
                }
            }

            // 预计要插入的数据
            $insertData = array();

            // 日志缓存
            foreach ($logCache as $k => $v) {

                // 如果当日已经有别的日志，则跳过这个日期
                if (in_array($k, $logDoneMap)) {
                    continue;
                }

                // 工作状态
                $workStatus = in_array(date('w', strtotime($k)), array(0, 6)) ? $rest : $work;
                $workloadDay = round(1 / count($v), 2);
                $inWorkRate = round(100 / count($v), 2);

                // 根据日期构建插入内容
                foreach ($v as $vi) {
                    $log = $logBaseData[$vi];
                    $log['executionDate'] = $k;
                    $log['executionTimes'] = strtotime($k);
                    $log['workStatus'] = $workStatus;
                    $log['workloadDay'] = $workloadDay;
                    $log['inWorkRate'] = $inWorkRate;
                    $log['deptId'] = $deptInfo['DEPT_ID'];
                    $log['deptName'] = $deptInfo['DEPT_NAME'];
                    $insertData[] = $log;
                }
            }

            // 如果有需要插入的日志，则进行处理
            if (!empty($insertData)) {
                // 实例化一些dao
                $esmactivityDao = new model_engineering_activity_esmactivity();
                $esmmemberDao = new model_engineering_member_esmmember();
                $esmworklogDao = new model_engineering_worklog_esmworklog();

                try {
                    $this->start_d();

                    // 日志插入
                    $esmworklogDao->addBatch_d($insertData);

                    foreach ($logBaseData as $v) {
                        // 更新上级单据
                        $esmworklogDao->updateLogSource_d($this, $esmactivityDao, $esmmemberDao,
                            $v['projectId'], $v['activityId'], $v['memberId'], $v['planBeginDate']);
                    }

                    $this->commit_d();
                } catch (Exception $e) {
                    $this->rollBack();
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 将项目状态更新为逾期未关闭
     * @param $project
     * @return bool
     */
    function autoCloseOverdue_d($project)
    {
        if (!empty($project) && $project['id']) {
            $object = array(
                'status' => 'GCXMZT00'
            );
            $object = $this->processDatadict($object);
            $object = $this->addUpdateInfo($object);

            // 执行更新
            $this->update(array('id' => $project['id']), $object);
        }
        return true;
    }

    /**
     * 获取默认部门
     * @param bool $onlyShow
     * @return array
     */
    function getDefaultDept_d($onlyShow = false)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['部门权限'];
        if (!$deptLimit) {
            return array('deptId' => '', 'deptName' => '');
        }
        if ($onlyShow && strpos($deptLimit, ';;') !== false) {
            return array('deptId' => 'all', 'deptName' => '全部');
        }
        $deptLimitArr = explode(',', $deptLimit);
        $deptIdArr = array();
        foreach ($deptLimitArr as $v) {
            if ($v != ';;' && $v) {
                $deptIdArr[] = $v;
            }
        }
        $deptNameArr = array();
        if (!empty($deptIdArr)) {
            $deptDao = new model_deptuser_dept_dept();
            $deptArr = $deptDao->getDeptByIds_d(implode(',', $deptIdArr));
            foreach ($deptArr as $key => $val) {
                if ($val['deptName']) {
                    $deptNameArr[$key] = $val['deptName'];
                }
            }
        }
        return array(
            'deptId' => implode(',', $deptIdArr),
            'deptName' => implode(',', $deptNameArr)
        );
    }

    /**
     * 根据源单更新概算
     * @param $contractId
     * @param $contractType
     * @param $estimates
     * @throws Exception
     */
    function updateTriEstimates_d($contractId, $contractType, $estimates)
    {
        $object = array(
            'estimates' => $estimates
        );
        $object = $this->addUpdateInfo($object);
        try {
            $this->update(array(
                'contractId' => $contractId,
                'contractType' => $contractType
            ), $object);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新项目的合同数据
     * @param $param
     * @param $month
     * @return bool
     */
    function updateProjectFields_d($param, $month = '')
    {
        set_time_limit(0);
        // 查询已经审核过的合同该项目来处理
        $condition = null;
        if (!is_array($param) && $param) $condition = array('projectCode' => $param);
        $rows = $this->findAll($condition);

        if ($rows) {
            $projectIds = array();
            foreach ($rows as $v) {
                $module = $v['module'];
                $moduleName = $v['moduleName'];
                $areaCode = $v['areaCode'];
                $areaName = $v['areaName'];

                $v = $this->feeDeal($v);
                $v = $this->contractDeal($v);
                if($v['contractId'] > 0){
                    $module = $v['module'];
                    $moduleName = $v['moduleName'];
                    $areaCode = $v['areaCode'];
                    $areaName = $v['areaName'];
                }

                // 缓存需要更新的项目ID
                $projectIds[] = $v['id'];

                // 更新项目信息
                //pms 3055 'feeField' => $v['feeFieldClean'],改成'feeField' => $v['feeField']
                //因为缺少了租车预提 feefield字段是报销总和 是包括租车预提的 clean是不包括
                $this->edit_d(array(
                    'id' => $v['id'], 'moduleName' => $moduleName, 'module' => $module,
                    'areaCode' => $areaCode, 'areaName' => $areaName, 'contractRate' => $v['contractRate'],
                    'deductMoney' => $v['deductMoney'], 'curIncome' => $v['curIncome'], 'exgross' => $v['exgross'],
                    'reserveEarnings' => $v['reserveEarnings'], 'contractMoney' => $v['contractMoney'],
                    'estimates' => $v['estimates'], 'budgetAll' => $v['budgetAllClean'], 'feeAll' => $v['feeAll'],
                    'budgetPK' => $v['budgetPK'], 'feePK' => $v['feePK'], 'feeField' => $v['feeFieldClean'],
                    'feeSubsidy' => $v['feeSubsidy'], 'feeEquDepr' => $v['feeEquDepr'], 'feePayables' => $v['feePayables'],
                    'projectMoney' => $v['projectMoney'], 'projectMoneyWithTax' => $v['projectMoneyWithTax'],
                    'invoiceMoney' => $v['invoiceMoney'], 'incomeMoney' => $v['incomeMoney'],
                    'uninvoiceMoney' => $v['uninvoiceMoney'], 'contractStatus' => $v['contractStatus'],
                    'contractDeduct' => $v['contractDeduct']
                ), true);
            }

            // 更新项目预算项
            $this->updateBudgetAll_d(implode(',', $projectIds));
        }

        // 日志写入
        $logDao = new model_engineering_baseinfo_esmlog();
        $month = $month ? $month : date('n');
        $logDao->addLog_d(-1, '更新项目数据', count($rows) . '|' . $month);

        return true;
    }

    /**
     * 根据合同id获取项目状态
     * @param $contractId
     * @param string $contractType
     * @return bool
     */
    function getStatusNameByContractId_d($contractId, $contractType = 'GCXMYD-01')
    {
        $object = $this->findAll(array(
            'contractId' => $contractId,
            'contractType' => $contractType
        ), null, 'statusName');

        if (!empty($object)) {
            $statusNames = array();
            foreach ($object as $v) {
                $statusNames[] = $v['statusName'];
            }
            return implode(';', $statusNames);
        } else {
            return false;
        }
    }

    /**
     * 汇总表获取收入值
     */
    function getCurIncomeByPro($pro)
    {
        $conprojectDao = new model_contract_conproject_conproject();
        return $conprojectDao->getCurIncomeByPro($pro);
    }

    /**
     * 汇总表获取成本值
     */
    function getFeeAllByPro($pro)
    {
        $conprojectDao = new model_contract_conproject_conproject();
        return $conprojectDao->getFeeAllByPro($pro);
    }

    /**
     *
     * 获取当前登陆人所在项目的待审核日志
     */
    function getWaitAuditLog_d()
    {
        $sql = "SELECT COUNT(id) AS waitAuditLog FROM oa_esm_worklog
			WHERE confirmStatus <> 1 AND projectId IN(
				SELECT id FROM oa_esm_project
				WHERE FIND_IN_SET('" . $_SESSION['USER_ID'] . "', managerId
			) AND ExaStatus = '完成')";
        $data = $this->_db->get_one($sql);

        if ($data) {
            return $data['waitAuditLog'];
        } else {
            return 0;
        }
    }

    /**
     * 获取当前登陆人未提交周报
     * @return int
     */
    function getWaitSubReport_d()
    {
        $sql = "SELECT id FROM oa_esm_project
				WHERE FIND_IN_SET('" . $_SESSION['USER_ID'] . "', managerId) AND status = 'GCXMZT02'";
        $data = $this->_db->getArray($sql);

        // 项目日期
        $idArr = array();

        foreach ($data as $v) {
            $idArr[] = $v['id'];
        }

        if (empty($idArr)) {
            return 0;
        } else {
            $statusreportDao = new model_engineering_project_statusreport();
            $warnData = $statusreportDao->warnView_d(
                array('beginDateThan' => day_date, 'endDateThan' => day_date,
                    'projectIds' => implode(',', $idArr), 'all' => 1
                )
            );

            $num = 0;
            foreach ($warnData as $v) {
                if ($v['msg'] == '未填报') {
                    $num++;
                }
            }
            return $num;
        }
    }

    /**
     * 获取最大版本
     */
    function getVersionInfo_d()
    {
        $versionInfo = array();

        // 查询当前最高版本
        $this->tbl_name = "oa_esm_records_project";
        $maxVersion = $this->find(null, 'version DESC', 'version');

        if ($maxVersion) {
            $today = date('Ymd');
            $versionDay = substr($maxVersion['version'], 0, 8);
            if ($today != $versionDay) {
                $versionInfo['version'] = $today . '01';
            } else {
                $versionInfo['version'] = intval($maxVersion['version']) + 1;
            }
            $versionInfo['maxVersion'] = $maxVersion['version'];
        } else {
            $versionInfo['version'] = date('Ymd') . '01';
            $versionInfo['maxVersion'] = 0;
        }
        // 年、月份
        $versionInfo['storeYear'] = date('Y');
        $versionInfo['storeMonth'] = date('m');

        $this->tbl_name = "oa_esm_project";
        return $versionInfo;
    }

    /**
     * 根据合同id获取项目数据[合同数据更新时用]
     * @param $cid
     * @param array $conArr
     * @return mixed|null
     */
    function getProByCidForUpload($cid,$conArr = array())
    {
        $backArr['cid'] = $cid;
        $backArr['hasProject'] = false;
        $backArr['contractCode'] = $conArr['contractCode'];
        $backArr['contractMoney'] = $conArr['contractMoney'];
        $backArr['allProjMoneyWithSchl'] = 0;//项目合同额
        $backArr['budgetAll'] = 0;// 总预算
        $backArr['curIncome'] = 0;//项目营收
        $backArr['feeAll'] = 0;//总 决 算(实时)
        $this->searchArr = "";
        $this->searchArr['contractId'] = $cid;
        //试用项目排除
        $rows = $this->pageBySqlId('select_defaultAndFeeForUpload');
        if($rows){
            $backArr['hasProject'] = true;
            // 项目信息处理
            foreach ($rows as $k => $v) {
                $budgetAll = $feeAll = 0;
                // 预算预决算
                $feeAll = $v['feeAll'];
                if ($v['contractType'] == 'GCXMYD-01' && (!isset($v['pType']) || $v['pType'] == 'esm')) {
                    // 获取当前行的试用项目
                    $fee = 0;
                    $thisRowPKProject = $this->getPKInfo_d(null, $v);
                    if ($thisRowPKProject) {
                        foreach ($thisRowPKProject as $val) {
                            $fee = bcadd($fee, $val['feeAll'], 2);
                        }
                        $budgetAll = bcadd(bcsub($v['budgetAll'], $v['budgetPK'], 2), $fee, 2); // 总预算
                    }else{
                        $budgetAll = $v['budgetAll'];
                    }
                }else{
                    $budgetAll = $v['budgetAll'];
                }
                $projectMoneyWithTax = sprintf("%.3f", $v['projectMoney']);
                $projectMoneyWithTax = bcmul($projectMoneyWithTax,1,10);
                $backArr['allProjMoneyWithSchl'] += bcmul($projectMoneyWithTax,bcdiv($v['projectProcess'],100,11),10);//项目合同额
                $backArr['budgetAll'] += sprintf("%.3f", $budgetAll);// 总预算
                $backArr['curIncome'] += sprintf("%.3f", $v['curIncome']);//项目营收
                $backArr['feeAll'] += sprintf("%.3f", $feeAll);//总 决 算(实时)
            }
            return $backArr;
        }else{
            return $backArr;
        }
    }

    /**
     *  根据合同id获取项目数据
     */
    function getProByCid($cid)
    {

        $rows = null;
        # 默认指向表的别称是
        $this->setComLocal(array(
            "c" => $this->tbl_name
        ));
        $this->searchArr = "";
        $this->searchArr['contractId'] = $cid;
        $rows = $this->pageBySqlId('select_defaultAndFee');
        //扩展数据处理
        if ($rows) {
            $productDao = new model_contract_contract_product();
            $conProjectDao = new model_contract_conproject_conproject();
            $conDao = new model_contract_contract_contract();
            //试用预算，试用决算
            $rows = $this->PKFeeDeal_d($rows);
            // 其余信息处理
            foreach ($rows as $k => $v) {
                // 设置项目类型，如果该值没有传入，则默认工程项目
                $pType = isset($v['pType']) ? $v['pType'] : 'esm';
                // 只有合同项目才加入这些处理
                if ($pType == 'esm') {
                    $rows[$k] = $this->contractDeal($v);
                } else if ($v['pType'] == "pro") {
                    //执行区域
                    $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                        'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                    $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                    //总成本
                    $conArr = $conDao->get_d($v['contractId']);
                    $revenue = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 1); //项目营收;
                    $earningsType = $v['incomeTypeName']; //收入确认方式
                    $estimates = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //项目概算
                    $DeliverySchedule = $conProjectDao->getFHJD($v);//发货进度
                    $schedule = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //项目进度
                    $shipCostT = $conProjectDao->getFinalCost($v['projectCode'], $revenue, $earningsType, $conArr, $DeliverySchedule, $estimates, 2);//发货成本;

                    //项目实时状况
                    if ($conArr['contractMoney'] === $conArr['uninvoiceMoney']) {
                        $invoiceExe = 100;
                    } else {
                        $deductAndBad = bcadd($conArr['deductMoney'], $conArr['badMoney'], 2);
                        $invoiceExe = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($deductAndBad, $conArr['uninvoiceMoney'], 9), 9), 9), 9) * 100;//开票进度-计算
                    }

                    // 租赁合同进度
                    $date1 = strtotime($conArr['beginDate']);
                    $date2 = strtotime($conArr['endDate']);
                    $date3 = strtotime(date("Y-m-d"));
                    $allDays = ($date2 - $date1) / 86400 + 1;
                    $finishDays = ($date3 - $date1) / 86400 + 1;
                    $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                    $otherCost = $conProjectDao->getPotherCost($v['projectCode']);
                    $proportion = $conProjectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                    $workRate = round($proportion, 2);
                    $feeCostbx = $conProjectDao->getFeeCostBx($conArr, $workRate);//报销支付成本
                    $shipCost = $conProjectDao->getShipCost($schedule, $invoiceExe, $DeliverySchedule, $shipCostT, $estimates, $earningsType, null, $conArr); //计提发货成本;
                    $finalCost = $otherCost + $feeCostbx + $shipCost;//项目决算

                    $rows[$k]['feeAll'] = $finalCost;//总成本
                    $rows[$k]['curIncome'] = $revenue;
                    $rows[$k]['estimates'] = $estimates;
                    $rows[$k]['projectProcess'] = $projectProcess = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //项目进度;
                    $rows[$k]['shipCostT'] = $conProjectDao->getFinalCost($v['projectCode'], $revenue, $earningsType, $conArr, $DeliverySchedule, $estimates, 2);//发货成本;
                    $rows[$k]['shipCost'] = $shipCost;
                    $rows[$k]['feeProcess'] = round($finalCost / $estimates, 2) * 100; //费用进度;
                    $rows[$k]['equCost'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //存货核算
                    $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //发货进度
                    $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //项目合同额
//                        $rows[$k]['grossProfit'] = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 2) - $otherCost - $feeCostbx - $shipCost; //项目毛利
                    $rows[$k]['curIncome'] = sprintf("%.2f", $rows[$k]['curIncome']);
                    $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //项目毛利
                    $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                    $rows[$k]['projectRate'] = $workRate;
                    $rows[$k]['projectMoney'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 3); //税后项目金额
                    $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //状态
                }
            }
        }
        return $rows;
    }

    /**
     * 通用预警自动更新当前项目的进度值 （只更新目前按开票或按到款确认收入的服务项目）
     */
    function autoUpdateProjectsProcess(){
        $weekNo = model_engineering_util_esmtoolutil::getEsmWeekNo();
        //获取任务信息
        $esmactivityDao = new model_engineering_activity_esmactivity();

        //获取实际周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        $sql = "select * from oa_esm_project where incomeType in ('SRQRFS-02','SRQRFS-03');";
        $esmProjectObj = $this->_db->getArray($sql);

        if($esmProjectObj){
            foreach ($esmProjectObj as $object){
                $isAProject = $this->isCategoryAProject_d($object); // 是否是A类项目

                //获取即时项目进度
                $projectProcess = in_array($object['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
                    $esmactivityDao->getActFinanceProcess_d($object, null, $weekDateInfo['endDate'])
                    : $esmactivityDao->getActCountProcess_d($object['id'], null, $weekDateInfo['endDate'], $isAProject);

                // 项目当前进度
                $projectProcess = $projectProcess > 100 ? 100 : round($projectProcess, 4);

                // 更新项目总进展
                $this->updateById(array("id" => $object['id'],"projectProcess" => $projectProcess));
//                $projectAllProcess = array(
//                    'id' => $object['id'],
//                    'processAct' => $projectProcess > 100 ? 100 : round($projectProcess, 4), // 实际进度
//                    'realProcess' => $object['projectProcess']
//                ); if($projectAllProcess['processAct'] != $projectAllProcess['realProcess']){echo"<pre>";print_r($projectAllProcess);}
            }
        }
    }
}