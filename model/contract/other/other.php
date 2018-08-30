<?php

/**
 * @author Show
 * @Date 2011年12月5日 星期一 10:19:51
 * @version 1.0
 * @description:其他合同 Model层
 */
class model_contract_other_other extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_sale_other";
        $this->sql_map = "contract/other/otherSql.php";
        parent::__construct();
    }

    // 合同状态 - 因为下面没有用到的地方，所以这里注释掉
    // private $statusArr = array("未提交", "审批中", "执行中", "已关闭", "变更中");

    // 数据字典
    public $datadictFieldArr = array('projectType', 'fundType', 'invoiceType');

    // 策略类配置
    private $relatedStrategyArr = array(
        'KXXZA' => 'model_finance_income_strategy_income', // 到款单
        'KXXZB' => 'model_finance_income_strategy_prepayment', // 预收款
        'KXXZC' => 'model_finance_income_strategy_refund' // 退款单
    );

    // 款项性质
    private $relatedCode = array(
        'KXXZA' => 'income', // 到款单
        'KXXZB' => 'pay', // 预收款
        'KXXZC' => 'none' // 退款单
    );

    // 公司权限处理
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

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
     * 根据数据类型返回类
     * @param $objType
     * @return string
     */
    public function getClass($objType)
    {
        return $this->relatedStrategyArr[$objType];
    }

    // 是否
    function rtYesOrNo_d($value)
    {
        return $value == 1 ? '是' : '否';
    }

    // 签收状态
    function rtIsSign_d($value)
    {
        return $value == 1 ? '已签收' : '未签收';
    }

    /**
     * SQL配置
     * @param $thisVal
     * @return string
     */
    function initSetting_c($thisVal)
    {
        switch ($thisVal) {
            case '1' :
                return 'larger';
            case '2' :
                return 'largerEqu';
            case '3' :
                return 'equ';
            case '4' :
                return 'lessEqu';
            case '5' :
                return 'less';
            default :
                return 'noThisSetting';
        }
    }
    /************************* 增删改查 ***********************/

    /**
     * 添加对象
     * @param $object
     * @return mixed
     */
    function add_d($object)
    {

        //业务前签约单位信息处理
        $signCompanyDao = new model_contract_signcompany_signcompany();
        $signCompanyArr = array(
            'signCompanyName' => $object['signCompanyName'],
            'proName' => $object['proName'],
            'proCode' => $object['proCode'],
            'phone' => $object['phone'],
            'address' => $object['address'],
            'linkman' => $object['linkman']
        );
        $signCompanyDao->saveCompanyInfo_d($signCompanyArr);

        //获取付款申请信息
        $payapplyInfo = $object['payapply'];
        unset($object['payapply']);

        //获取分摊明细
        $costShare = $object['costshare'];
        unset($object['costshare']);

        //更新结算单
        if ($object['projectType'] == 'QTHTXMLX-03' && $object['projectId']) {
            // 机票处理
            $balanceDao = new model_flights_balance_balance();

            // 机票的时候，将机票费用转入费用报销
            $costShare = $balanceDao->getCostShare_d($object['projectId']);

            if (!$costShare) {
                header("charset:GBK");
                exit('未能获取机票分摊数据，请联系管理员');
            }
        }

        // 初始化临时延后回款天数等于正式延后回款天数
        $object['delayPayDaysTemp'] = $object['delayPayDays'];

        // 是否有保证金关联其他类合同关联字段更新,避免默认值为“是”,但费用明细不含“投标服务费”的情况
        $object['hasRelativeContract'] = ($object['isNeedRelativeContract'] == 1) ? $object['hasRelativeContract'] : null;

        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict ();

        try {
            $this->start_d();//开启事务

            //业务编号生成部分
            $deptDao = new model_deptuser_dept_dept();
            $dept = $deptDao->getDeptByUserId($object['principalId']);

            $orderCodeDao = new model_common_codeRule ();
            $object['objCode'] = $orderCodeDao->getObjCode($this->tbl_name . "_objCode", $dept['Code']);

            if (ORDERCODE_INPUT == 1) {
                $object['orderCode'] = $object['objCode'];
            }

            $object['ExaStatus'] = WAITAUDIT;
            $object['status'] = 0;

            //数据字典处理
            $object = $this->processDatadict($object);

            //调用父类
            $newId = parent:: add_d($object, true);

            if ($object['isNeedPayapply']) {
                //插入付款申请信息
                $payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
                $payapplyInfo['contractId'] = $newId;
                $payapplyInfo['contractType'] = $this->tbl_name;
                $payapplyInfoDao->dealInfo_d($payapplyInfo);
            }

            //更新结算单
            if (isset($balanceDao)) {
                $balanceDao->updatebalanceStatus_d($object['projectId']);
                // 是否飞机票
                $isFlight = true;
            }

            //费用分摊处理
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();

                // 如果是机票，自动开启识别效果
                if (isset($isFlight) && $isFlight) {
                    if ($object['orderMoney'] != $object['moneyNoTax']) {
                        $costShare = $costShareDao->setShareObjType_d($costShare, $object['moneyNoTax'], $object['taxPoint']);
                    } else {
                        $costShare = $costShareDao->setShareObjType_d($costShare);
                    }

                    // 在这里加入一些属性
                    if (is_array($costShare)) {
                        // 部门获取
                        $deptDao = new model_deptuser_dept_dept();
                        $deptArr = $deptDao->getDeptList_d();

                        // 人员获取
                        $userDao = new model_deptuser_user_user();

                        // 模板中文转转义
                        $moduleMap = $datadictDao->getDataDictList_d('HTBK');

                        foreach ($costShare as $k => $v) {
                            $costShare[$k]['belongCompanyName'] = $object['businessBelongName'];
                            $costShare[$k]['belongCompany'] = $object['businessBelong'];
                            if (isset($v['belongDeptName'])) {
                                $costShare[$k]['belongDeptId'] = $deptArr[$v['belongDeptName']]['DEPT_ID'];
                            } else {
                                $costShare[$k]['belongDeptName'] = $deptArr[$v['belongDeptId']]['DEPT_NAME'];
                            }
                            $costShare[$k]['module'] = $deptArr[$costShare[$k]['belongDeptId']]['module'];
                            $costShare[$k]['moduleName'] = $moduleMap[$costShare[$k]['module']];

                            // 如果存在费用承担人，并且值不为空，则去查找对应的账号
                            if (isset($costShare[$k]['feeMan']) && $costShare[$k]['feeMan']) {
                                $userInfo = $userDao->getUserByName($costShare[$k]['feeMan']);
                                $costShare[$k]['feeManId'] = $userInfo['USER_ID'];
                            }
                        }
                    }
                }

                $costShare = util_arrayUtil::setArrayFn(
                    array('objId' => $newId, 'objCode' => $object['orderCode'], 'objType' => '2',
                        'company' => $object['businessBelong'], 'companyName' => $object['businessBelongName'],
                        'supplierName' => $object['signCompanyName'], 'currency' => $object['currency']
                    ),
                    $costShare
                );
                $costShareDao->saveDelBatch($costShare);
            }

            //更新附件关联关系
            $this->updateObjWithFile($newId, $object['orderCode']);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 添加返款以及不开票记录
     * @param $insertRow
     * @param $extRecord
     * @return mixed
     */
    function addCostChangeRecord($insertRow, $extRecord = array())
    {
        $tableName = "oa_sale_costchange_record";
        unset($insertRow['id']);
        $setStr = "";
        $insertRow = $this->addCreateInfo($insertRow);
        foreach ($insertRow as $k => $v) {
            $setStr .= ($setStr == "") ? " set {$k} = '{$v}'" : ",{$k} = '{$v}'";
        }

        if (!empty($extRecord)) {
            foreach ($extRecord as $k => $v) {
                $setStr .= ($setStr == "") ? " set {$k} = '{$v}'" : ",{$k} = '{$v}'";
            }
        }

        $sql = "INSERT INTO {$tableName} {$setStr};";
//        echo $sql;exit();
        return $this->_db->query($sql);
    }

    /**
     * 重写修改方法
     * @param $object
     * @return mixed
     */
    function edit_d($object)
    {
        $extRecord = array();
        $isReturnApply = 0;// 是否为返款申请标示
        if (isset($object['extRecord'])) {
            $extRecord = $object['extRecord'];
            unset($object['extRecord']);

            if (isset($extRecord['isReturnApply'])) {
                $isReturnApply = $extRecord['isReturnApply'];
                unset($extRecord['isReturnApply']);
            }
        }

        //处理数据字典字段
        $object = $this->processDatadict($object);

        // 是否有保证金关联其他类合同关联字段更新,避免默认值为“是”,但费用明细不含“投标服务费”的情况
        $object['hasRelativeContract'] = ($object['isNeedRelativeContract'] == 1) ? $object['hasRelativeContract'] : null;

        // 初始化临时延后回款天数等于正式延后回款天数
        $object['delayPayDaysTemp'] = $object['delayPayDays'];

        $result = parent::edit_d($object, true);

        if ($result && $isReturnApply == 1) {
            $recordArr['objId'] = $object['id'];
            $recordArr['costType'] = "returnMoney";
            $recordArr['costAmount'] = $object['returnMoney'];
            $recordArr['remarks'] = $object['remark'];
            $this->addCostChangeRecord($recordArr, $extRecord);
        }

        return $result;
    }

    /**
     * 编辑方法
     * @param $object
     * @return mixed
     */
    function editInfo_d($object)
    {
        //获取付款申请信息
        $payapplyInfo = $object['payapply'];
        unset($object['payapply']);

        //获取分摊明细
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //处理数据字典字段
            $object = $this->processDatadict($object);

            $payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
            //付款信息处理
            if ($object['isNeedPayapply'] == 1) {
                //更新付款申请信息
                $payapplyInfo['contractId'] = $object['id'];
                $payapplyInfo['contractType'] = $this->tbl_name;
                $payapplyInfoDao->dealInfo_d($payapplyInfo);
            } else {
                $object['isNeedPayapply'] = 0;
                //删除付款申请信息
                $payapplyInfoDao->delete(array('contractId' => $object['id'], 'contractType' => $this->tbl_name));
            }

            //费用分摊处理
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->update(
                    array('objId' => $object['id'], 'objType' => '2'),
                    array('objCode' => $object['orderCode'], 'company' => $object['businessBelong'],
                        'companyName' => $object['businessBelongName'], 'supplierName' => $object['signCompanyName'],
                        'currency' => $object['currency']
                    )
                );
            }

            parent::edit_d($object, true);

            //更新附件关联关系
            $this->updateObjWithFile($object['id'], $object['orderCode']);

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 获取合同信息及付款信息
     * @param $id
     * @return array
     */
    function getInfo_d($id)
    {
        $obj = parent::get_d($id);

        $payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
        $payapplyArr = $payapplyInfoDao->getPayapplyInfo_d($id, $this->tbl_name);

        $obj['otherFeeDeptName'] = $obj['feeDeptName'];
        $obj['otherFeeDeptId'] = $obj['feeDeptId'];

        $obj = array_merge($obj, $payapplyArr);

        return $obj;
    }

    /**
     * 获取合同及其付款、开票信息 -- 包含初始化金额
     * @param $id
     * @return array
     */
    function getInfoAndPay_d($id)
    {
        $obj = parent::get_d($id);

        // 如果是付款合同，才取值
        if ($obj['fundType'] == 'KXXZB') {
            //获取付款
            $payablesDao = new model_finance_payables_payables();
            $obj['payedMoney'] = bcadd($payablesDao->getPayedMoneyByPur_d($id, 'YFRK-02'), $obj['initPayMoney'], 2);

            //获取开票
            $invotherDao = new model_finance_invother_invother();
            $obj['invotherMoney'] = bcadd($invotherDao->getInvotherMoney_d($id), $obj['initInvotherMoney'], 2);
        }

        if ($obj['fundType'] == 'KXXZB' || $obj['fundType'] == 'KXXZC') {
            // 获取分摊信息
            $costShareDao = new model_finance_cost_costshare();
            $costShareInfo = $costShareDao->getShareInfo_d($id, 2);
            if ($costShareInfo) {
                $obj = array_merge($obj, $costShareInfo);
            }
        }

        return $obj;
    }

    /**
     * 删除对象
     * @param $id
     * @return true
     * @throws $e
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $obj = $this->get_d($id);

            $this->deletes($id);

            // 更新结算单
            if ($obj['projectType'] == 'QTHTXMLX-03' && $obj['projectId']) {
                $balanceDao = new model_flights_balance_balance();
                $balanceDao->updatebalanceStatus_d($obj['projectId'], 0);
            }

            // 删除分摊记录
            if ($obj['fundType'] == 'KXXZB' || $obj['fundType'] == 'KXXZC') {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->deleteByObjInfo_d($id, 2);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 新增申请盖章信息
     * @param $obj
     * @return boolean
     */
    function stamp_d($obj)
    {
        $stampDao = new model_contract_stamp_stamp();
        try {
            $this->start_d();

            //获取对应对象的最大批次号
            $maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name, " contractType = 'HTGZYD-02' and contractId=" .
                $obj['contractId'], "max(batchNo)");
            $obj['batchNo'] = intval($maxBatchNo) + 1;

            //新增盖章信息
            $obj['contractType'] = 'HTGZYD-02';
            $stampDao->addStamps_d($obj, true);

            //更新合同字段信息
            $this->edit_d(array('id' => $obj['contractId'], 'isNeedStamp' => 1, 'stampType' => $obj['stampType'], 'isStamp' => 0));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 付款结束
     * @param $id
     * @return true
     * @throws $e
     */
    function end_d($id)
    {
        try {
            //判断款项是否已经付清
            $obj = $this->getInfoAndPay_d($id);
            if ($obj['orderMoney'] == $obj['payedMoney'] && $obj['projectType'] == 'QTHTXMLX-03' && $obj['projectId']) {
                $balanceDao = new model_flights_balance_balance();
                $balanceDao->updatebalanceStatus_d($obj['projectId'], 1);
            }
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 审批成功后在盖章列表添加信息
     * @param $spid
     * @return boolean
     */
    function dealAfterAudit_d($spid)
    {
        $otherDatas = new model_common_otherdatas ();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $userId = $flowInfo['Enter_user'];
        $object = $this->getInfo_d($objId);
        if ($object['ExaStatus'] != AUDITED) {
            $this->update("id = {$objId}", array("delayPayDaysTemp" => $object['delayPayDays']));// 初始化延后回款天数与正式的天数相等
            return true;
        }

        try {
            $this->start_d();

            if ($object['fundType'] == 'KXXZB' || $object['fundType'] == 'KXXZC') {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->setDataEffective_d($object['id'], 2);

                // 更新延迟回款天数
                $this->update("id = {$objId} and delayPayDaysTemp >= 0", array("delayPayDays" => $object['delayPayDaysTemp']));

                // 费用分摊审核邮件发送
                $costShareDao->sendWaitingAuditMail_d($object['id'], $object['orderCode'], 2);
            }

            if ($object['isNeedStamp'] == "1" && $object['ExaStatus'] == AUDITED) {
                $applyId = '';
                // 创建盖章申请数组
                if ($object['stampIds'] && $object['stampIds'] != '') {
                    $stampConfigDao = new model_system_stamp_stampconfig();
                    $stampapplyDao = new model_contract_stamp_stampapply();
                    $legalPersonUsername = $legalPersonName = $businessBelongId = '';
                    $stampIdArr = explode(",", $object['stampIds']);
                    $stampConfigInfo = $stampConfigDao->get_d($stampIdArr[0]);
                    if ($stampConfigInfo) {
                        $legalPersonUsername = $stampConfigInfo['legalPersonUsername'];
                        $legalPersonName = $stampConfigInfo['legalPersonName'];
                        $businessBelongId = $stampConfigInfo['businessBelongId'];
                    }

                    $stampApplyArr = array(
                        "contractId" => $object['id'],
                        "contractName" => $object['orderName'],
                        "contractCode" => ($object['orderCode'] ? $object['orderCode'] : $object['orderTempCode']),
                        "contractType" => 'HTGZYD-02',
                        "signCompanyName" => $object['signCompanyName'],
                        "applyUserId" => $userId,
                        "applyUserName" => $userId == $object['createId'] ? $object['createName'] : $object['principalName'],
                        "deptId" => $object['deptId'],
                        "deptName" => $object['deptName'],
                        "applyDate" => day_date,
                        "stampType" => $object['stampType'],
                        "stampIds" => $object['stampIds'],
                        "legalPersonUsername" => $legalPersonUsername,
                        "legalPersonName" => $legalPersonName,
                        "stampCompanyId" => $businessBelongId,
                        "status" => 0,
                        "ExaDT" => day_date,
                        "ExaStatus" => "完成",
                        "fileName" => $object['orderName'],
                        "isNeedAudit" => 0,
                    );
                    $stampApplyArr['createId'] = $stampApplyArr['updateId'] = $userId;
                    $stampApplyArr['createName'] = $stampApplyArr['updateName'] = $userId == $object['createId'] ? $object['createName'] : $object['principalName'];
                    $stampApplyArr['createTime'] = $stampApplyArr['updateTime'] = date("Y-m-d H:i:s");
                    $applyId = $stampapplyDao->add_d($stampApplyArr);
                }

                // 创建合同盖章数组
                $stampObject = array(
                    "contractId" => $object['id'],
                    "contractCode" => ($object['orderCode'] ? $object['orderCode'] : $object['orderTempCode']),
                    "contractType" => 'HTGZYD-02',
                    "objCode" => $object['objCode'],
                    "contractName" => $object['orderName'],
                    "signCompanyName" => $object['signCompanyName'],
                    "contractMoney" => $object['orderMoney'],
                    "applyUserId" => $userId,
                    "applyId" => $applyId,
                    "applyUserName" => $userId == $object['createId'] ? $object['createName'] : $object['principalName'],
                    "applyDate" => day_date,
                    "stampType" => $object['stampType'],
                    "status" => 0
                );
                $stampDao = new model_contract_stamp_stamp();
                $stampDao->addStamps_d($stampObject, true);
            }

            // 如果付款业务类型为鼎利学院的,发送附加邮件
            if($object['payForBusiness'] == 'FKYWLX-08'){
                $addresses = "dengwei@ultrawise.com.cn,joe.wang@dinglicom.com";
                $auditContent = ($flowInfo['content'] == '')? '' : "审批意见：{$flowInfo['content']}！<br /> &nbsp;&nbsp;&nbsp;&nbsp;";
                $mailContent = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;{$flowInfo['USER_NAME']}已经对审批单号为：{$flowInfo['task']} , 申请人：{$object['createName']}       的申请单进行审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批结果：<font color='blue'>通过</font><br />{$auditContent} &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;合同号: {$object['orderCode']} , 合同名称 : {$object['orderName']}  , 签约公司 : {$object['signCompanyName']} , 合同金额 : {$object['orderMoney']} ";
                $mailContent = addslashes($mailContent);
                $title = "其他合同审批";
                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType,status)values('".$_SESSION['USER_ID']."','$title','$mailContent','$addresses','',NOW(),'','','1','0')";
                $this->_db->query($sql);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 其他合同立项付款申请审批成功后处理
     * @param $spid
     * @return boolean
     */
    function dealAfterAuditPayapply_d($spid)
    {
        $otherDatas = new model_common_otherdatas ();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $userId = $flowInfo['Enter_user'];
        $object = $this->getInfo_d($objId);
        if ($object['ExaStatus'] != AUDITED) {
            return true;
        } else if ($object['ExaStatus'] == BACK) {
            $this->update("id = {$objId}", array("delayPayDaysTemp" => $object['delayPayDays']));// 初始化延后回款天数与正式的天数相等
            return true;
        }

        try {
            $this->start_d();

            //主要责任人设定
            $userName = $userId == $object['createId'] ? $object['createName'] : $object['principalName'];

            //如果需要盖章
            if ($object['isNeedStamp'] == "1") {
                //创建数组
                $stampArr = array(
                    "contractId" => $object['id'],
                    "contractCode" => ($object['orderCode'] ? $object['orderCode'] : $object['orderTempCode']),
                    "contractType" => 'HTGZYD-02',
                    "objCode" => $object['objCode'],
                    "contractName" => $object['orderName'],
                    "signCompanyName" => $object['signCompanyName'],
                    "contractMoney" => $object['orderMoney'],
                    "applyUserId" => $userId,
                    "applyUserName" => $userName,
                    "applyDate" => day_date,
                    "stampType" => $object['stampType'],
                    "status" => 0
                );
                $stampDao = new model_contract_stamp_stamp();
                $stampDao->addStamps_d($stampArr, true);
            }

            // 币种处理
            $currencyDao = new model_system_currency_currency();
            $currencyInfo = $currencyDao->getCurrencyInfo_d($object['currency']);
            //付款申请处理
            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
            //构建付款申请数组
            $payablesapplyArr = array(
                'deptName' => $object['deptName'],
                'deptId' => $object['deptId'],
                'salesman' => $userName,
                'salesmanId' => $userId,
                'supplierName' => $object['payee'],
                'payMoney' => $object['applyMoney'],
                'payMoneyCur' => $object['applyMoney'] * $currencyInfo['rate'],
                'payDate' => $object['formDate'],
                'formDate' => day_date,
                'feeDeptName' => $object['feeDeptName'],
                'feeDeptId' => $object['feeDeptId'],
                'bank' => $object['bank'],
                'account' => $object['account'],
                'payFor' => $object['payFor'],
                'payType' => $object['payType'],
                'remark' => $object['remark'],
                'payCondition' => $object['fundCondition'],
                'sourceCode' => $object['orderCode'],
                'sourceType' => 'YFRK-02',
                'ExaStatus' => '完成',
                'ExaDT' => day_date,
                'exaId' => $object['id'],
                'exaCode' => $this->tbl_name,
                'ExaUser' => $flowInfo['USER_NAME'],
                'ExaUserId' => $flowInfo['USER_ID'],
                'ExaContent' => $flowInfo['content'],
                'payDesc' => $object['payDesc'],
                'isEntrust' => $object['isEntrust'],
                'currency' => $object['currency'],
                'currencyCode' => $currencyInfo['currencyCode'],
                'rate' => $currencyInfo['rate'],
                'place' => $object['place'],
                'isInvoice' => $object['isInvoice'],
                'comments' => $object['comments'],
                'businessBelong' => $object['businessBelong'],
                'businessBelongName' => $object['businessBelongName'],
                'formBelong' => $object['formBelong'],
                'formBelongName' => $object['formBelongName'],
                'isSalary' => $object['isSalary'],
                'detail' => array(
                    0 => array(
                        'money' => $object['applyMoney'],
                        'objId' => $object['id'],
                        'objCode' => $object['orderCode'],
                        'objType' => 'YFRK-02',
                        'purchaseMoney' => $object['orderMoney'],
                        'payDesc' => '其他合同立项付款审批单据',
                        'expand1' => $object['projectTypeName'],
                        'expand2' => $object['projectCode'],
                        'expand3' => $object['projectId'],
                        'orgFormType' => $object['projectName']
                    )
                )
            );
            $payablesapplyArr['createId'] = $payablesapplyArr['updateId'] = $userId;
            $payablesapplyArr['createName'] = $payablesapplyArr['updateName'] = $userName;
            $payablesapplyArr['createTime'] = $payablesapplyArr['updateTime'] = date("Y-m-d H:i:s");

            //如果是委托付款，直接录入付款单据
            if ($object['isEntrust']) {
                $payablesapplyArr['status'] = 'FKSQD-03';
                $payablesapplyArr['actPayDate'] = $object['formDate'];
                $payablesapplyArr['payedMoney'] = $object['applyMoney'];
            }

            $costShareDao = new model_finance_cost_costshare();
            $costShareDao->setDataEffective_d($object['id'], 2);

            // 费用分摊审核邮件发送
            $costShareDao->sendWaitingAuditMail_d($object['id'], $object['orderCode'], 2);

            //增加付款申请单
            $payablesapplyId = $payablesapplyDao->addOnly_d($payablesapplyArr);

            //将付款信息附件移动到付款申请里
            $manageDao = new model_file_uploadfile_management();
            $fileInfo = $manageDao->findAll(Array('serviceId' => $objId, 'serviceType' => "oa_sale_otherpayapply"));
            if ($fileInfo) {// 把文件也移到付款申请里 PMS2472
                foreach ($fileInfo as $v) {
                    $newPath = str_replace("oa_sale_otherpayapply", "oa_finance_payablesapply", $v['uploadPath']);
                    if (!is_dir($newPath)) {
                        mkdir($newPath);
                    }
                    $newFile = $newPath . $v['newName'];
                    $oldFile = $v['uploadPath'] . $v['newName'];
                    copy($oldFile, $newFile);
                }
            }
            $manageDao->update(Array('serviceId' => $objId, 'serviceType' => "oa_sale_otherpayapply"),
                Array('serviceId' => $payablesapplyId, 'serviceType' => "oa_finance_payablesapply", 'serviceNo' => ""));

            //如果是委托付款，直接录入付款单据
            if ($object['isEntrust']) {
                //获取付款申请单数据
                $rows = $payablesapplyDao->getApplyAndDetailNew_d(array('ids' => $payablesapplyId));
                $addObj = $payablesapplyDao->initAddIngroupNew_d($rows);
                $payablesDao = new model_finance_payables_payables();
                $payablesDao->addInGroup_d($addObj['payables'], true);
            }

            // 更新延迟回款天数
            $this->update("id = {$objId} and delayPayDaysTemp >= 0", array("delayPayDays" => $object['delayPayDaysTemp']));

            // 如果付款业务类型为鼎利学院的,发送附加邮件
            if($object['payForBusiness'] == 'FKYWLX-08'){
                $addresses = "dengwei@ultrawise.com.cn,joe.wang@dinglicom.com";
                $auditContent = ($flowInfo['content'] == '')? '' : "审批意见：{$flowInfo['content']}！<br /> &nbsp;&nbsp;&nbsp;&nbsp;";
                $mailContent = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;{$flowInfo['USER_NAME']}已经对审批单号为：{$flowInfo['task']} , 申请人：{$object['createName']}       的申请单进行审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批结果：<font color='blue'>通过</font><br />{$auditContent} &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;合同号: {$object['orderCode']} , 合同名称 : {$object['orderName']}  , 签约公司 : {$object['signCompanyName']} , 合同金额 : {$object['orderMoney']} ";
                $mailContent = addslashes($mailContent);
                $title = "其他合同立项付款申请";
                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType,status)values('".$_SESSION['USER_ID']."','$title','$mailContent','$addresses','',NOW(),'','','1','0')";
                $this->_db->query($sql);
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
        return 1;
    }

    /**
     * 单页小计处理
     * @param $object
     * @return mixed
     */
    function pageCount_d($object)
    {
        if (is_array($object)) {
            $newArr = array(
                'payApplyMoney' => 0, 'payedMoney' => 0, 'invotherMoney' => 0,
                'applyInvoice' => 0, 'invoiceMoney' => 0, 'incomeMoney' => 0,
                'orderMoney' => 0, 'uninvoiceMoney' => 0, 'returnMoney' => 0,
                'confirmInvotherMoney' => 0, 'needInvotherMoney' => 0
            );
            foreach ($object as $val) {
                $newArr['payApplyMoney'] = bcadd($newArr['payApplyMoney'], $val['payApplyMoney'], 2);
                $newArr['payedMoney'] = bcadd($newArr['payedMoney'], $val['payedMoney'], 2);
                $newArr['invotherMoney'] = bcadd($newArr['invotherMoney'], $val['invotherMoney'], 2);
                $newArr['applyInvoice'] = bcadd($newArr['applyInvoice'], $val['applyInvoice'], 2);
                $newArr['invoiceMoney'] = bcadd($newArr['invoiceMoney'], $val['invoiceMoney'], 2);
                $newArr['incomeMoney'] = bcadd($newArr['incomeMoney'], $val['incomeMoney'], 2);
                $newArr['orderMoney'] = bcadd($newArr['orderMoney'], $val['orderMoney'], 2);
                $newArr['uninvoiceMoney'] = bcadd($newArr['uninvoiceMoney'], $val['uninvoiceMoney'], 2);
                $newArr['returnMoney'] = bcadd($newArr['returnMoney'], $val['returnMoney'], 2);
                $newArr['confirmInvotherMoney'] = bcadd($newArr['confirmInvotherMoney'], $val['confirmInvotherMoney'], 2);
                $newArr['needInvotherMoney'] = bcadd($newArr['needInvotherMoney'], $val['needInvotherMoney'], 2);
            }
            $newArr['createDate'] = '本页小计';
            $newArr['id'] = 'noId';
            $object[] = $newArr;
            return $object;
        }
        return false;
    }

    /***************** S 变更系列 *********************/
    /**
     * 变更操作
     * @param $object
     * @return mixed
     */
    function change_d($object)
    {
        try {
            $this->start_d();

            //实例化变更类
            $changeLogDao = new model_common_changeLog('other');

            //费用分摊处理
            if ($object['costshare']) {
                $object['costshare'] = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['orderCode'], 'objType' => '2',
                        'company' => $object['businessBelong'], 'companyName' => $object['businessBelongName'],
                        'supplierName' => $object['signCompanyName'], 'currency' => $object['currency']
                    ),
                    $object['costshare']
                );
            }

            //附件处理
            $object['uploadFiles'] = $changeLogDao->processUploadFile($object, $this->tbl_name);

            // 是否有保证金关联其他类合同关联字段更新,避免默认值为“是”,但费用明细不含“投标服务费”的情况
            if ($object['isNeedRelativeContract'] != 1) {
                unset($object['hasRelativeContract']);
            }

            //建立变更信息
            $id = $changeLogDao->addLog($object);

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 更新变更记录中编码代表（是/否）的字段内容,转译成中文显示
     * @param $tempId
     * @param $originalObj
     */
    function updateChangeDetailField($tempId, $originalObj)
    {
        // 整理变更记录对应字段的值
        $objId = $tempId;
        $chkChangeLogSql = "select * from oa_sale_other_changelog where objId = {$originalObj['id']} and tempId = {$objId};";
        $changeLog = $this->_db->getArray($chkChangeLogSql);
        $changeLog = ($changeLog) ? $changeLog[0] : array();

        // 检查并转换变更记录中的【是否是银行保函】与【是否有保证金关联其他类合同】记录的显示方式
        $chgLogDetailSql = "select id,oldValue,newValue,changeField from oa_sale_other_changelogdetail where objId = '{$changeLog['objId']}' and tempId = '{$changeLog['tempId']}' and changeField in ('isBankbackLetter','hasRelativeContract');";
        $chgLogDetailArr = $this->_db->getArray($chgLogDetailSql);

        if (!empty($chgLogDetailArr)) {
            foreach ($chgLogDetailArr as $v) {
                $id = $v['id'];
                $sql = "";
                switch ($v['changeField']) {
                    case 'isBankbackLetter':// 是否是银行保函（1/0）
                        $oldVal = ($v['oldValue'] == 1) ? "是" : (($originalObj['payForBusiness'] == "FKYWLX-03" || $originalObj['payForBusiness'] == "FKYWLX-04") ? "否" : "");
                        $newVal = ($v['newValue'] == 1) ? "是" : (($originalObj['payForBusiness'] == "FKYWLX-03" || $originalObj['payForBusiness'] == "FKYWLX-04") ? "否" : "");
                        $sql = "update oa_sale_other_changelogdetail set oldValue='{$oldVal}',newValue='{$newVal}' where id = {$id}";
                        break;
                    case 'hasRelativeContract':// 是否有保证金关联其他类合同（1/2）
                        $oldVal = ($v['oldValue'] == 1) ? "是" : "否";
                        $oldVal = ($v['oldValue'] == 'NULL') ? "" : $oldVal;
                        $newVal = ($v['newValue'] == 1) ? "是" : "否";
                        $sql = "update oa_sale_other_changelogdetail set oldValue='{$oldVal}',newValue='{$newVal}' where id = {$id}";
                        break;
                }
                if ($sql != "") {
                    $this->query($sql);
                }
            }
        }
    }

    /**
     * 变更审批完成后更新合同状态
     * @param $spid
     * @return mixed
     */
    function dealAfterAuditChange_d($spid)
    {
        $otherDatas = new model_common_otherdatas ();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $obj = $this->get_d($objId);
        $originalObj = $this->get_d($obj['originalId']);

        if ($obj['ExaStatus'] == AUDITED) {
            try {
                $this->start_d();

                $changeLogDao = new model_common_changeLog('other');
                $changeLogDao->confirmChange_d($obj, null);

                if ($obj['fundType'] == 'KXXZB' || $obj['fundType'] == 'KXXZC') {
                    if ($obj['fundType'] == 'KXXZB') {
                        $chkChangeLogSql = "select * from oa_sale_other_changelog where objId = {$originalObj['id']} and tempId = {$objId};";
                        $changeLog = $this->_db->getArray($chkChangeLogSql);
                        if ($obj['delayPayDays'] >= 0 && $obj['delayPayDays'] != $originalObj['delayPayDays']) {
                            $updateDaysArr['id'] = $originalObj['id'];
                            $updateDaysArr['delayPayDays'] = $obj['delayPayDays'];
                            $updateDaysArr['delayPayDaysTemp'] = '';

                            // 如果变更审批时修改了延后天数,审批完成后反写原合同,并添加变更记录
                            $this->updateById($updateDaysArr);
                            if ($changeLog) {
                                $changeLog = $changeLog[0];
                                $addDetailSql = "INSERT INTO oa_sale_other_changelogdetail " .
                                    "SET parentId = '{$changeLog['id']}',objId = '{$changeLog['objId']}',detailTypeCn = '其他合同',detailType = 'other',tempId = '{$changeLog['tempId']}'" .
                                    ",changeFieldCn='延后回款天数',changeField='delayPayDays',oldValue='{$originalObj['delayPayDays']}',newValue='{$obj['delayPayDaysTemp']}';";
                                $this->_db->query($addDetailSql);
                            }
                        }

                        // 缓冲天数变更
                        if ($obj['bufferDays'] >= 0 && $obj['bufferDays'] != $originalObj['bufferDays']) {
                            $updateBufferDaysArr['id'] = $originalObj['id'];
                            $updateBufferDaysArr['bufferDays'] = $obj['bufferDays'];

                            // 如果变更审批时修改了延后天数,审批完成后反写原合同,并添加变更记录
                            $this->updateById($updateBufferDaysArr);
                            if ($changeLog) {
                                $changeLog = $changeLog[0];
                                $addDetailSql = "INSERT INTO oa_sale_other_changelogdetail " .
                                    "SET parentId = '{$changeLog['id']}',objId = '{$changeLog['objId']}',detailTypeCn = '其他合同',detailType = 'other',tempId = '{$changeLog['tempId']}'" .
                                    ",changeFieldCn='缓冲天数',changeField='bufferDays',oldValue='{$originalObj['bufferDays']}',newValue='{$obj['bufferDays']}';";
                                $this->_db->query($addDetailSql);
                            }
                        }

                        $this->update(
                            array('id' => $obj['originalId']),
                            array(
                                'delayPayDaysTemp' => $obj['delayPayDays'],
                                'isNeedRelativeContract' => $obj['isNeedRelativeContract'],
                                'hasRelativeContract' => $obj['hasRelativeContract'],
                                'relativeContract' => $obj['relativeContract'],
                                'relativeContractId' => $obj['relativeContractId']
                            )
                        );
                    }

                    $costShareDao = new model_finance_cost_costshare();
                    $costShareDao->setDataEffective_d($obj['originalId'], 2);

                    // 费用分摊审核邮件发送
                    $costShareDao->sendWaitingAuditMail_d($obj['id'], $obj['orderCode'], 2);
                }

                //源单状态处理
                if ($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1) {//需要重新盖章
                    //直接重置盖章状态位，将现有盖章记录关闭
                    $this->update(
                        array('id' => $obj['originalId']),
                        array('status' => 2, 'isStamp' => 0, 'isNeedRestamp' => 0, 'isNeedStamp' => 0, 'stampType' => '')
                    );
                } elseif ($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0) {//正在盖章的处理
                    $this->update(
                        array('id' => $obj['originalId']),
                        array('status' => 2, 'isStamp' => 0, 'isNeedRestamp' => 0, 'isNeedStamp' => 0, 'stampType' => '')
                    );
                    $stampDao = new model_contract_stamp_stamp();
                    $stampDao->closeWaiting_d($obj['originalId'], 'HTGZYD-02');
                } else {//非盖章处理
                    $this->update(array('id' => $obj['originalId']), array('status' => 2, 'isNeedRestamp' => 0));
                }

                //调用邮件发送
                $this->mailDeal_d('contractOtherChange', null, array('id' => $objId));

                $this->commit_d();
                return true;
            } catch (Exception $e) {
                $this->rollBack();
                return false;
            }
        } elseif ($obj['ExaStatus'] == BACK) {
            $changeLopUpdateSql = "update oa_sale_other_changelog set ExaStatus = '打回',ExaDT = Now() where tempId = '{$objId}' and objId = '{$obj['originalId']}';";
            $this->query($changeLopUpdateSql);
            return $this->update(array('id' => $obj['originalId']), array('status' => 2, 'ExaStatus' => '完成', 'delayPayDaysTemp' => ''));
        }
    }
    /***************** E 变更系列 *********************/

    /***************** S 签收系列 *********************/
    /**
     * 合同签收 - 签收功能
     * @param $object
     * @return mixed
     */
    function sign_d($object)
    {
        //实例化变更类
        $changeLogDao = new model_common_changeLog('otherSign');
        try {
            $this->start_d();

            //原来签收状态处理
            $signInfo = array(
                'signedDate' => day_date,
                'signedStatus' => 1,
                'signedMan' => $_SESSION['USERNAME'],
                'signedManId' => $_SESSION['USER_ID'],
                'id' => $object['oldId']
            );
            parent::edit_d($signInfo, true);

            //数据处理
            $object = $this->processDatadict($object);

            //附件处理
            $object['uploadFiles'] = $changeLogDao->processUploadFile($object, $this->tbl_name);

            //建立变更信息
            $tempObjId = $changeLogDao->addLog($object);

            $changeObj = $object;
            $changeObj['id'] = $tempObjId;
            $changeObj['originalId'] = $changeObj['oldId'];

            //变更确认
            $changeLogDao->confirmChange_d($changeObj, null);

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /***************** E 签收系列 *********************/

    /******************* S 修改分摊明细 *****************/

    /**
     * 修改分摊明细
     * @param $object
     * @return mixed
     */
    function changeCostShare_d($object)
    {
        //实例化变更类
        $changeLogDao = new model_common_changeLog('other');
        try {
            $this->start_d();

            if (isset($object['isNeedRelativeContract'])) {
                $relativeContractUpdateArr['id'] = $object['oldId'];
                $relativeContractUpdateArr['relativeContract'] = $object['relativeContract'];
                $relativeContractUpdateArr['relativeContractId'] = $object['relativeContractId'];
                $relativeContractUpdateArr['isNeedRelativeContract'] = $object['isNeedRelativeContract'];
                $relativeContractUpdateArr['hasRelativeContract'] = $object['hasRelativeContract'];
                $this->updateById($relativeContractUpdateArr);
            }

            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = day_date;
            $object['changeReason'] = '系统提示：调整费用分摊信息';

            //费用分摊处理
            if ($object['costshare']) {
                $object['costshare'] = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['orderCode'], 'objType' => '2',
                        'company' => $object['businessBelong'], 'companyName' => $object['businessBelongName'],
                        'supplierName' => $object['signCompanyName'], 'currency' => $object['currency']
                    ),
                    $object['costshare']
                );
            }

            // 数据字典
            $this->processDatadict($object);

            //建立变更信息
            $tempObjId = $changeLogDao->addLog($object);

            $changeObj = $object;
            $changeObj['id'] = $tempObjId;
            $changeObj['originalId'] = $changeObj['oldId'];

            //变更确认
            $changeLogDao->confirmChange_d($changeObj, null);

            if ($object['fundType'] == 'KXXZB' || $object['fundType'] == 'KXXZC') {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->setDataEffective_d($object['oldId'], 2);

                // 费用分摊审核邮件发送
                $costShareDao->sendWaitingAuditMail_d($object['oldId'], $object['orderCode'], 2);
            }

            // 更新临时ID，讲状态设置为其他的类型
            $this->update(array('id' => $tempObjId), array('ExaStatus' => '修改分摊明细'));

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /******************* E 修改分摊明细 *****************/

    /************************ 付款申请可申请验证 ********************/
    /**
     * 验证方法
     * @param $id
     * @return string
     */
    function canPayapply_d($id)
    {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        return $payablesapplyDao->isExistence_d($id, 'YFRK-02', 'back') ? 'hasBack' : '';
    }

    /**
     * 退款申请验证
     * @param $id
     * @return mixed
     */
    function canPayapplyBack_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'initPayMoney');
        //获取已付款金额(包含退款)
        $payablesDao = new model_finance_payables_payables();
        $payedMoney = bcadd($payablesDao->getPayedMoneyByPur_d($id, 'YFRK-02'), $obj['initPayMoney'], 2);
        if ($payedMoney * 1 != 0) {
            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
            $rs = $payablesapplyDao->isExistence_d($id, 'YFRK-02');
            if ($rs) {
                return 'hasBack';
            }
            $payedApplyMoney = bcadd($payablesapplyDao->getApplyMoneyByPurAll_d($id, 'YFRK-02'), $obj['initPayMoney'], 2);
            if ($payedApplyMoney * 1 != 0) {
                return $payedApplyMoney;
            } else {
                return -1;
            }
        } else {
            return 0;
        }
    }
    /************************ 付款申请可申请验证 ********************/

    /**
     * 获取合同信息 - 不区分公司
     * @param $ids
     * @return array
     */
    function getList_d($ids)
    {
        $this->setCompany(0);
        $this->searchArr = array('ids' => $ids);
        return $this->list_d();
    }

    /**
     * 获取合同欠票金额
     * @param $orderCode
     * @return int
     */
    function getNeedInvotherMoney_d($orderCode)
    {
        $obj = $this->find(array('orderCode' => $orderCode, 'isTemp' => 0), null, 'id');
        if ($obj) {
            $sql = "SELECT
                if(p.payedMoney IS NULL,0,p.payedMoney) + c.initPayMoney -
                  (if(invo.confirmInvotherMoney IS NULL ,0,invo.confirmInvotherMoney) + c.initInvotherMoney)
                  - c.returnMoney - c.uninvoiceMoney AS needInvotherMoney
            FROM
                oa_sale_other c
                LEFT JOIN
                    (
                    SELECT
                        p.objId,SUM(if(i.formType <>'CWYF-03', p.money,-p.money)) AS payedMoney
                    FROM oa_finance_payables i INNER JOIN oa_finance_payables_detail p ON i.id = p.advancesId
                    WHERE p.objId = " . $obj['id'] . " AND p.objType = 'YFRK-02' GROUP BY p.objId
                    ) p ON c.id = p.objId
                LEFT JOIN
                    (
                    SELECT
                        i.objId,SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                        SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
                    FROM oa_finance_invother_detail i LEFT JOIN oa_finance_invother c ON i.mainId = c.id
                    WHERE i.objId = " . $obj['id'] . " AND i.objType = 'YFQTYD02' GROUP BY i.objId
                    ) invo ON c.id = invo.objId
            WHERE c.isTemp = 0 AND c.id = " . $obj['id'];
            $data = $this->_db->getArray($sql);

            return $data[0]['needInvotherMoney'] > 0 ? $data[0]['needInvotherMoney'] : 0;
        }
        return 0;
    }

    /**
     * 获取合同的可分摊金额
     * @param $id
     * @return int
     */
    function getObjMoney_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'orderMoney,moneyNoTax,invoiceType');

        if ($obj) {
            // 查询数据字典
            $dataDictDao = new model_system_datadict_datadict();
            $dataDictObj = $dataDictDao->find(array('dataCode' => $obj['invoiceType']), null, 'expand3');

            // 如果expand3有效并且等于1，则返回不含税金额
            return $dataDictObj['expand3'] == "1" ? $obj['moneyNoTax'] : $obj['orderMoney'];
        } else {
            return 0;
        }
    }

    /**
     * 获取有效的分摊合同
     * 目前是只过滤机票的金额
     * @param $ids
     * @return string
     */
    function getEffectiveObjIds_d($ids)
    {
        $idsArr = $this->_db->getArray("SELECT GROUP_CONCAT(CAST(id AS CHAR)) AS ids FROM oa_sale_other WHERE id IN(" . $ids .
            ") AND projectType <> 'QTHTXMLX-03'");
        return $idsArr ? $idsArr[0]['ids'] : '';
    }

    /**
     * 统计保证金金额
     * @param string $type
     * @param $param
     * @return int
     */
    function sumBzjMoney($type = "byMan", $param)
    {
        $totalMoney['needPay'] = 0;
        $totalMoney['needPayBeyond'] = 0;
        $sql = "select c.id,c.prefBidDate,c.prefPayDate,c.projectPrefEndDate,c.delayPayDays,c.isBankbackLetter,c.backLetterEndDate,c.payForBusiness,c.principalId,if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
			  (if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) +c.initInvotherMoney)
			  - c.returnMoney - c.uninvoiceMoney  as needInvotherMoney from oa_sale_other c 
              left join
				(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
              left join
				(
                    select i.objId,
                        SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                        SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
                    from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
                    where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
                ) invo
                on c.id = invo.objId where 1=1";
        switch ($type) {
            case 'byCompany':
                $sql = $sql . " and c.signCompanyName = '{$param}' and c.fundType = 'KXXZB' and c.payForBusiness in ('FKYWLX-03','FKYWLX-04','FKYWLX-06','FKYWLX-07') and c.status = 2 and c.isTemp = 0;";
                break;
            case 'byMan':
                $sql = $sql . " and c.principalId = '{$param}' and c.fundType = 'KXXZB' and c.payForBusiness in ('FKYWLX-03','FKYWLX-04','FKYWLX-06','FKYWLX-07') and c.status = 2 and c.isTemp = 0;";
                break;
            default:
                $sql = "";
                break;
        }

        $needPayIds = $needPayBeyondIds = '';
        if ($sql != "") {
            $result = $this->_db->getArray($sql);
            foreach ($result as $v) {
                $totalMoney['needPay'] = bcadd($totalMoney['needPay'], $v['needInvotherMoney'], 2);
                $needPayIds .= ($needPayIds != '') ? "," . $v['id'] : $v['id'];
            }

            // 对未还款的数据进行逾期检查并统计
            $chkBeyondNeedPayArr = $this->chkBeyondNeedPayDateById(false, $result);
            foreach ($chkBeyondNeedPayArr as $v) {
                if ($v['isBeyond'] == 1) {
                    $totalMoney['needPayBeyond'] = bcadd($totalMoney['needPayBeyond'], $v['needInvotherMoney'], 2);
                    $needPayBeyondIds .= ($needPayBeyondIds != '') ? "," . $v['id'] : $v['id'];
                }
            }

            if(count($result) > 300){// 当记录大于300条的时候,用Session存的字符串
                $_SESSION['needPayIds'.$type] = $needPayIds;
                $_SESSION['needPayBeyondIds'.$type] = $needPayBeyondIds;
                $needPayIds = '';
                $needPayBeyondIds = '';
            }
        }
        $totalMoney['needPayIds'] = $needPayIds;
        $totalMoney['needPayBeyondIds'] = $needPayBeyondIds;

        return $totalMoney;
    }

    /**
     * 检查相应数据是否为逾期未还款的合同（传入的ID或数组必须都是付款类的合同,否则数据可能有误）
     *
     * @param null $objId
     * @param null $rows
     * @return array|bool|null
     */
    function chkBeyondNeedPayDateById($objId = null, $rows = null)
    {
        $chkRows = array();
        if ($objId) {
            $sql = "select c.id,c.prefBidDate,c.prefPayDate,p.actPayDate,c.projectPrefEndDate,c.delayPayDays,c.isBankbackLetter,c.backLetterEndDate,c.payForBusiness,c.principalId,if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
			  (if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) +c.initInvotherMoney)
			  - c.returnMoney - c.uninvoiceMoney  as needInvotherMoney from oa_sale_other c 
              left join
				(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
              left join
				(
                    select i.objId,
                        SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                        SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
                    from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
                    where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
                ) invo
                on c.id = invo.objId
                left join oa_finance_payablesapply p on p.exaId = c.id and p.exaCode = 'oa_sale_other' and p.ExaStatus = '完成'
                where 1=1 and c.id = {$objId} and c.fundType = 'KXXZB' and c.isTemp = 0;";
            $result = $this->_db->getArray($sql);
            $chkRows = ($result) ? $result : array();
        } else if (is_array($rows) && !empty($rows)) {
            $chkRows = $rows;
        }

        foreach ($chkRows as $k => $v) {
            $chkRows[$k]['today'] = $today = date('Y-m-d');
            $deadline = "";
            // 获取相应类型的还款期限日期
            switch ($v['payForBusiness']) {
                case "FKYWLX-03":// 投标保证金 （加6个月）
                    $deadlineDate = ($v['isBankbackLetter'] == '1') ? $v['backLetterEndDate'] : $v['prefBidDate'];
                    $deadline = date('Y-m-d', strtotime("$deadlineDate +3 month"));
                    break;
                case "FKYWLX-04":// 履约保证金 （加延后回款天数）
                    $deadlineDate = ($v['isBankbackLetter'] == '1') ? $v['backLetterEndDate'] : $v['projectPrefEndDate'];
                    $deadlineDate = $v['projectPrefEndDate'];
                    $dateTime = strtotime($deadlineDate);
                    $days = bcadd($v['delayPayDays'],$v['bufferDays'],0);
                    $dateTime += ($days * 60 * 60 * 24);
                    $deadline = date("Y-m-d", $dateTime);
                    break;
                case "FKYWLX-05":// 押金
                    $deadline = $v['prefPayDate'];
                    break;
                case "FKYWLX-06":// 投标服务费
                    $deadline = $v['actPayDate'];
                    break;
                case "FKYWLX-07":// 标书制作费
                    $deadline = $v['prefBidDate'];
                    break;
            }

            // 最后还款日期
            $chkRows[$k]['deadline'] = $deadline;

            // 根据欠票金额以及还款期限判断是否逾期未还款
            if ($v['needInvotherMoney'] > 0) {
                if ($deadline == "") {
                    $chkRows[$k]['isBeyond'] = 0;
                } else {
                    if (str_replace("-", "", $today) > str_replace("-", "", $deadline)) {
                        $chkRows[$k]['isBeyond'] = 1;
                    } else {
                        $chkRows[$k]['isBeyond'] = 0;
                    }
                }
            } else {
                $chkRows[$k]['isBeyond'] = 0;
            }
        }

        return $chkRows;
    }

    /****************************    邮件预警部分   ******************************/

    /**
     * 预警邮件发送 - 投标保证金
     * @param $params
     */
    function warningMail($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // 没有逾期，直接跳过
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);
        $mailContent['months'] = $overMonth; // 逾期月份
        $users = array($obj['principalId']);// 邮件接收用户

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // 如果是逾期 1 ~ 2 个月，发送预警通知
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 履约保证金
     * @param $params
     */
    function warningMail2($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
//        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
//        $overMonth = $overMonth > 3 ? 3 : $overMonth;
//
//        // 如果是3月，加入延后回款天数的计算
//        if ($overMonth == 3) {
//            $overMonth = ((strtotime(day_date) - strtotime($params['judgeDate'])) / 86400) >= $obj['delayPayDays'] ?
//                3 : 2;
//        }
//
//        // 没有逾期，直接跳过
//        if (!$overMonth) {
//            return;
//        }
//

        $noticeDays = bcadd($params['bufferDays'],30);
        $warningDays = bcadd($params['bufferDays'],60);
        $today = date("Y-m-d");
        $noticeDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$noticeDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $warningDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$warningDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $overMonth = 1;
        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);
        $mailContent['months'] = $overMonth; // 逾期月份
        $users = array($obj['principalId']);// 邮件接收用户

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // 判断该合同是否已发送过相应的邮件通知
        $noticeMailed = $warningMailed = false;
        if (isset($logMap['warningMail2notice']) || $logMap['warningMail2notice']) {
            $noticeMailed = true;
        }

        if (isset($logMap['warningMail2warning']) || $logMap['warningMail2warning']) {
            $warningMailed = true;
        }

        // 如果是逾期 1 ~ 2 个月，发送预警通知
        if (strtotime($noticeDate) - strtotime($today) <= 0 && !$noticeMailed) {
            $logMap['warningMail2notice'] = 1;
            $logDao->setMap_d($id, $logMap);
            $mailContent['months'] = 1;
            $this->mailDeal_d('otherWarningMail2', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if (strtotime($warningDate) - strtotime($today) <= 0 && !$warningMailed) {
            $logMap['warningMail2warning'] = 1;
            $logDao->setMap_d($id, $logMap);
            // 逾期 3个月，发送逾期通知
            $mailContent['days'] = $obj['delayPayDays'];
            $mailContent['months'] = 2;

            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail2_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 投标保证金银行保函
     * @param $params
     */
    function warningMail3($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // 没有逾期，直接跳过
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);

        // 逾期月份
        $mailContent['months'] = $overMonth;

        // 邮件接收用户
        $users = array($obj['principalId']);

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['dateField'] = '预计投标时间'; // empty($params['backLetterEndDate']) ? '预计投标时间' : '保函结束日期';
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // 如果是逾期 1 ~ 2 个月，发送预警通知
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail3', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            // 逾期 6个月，发送逾期通知
            $this->mailDeal_d('otherWarningMail3_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 履约保证金银行保函
     * @param $params
     */
    function warningMail4($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

//        // 计算逾期月份
//        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
//        $overMonth = $overMonth > 3 ? 3 : $overMonth;
//
//        // 没有逾期，直接跳过
//        if (!$overMonth) {
//            return;
//        }
//
//        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
//            return;
//        } else {
//            $logMap[$overMonth] = 1;
//            $logDao->setMap_d($id, $logMap);
//        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // 逾期月份
        $mailContent['months'] = 1;//$overMonth;

        $noticeDays = bcadd($params['bufferDays'],30);
        $warningDays = bcadd($params['bufferDays'],60);
        $today = date("Y-m-d");
        $noticeDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$noticeDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $warningDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$warningDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $overMonth = 1;

        // 邮件接收用户
        $users = array($obj['principalId']);

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['dateField'] = '项目预计结束日期'; // empty($params['backLetterEndDate']) ? '项目预计结束日期' : '保函结束日期';

        // 判断该合同是否已发送过相应的邮件通知
        $noticeMailed = $warningMailed = false;
        if (isset($logMap['warningMail4notice']) || $logMap['warningMail4notice']) {
            $noticeMailed = true;
        }
        if (isset($logMap['warningMail4warning']) || $logMap['warningMail4warning']) {
            $warningMailed = true;
        }

        // 如果是逾期 1 ~ 2 个月，发送预警通知
//        if ($overMonth <= 2) {
        if (strtotime($noticeDate) - strtotime($today) <= 0 && !$noticeMailed){
            $logMap['warningMail4notice'] = 1;
            $logDao->setMap_d($id, $logMap);
            $mailContent['months'] = 1;
            $this->mailDeal_d('otherWarningMail4', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
        //else if ($overMonth == 3) {}
        else if (strtotime($warningDate) - strtotime($today) <= 0 && !$warningMailed)  {
            $logMap['warningMail4warning'] = 1;
            $logDao->setMap_d($id, $logMap);
            $mailContent['months'] = 2;
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail4_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 押金
     * @param $params
     */
    function warningMail5($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // 没有逾期，直接跳过
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // 邮件接收用户
        $users = array($obj['principalId']);

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // 逾期月份
        $mailContent['months'] = $overMonth;

        // 如果是逾期 1 ~ 2 个月，发送预警通知
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail5', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail5_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 投标服务费
     * @param $params
     */
    function warningMail6($params){
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 1 ? 1 : $overMonth;

        // 没有逾期，直接跳过
        if (!$overMonth) {
            return;
        }

        if (isset($logMap['warningMail6']) || $logMap['warningMail6']) {
            return;
        } else {
            $logMap['warningMail6'] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // 逾期月份
        $mailContent['months'] = $overMonth;

        // 邮件接收用户
        $users = array($obj['principalId']);

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['needInvotherMoney'] = isset($params['needInvotherMoney']) ? $params['needInvotherMoney'] : 0;

        // 如果是逾期 1 个月，发送预警通知
        if ($overMonth == 1 && $mailContent['needInvotherMoney'] > 0) {
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail6_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 预警邮件发送 - 标书制作费
     * @param $params
     */
    function warningMail7($params){
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // 初始化其他合同用的日志类
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // 数据获取
        $obj = $this->get_d($id);

        // 计算逾期月份
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 1 ? 1 : $overMonth;

        // 没有逾期，直接跳过
        if (!$overMonth) {
            return;
        }

        if (isset($logMap['warningMail7']) || $logMap['warningMail7']) {
            return;
        } else {
            $logMap['warningMail7'] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // 邮件内容传入
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // 逾期月份
        $mailContent['months'] = $overMonth;

        // 邮件接收用户
        $users = array($obj['principalId']);

        // 财务人员获取
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['needInvotherMoney'] = isset($params['needInvotherMoney']) ? $params['needInvotherMoney'] : 0;

        // 如果是逾期 1 个月，发送预警通知
        if ($overMonth == 1 && $mailContent['needInvotherMoney'] > 0) {
            // 获取负责人部门的的总监和副总
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail7_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * 根据日期计算逾期月份
     * @param $judgeDate
     * @return int
     */
    function calculateOverMonth_d($judgeDate)
    {
        return floor((strtotime(day_date) - strtotime($judgeDate)) / 86400 / 30);
    }

    /****************************    邮件预警部分   ******************************/

    function getSendMailInfo_d($signCompanyName)
    {
        $sql = "SELECT
				orderCode,createId,createName,principalId,principalName,
				if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
					  (if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) +c.initInvotherMoney)
					  - c.returnMoney - c.uninvoiceMoney  as needInvotherMoney
			FROM
				oa_sale_other c
				left join
					(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
				left join
					(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
				left join
					(
						select i.objId,
							SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
							SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
						from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
						where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
					) invo
					on c.id = invo.objId
			WHERE fundType = 'KXXZB' AND payForBusiness IN('FKYWLX-03','FKYWLX-04') AND isTemp = 0 AND signCompanyName = '$signCompanyName'
			HAVING needInvotherMoney > 0";
        $data = $this->_db->getArray($sql);

        $rst = array(
            'mailUser' => '',
            'mailUserId' => '',
            'mailContent' => '',
        );

        $mailConfigDao = new model_system_mailconfig_mailconfig();
        $mailConfigInfo = $mailConfigDao->getMailAllInfo_d("otherReturnMsg", null, array('signCompanyName' => $signCompanyName));

        if (!empty($data)) {
            $mailUser = array($_SESSION['USERNAME']);
            $mailUserId = array($_SESSION['USER_ID']);
            $orderCode = array();

            // 邮件内容
            foreach ($data as $v) {
                if (!in_array($v['createId'], $mailUserId)) $mailUserId[] = $v['createId'];
                if (!in_array($v['createName'], $mailUser)) $mailUser[] = $v['createName'];
                if (!in_array($v['principalId'], $mailUserId)) $mailUserId[] = $v['principalId'];
                if (!in_array($v['principalName'], $mailUser)) $mailUser[] = $v['principalName'];
                if (!in_array($v['orderCode'], $orderCode)) $orderCode[] = $v['orderCode'];
            }

            $rst['mailUser'] = implode(',', $mailUser);
            $rst['mailUserId'] = implode(',', $mailUserId);
            $rst['mailContent'] = $this->buildMailItem_d($mailConfigInfo['mailContent'], $data);
        }
        return $rst;
    }

    /**
     * 邮件发送
     * @param $obj
     */
    function sendMail_d($obj)
    {
        $emailDao = new model_common_mail();
        $emailDao->mailGeneral("其他合同回款通知", $obj['mailUserId'], $obj['mailContent']);
    }

    /**
     * 构建这里的明细表格
     * @param $mailContent
     * @param $data
     * @return mixed
     */
    function buildMailItem_d($mailContent, $data)
    {
        //初始化明细表个
        $detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center><tr>";
        $detailStr .= "<td>合同编号</td><td>合同负责人</td></tr>";
        //表内容渲染
        foreach ($data as $key => $val) {
            //每条记录到最后，必须换行
            $detailStr .= "<tr><td>$val[orderCode]</td><td>$val[principalName]</td></tr>";
        }
        $detailStr .= "</tr></table>";

        //加载从表内容
        $mailContent = str_replace('!itemTable!', $detailStr, $mailContent);

        return $mailContent;
    }
}