<?php
/**
 * ����model����
 */
class model_finance_income_income extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_income";
        $this->sql_map = "finance/income/incomeSql.php";
        parent :: __construct();
    }

    /********************�²��Բ���ʹ��************************/
    private $relatedStrategyArr = array( //��ͬ����������������,������Ҫ���������׷��
        'YFLX-DKD' => 'model_finance_income_strategy_income', //���
        'YFLX-YFK' => 'model_finance_income_strategy_prepayment', //Ԥ�տ�
        'YFLX-TKD' => 'model_finance_income_strategy_refund' //�˿
    );

    private $relatedCode = array(
        'YFLX-DKD' => 'income',
        'YFLX-YFK' => 'prepayment',
        'YFLX-TKD' => 'refund'
    );

    /**
     * �������ͷ���ҵ������
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * �����������ͷ�����
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    /***************************************************************************************************
     * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
     *************************************************************************************************/

    /**
     * ��ӵ���������
     */
    function add_d($income, iincome $strategy) {
        $codeRuleDao = new model_common_codeRule();
        $emailArr = null;
        try {
            $this->start_d();

            $incomeAllot = $income['incomeAllots'];
            unset($income['incomeAllots']);

            //�Զ����������
            if ($income['formType'] == 'YFLX-TKD') {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'ST');
            } else {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'SK');
            }

            //״̬����
            if (isset($income['sectionType']) && $income['sectionType'] == 'DKLX-FHK') {
                $income['status'] = 'DKZT-FHK';
            } else {
                $income['status'] = $this->changeStatus($income, 'val');
            }

            //�ʼ���Ϣ��ȡ
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
                //���������¼
                $logSettringDao = new model_syslog_setting_logsetting ();
                $logSettringDao->addObjLog($this->tbl_name, $incomeId, $income, '¼���˿�');
            } else {
                //���������¼
                $logSettringDao = new model_syslog_setting_logsetting ();
                $logSettringDao->addObjLog($this->tbl_name, $incomeId, $income, '¼�뵽��');
            }
            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
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
     * ������������
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

            //�ʼ���Ϣ��ȡ
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

            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
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
     * ���������޸Ķ���
     */
    function edit_d($object) {
        $object['status'] = $object['sectionType'] == 'DKLX-FHK' ? 'DKZT-FHK' : 'DKZT-WFP'; // ״̬�ж�

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

            //���²�����־
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '�༭����');

            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
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
     * ���������޸Ķ���
     */
    function editEasy_d($object) {
        try {
            $this->start_d();

            $oldObj = parent::get_d($object['id']);

            parent::edit_d($object, true);

            $this->commit_d();

            //���²�����־
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '�༭����');

            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        };
    }

    /**
     * ����������ĵ���״̬
     */
    function changeStatus($income, $rsType = 'act') {
        if ($rsType == 'act') {
            if ($income['allotAble'] == 0) {
                $income['status'] = 'DKZT-YFP';
                //�رյ����ʼ�
                $mailRecordDao = new model_finance_income_mailrecord();
                $mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
            } else if ($income['allotAble'] == $income['incomeMoney']) {
                $income['status'] = 'DKZT-WFP';
                //���������ʼ�
                $mailRecordDao = new model_finance_income_mailrecord();
                $mailRecordDao->closeMailrecordByIncomeId_d($income['id'], 0);
            } else {
                $income['status'] = 'DKZT-BFFP';
                //�رյ����ʼ�
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
     * ��ȡ����Ϣ�ʹӱ���Ϣ
     */
    function getInfoAndDetail_d($id) {
        $rs = $this->get_d($id);
        $incomeAllotDao = new model_finance_income_incomeAllot();
        $rs['incomeAllot'] = $incomeAllotDao->getAllotsByIncomeId($id);
        return $rs;
    }

    /**
     * �������
     */
    function allot_d($object) {
        $incomeAllotDao = new model_finance_income_incomeAllot();
        //��ȡԭʼ�ӱ�����
        $rs = $incomeAllotDao->findAll(array('incomeId' => $object['id']), null, 'objId,objType');

        try {
            $this->start_d();

            $oldObj = parent::get_d($object['id']);
            $incomeAllot = $object['incomeAllots'];
            unset($object['incomeAllots']);

            $contractArr = array(); // ��ͬid
            //����ӱ�
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

            //ҵ����Ϣ����
            $incomeAllotDao->businessDeal_d($incomeAllot, $rs);

            //�ؿ�������� create on 2013-08-14 by kuangzw
            $incomeCheck = $object['incomeCheck'];
            unset($object['incomeCheck']);
            // ��������ں�����¼�ҷ�̯����Ϊ��ͬ����ϵͳȥ�Զ�ƥ��ɺ�����������
            if (!empty($dealAllot) && empty($incomeCheck) && !empty($contractArr)) {
                //ʵ��������������
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d($contractArr);
            }
            if ($incomeCheck) {
                //��������
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(array('incomeId' => $object['id'], 'incomeNo' => $object['incomeNo']), $incomeCheck);
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            //�ı�Դ��״̬
            $this->changeStatus($object);

            $this->commit_d();

            //���²�����־
            $logSettringDao = new model_syslog_setting_logsetting ();
            $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object, '�������');

            // ִ�й켣��¼
            if(!empty($dealAllot)){
                $conDao = new model_contract_contract_contract();
                foreach($dealAllot as $v){
                    $trackArr['contractCode'] = $v['objCode'];
                    $trackArr['contractId'] = $v['objId'];$trackArr['modelName'] = 'incomeMoney';
                    $trackArr['operationName'] = '�����¼';$trackArr['result'] = $v['money'];
                    $trackArr['expand1'] = $v['id'];$trackArr['remarks'] = 'expand1Ϊ��Ӧ������䵥��ID';
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
     * ��ҳС�Ƽ���
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
            $newArr['incomeNo'] = '��ҳС��';
            $newArr['id'] = 'noId';
            $object[] = $newArr;
            return $object;
        }
    }

    /**
     * ɾ��
     */
    function deletes_d($id) {
        $incomeAllotDao = new model_finance_income_incomeAllot();
//		//��ȡԭʼ�ӱ�����
        $rs = $incomeAllotDao->findAll(array('incomeId' => $id), null, 'objId,objType');
        //��־
        $logSettringDao = new model_syslog_setting_logsetting ();
        try {
            $this->start_d();
            //ɾ�����������־
            $logSettringDao->deleteObjLog($this->tbl_name, parent::get_d($id));

            $this->deletes($id);

            //ҵ��������� - ����
            $incomeAllotDao->businessDeal_d($rs);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**************************************����벿��*********************************/

    /**
     * ����빦��
     */
    function addExecelData_d($isCheck = 1) {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array(); //�������
        $contArr = array(); //��ͬ��Ϣ����
        $contDao = new model_contract_contract_contract();
        $customerArr = array(); //�ͻ���Ϣ����
        $customerDao = new model_customer_customer_customer();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                $status = $isCheck == 1 ? 'DKZT-YFP' : $status = 'DKZT-WFP'; // ����״̬�ж�
                //������ѭ��
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
                            //�жϵ�������
                            $incomeDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[0] - 1, 1900)));
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û���տ�����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($isCheck == 1) {
                            if (!empty($val[2])) {
                                //������ͬ��������
                                $contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->getContractInfoByCode($val[2], 'main');
                                if (is_array($contArr[$val[2]])) {
                                    $orderCode = $val[2];
                                    $orderId = $contArr[$val[2]]['id'];
                                    $rObjCode = $contArr[$val[2]]['objCode'];
                                    $orderType = 'KPRK-12';
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!û�к�ͬ��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $orderCode = $val[2];
                            $orderId = $orderType = $rObjCode = "";
                        }

                        if (!empty($val[1])) {
                            //�ͻ�����
                            if (!isset($customerArr[$val[1]])) {
                                $rs = $customerDao->findCus($val[1]);
                                if (is_array($rs)) {
                                    $customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
                                    $prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
                                    $customerName = $val[1];
                                    $customerType = $customerArr[$val[1]]['typeOne'] = $rs[0]['TypeOne'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�ͻ�ϵͳ�в����ڴ˿ͻ�';
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
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�пͻ�����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[3]) && $val[3] * 1 == $val[3] && $val[3] != 0) {
                            //�жϵ�����
                            $incomeMoney = $val[3];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�е�������ߵ�����Ϊ0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //������˾
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
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!������˾���ͬ��Ӧ��˾����ͬ';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڵĹ�����˾';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û��¼�������˾';
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
                                'remark' => 'ϵͳ��������',
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
                                    'remark' => 'ϵͳ��������',
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
                                    'remark' => 'ϵͳ��������',
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
                            $tempArr['result'] = '����ɹ�';
                            $tempArr['docCode'] = '��' . $actNum . '������';
                        } else {
                            $tempArr['result'] = '����ʧ��';
                            $tempArr['docCode'] = '��' . $actNum . '������';
                        }
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ��ӵ���������
     */
    function addForExcel_d($income) {
        $codeRuleDao = new model_common_codeRule();
        $emailArr = null;
        try {
            $this->start_d();

            $incomeAllot = $income['incomeAllots'];
            unset($income['incomeAllots']);

            //�Զ����������
            if ($income['formType'] == 'YFLX-TKD') {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'ST');
            } else {
                $income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'SK');
            }

            $incomeId = parent :: add_d($income, true);

            if (is_array($incomeAllot)) {
                $incomeAllotDao = new model_finance_income_incomeAllot();
                $incomeAllotDao->createBatch($incomeAllot, array('incomeId' => $incomeId));
                //ҵ����Ϣ����
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