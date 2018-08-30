<?php
/**
 * 到款model层类
 */
class model_finance_income_income extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_income";
        $this->sql_map = "finance/income/incomeSql.php";
        parent :: __construct();
    }

    /********************新策略部分使用************************/
    private $relatedStrategyArr = array( //不同类型入库申请策略类,根据需要在这里进行追加
        'YFLX-DKD' => 'model_finance_income_strategy_income', //到款单
        'YFLX-YFK' => 'model_finance_income_strategy_prepayment', //预收款
        'YFLX-TKD' => 'model_finance_income_strategy_refund' //退款单
    );

    private $relatedCode = array(
        'YFLX-DKD' => 'income',
        'YFLX-YFK' => 'prepayment',
        'YFLX-TKD' => 'refund'
    );

    /**
     * 根据类型返回业务名称
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * 根据数据类型返回类
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    //公司权限处理
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    /***************************************************************************************************
     * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
     *************************************************************************************************/

    /**
     * 添加到款及到款分配
     */
    function add_d($income, iincome $strategy) {
        $codeRuleDao = new model_common_codeRule();
        $emailArr = null;
        try {
            $this->start_d();

            $incomeAllot = $income['incomeAllots'];
            unset($income['incomeAllots']);

            //自动产生到款号
            if ($income['formType'] == 'YFLX-TKD') {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'ST');
            } else {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'SK');
            }

            //状态设置
            if (isset($income['sectionType']) && $income['sectionType'] == 'DKLX-FHK') {
                $income['status'] = 'DKZT-FHK';
            } else {
                $income['status'] = $this->changeStatus($income, 'val');
            }

            //邮件信息获取
            if (isset($income['email'])) {
                $emailArr = $income['email'];
                unset($income['email']);
            }

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $income['isSended'] = 1;
            }

            $incomeId = parent::add_d($income, true);

            if (is_array($incomeAllot)) {
                $incomeAllotDao = new model_finance_income_incomeAllot();
                $incomeAllotDao->createBatch($incomeAllot, array('incomeId' => $incomeId), 'objCode');
                $incomeAllotDao->businessDeal_d($incomeAllot);
            }

            $income['id'] = $incomeId;

            $this->commit_d();

            if ($income['formType'] == 'YFLX-TKD') {
                //插入操作记录
                $logSettringDao = new model_syslog_setting_logsetting ();
                $logSettringDao->addObjLog($this->tbl_name, $incomeId, $income, '录入退款');
            } else {
                //插入操作记录
                $logSettringDao = new model_syslog_setting_logsetting ();
                $logSettringDao->addObjLog($this->tbl_name, $incomeId, $income, '录入到款');
            }
            //发送邮件 ,当操作为提交时才发送
            if (isset($emailArr)) {
                if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    $this->mailDeal_d('incomeMail', $emailArr['TO_ID'], array('id' => $incomeId, 'mailUser' => $emailArr['TO_NAME']));
                }
            }

            return $incomeId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 新增其它到款
     */
    function addOther_d($income) {
        $codeRuleDao = new model_common_codeRule();
        try {
            $this->start_d();

            $incomeAllot = $income['incomeAllots'];
            unset($income['incomeAllots']);

            $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'SK');
            $income['status'] = 'DKZT-YFP';
            $income['formType'] = 'YFLX-DKD';
            $income['sectionType'] = 'DKLX-HK';

            $income['allotAble'] = 0;
            $incomeAllot[1]['money'] = $income['incomeMoney'];
            $incomeAllot[1]['allotDate'] = day_date;

            //邮件信息获取
            if (isset($income['email'])) {
                $emailArr = $income['email'];
                unset($income['email']);
            }

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $income['isSended'] = 1;
            }

            $incomeId = parent :: add_d($income, true);

            if (is_array($incomeAllot)) {
                $incomeAllotDao = new model_finance_income_incomeAllot();
                $incomeAllotDao->createBatch($incomeAllot, array('incomeId' => $incomeId));
            }

            //发送邮件 ,当操作为提交时才发送
            if (isset($emailArr) && $emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $this->mailDeal_d('incomeOtherMail', $emailArr['TO_ID'], array('id' => $incomeId, 'mailUser' => $emailArr['TO_NAME']));
            }

            $this->commit_d();
            return $incomeId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据主键修改对象
     */
    function edit_d($object) {
        $object['status'] = $object['sectionType'] == 'DKLX-FHK' ? 'DKZT-FHK' : 'DKZT-WFP'; // 状态判断

        $emailArr = $object['email'];
        unset($object['email']);

        if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
            $object['isSended'] = 1;
        }

        try {
            $this->start_d();

            $oldObj = parent::get_d($object['id']);

            parent::edit_d($object, true);

            $this->commit_d();

            //更新操作日志
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '编辑到款');

            //发送邮件 ,当操作为提交时才发送
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $this->mailDeal_d('incomeMail', $emailArr['TO_ID'], array('id' => $object['id'], 'mailUser' => $emailArr['TO_NAME']));
            }

            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据主键修改对象
     */
    function editEasy_d($object) {
        try {
            $this->start_d();

            $oldObj = parent::get_d($object['id']);

            parent::edit_d($object, true);

            $this->commit_d();

            //更新操作日志
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '编辑到款');

            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        };
    }

    /**
     * 按分配金额更改到款状态
     */
    function changeStatus($income, $rsType = 'act') {
        if ($rsType == 'act') {
            if ($income['allotAble'] == 0) {
                $income['status'] = 'DKZT-YFP';
                //关闭到款邮件
                $mailRecordDao = new model_finance_income_mailrecord();
                $mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
            } else if ($income['allotAble'] == $income['incomeMoney']) {
                $income['status'] = 'DKZT-WFP';
                //开启到款邮件
                $mailRecordDao = new model_finance_income_mailrecord();
                $mailRecordDao->closeMailrecordByIncomeId_d($income['id'], 0);
            } else {
                $income['status'] = 'DKZT-BFFP';
                //关闭到款邮件
                $mailRecordDao = new model_finance_income_mailrecord();
                $mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
            }
            parent::edit_d($income, true);
        } else {
            if ($income['allotAble'] == 0) {
                return 'DKZT-YFP';
            } else if ($income['allotAble'] == $income['incomeMoney']) {
                return 'DKZT-WFP';
            } else {
                return 'DKZT-BFFP';
            }
        }
    }

    /**
     * 获取表单信息和从表信息
     */
    function getInfoAndDetail_d($id) {
        $rs = $this->get_d($id);
        $incomeAllotDao = new model_finance_income_incomeAllot();
        $rs['incomeAllot'] = $incomeAllotDao->getAllotsByIncomeId($id);
        return $rs;
    }

    /**
     * 到款分配
     */
    function allot_d($object) {
        $incomeAllotDao = new model_finance_income_incomeAllot();
        //获取原始从表数据
        $rs = $incomeAllotDao->findAll(array('incomeId' => $object['id']), null, 'objId,objType');

        try {
            $this->start_d();

            $oldObj = parent::get_d($object['id']);
            $incomeAllot = $object['incomeAllots'];
            unset($object['incomeAllots']);

            $contractArr = array(); // 合同id
            //插入从表
            if (!empty($incomeAllot)) {
                foreach ($incomeAllot as $key => $val) {
                    // init allotDate
                    $incomeAllot[$key]['incomeId'] = $object['id'];
                    $incomeAllot[$key]['allotDate'] = empty($val['allotDate']) ? day_date : $incomeAllot[$key]['allotDate'];
                    // init a contractId => money array
                    if ($val['objType'] == 'KPRK-12') {
                        $contractArr[$val['objId']] = isset($contractArr[$val['objId']]) ?
                            bcadd($contractArr[$val['objId']], $val['money'], 2) : $val['money'];
                    }
                }
                $dealAllot = $incomeAllotDao->saveDelBatch($incomeAllot, array('incomeId' => $object['id']));
            };

            //业务信息处理
            $incomeAllotDao->businessDeal_d($incomeAllot, $rs);

            //回款核销部分 create on 2013-08-14 by kuangzw
            $incomeCheck = $object['incomeCheck'];
            unset($object['incomeCheck']);
            // 如果不存在核销记录且分摊对象为合同，则系统去自动匹配可核销付款条件
            if (!empty($dealAllot) && empty($incomeCheck) && !empty($contractArr)) {
                //实例化付款条件类
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d($contractArr);
            }
            if ($incomeCheck) {
                //核销处理
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(array('incomeId' => $object['id'], 'incomeNo' => $object['incomeNo']), $incomeCheck);
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            //改变源单状态
            $this->changeStatus($object);

            $this->commit_d();

            //更新操作日志
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '到款分配');

            // 执行轨迹记录
            if(!empty($dealAllot)){
                $conDao = new model_contract_contract_contract();
                foreach($dealAllot as $v){
                    $trackArr['contractCode'] = $v['objCode'];
                    $trackArr['contractId'] = $v['objId'];$trackArr['modelName'] = 'incomeMoney';
                    $trackArr['operationName'] = '到款记录';$trackArr['result'] = $v['money'];
                    $trackArr['expand1'] = $v['id'];$trackArr['remarks'] = 'expand1为对应到款分配单的ID';
                    $trackArr['time'] = $object['incomeDate'];$trackArr['expand2'] = 'oa_finance_income_allot';
                    $conDao->addTracksRecord($trackArr);
                }
            }

            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 单页小计加载
     * create by kuangzw
     * create on 2012-5-22
     */
    function pageCount_d($object) {
        if (is_array($object)) {
            $newArr = array(
                'incomeMoney' => 0
            );
            foreach ($object as $val) {
                $newArr['incomeMoney'] = bcadd($newArr['incomeMoney'], $val['incomeMoney'], 2);
            }
            $newArr['incomeNo'] = '本页小计';
            $newArr['id'] = 'noId';
            $object[] = $newArr;
            return $object;
        }
    }

    /**
     * 删除
     */
    function deletes_d($id) {
        $incomeAllotDao = new model_finance_income_incomeAllot();
//		//获取原始从表数据
        $rs = $incomeAllotDao->findAll(array('incomeId' => $id), null, 'objId,objType');
        //日志
        $logSettringDao = new model_syslog_setting_logsetting ();
        try {
            $this->start_d();
            //删除对象操作日志
            $logSettringDao->deleteObjLog($this->tbl_name, parent::get_d($id));

            $this->deletes($id);

            //业务对象处理方法 - 暂用
            $incomeAllotDao->businessDeal_d($rs);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**************************************到款导入部分*********************************/

    /**
     * 到款导入功能
     */
    function addExecelData_d($isCheck = 1) {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array(); //结果数组
        $contArr = array(); //合同信息数组
        $contDao = new model_contract_contract_contract();
        $customerArr = array(); //客户信息数组
        $customerDao = new model_customer_customer_customer();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                $status = $isCheck == 1 ? 'DKZT-YFP' : $status = 'DKZT-WFP'; // 单据状态判断
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $val[0] = str_replace(' ', '', $val[0]);
                    $val[1] = trim($val[1]);
                    $val[2] = str_replace(' ', '', $val[2]);
                    $val[3] = str_replace(' ', '', $val[3]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            //判断单据日期
                            $incomeDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[0] - 1, 1900)));
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有收款日期';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($isCheck == 1) {
                            if (!empty($val[2])) {
                                //产生合同缓存数组
                                $contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->getContractInfoByCode($val[2], 'main');
                                if (is_array($contArr[$val[2]])) {
                                    $orderCode = $val[2];
                                    $orderId = $contArr[$val[2]]['id'];
                                    $rObjCode = $contArr[$val[2]]['objCode'];
                                    $orderType = 'KPRK-12';
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!不存在的合同号';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '插入失败!没有合同号';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $orderCode = $val[2];
                            $orderId = $orderType = $rObjCode = "";
                        }

                        if (!empty($val[1])) {
                            //客户名称
                            if (!isset($customerArr[$val[1]])) {
                                $rs = $customerDao->findCus($val[1]);
                                if (is_array($rs)) {
                                    $customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
                                    $prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
                                    $customerName = $val[1];
                                    $customerType = $customerArr[$val[1]]['typeOne'] = $rs[0]['TypeOne'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!客户系统中不存在此客户';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $customerId = $customerArr[$val[1]]['id'];
                                $prov = $customerArr[$val[1]]['prov'];
                                $customerName = $val[1];
                                $customerType = $customerArr[$val[1]]['typeOne'];
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有客户名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[3]) && $val[3] * 1 == $val[3] && $val[3] != 0) {
                            //判断到款金额
                            $incomeMoney = $val[3];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有到款金额或者到款金额为0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //归属公司
                        if (!empty($val[4])) {
                            $branchDao = new model_deptuser_branch_branch();
                            $branchObj = $branchDao->find(array('NameCN' => $val[4]));
                            if (!empty($branchObj)) {
                                if ($val[4] == $contArr[$val[2]]['businessBelongName'] || $isCheck == 2) {
                                    $businessBelongName = $val[4];
                                    $businessBelong = $branchObj['NamePT'];
                                    $formBelong = $branchObj['NamePT'];
                                    $formBelongName = $branchObj['NameCN'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '导入失败!归属公司与合同对应公司不相同';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '导入失败!不存在的归属公司';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                            $tempArr['result'] = '更新失败!没有录入归属公司';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($isCheck == 1) {
                            $inArr = array(
                                'incomeDate' => $incomeDate,
                                'incomeUnitName' => $customerName,
                                'incomeUnitType' => $customerType,
                                'incomeMoney' => $incomeMoney,
                                'incomeCurrency' => $incomeMoney,
                                'allotAble' => 0,
                                'incomeUnitId' => $customerId,
                                'contractUnitName' => $customerName,
                                'contractUnitId' => $customerId,
                                'province' => $prov,
                                'remark' => '系统导入数据',
                                'formType' => 'YFLX-DKD',
                                'status' => $status,
                                'sectionType' => 'DKLX-HK',
                                'incomeAllots' => array(
                                    array(
                                        'objId' => $orderId,
                                        'objCode' => $orderCode,
                                        'objType' => $orderType,
                                        'money' => $incomeMoney,
                                        'allotDate' => day_date,
                                        'rObjCode' => $rObjCode
                                    )
                                ),
                                'businessBelongName' => $businessBelongName,
                                'businessBelong' => $businessBelong,
                                'formBelongName' => $formBelongName,
                                'formBelong' => $formBelong
                            );
                        } else {
                            if (empty($orderCode)) {
                                $inArr = array(
                                    'incomeDate' => $incomeDate,
                                    'incomeUnitName' => $customerName,
                                    'incomeUnitType' => $customerType,
                                    'incomeMoney' => $incomeMoney,
                                    'incomeCurrency' => $incomeMoney,
                                    'allotAble' => 0,
                                    'contractUnitName' => $customerName,
                                    'contractUnitId' => $customerId,
                                    'contractId' => $customerId,
                                    'province' => $prov,
                                    'remark' => '系统导入数据',
                                    'formType' => 'YFLX-DKD',
                                    'status' => $status,
                                    'sectionType' => 'DKLX-HK',
                                    'businessBelongName' => $businessBelongName,
                                    'businessBelong' => $businessBelong,
                                    'formBelongName' => $formBelongName,
                                    'formBelong' => $formBelong
                                );

                            } else {
                                $inArr = array(
                                    'incomeDate' => $incomeDate,
                                    'incomeUnitName' => $customerName,
                                    'incomeUnitType' => $customerType,
                                    'incomeMoney' => $incomeMoney,
                                    'incomeCurrency' => $incomeMoney,
                                    'allotAble' => 0,
                                    'contractUnitName' => $customerName,
                                    'contractUnitId' => $customerId,
                                    'contractId' => $customerId,
                                    'province' => $prov,
                                    'remark' => '系统导入数据',
                                    'formType' => 'YFLX-DKD',
                                    'status' => 'DKZT-YFP',
                                    'sectionType' => 'DKLX-HK',
                                    'incomeAllots' => array(
                                        array(
                                            'objId' => $orderId,
                                            'objCode' => $orderCode,
                                            'objType' => $orderType,
                                            'money' => $incomeMoney,
                                            'allotDate' => day_date,
                                            'rObjCode' => $rObjCode
                                        )
                                    ),
                                    'businessBelongName' => $businessBelongName,
                                    'businessBelong' => $businessBelong,
                                    'formBelongName' => $formBelongName,
                                    'formBelong' => $formBelong
                                );
                            }
                        }
                        if ($this->addForExcel_d($inArr)) {
                            $tempArr['result'] = '插入成功';
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                        } else {
                            $tempArr['result'] = '插入失败';
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
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
     * 添加到款及到款分配
     */
    function addForExcel_d($income) {
        $codeRuleDao = new model_common_codeRule();
        $emailArr = null;
        try {
            $this->start_d();

            $incomeAllot = $income['incomeAllots'];
            unset($income['incomeAllots']);

            //自动产生到款号
            if ($income['formType'] == 'YFLX-TKD') {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'ST');
            } else {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'SK');
            }

            $incomeId = parent :: add_d($income, true);

            if (is_array($incomeAllot)) {
                $incomeAllotDao = new model_finance_income_incomeAllot();
                $incomeAllotDao->createBatch($incomeAllot, array('incomeId' => $incomeId));
                //业务信息处理
                $incomeAllotDao->businessDeal_d($incomeAllot);
            }

            $this->commit_d();
            return $incomeId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }
}