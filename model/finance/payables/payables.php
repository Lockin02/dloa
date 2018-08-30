<?php

/**
 * @author Show
 * @Date 2011��5��6�� ������ 16:17:38
 * @version 1.0
 * @description:Ӧ�����/Ӧ��Ԥ����/Ӧ���˿ Model��
 */
class model_finance_payables_payables extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_payables";
        $this->sql_map = "finance/payables/payablesSql.php";
        parent::__construct();
    }

    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    /********************�²��Բ���ʹ��************************/
    private $relatedStrategyArr = array(//��ͬ����������������,������Ҫ���������׷��
        'CWYF-01' => 'model_finance_payables_strategy_spayment', //���
        'CWYF-02' => 'model_finance_payables_strategy_advances', //Ԥ����
        'CWYF-03' => 'model_finance_payables_strategy_refund' //�˿
    );

    private $relatedCode = array(
        'CWYF-01' => 'spayment',
        'CWYF-02' => 'advances',
        'CWYF-03' => 'refund'
    );

    /**
     * ������ת��
     */
    private $relateName = array(
        'CWYF-01' => '���',
        'CWYF-02' => 'Ԥ����',
        'CWYF-03' => '�˿'
    );

    /**
     * �������ͷ���ҵ������
     * @param $objType
     * @return mixed
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * �����������ͷ�����
     * @param $objType
     * @return mixed
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    /**
     * ����ҵ��id�����ͻ�ȡ�����Ϣ
     * @param $objId
     * @param ipayables $strategy
     * @return mixed
     */
    public function getObjInfo_d($objId, ipayables $strategy) {
        return $strategy->getInfoAndDetail_d($objId);
    }

    /**
     * ������������
     * @param $objType
     * @return mixed
     */
    function rtFormName($objType) {
        return $this->relateName[$objType];
    }

    // �����Ƿ�
    function rtYesOrNo_d($thisVal) {
        if ($thisVal == '1') {
            return '��';
        } else {
            return '��';
        }
    }

    /**********************�ⲿ���ýӿ�**************************/
    /**
     * ��дadd_d
     * @param $object
     * @param ipayables $strategy
     * @return bool
     */
    function add_d($object, ipayables $strategy) {
        $codeRuleDao = new model_common_codeRule();
        try {
            $this->start_d();

            //������ϸ
            $detail = $object['detail'];
            unset($object['detail']);

            //�����ʼ�
            if (isset($object['email'])) {
                $emailArr = $object['email'];
                unset($object['email']);
            }

            //��������״̬
            if (isset($object['pushDown'])) {
                $pushDown = $object['pushDown'];
                unset($object['pushDown']);
            }

            //�Զ����������
            if ($object['formType'] == 'CWYF-01') {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKJL');
            } else if ($object['formType'] == 'CWYF-02') {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_k', 'DL', 'FK');
            } else {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'FT');
            }
			// ��λ�ҽ��Ĭ�ϵ��ڵ��ݽ��
			if(empty($object['amountCur'])){
				$object['amountCur'] = $object['amount'];
			}
            $newId = parent:: add_d($object, true);

            if ($object['payApplyNo']) {
                $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                $updateArr = array('status' => 'FKSQD-03', 'payedMoney' => $object['amount'], 'actPayDate' => $object['formDate']);
                $updateArr = $this->addUpdateInfo($updateArr);
                $payablesapplyDao->update(array('id' => $object['payApplyId']), $updateArr);

                if ($object['sourceType'] == 'YFRK-01') {
                    $payablesapplyDao->getMailDetail_d($object['payApplyId']);
                }
            }

            /**
             * �ӱ���Ϣ¼��
             */
            if (!empty($detail)) {
                $payablesDetailDao = new model_finance_payables_detail();
                $payablesDetailDao->createBatch($detail, array('advancesId' => $newId));
            }

            /**
             * Դ�����ݷ�д
             */
            if (!empty($detail)) {
                $objArr = array();
                foreach ($detail as $val) {
                    if ($val['objType'] == 'YFRK-01' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_purchase_contract_purchasecontract();
                        }
                        $objArr[$val['objType']]->end_d($val['objId']);
                    }
                    //����� ��д������ͬ����
                    if ($val['objType'] == 'YFRK-02' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_contract_other_other();
                        }
                        $objArr[$val['objType']]->end_d($val['objId']);
                    }
                    //����� ��д�⳵��ͬ
                    if ($val['objType'] == 'YFRK-06' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_outsourcing_vehicle_register();
                        }
                        $objArr[$val['objType']]->dealAfterPay_d(
                            array(
                                array(
                                    'id' => $val['expand1'],
                                    'money' => $val['money'],
                                    'payType' => 1
                                )
                            )
                        );
                    }
                }
            }

            //���ƴ���
            if (isset($pushDown)) {
                if ($this->checkIsRefundAll_d($object['belongId'])) {
                    //�����״̬�ı�
                    $this->pushDown_d($object['belongId']);

                    //��ȡ���뵥id
                    $orgObj = $this->find(array('id' => $object['belongId']), null, 'payApplyId, payApplyNo');
                    if ($orgObj['payApplyId']) {
                        //�������뵥��״̬�ı�
                        $payablesapplyDao = isset($payablesapplyDao) ? $payablesapplyDao : new model_finance_payablesapply_payablesapply();
                        $payablesapplyDao->applyRefund_d($orgObj['payApplyId']);
                    }
                }
            }

            if (isset($emailArr)) {
                if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    if ($object['formType'] == 'CWYF-03') {
                        // �ʼ�����
                        $mailInfo = Array('id' => $object['id'],
                            'mailUser' => $emailArr['TO_NAME'], 'formName' => $this->rtFormName($object['formType']),
                            'formNo' => $object['formNo'], 'supplierName' => $object['supplierName'],
                            'amount' => $object['amount'], 'payApplyNo' => '', 'payApplyId' => '',
                            'refundReason' => $emailArr['REFUNDREASON']);
                        // �����˿�ʱ����ȡԴ���ĸ���������Ϣ
                        if ($object['belongId'] && isset($orgObj)) {
                            $mailInfo['payApplyNo'] = $orgObj['payApplyNo'];
                            $mailInfo['payApplyId'] = $orgObj['payApplyId'];
                        }

                        $this->mailDeal_d('refundMail', $emailArr['TO_ID'], $mailInfo, $emailArr['ADDNAMES']);
                    } else {
                        $this->mailDeal_d('payMail', $emailArr['TO_ID'], Array('id' => $object['id'],
                            'mailUser' => $emailArr['TO_NAME'], 'formName' => $this->rtFormName($object['formType']),
                            'formNo' => $object['formNo'], 'supplierName' => $object['supplierName'],
                            'amount' => $object['amount'], 'payApplyNo' => $object['payApplyNo'],
                            'payApplyId' => $object['payApplyId']), $emailArr['ADDNAMES']);
                    }
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
     * ���������������ɸ�����Ϣ
     * @param $object
     * @param bool $isEntrust
     * @return bool
     */
    function addInGroup_d($object, $isEntrust = false) {
        $codeRuleDao = new model_common_codeRule();
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payablesDetailDao = new model_finance_payables_detail();

        //�ʼ�����
        $emailArr = array();
        //ʧ�����黺��
        $failureArr = array();

        foreach ($object as $val) {
            //�ʼ������ַ���
            $applyMailDetail = null;
            //�ʼ�����
            $mailDetail = null;

            try {
                $this->start_d();

                //ת�ƴӱ���Ϣ
                $detail = $val['detail'];
                unset($val['detail']);

                //ת���ʼ���Ϣ
                if (isset($val['email'])) {
                    $mailDetail = $val['email'];
                    unset($val['email']);
                }

                //�Զ����������
                if ($val['formType'] == 'CWYF-01') {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKJL');
                } else if ($val['formType'] == 'CWYF-02') {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_k', 'DL', 'FK');
                } else {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'FT');
                }

                $newId = parent:: add_d($val, true);

                //���µ��ݸ���״̬
                if ($val['payApplyNo']) {
                    $updateArr = array('status' => 'FKSQD-03', 'payedMoney' => $val['amount'], 'actPayDate' => day_date);
                    $updateArr = $this->addUpdateInfo($updateArr);
                    $payablesapplyDao->update(array('id' => $val['payApplyId']), $updateArr);
                }

                //��������ӱ���Ϣ
                if (!empty($detail)) {
                    $payablesDetailDao->createBatch($detail, array('advancesId' => $newId));
                }

                /**
                 * ������ɺ�رն���
                 */
                if (!empty($detail)) {
                    $objArr = array();
                    foreach ($detail as $innerVal) {
                        if ($innerVal['objType'] == 'YFRK-01' && !empty($innerVal['objId']) && $innerVal['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_purchase_contract_purchasecontract();
                            }
                            $objArr[$val['objType']]->end_d($innerVal['objId']);
                        }
                        //����� ��д������ͬ����
                        if ($innerVal['objType'] == 'YFRK-02' && !empty($innerVal['objId']) && $innerVal['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_contract_other_other();
                            }
                            $objArr[$val['objType']]->end_d($innerVal['objId']);
                        }
                        //����� ��д�⳵��ͬ
                        if ($val['objType'] == 'YFRK-06' && !empty($val['objId']) && $val['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_outsourcing_vehicle_register();
                            }
                            $objArr[$val['objType']]->dealAfterPay_d(
                                array(
                                    array(
                                        'id' => $val['expend1'],
                                        'money' => $val['money'],
                                        'payType' => 1
                                    )
                                )
                            );
                        }
                    }
                }

                $this->commit_d();

                // ��������¸����֪ͨ���ض���Ա
                if (!$isEntrust) {
                    //��������ɹ�,�����ʼ���Ϣ
                    if ($val['payApplyNo']) {
                        if ($val['sourceType'] == 'YFRK-01') {
                            $mailDetail['applyMailDetail'] = $payablesapplyDao->getMailDetail_d($val['payApplyId']);
                        }
                    }

                    //��ȡ��Ҫ��������Ϣ
                    $mailDetail['formNo'] = $val['formNo'];
                    $mailDetail['supplierName'] = $val['supplierName'];
                    $mailDetail['amount'] = $val['amount'];
                    $mailDetail['payApplyNo'] = $val['payApplyNo'];
                    $mailDetail['payApplyId'] = $val['payApplyId'];
                    $mailDetail['formType'] = $val['formType'];
                    $mailDetail['detail'] = $detail;
                    //�ɹ��󻺴浽�ʼ�����
                    if (isset($emailArr[$mailDetail['TO_ID']])) {
                        array_push($emailArr[$mailDetail['TO_ID']], $mailDetail);
                    } else {
                        $emailArr[$mailDetail['TO_ID']][0] = $mailDetail;
                    }
                } else {
                    $this->mailDeal_d("payApplyEntrust", "", array('id' => $val['payApplyId']));
                }
            } catch (Exception $e) {
                $this->rollBack();

                //������ڵ���ʧ��,�򻺴���ʧ��������
                array_push($failureArr, $val);
            }
        }

        //�����ʼ� ,������Ϊ�ύʱ�ŷ���
        if (!empty($emailArr) && !$isEntrust) {
            $this->thisMailBatch_d($emailArr);
        }
        return true;
    }

    /**
     * �ʼ����� - �Ը���Ϊ��λ�����ʼ�
     * @param $emailArr
     */
    function thisMailBatch_d($emailArr) {
        $emailDao = new model_common_mail();

        foreach ($emailArr as $val) {
            $str = '���ã�<br/>';
            $str .= $_SESSION['USERNAME'] . "¼�������¼�¼:<br/><br/>";
            $TO_ID = null;
            $i = 0;
            $title = null;

            //ѭ��,�������������ʼ�
            foreach ($val as $v) {
                $i++;
                $formName = $this->rtFormName($v['formType']);
                $str .= "<font color='blue'>[ " . $formName . " ]</font>:" . $v['formNo'] . "<br/>";

                if ($v['formType'] == 'CWYF-03') {
                    $title = '�˿���Ϣ';
                    $str .= '��ϸ��Ϣ���� �� <br/> �˿λ��' . $v['supplierName'] . ' , �˿�� ' . $v['amount'] . ' , ���뵥��Ϊ:' . $v['payApplyNo'] . ' , ���뵥idΪ:' . $v['payApplyId'] . '<br/>';
                } else {
                    $title = '������Ϣ';
                    $str .= '��ϸ��Ϣ���� �� <br/> ���λ��' . $v['supplierName'] . ' , ����� ' . $v['amount'] . ' , ���뵥��Ϊ:' . $v['payApplyNo'] . ' , ���뵥idΪ:' . $v['payApplyId'] . '<br/>';
                }

                if (empty($TO_ID)) {
                    $TO_ID = $v['TO_ID'];
                }

                if (empty($v['applyMailDetail'])) {
                    $str .= $this->thisMailDetail_d($v['detail']);
                } else {
                    $str .= $v['applyMailDetail'];
                }

                $str .= "<br/>";
            }
            $emailDao->mailClear($title . '(' . $i . ')', $TO_ID, $str);
        }
    }

    /**
     * ��дget_d
     * @param $id
     * @param string $getType
     * @param bool $isInit
     * @return bool|mixed
     */
    function get_d($id, $getType = 'main', $isInit = false) {
        $rs = parent::get_d($id);

        //���ӱ�
        if ($getType != 'main') {
            $payablesDetailDao = new model_finance_payables_detail();
            $rs['detail'] = $payablesDetailDao->getDetail($id);
            if ($isInit == 'view') {
                $rs['detail'] = $payablesDetailDao->initView($rs['detail']);
            } else if ($isInit == 'edit') {
                $rs['detail'] = $payablesDetailDao->initEdit($rs['detail']);
            }
        }
        return $rs;
    }

    /**
     * ��ȡʣ������ƽ��
     * @param $id
     * @return bool|mixed
     */
    function getCanRefund_d($id) {
        $rs = parent::get_d($id);
        //��ȡ�ӱ���Ϣ
        $payablesDetailDao = new model_finance_payables_detail();
        $rs['detail'] = $payablesDetailDao->getCanRefundDetailGE($id);
        $rs['detail'] = $payablesDetailDao->initRefund($rs['detail']);
        return $rs;
    }

    /**
     * ��дedit_d
     * @param $object
     * @param ipayables $strategy
     * @return bool
     */
    function edit_d($object, ipayables $strategy) {
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            if (empty($object['payApplyId'])) {
                unset($object['payApplyId']);
            }
            // ��λ�ҽ��Ĭ�ϵ��ڵ��ݽ��
            if(empty($object['amountCur'])){
            	$object['amountCur'] = $object['amount'];
            }
            parent:: edit_d($object, true);


            $payablesDetailDao = new model_finance_payables_detail();
            $payablesDetailDao->deleteDetail($object['id']);
            if (!empty($detail)) {
                $payablesDetailDao->createBatch($detail, array('advancesId' => $object['id']));
            }

            if (isset($object['payApplyId'])) {
                $payablesapply = new model_finance_payablesapply_payablesapply();
                $payablesapply->updateStatusByPayedMoney_d($object['payApplyId']);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �ʼ�����
     * @param $emailArr
     * @param $object
     * @param $detail
     * @param null $applyMailDetail
     */
    function thisMail_d($emailArr, $object, $detail, $applyMailDetail = null) {
        $formName = $this->rtFormName($object['formType']);
        $str = '���ã�<br/>';
        $str .= $_SESSION['USERNAME'] . " ¼����<font color='blue'>[ " . $formName . " ]</font>:" . $object['formNo'] . "<br/>";

        if ($object['formType'] == 'CWYF-03') {
            $title = '�˿���Ϣ';
            $str .= '��������Ϊ �� <br/> �˿λ��' . $object['supplierName'] . ' , �˿�� ' . $object['amount'] . ' , �˿����뵥��Ϊ:' . $object['payApplyNo'] . ' , �˿����뵥id��Ϊ:' . $object['payApplyId'] . '<br/>';
        } else {
            $title = '������Ϣ';
            $str .= '��������Ϊ �� <br/> ���λ��' . $object['supplierName'] . ' , ����� ' . $object['amount'] . ' , �������뵥��Ϊ:' . $object['payApplyNo'] . ' , �������뵥id��Ϊ:' . $object['payApplyId'] . '<br/>';
        }
        if (empty($applyMailDetail)) {
            $str .= $this->thisMailDetail_d($detail);
        } else {
            $str .= $applyMailDetail;
        }
        $str .= '�˿�ԭ�� <br/>' . $emailArr['REFUNDREASON'];
        $emailDao = new model_common_mail();
        $emailDao->mailClear($title, $emailArr['TO_ID'], $str);
    }

    /**
     * �ʼ��ӱ�������
     * @param $object
     * @return string
     */
    function thisMailDetail_d($object) {
        $str = "����ϸ��Ϣ.<br/>";
        if (is_array($object)) {
            $i = 0;
            $datadictDao = new model_system_datadict_datadict();
            $str = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>Դ������</b></td><td><b>Դ�����</b></td><td><b>������</b></td></tr>";
            foreach ($object as $key => $val) {
                $i++;
                if (!empty($val['objCode'])) {
                    $objTypeCN = $datadictDao->getDataNameByCode($val['objType']);
                } else {
                    $objTypeCN = "";
                }
                $str .= <<<EOT
					<tr align="center" ><td>$i</td><td>$objTypeCN</td><td>$val[objCode]</td><td>$val[money]</td></tr>
EOT;
            }
            $str .= "</table>";
        }

        return $str;
    }

    /**
     * ����
     * @param $id
     * @return mixed
     */
    function normal_d($id) {
        return parent::edit_d(array('id' => $id, 'status' => '0'), true);
    }

    /**
     * �˿�
     * @param $id
     * @return mixed
     */
    function pushDown_d($id) {
        return parent::edit_d(array('id' => $id, 'status' => '1'), true);
    }

    /*************************�������벿��*************************/
    /**
     * ��Ը��������ɾ��
     * @param $ids
     * @return int|string
     * @throws Exception
     */
    function delForApply_d($ids) {
        $idArr = explode(',', $ids);
        $formNoArr = array();
        try {
            $this->start_d();
            foreach ($idArr as $key => $val) {
                $rs = $this->isPayApply_d($val);
                if ($rs['status'] == 1) {
                    array_push($formNoArr, $rs['formNo']);
                    continue;
                }
                if ($applyId = $rs['payApplyId']) {
                    if (!isset($payablesapplyDao)) {
                        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                    }
                    $this->deletes($val);
                    $payablesapplyDao->updateStatusByPayedMoney_d($applyId);
                } else {
                    $this->deletes($val);
                }
                if (!empty($rs['belongId'])) {
                    //�����״̬��ԭ
                    $this->normal_d($rs['belongId']);

                    $orgObj = $this->find(array('id' => $rs['belongId']), null, 'payApplyId');
                    if ($orgObj['payApplyId']) {
                        //���¸�������״̬
                        if (!isset($payablesapplyDao)) {
                            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                        }
                        $payablesapplyDao->applyDelRefund_d($orgObj['payApplyId']);
                    }
                }
            }
            $this->commit_d();
            if (!empty($formNoArr)) {
                return implode($formNoArr, ',');
            } else {
                return 1;
            }
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * �жϵ�ǰ����Ƿ��и�������
     * @param $id
     * @return bool|mixed
     */
    function isPayApply_d($id) {
        return $this->find(array('id' => $id), null, 'payApplyId,belongId,formNo,status');
    }

    /**
     * ���ݲɹ�����id��ȡ�Ѹ�����
     * @param $objId
     * @param $objType
     * @return int
     */
    function getPayedMoneyByPur_d($objId, $objType) {
        $this->setCompany(0);//�����ù�˾����
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->groupBy = 'd.objId,d.objType';
        $this->sort = '';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id��ȡ�Ѹ���¼
     * @param $objId
     * @param $objType
     * @return mixed
     */
    function getPayedByPur_d($objId, $objType) {
        $this->setCompany(0);//�����ù�˾����
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->groupBy = 'c.id';
        return $this->list_d('select_historyNew');
    }

    /**
     * ���ݸ�������Ż�ȡ������Ϣ
     * @param null $ids
     * @return array
     */
    function getPayapply_d($ids = null) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $rows = $payablesapplyDao->getApplyAndDetail_d(array('ids' => $ids));
        $initRows = $payablesapplyDao->initAddIngroup_d($rows);
        return array('detail' => $initRows[0], 'rowCount' => $initRows[1]);
    }

    /**
     * ���ݸ�������Ż�ȡ������Ϣ -- ȷ�ϸ���ʱ��ֻȡδ����ĵ���
     * @param null $ids
     * @return array
     */
    function getPayapplyOneKey_d($ids = null) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $rows = $payablesapplyDao->getApplyAndDetailNew_d(array('ids' => $ids, 'status' => 'FKSQD-01'));
        return $payablesapplyDao->initAddIngroupNew_d($rows);
    }

    /**
     * ��⸶��Ƿ��Ѿ���ȫ����
     * @param $id
     * @return bool
     */
    function checkIsRefundAll_d($id) {
        $rs = $this->get_d($id);

        $this->searchArr['belongId'] = $id;
        $belongArr = $this->list_d('sum_money');

        if (is_array($belongArr)) {
            if ($rs['amount'] <= $belongArr[0]['amount']) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * ��ѯ�����������Ϣ
     * @param $payApplyId
     * @return array|bool|mixed
     */
    function getPayapplyExaInfo_d($payApplyId) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payapplyObj = $payablesapplyDao->find(array('id' => $payApplyId), null, 'exaId,exaCode');
        if ($payapplyObj['exaCode']) {
            return $payapplyObj;
        } else {
            return array(
                'exaId' => $payApplyId,
                'exaCode' => $payablesapplyDao->tbl_name
            );
        }
    }
}