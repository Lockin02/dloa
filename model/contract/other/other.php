<?php

/**
 * @author Show
 * @Date 2011��12��5�� ����һ 10:19:51
 * @version 1.0
 * @description:������ͬ Model��
 */
class model_contract_other_other extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_sale_other";
        $this->sql_map = "contract/other/otherSql.php";
        parent::__construct();
    }

    // ��ͬ״̬ - ��Ϊ����û���õ��ĵط�����������ע�͵�
    // private $statusArr = array("δ�ύ", "������", "ִ����", "�ѹر�", "�����");

    // �����ֵ�
    public $datadictFieldArr = array('projectType', 'fundType', 'invoiceType');

    // ����������
    private $relatedStrategyArr = array(
        'KXXZA' => 'model_finance_income_strategy_income', // ���
        'KXXZB' => 'model_finance_income_strategy_prepayment', // Ԥ�տ�
        'KXXZC' => 'model_finance_income_strategy_refund' // �˿
    );

    // ��������
    private $relatedCode = array(
        'KXXZA' => 'income', // ���
        'KXXZB' => 'pay', // Ԥ�տ�
        'KXXZC' => 'none' // �˿
    );

    // ��˾Ȩ�޴���
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    /**
     * �������ͷ���ҵ������
     * @param $objType
     * @return string
     */
    public function getBusinessCode($objType)
    {
        return $this->relatedCode[$objType];
    }

    /**
     * �����������ͷ�����
     * @param $objType
     * @return string
     */
    public function getClass($objType)
    {
        return $this->relatedStrategyArr[$objType];
    }

    // �Ƿ�
    function rtYesOrNo_d($value)
    {
        return $value == 1 ? '��' : '��';
    }

    // ǩ��״̬
    function rtIsSign_d($value)
    {
        return $value == 1 ? '��ǩ��' : 'δǩ��';
    }

    /**
     * SQL����
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
    /************************* ��ɾ�Ĳ� ***********************/

    /**
     * ��Ӷ���
     * @param $object
     * @return mixed
     */
    function add_d($object)
    {

        //ҵ��ǰǩԼ��λ��Ϣ����
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

        //��ȡ����������Ϣ
        $payapplyInfo = $object['payapply'];
        unset($object['payapply']);

        //��ȡ��̯��ϸ
        $costShare = $object['costshare'];
        unset($object['costshare']);

        //���½��㵥
        if ($object['projectType'] == 'QTHTXMLX-03' && $object['projectId']) {
            // ��Ʊ����
            $balanceDao = new model_flights_balance_balance();

            // ��Ʊ��ʱ�򣬽���Ʊ����ת����ñ���
            $costShare = $balanceDao->getCostShare_d($object['projectId']);

            if (!$costShare) {
                header("charset:GBK");
                exit('δ�ܻ�ȡ��Ʊ��̯���ݣ�����ϵ����Ա');
            }
        }

        // ��ʼ����ʱ�Ӻ�ؿ�����������ʽ�Ӻ�ؿ�����
        $object['delayPayDaysTemp'] = $object['delayPayDays'];

        // �Ƿ��б�֤������������ͬ�����ֶθ���,����Ĭ��ֵΪ���ǡ�,��������ϸ������Ͷ�����ѡ������
        $object['hasRelativeContract'] = ($object['isNeedRelativeContract'] == 1) ? $object['hasRelativeContract'] : null;

        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict ();

        try {
            $this->start_d();//��������

            //ҵ�������ɲ���
            $deptDao = new model_deptuser_dept_dept();
            $dept = $deptDao->getDeptByUserId($object['principalId']);

            $orderCodeDao = new model_common_codeRule ();
            $object['objCode'] = $orderCodeDao->getObjCode($this->tbl_name . "_objCode", $dept['Code']);

            if (ORDERCODE_INPUT == 1) {
                $object['orderCode'] = $object['objCode'];
            }

            $object['ExaStatus'] = WAITAUDIT;
            $object['status'] = 0;

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //���ø���
            $newId = parent:: add_d($object, true);

            if ($object['isNeedPayapply']) {
                //���븶��������Ϣ
                $payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
                $payapplyInfo['contractId'] = $newId;
                $payapplyInfo['contractType'] = $this->tbl_name;
                $payapplyInfoDao->dealInfo_d($payapplyInfo);
            }

            //���½��㵥
            if (isset($balanceDao)) {
                $balanceDao->updatebalanceStatus_d($object['projectId']);
                // �Ƿ�ɻ�Ʊ
                $isFlight = true;
            }

            //���÷�̯����
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();

                // ����ǻ�Ʊ���Զ�����ʶ��Ч��
                if (isset($isFlight) && $isFlight) {
                    if ($object['orderMoney'] != $object['moneyNoTax']) {
                        $costShare = $costShareDao->setShareObjType_d($costShare, $object['moneyNoTax'], $object['taxPoint']);
                    } else {
                        $costShare = $costShareDao->setShareObjType_d($costShare);
                    }

                    // ���������һЩ����
                    if (is_array($costShare)) {
                        // ���Ż�ȡ
                        $deptDao = new model_deptuser_dept_dept();
                        $deptArr = $deptDao->getDeptList_d();

                        // ��Ա��ȡ
                        $userDao = new model_deptuser_user_user();

                        // ģ������תת��
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

                            // ������ڷ��óе��ˣ�����ֵ��Ϊ�գ���ȥ���Ҷ�Ӧ���˺�
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

            //���¸���������ϵ
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
     * ��ӷ����Լ�����Ʊ��¼
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
     * ��д�޸ķ���
     * @param $object
     * @return mixed
     */
    function edit_d($object)
    {
        $extRecord = array();
        $isReturnApply = 0;// �Ƿ�Ϊ���������ʾ
        if (isset($object['extRecord'])) {
            $extRecord = $object['extRecord'];
            unset($object['extRecord']);

            if (isset($extRecord['isReturnApply'])) {
                $isReturnApply = $extRecord['isReturnApply'];
                unset($extRecord['isReturnApply']);
            }
        }

        //���������ֵ��ֶ�
        $object = $this->processDatadict($object);

        // �Ƿ��б�֤������������ͬ�����ֶθ���,����Ĭ��ֵΪ���ǡ�,��������ϸ������Ͷ�����ѡ������
        $object['hasRelativeContract'] = ($object['isNeedRelativeContract'] == 1) ? $object['hasRelativeContract'] : null;

        // ��ʼ����ʱ�Ӻ�ؿ�����������ʽ�Ӻ�ؿ�����
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
     * �༭����
     * @param $object
     * @return mixed
     */
    function editInfo_d($object)
    {
        //��ȡ����������Ϣ
        $payapplyInfo = $object['payapply'];
        unset($object['payapply']);

        //��ȡ��̯��ϸ
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //���������ֵ��ֶ�
            $object = $this->processDatadict($object);

            $payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
            //������Ϣ����
            if ($object['isNeedPayapply'] == 1) {
                //���¸���������Ϣ
                $payapplyInfo['contractId'] = $object['id'];
                $payapplyInfo['contractType'] = $this->tbl_name;
                $payapplyInfoDao->dealInfo_d($payapplyInfo);
            } else {
                $object['isNeedPayapply'] = 0;
                //ɾ������������Ϣ
                $payapplyInfoDao->delete(array('contractId' => $object['id'], 'contractType' => $this->tbl_name));
            }

            //���÷�̯����
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

            //���¸���������ϵ
            $this->updateObjWithFile($object['id'], $object['orderCode']);

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ȡ��ͬ��Ϣ��������Ϣ
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
     * ��ȡ��ͬ���丶���Ʊ��Ϣ -- ������ʼ�����
     * @param $id
     * @return array
     */
    function getInfoAndPay_d($id)
    {
        $obj = parent::get_d($id);

        // ����Ǹ����ͬ����ȡֵ
        if ($obj['fundType'] == 'KXXZB') {
            //��ȡ����
            $payablesDao = new model_finance_payables_payables();
            $obj['payedMoney'] = bcadd($payablesDao->getPayedMoneyByPur_d($id, 'YFRK-02'), $obj['initPayMoney'], 2);

            //��ȡ��Ʊ
            $invotherDao = new model_finance_invother_invother();
            $obj['invotherMoney'] = bcadd($invotherDao->getInvotherMoney_d($id), $obj['initInvotherMoney'], 2);
        }

        if ($obj['fundType'] == 'KXXZB' || $obj['fundType'] == 'KXXZC') {
            // ��ȡ��̯��Ϣ
            $costShareDao = new model_finance_cost_costshare();
            $costShareInfo = $costShareDao->getShareInfo_d($id, 2);
            if ($costShareInfo) {
                $obj = array_merge($obj, $costShareInfo);
            }
        }

        return $obj;
    }

    /**
     * ɾ������
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

            // ���½��㵥
            if ($obj['projectType'] == 'QTHTXMLX-03' && $obj['projectId']) {
                $balanceDao = new model_flights_balance_balance();
                $balanceDao->updatebalanceStatus_d($obj['projectId'], 0);
            }

            // ɾ����̯��¼
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
     * �������������Ϣ
     * @param $obj
     * @return boolean
     */
    function stamp_d($obj)
    {
        $stampDao = new model_contract_stamp_stamp();
        try {
            $this->start_d();

            //��ȡ��Ӧ�����������κ�
            $maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name, " contractType = 'HTGZYD-02' and contractId=" .
                $obj['contractId'], "max(batchNo)");
            $obj['batchNo'] = intval($maxBatchNo) + 1;

            //����������Ϣ
            $obj['contractType'] = 'HTGZYD-02';
            $stampDao->addStamps_d($obj, true);

            //���º�ͬ�ֶ���Ϣ
            $this->edit_d(array('id' => $obj['contractId'], 'isNeedStamp' => 1, 'stampType' => $obj['stampType'], 'isStamp' => 0));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �������
     * @param $id
     * @return true
     * @throws $e
     */
    function end_d($id)
    {
        try {
            //�жϿ����Ƿ��Ѿ�����
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
     * �����ɹ����ڸ����б������Ϣ
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
            $this->update("id = {$objId}", array("delayPayDaysTemp" => $object['delayPayDays']));// ��ʼ���Ӻ�ؿ���������ʽ���������
            return true;
        }

        try {
            $this->start_d();

            if ($object['fundType'] == 'KXXZB' || $object['fundType'] == 'KXXZC') {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->setDataEffective_d($object['id'], 2);

                // �����ӳٻؿ�����
                $this->update("id = {$objId} and delayPayDaysTemp >= 0", array("delayPayDays" => $object['delayPayDaysTemp']));

                // ���÷�̯����ʼ�����
                $costShareDao->sendWaitingAuditMail_d($object['id'], $object['orderCode'], 2);
            }

            if ($object['isNeedStamp'] == "1" && $object['ExaStatus'] == AUDITED) {
                $applyId = '';
                // ����������������
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
                        "ExaStatus" => "���",
                        "fileName" => $object['orderName'],
                        "isNeedAudit" => 0,
                    );
                    $stampApplyArr['createId'] = $stampApplyArr['updateId'] = $userId;
                    $stampApplyArr['createName'] = $stampApplyArr['updateName'] = $userId == $object['createId'] ? $object['createName'] : $object['principalName'];
                    $stampApplyArr['createTime'] = $stampApplyArr['updateTime'] = date("Y-m-d H:i:s");
                    $applyId = $stampapplyDao->add_d($stampApplyArr);
                }

                // ������ͬ��������
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

            // �������ҵ������Ϊ����ѧԺ��,���͸����ʼ�
            if($object['payForBusiness'] == 'FKYWLX-08'){
                $addresses = "dengwei@ultrawise.com.cn,joe.wang@dinglicom.com";
                $auditContent = ($flowInfo['content'] == '')? '' : "���������{$flowInfo['content']}��<br /> &nbsp;&nbsp;&nbsp;&nbsp;";
                $mailContent = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;{$flowInfo['USER_NAME']}�Ѿ�����������Ϊ��{$flowInfo['task']} , �����ˣ�{$object['createName']}       �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������<font color='blue'>ͨ��</font><br />{$auditContent} &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;��ͬ��: {$object['orderCode']} , ��ͬ���� : {$object['orderName']}  , ǩԼ��˾ : {$object['signCompanyName']} , ��ͬ��� : {$object['orderMoney']} ";
                $mailContent = addslashes($mailContent);
                $title = "������ͬ����";
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
     * ������ͬ��������������ɹ�����
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
            $this->update("id = {$objId}", array("delayPayDaysTemp" => $object['delayPayDays']));// ��ʼ���Ӻ�ؿ���������ʽ���������
            return true;
        }

        try {
            $this->start_d();

            //��Ҫ�������趨
            $userName = $userId == $object['createId'] ? $object['createName'] : $object['principalName'];

            //�����Ҫ����
            if ($object['isNeedStamp'] == "1") {
                //��������
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

            // ���ִ���
            $currencyDao = new model_system_currency_currency();
            $currencyInfo = $currencyDao->getCurrencyInfo_d($object['currency']);
            //�������봦��
            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
            //����������������
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
                'ExaStatus' => '���',
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
                        'payDesc' => '������ͬ�������������',
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

            //�����ί�и��ֱ��¼�븶���
            if ($object['isEntrust']) {
                $payablesapplyArr['status'] = 'FKSQD-03';
                $payablesapplyArr['actPayDate'] = $object['formDate'];
                $payablesapplyArr['payedMoney'] = $object['applyMoney'];
            }

            $costShareDao = new model_finance_cost_costshare();
            $costShareDao->setDataEffective_d($object['id'], 2);

            // ���÷�̯����ʼ�����
            $costShareDao->sendWaitingAuditMail_d($object['id'], $object['orderCode'], 2);

            //���Ӹ������뵥
            $payablesapplyId = $payablesapplyDao->addOnly_d($payablesapplyArr);

            //��������Ϣ�����ƶ�������������
            $manageDao = new model_file_uploadfile_management();
            $fileInfo = $manageDao->findAll(Array('serviceId' => $objId, 'serviceType' => "oa_sale_otherpayapply"));
            if ($fileInfo) {// ���ļ�Ҳ�Ƶ����������� PMS2472
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

            //�����ί�и��ֱ��¼�븶���
            if ($object['isEntrust']) {
                //��ȡ�������뵥����
                $rows = $payablesapplyDao->getApplyAndDetailNew_d(array('ids' => $payablesapplyId));
                $addObj = $payablesapplyDao->initAddIngroupNew_d($rows);
                $payablesDao = new model_finance_payables_payables();
                $payablesDao->addInGroup_d($addObj['payables'], true);
            }

            // �����ӳٻؿ�����
            $this->update("id = {$objId} and delayPayDaysTemp >= 0", array("delayPayDays" => $object['delayPayDaysTemp']));

            // �������ҵ������Ϊ����ѧԺ��,���͸����ʼ�
            if($object['payForBusiness'] == 'FKYWLX-08'){
                $addresses = "dengwei@ultrawise.com.cn,joe.wang@dinglicom.com";
                $auditContent = ($flowInfo['content'] == '')? '' : "���������{$flowInfo['content']}��<br /> &nbsp;&nbsp;&nbsp;&nbsp;";
                $mailContent = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;{$flowInfo['USER_NAME']}�Ѿ�����������Ϊ��{$flowInfo['task']} , �����ˣ�{$object['createName']}       �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������<font color='blue'>ͨ��</font><br />{$auditContent} &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;��ͬ��: {$object['orderCode']} , ��ͬ���� : {$object['orderName']}  , ǩԼ��˾ : {$object['signCompanyName']} , ��ͬ��� : {$object['orderMoney']} ";
                $mailContent = addslashes($mailContent);
                $title = "������ͬ���������";
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
     * ��ҳС�ƴ���
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
            $newArr['createDate'] = '��ҳС��';
            $newArr['id'] = 'noId';
            $object[] = $newArr;
            return $object;
        }
        return false;
    }

    /***************** S ���ϵ�� *********************/
    /**
     * �������
     * @param $object
     * @return mixed
     */
    function change_d($object)
    {
        try {
            $this->start_d();

            //ʵ���������
            $changeLogDao = new model_common_changeLog('other');

            //���÷�̯����
            if ($object['costshare']) {
                $object['costshare'] = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['orderCode'], 'objType' => '2',
                        'company' => $object['businessBelong'], 'companyName' => $object['businessBelongName'],
                        'supplierName' => $object['signCompanyName'], 'currency' => $object['currency']
                    ),
                    $object['costshare']
                );
            }

            //��������
            $object['uploadFiles'] = $changeLogDao->processUploadFile($object, $this->tbl_name);

            // �Ƿ��б�֤������������ͬ�����ֶθ���,����Ĭ��ֵΪ���ǡ�,��������ϸ������Ͷ�����ѡ������
            if ($object['isNeedRelativeContract'] != 1) {
                unset($object['hasRelativeContract']);
            }

            //���������Ϣ
            $id = $changeLogDao->addLog($object);

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���±����¼�б��������/�񣩵��ֶ�����,ת���������ʾ
     * @param $tempId
     * @param $originalObj
     */
    function updateChangeDetailField($tempId, $originalObj)
    {
        // ��������¼��Ӧ�ֶε�ֵ
        $objId = $tempId;
        $chkChangeLogSql = "select * from oa_sale_other_changelog where objId = {$originalObj['id']} and tempId = {$objId};";
        $changeLog = $this->_db->getArray($chkChangeLogSql);
        $changeLog = ($changeLog) ? $changeLog[0] : array();

        // ��鲢ת�������¼�еġ��Ƿ������б������롾�Ƿ��б�֤������������ͬ����¼����ʾ��ʽ
        $chgLogDetailSql = "select id,oldValue,newValue,changeField from oa_sale_other_changelogdetail where objId = '{$changeLog['objId']}' and tempId = '{$changeLog['tempId']}' and changeField in ('isBankbackLetter','hasRelativeContract');";
        $chgLogDetailArr = $this->_db->getArray($chgLogDetailSql);

        if (!empty($chgLogDetailArr)) {
            foreach ($chgLogDetailArr as $v) {
                $id = $v['id'];
                $sql = "";
                switch ($v['changeField']) {
                    case 'isBankbackLetter':// �Ƿ������б�����1/0��
                        $oldVal = ($v['oldValue'] == 1) ? "��" : (($originalObj['payForBusiness'] == "FKYWLX-03" || $originalObj['payForBusiness'] == "FKYWLX-04") ? "��" : "");
                        $newVal = ($v['newValue'] == 1) ? "��" : (($originalObj['payForBusiness'] == "FKYWLX-03" || $originalObj['payForBusiness'] == "FKYWLX-04") ? "��" : "");
                        $sql = "update oa_sale_other_changelogdetail set oldValue='{$oldVal}',newValue='{$newVal}' where id = {$id}";
                        break;
                    case 'hasRelativeContract':// �Ƿ��б�֤������������ͬ��1/2��
                        $oldVal = ($v['oldValue'] == 1) ? "��" : "��";
                        $oldVal = ($v['oldValue'] == 'NULL') ? "" : $oldVal;
                        $newVal = ($v['newValue'] == 1) ? "��" : "��";
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
     * ���������ɺ���º�ͬ״̬
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

                            // ����������ʱ�޸����Ӻ�����,������ɺ�дԭ��ͬ,����ӱ����¼
                            $this->updateById($updateDaysArr);
                            if ($changeLog) {
                                $changeLog = $changeLog[0];
                                $addDetailSql = "INSERT INTO oa_sale_other_changelogdetail " .
                                    "SET parentId = '{$changeLog['id']}',objId = '{$changeLog['objId']}',detailTypeCn = '������ͬ',detailType = 'other',tempId = '{$changeLog['tempId']}'" .
                                    ",changeFieldCn='�Ӻ�ؿ�����',changeField='delayPayDays',oldValue='{$originalObj['delayPayDays']}',newValue='{$obj['delayPayDaysTemp']}';";
                                $this->_db->query($addDetailSql);
                            }
                        }

                        // �����������
                        if ($obj['bufferDays'] >= 0 && $obj['bufferDays'] != $originalObj['bufferDays']) {
                            $updateBufferDaysArr['id'] = $originalObj['id'];
                            $updateBufferDaysArr['bufferDays'] = $obj['bufferDays'];

                            // ����������ʱ�޸����Ӻ�����,������ɺ�дԭ��ͬ,����ӱ����¼
                            $this->updateById($updateBufferDaysArr);
                            if ($changeLog) {
                                $changeLog = $changeLog[0];
                                $addDetailSql = "INSERT INTO oa_sale_other_changelogdetail " .
                                    "SET parentId = '{$changeLog['id']}',objId = '{$changeLog['objId']}',detailTypeCn = '������ͬ',detailType = 'other',tempId = '{$changeLog['tempId']}'" .
                                    ",changeFieldCn='��������',changeField='bufferDays',oldValue='{$originalObj['bufferDays']}',newValue='{$obj['bufferDays']}';";
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

                    // ���÷�̯����ʼ�����
                    $costShareDao->sendWaitingAuditMail_d($obj['id'], $obj['orderCode'], 2);
                }

                //Դ��״̬����
                if ($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1) {//��Ҫ���¸���
                    //ֱ�����ø���״̬λ�������и��¼�¼�ر�
                    $this->update(
                        array('id' => $obj['originalId']),
                        array('status' => 2, 'isStamp' => 0, 'isNeedRestamp' => 0, 'isNeedStamp' => 0, 'stampType' => '')
                    );
                } elseif ($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0) {//���ڸ��µĴ���
                    $this->update(
                        array('id' => $obj['originalId']),
                        array('status' => 2, 'isStamp' => 0, 'isNeedRestamp' => 0, 'isNeedStamp' => 0, 'stampType' => '')
                    );
                    $stampDao = new model_contract_stamp_stamp();
                    $stampDao->closeWaiting_d($obj['originalId'], 'HTGZYD-02');
                } else {//�Ǹ��´���
                    $this->update(array('id' => $obj['originalId']), array('status' => 2, 'isNeedRestamp' => 0));
                }

                //�����ʼ�����
                $this->mailDeal_d('contractOtherChange', null, array('id' => $objId));

                $this->commit_d();
                return true;
            } catch (Exception $e) {
                $this->rollBack();
                return false;
            }
        } elseif ($obj['ExaStatus'] == BACK) {
            $changeLopUpdateSql = "update oa_sale_other_changelog set ExaStatus = '���',ExaDT = Now() where tempId = '{$objId}' and objId = '{$obj['originalId']}';";
            $this->query($changeLopUpdateSql);
            return $this->update(array('id' => $obj['originalId']), array('status' => 2, 'ExaStatus' => '���', 'delayPayDaysTemp' => ''));
        }
    }
    /***************** E ���ϵ�� *********************/

    /***************** S ǩ��ϵ�� *********************/
    /**
     * ��ͬǩ�� - ǩ�չ���
     * @param $object
     * @return mixed
     */
    function sign_d($object)
    {
        //ʵ���������
        $changeLogDao = new model_common_changeLog('otherSign');
        try {
            $this->start_d();

            //ԭ��ǩ��״̬����
            $signInfo = array(
                'signedDate' => day_date,
                'signedStatus' => 1,
                'signedMan' => $_SESSION['USERNAME'],
                'signedManId' => $_SESSION['USER_ID'],
                'id' => $object['oldId']
            );
            parent::edit_d($signInfo, true);

            //���ݴ���
            $object = $this->processDatadict($object);

            //��������
            $object['uploadFiles'] = $changeLogDao->processUploadFile($object, $this->tbl_name);

            //���������Ϣ
            $tempObjId = $changeLogDao->addLog($object);

            $changeObj = $object;
            $changeObj['id'] = $tempObjId;
            $changeObj['originalId'] = $changeObj['oldId'];

            //���ȷ��
            $changeLogDao->confirmChange_d($changeObj, null);

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /***************** E ǩ��ϵ�� *********************/

    /******************* S �޸ķ�̯��ϸ *****************/

    /**
     * �޸ķ�̯��ϸ
     * @param $object
     * @return mixed
     */
    function changeCostShare_d($object)
    {
        //ʵ���������
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

            $object['ExaStatus'] = '���';
            $object['ExaDT'] = day_date;
            $object['changeReason'] = 'ϵͳ��ʾ���������÷�̯��Ϣ';

            //���÷�̯����
            if ($object['costshare']) {
                $object['costshare'] = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['orderCode'], 'objType' => '2',
                        'company' => $object['businessBelong'], 'companyName' => $object['businessBelongName'],
                        'supplierName' => $object['signCompanyName'], 'currency' => $object['currency']
                    ),
                    $object['costshare']
                );
            }

            // �����ֵ�
            $this->processDatadict($object);

            //���������Ϣ
            $tempObjId = $changeLogDao->addLog($object);

            $changeObj = $object;
            $changeObj['id'] = $tempObjId;
            $changeObj['originalId'] = $changeObj['oldId'];

            //���ȷ��
            $changeLogDao->confirmChange_d($changeObj, null);

            if ($object['fundType'] == 'KXXZB' || $object['fundType'] == 'KXXZC') {
                $costShareDao = new model_finance_cost_costshare();
                $costShareDao->setDataEffective_d($object['oldId'], 2);

                // ���÷�̯����ʼ�����
                $costShareDao->sendWaitingAuditMail_d($object['oldId'], $object['orderCode'], 2);
            }

            // ������ʱID����״̬����Ϊ����������
            $this->update(array('id' => $tempObjId), array('ExaStatus' => '�޸ķ�̯��ϸ'));

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /******************* E �޸ķ�̯��ϸ *****************/

    /************************ ���������������֤ ********************/
    /**
     * ��֤����
     * @param $id
     * @return string
     */
    function canPayapply_d($id)
    {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        return $payablesapplyDao->isExistence_d($id, 'YFRK-02', 'back') ? 'hasBack' : '';
    }

    /**
     * �˿�������֤
     * @param $id
     * @return mixed
     */
    function canPayapplyBack_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'initPayMoney');
        //��ȡ�Ѹ�����(�����˿�)
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
    /************************ ���������������֤ ********************/

    /**
     * ��ȡ��ͬ��Ϣ - �����ֹ�˾
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
     * ��ȡ��ͬǷƱ���
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
     * ��ȡ��ͬ�Ŀɷ�̯���
     * @param $id
     * @return int
     */
    function getObjMoney_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'orderMoney,moneyNoTax,invoiceType');

        if ($obj) {
            // ��ѯ�����ֵ�
            $dataDictDao = new model_system_datadict_datadict();
            $dataDictObj = $dataDictDao->find(array('dataCode' => $obj['invoiceType']), null, 'expand3');

            // ���expand3��Ч���ҵ���1���򷵻ز���˰���
            return $dataDictObj['expand3'] == "1" ? $obj['moneyNoTax'] : $obj['orderMoney'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ��Ч�ķ�̯��ͬ
     * Ŀǰ��ֻ���˻�Ʊ�Ľ��
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
     * ͳ�Ʊ�֤����
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

            // ��δ��������ݽ������ڼ�鲢ͳ��
            $chkBeyondNeedPayArr = $this->chkBeyondNeedPayDateById(false, $result);
            foreach ($chkBeyondNeedPayArr as $v) {
                if ($v['isBeyond'] == 1) {
                    $totalMoney['needPayBeyond'] = bcadd($totalMoney['needPayBeyond'], $v['needInvotherMoney'], 2);
                    $needPayBeyondIds .= ($needPayBeyondIds != '') ? "," . $v['id'] : $v['id'];
                }
            }

            if(count($result) > 300){// ����¼����300����ʱ��,��Session����ַ���
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
     * �����Ӧ�����Ƿ�Ϊ����δ����ĺ�ͬ�������ID��������붼�Ǹ�����ĺ�ͬ,�������ݿ�������
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
                left join oa_finance_payablesapply p on p.exaId = c.id and p.exaCode = 'oa_sale_other' and p.ExaStatus = '���'
                where 1=1 and c.id = {$objId} and c.fundType = 'KXXZB' and c.isTemp = 0;";
            $result = $this->_db->getArray($sql);
            $chkRows = ($result) ? $result : array();
        } else if (is_array($rows) && !empty($rows)) {
            $chkRows = $rows;
        }

        foreach ($chkRows as $k => $v) {
            $chkRows[$k]['today'] = $today = date('Y-m-d');
            $deadline = "";
            // ��ȡ��Ӧ���͵Ļ�����������
            switch ($v['payForBusiness']) {
                case "FKYWLX-03":// Ͷ�걣֤�� ����6���£�
                    $deadlineDate = ($v['isBankbackLetter'] == '1') ? $v['backLetterEndDate'] : $v['prefBidDate'];
                    $deadline = date('Y-m-d', strtotime("$deadlineDate +3 month"));
                    break;
                case "FKYWLX-04":// ��Լ��֤�� �����Ӻ�ؿ�������
                    $deadlineDate = ($v['isBankbackLetter'] == '1') ? $v['backLetterEndDate'] : $v['projectPrefEndDate'];
                    $deadlineDate = $v['projectPrefEndDate'];
                    $dateTime = strtotime($deadlineDate);
                    $days = bcadd($v['delayPayDays'],$v['bufferDays'],0);
                    $dateTime += ($days * 60 * 60 * 24);
                    $deadline = date("Y-m-d", $dateTime);
                    break;
                case "FKYWLX-05":// Ѻ��
                    $deadline = $v['prefPayDate'];
                    break;
                case "FKYWLX-06":// Ͷ������
                    $deadline = $v['actPayDate'];
                    break;
                case "FKYWLX-07":// ����������
                    $deadline = $v['prefBidDate'];
                    break;
            }

            // ��󻹿�����
            $chkRows[$k]['deadline'] = $deadline;

            // ����ǷƱ����Լ����������ж��Ƿ�����δ����
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

    /****************************    �ʼ�Ԥ������   ******************************/

    /**
     * Ԥ���ʼ����� - Ͷ�걣֤��
     * @param $params
     */
    function warningMail($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // û�����ڣ�ֱ������
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);
        $mailContent['months'] = $overMonth; // �����·�
        $users = array($obj['principalId']);// �ʼ������û�

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // ��������� 1 ~ 2 ���£�����Ԥ��֪ͨ
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - ��Լ��֤��
     * @param $params
     */
    function warningMail2($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
//        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
//        $overMonth = $overMonth > 3 ? 3 : $overMonth;
//
//        // �����3�£������Ӻ�ؿ������ļ���
//        if ($overMonth == 3) {
//            $overMonth = ((strtotime(day_date) - strtotime($params['judgeDate'])) / 86400) >= $obj['delayPayDays'] ?
//                3 : 2;
//        }
//
//        // û�����ڣ�ֱ������
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
        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);
        $mailContent['months'] = $overMonth; // �����·�
        $users = array($obj['principalId']);// �ʼ������û�

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // �жϸú�ͬ�Ƿ��ѷ��͹���Ӧ���ʼ�֪ͨ
        $noticeMailed = $warningMailed = false;
        if (isset($logMap['warningMail2notice']) || $logMap['warningMail2notice']) {
            $noticeMailed = true;
        }

        if (isset($logMap['warningMail2warning']) || $logMap['warningMail2warning']) {
            $warningMailed = true;
        }

        // ��������� 1 ~ 2 ���£�����Ԥ��֪ͨ
        if (strtotime($noticeDate) - strtotime($today) <= 0 && !$noticeMailed) {
            $logMap['warningMail2notice'] = 1;
            $logDao->setMap_d($id, $logMap);
            $mailContent['months'] = 1;
            $this->mailDeal_d('otherWarningMail2', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if (strtotime($warningDate) - strtotime($today) <= 0 && !$warningMailed) {
            $logMap['warningMail2warning'] = 1;
            $logDao->setMap_d($id, $logMap);
            // ���� 3���£���������֪ͨ
            $mailContent['days'] = $obj['delayPayDays'];
            $mailContent['months'] = 2;

            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail2_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - Ͷ�걣֤�����б���
     * @param $params
     */
    function warningMail3($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // û�����ڣ�ֱ������
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);

        // �����·�
        $mailContent['months'] = $overMonth;

        // �ʼ������û�
        $users = array($obj['principalId']);

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['dateField'] = 'Ԥ��Ͷ��ʱ��'; // empty($params['backLetterEndDate']) ? 'Ԥ��Ͷ��ʱ��' : '������������';
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // ��������� 1 ~ 2 ���£�����Ԥ��֪ͨ
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail3', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            // ���� 6���£���������֪ͨ
            $this->mailDeal_d('otherWarningMail3_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - ��Լ��֤�����б���
     * @param $params
     */
    function warningMail4($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

//        // ���������·�
//        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
//        $overMonth = $overMonth > 3 ? 3 : $overMonth;
//
//        // û�����ڣ�ֱ������
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

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // �����·�
        $mailContent['months'] = 1;//$overMonth;

        $noticeDays = bcadd($params['bufferDays'],30);
        $warningDays = bcadd($params['bufferDays'],60);
        $today = date("Y-m-d");
        $noticeDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$noticeDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $warningDate = ($params['projectPrefEndDate'] != '')? date('Y-m-d',strtotime("+{$warningDays} day",strtotime($params['projectPrefEndDate']))) : '';
        $overMonth = 1;

        // �ʼ������û�
        $users = array($obj['principalId']);

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['dateField'] = '��ĿԤ�ƽ�������'; // empty($params['backLetterEndDate']) ? '��ĿԤ�ƽ�������' : '������������';

        // �жϸú�ͬ�Ƿ��ѷ��͹���Ӧ���ʼ�֪ͨ
        $noticeMailed = $warningMailed = false;
        if (isset($logMap['warningMail4notice']) || $logMap['warningMail4notice']) {
            $noticeMailed = true;
        }
        if (isset($logMap['warningMail4warning']) || $logMap['warningMail4warning']) {
            $warningMailed = true;
        }

        // ��������� 1 ~ 2 ���£�����Ԥ��֪ͨ
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
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail4_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - Ѻ��
     * @param $params
     */
    function warningMail5($params)
    {
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 3 ? 3 : $overMonth;

        // û�����ڣ�ֱ������
        if (!$overMonth) {
            return;
        }

        if (isset($logMap[$overMonth]) || $logMap[$overMonth]) {
            return;
        } else {
            $logMap[$overMonth] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // �ʼ������û�
        $users = array($obj['principalId']);

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        // �����·�
        $mailContent['months'] = $overMonth;

        // ��������� 1 ~ 2 ���£�����Ԥ��֪ͨ
        if ($overMonth <= 2) {
            $this->mailDeal_d('otherWarningMail5', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        } else if ($overMonth == 3) {
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];

            $this->mailDeal_d('otherWarningMail5_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - Ͷ������
     * @param $params
     */
    function warningMail6($params){
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 1 ? 1 : $overMonth;

        // û�����ڣ�ֱ������
        if (!$overMonth) {
            return;
        }

        if (isset($logMap['warningMail6']) || $logMap['warningMail6']) {
            return;
        } else {
            $logMap['warningMail6'] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // �����·�
        $mailContent['months'] = $overMonth;

        // �ʼ������û�
        $users = array($obj['principalId']);

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['needInvotherMoney'] = isset($params['needInvotherMoney']) ? $params['needInvotherMoney'] : 0;

        // ��������� 1 ���£�����Ԥ��֪ͨ
        if ($overMonth == 1 && $mailContent['needInvotherMoney'] > 0) {
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail6_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * Ԥ���ʼ����� - ����������
     * @param $params
     */
    function warningMail7($params){
        $id = $params['id'];
        if (!$id) {
            return;
        }

        // ��ʼ��������ͬ�õ���־��
        $logDao = new model_contract_other_mailLog();
        $logMap = $logDao->getMap_d($id);

        // ���ݻ�ȡ
        $obj = $this->get_d($id);

        // ���������·�
        $overMonth = $this->calculateOverMonth_d($params['judgeDate']);
        $overMonth = $overMonth > 1 ? 1 : $overMonth;

        // û�����ڣ�ֱ������
        if (!$overMonth) {
            return;
        }

        if (isset($logMap['warningMail7']) || $logMap['warningMail7']) {
            return;
        } else {
            $logMap['warningMail7'] = 1;
            $logDao->setMap_d($id, $logMap);
        }

        // �ʼ����ݴ���
        $mailContent = array('orderCode' => $obj['orderCode'], 'orderMoney' => $obj['orderMoney']);
        $mailTitleExa = array('orderCode' => $obj['orderCode'], 'orderName' => $obj['orderName']);

        // �����·�
        $mailContent['months'] = $overMonth;

        // �ʼ������û�
        $users = array($obj['principalId']);

        // ������Ա��ȡ
        $otherDatasDao = new model_common_otherdatas();
        $financeUserId = $otherDatasDao->getConfig('otherWarningFinanceUserId_' . $obj['businessBelong']);
        $users[] = $financeUserId;

        $mailContent['needInvotherMoney'] = isset($params['needInvotherMoney']) ? $params['needInvotherMoney'] : 0;

        // ��������� 1 ���£�����Ԥ��֪ͨ
        if ($overMonth == 1 && $mailContent['needInvotherMoney'] > 0) {
            // ��ȡ�����˲��ŵĵ��ܼ�͸���
            $userInfo = $otherDatasDao->getUserDatas($obj['principalId'], array('ViceManager', 'MajorId'));
            if ($userInfo['ViceManager']) $users[] = $userInfo['ViceManager'];
            if ($userInfo['MajorId']) $users[] = $userInfo['MajorId'];
            $this->mailDeal_d('otherWarningMail7_overdue', implode(',', $users), $mailContent,null,false,null,$mailTitleExa);
        }
    }

    /**
     * �������ڼ��������·�
     * @param $judgeDate
     * @return int
     */
    function calculateOverMonth_d($judgeDate)
    {
        return floor((strtotime(day_date) - strtotime($judgeDate)) / 86400 / 30);
    }

    /****************************    �ʼ�Ԥ������   ******************************/

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
					(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '���' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
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

            // �ʼ�����
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
     * �ʼ�����
     * @param $obj
     */
    function sendMail_d($obj)
    {
        $emailDao = new model_common_mail();
        $emailDao->mailGeneral("������ͬ�ؿ�֪ͨ", $obj['mailUserId'], $obj['mailContent']);
    }

    /**
     * �����������ϸ���
     * @param $mailContent
     * @param $data
     * @return mixed
     */
    function buildMailItem_d($mailContent, $data)
    {
        //��ʼ����ϸ���
        $detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center><tr>";
        $detailStr .= "<td>��ͬ���</td><td>��ͬ������</td></tr>";
        //��������Ⱦ
        foreach ($data as $key => $val) {
            //ÿ����¼����󣬱��뻻��
            $detailStr .= "<tr><td>$val[orderCode]</td><td>$val[principalName]</td></tr>";
        }
        $detailStr .= "</tr></table>";

        //���شӱ�����
        $mailContent = str_replace('!itemTable!', $detailStr, $mailContent);

        return $mailContent;
    }
}