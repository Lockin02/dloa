<?php

/**
 * @author Show
 * @Date 2011��5��8�� ������ 13:55:05
 * @version 1.0
 * @description:��������(��) Model��
 */
class model_finance_payablesapply_payablesapply extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_payablesapply";
        $this->sql_map = "finance/payablesapply/payablesapplySql.php";
        parent::__construct();
    }

    /********************�²��Բ���ʹ��************************/

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    //��ͬ����������������,������Ҫ���������׷��
    private $relatedStrategyArr = array(
        'YFRK-01' => 'model_finance_payablesapply_strategy_purcontract', //�ɹ�����
        'YFRK-02' => 'model_finance_payablesapply_strategy_other', //���۶���
        'YFRK-03' => 'model_finance_payablesapply_strategy_outsourcing', //�����ͬ
        'YFRK-04' => 'model_finance_payablesapply_strategy_none', //��Դ����
        'YFRK-05' => 'model_finance_payablesapply_strategy_outaccount',//������㵥
        'YFRK-06' => 'model_finance_payablesapply_strategy_rentcar'//�⳵��ͬ
    );

    /**
     * �����������ͷ�����
     * @param $objType
     * @return null
     */
    public function getClass($objType) {
        return isset($this->relatedStrategyArr[$objType]) ? $this->relatedStrategyArr[$objType] : null;
    }

    //��Ӧҵ�����
    private $relatedCode = array(
        'YFRK-01' => 'purcontract',
        'YFRK-02' => 'other',
        'YFRK-03' => 'outsourcing',
        'YFRK-04' => 'none',
        'YFRK-05' => 'outaccount',
        'YFRK-06' => 'rentcar'
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
     * ��ȡ������Ϣ - �鿴�޸�ʱʹ��
     * @param $obj
     * @param ipayablesapply $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, ipayablesapply $strategy) {
        return $strategy->getObjInfo_d($obj);
    }

    /**
     * �����Ƿ�
     * @param $thisVal
     * @return string
     */
    function rtYesNo_d($thisVal) {
        if ($thisVal == 1) {
            return '��';
        } else {
            return '��';
        }
    }

    /**
     * ��Ⱦ����ҳ��
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initAdd_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initAddRefund_d($object, $this) : $strategy->initAdd_d($object, $this);
    }

    /**
     * ��Ⱦ����ҳ��
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initAddOne_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initAddOneRefund_d($object, $this) :
            $strategy->initAddOne_d($object, $this);
    }

    /**
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initEdit_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initEditRefund_d($object['detail'], $this) :
            $strategy->initEdit_d($object['detail'], $this);
    }

    /**
     * ���ز鿴ҳ����ϸ
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initView_d($object, ipayablesapply $strategy, $payFor) {
        //��ȡ����
        $object['detail'] = $payFor == 'FKLX-03' ? $strategy->initViewRefund_d($object['detail']) :
            $strategy->initView_d($object['detail']);

        return $object;
    }

    /**
     * ������˲鿴ҳ����ϸ
     */
    function initAudit_d($object, ipayablesapply $strategy, $payFor) {
        //���Ӹ�����Ϣ
        $object['addInfo'] = $strategy->initAddInfo_d($object['detail']);
        //��ȡ����
        $object['detail'] = $payFor == 'FKLX-03' ? $strategy->initAuditRefund_d($object['detail']) :
            $strategy->initAudit_d($object['detail']);

        return $object;
    }

    /**
     * ����ҳ���ӡ��ʽ
     * @param $object
     * @param ipayablesapply $strategy
     * @return mixed
     */
    function initPrint_d($object, ipayablesapply $strategy) {
        //��ȡ����
        $printRs = $strategy->initPrint_d($object['detail']);
        if (isset($printRs['sourceCode'])) {
            $object['detail'] = $printRs['detail'];
            $object['sourceCode'] = $printRs['sourceCode'];
        } else {
            $object['detail'] = $strategy->initPrint_d($object['detail']);
        }
        return $object;
    }

    /**
     * ��ȡ����������Ϣ ��������
     * @param $id
     * @param bool $isInit
     * @return bool|mixed
     */
    function getForPushForDetail_d($id, $isInit = true) {
        $rs = parent::get_d($id);
        //		print_r($rs);

        //���ò���
        $newClass = $this->getClass($rs['sourceType']);
        $initObj = new $newClass();
        $initObjGruop = $initObj->groupByCodeForPush;

        $applyDetailDao = new model_finance_payablesapply_detail();
        $applyDetailDao->searchArr = array('payapplyId' => $id);

        if (empty($initObjGruop)) {
            $applyDetailDao->groupBy = 'c.payapplyId,c.objId,c.objType';
        } else {
            $applyDetailDao->groupBy = $initObjGruop;
        }
        $rs['detail'] = $applyDetailDao->listBySqlId('select_push');

        if ($isInit == true) {
            $detail = $initObj->initPayablesAdd_i($rs['detail']);
            $rs['detail'] = $detail[0];
            $rs['coutNumb'] = $detail[1];
        }

        return $rs;
    }

    /********************�²��Բ���ʹ��************************/

    /******************** ����������� **********************/
    // ��������
    public $payForArr = array(
        'FKLX-01' => '',
        'FKLX-02' => '',
        'FKLX-03' => 'back'
    );

    // �ʼ���������
    public $mailTypeArr = array(
        'YFRK-01' => 'payablesapply_close',
        'YFRK-02' => 'payablesapply_outsourcing_close',
        'YFRK-03' => 'payablesapply_other_close',
        'YFRK-04' => 'payablesapply_none_close',
        'handUpPay' => 'payablesapply_handUpPay'
    );

    /**
     * @param $value
     * @return mixed
     */
    function getMailType_d($value) {
        if (isset($this->mailTypeArr[$value])) {
            return $this->mailTypeArr[$value];
        } else {
            return $value;
        }
    }

    /******************** ����������� **********************/

    /**
     * ��дadd_d
     * @param $object
     * @return bool
     */
    function add_d($object) {
        $codeRuleDao = new model_common_codeRule();
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            // ���¼��� payMoney ��ֹǰ��ͳ��ʧЧ����0�����
            $payMoneyNew = 0;
            if (!empty($detail)) {
                foreach ($detail as $val){
                    $payMoneyNew = round(bcadd($payMoneyNew,$val['money'],5),2);
                }
                if($payMoneyNew > 0 && $object['payMoney'] != $payMoneyNew){
                    $object['payMoney'] = $payMoneyNew;
                }
            }

            //�Զ����������
            $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKSQ');

            //״̬λ - ����Ǹ������Ҫ�ٽ��в���֧������
            if ($object['payFor'] == 'FKLX-03') {
                $object['status'] = 'FKSQD-01';
            } else {
                $object['status'] = 'FKSQD-00';
            }
            //����״̬
            $object['ExaStatus'] = WAITAUDIT;
            // ��λ�ҽ�� = ������ * ����
            $object['payMoneyCur'] = $object['payMoney'] * $object['rate'];

            $newId = parent:: add_d($object, true);

            if (!empty($detail)) {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            } else {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $detail = array(
                    array('money' => $object['payMoney'], 'objType' => $object['sourceType'])
                );
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            }

            //���¸���������ϵ
            $this->updateObjWithFile($newId, $object['formNo']);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������������ �� ���������� - ����������ͬ�������ͬ�������븶��
     * @param $object
     * @return bool
     */
    function addOnly_d($object) {
        $codeRuleDao = new model_common_codeRule();

        //�ж��Ƿ��Ѿ������˸������뵥������Ѵ��ڣ���ֱ�ӷ���
        $this->searchArr = array('exaCode' => $object['exaCode'], 'exaId' => $object['exaId']);
        $rs = $this->list_d();
        if ($rs) {
            return true;
        }

        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            //�Զ����������
            $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKSQ');
            //״̬λ
            $object['status'] = isset($object['status']) ? $object['status'] : 'FKSQD-00';

            //�ر��� -- �������û�д��빫˾��Ϣ,��ô����ȡsalesmanId�Ĺ�˾��Ϣ
            if (!$object['businessBelong']) {
                $userDao = new model_deptuser_user_user();
                $userInfo = $userDao->getUserById($object['salesmanId']);
                $object['businessBelong'] = $object['formBelong'] = $userInfo['Company'];
                $object['businessBelongName'] = $object['formBelongName'] = $userInfo['CompanyName'];
            }

            $newId = parent:: add_d($object);

            if (!empty($detail)) {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            }

            $this->commit_d();

            //�����ί�и���򲻷����ʼ�
            if (!$object['isEntrust']) {
                //�ʼ�����
                $content = "��ã������������� id: " . $newId . ",���뵥��: " . $object['formNo'] . " �Ѿ����������<br><span style='color:red'>�뽫���������ύ���������֧����</span>";

                $emailDao = new model_common_mail();
                $emailDao->mailClear('������������ id:' . $newId, $object['salesmanId'], $content);
            }

            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * @param $id
     * @param string $getType
     * @param bool $isInit
     * @return bool|mixed
     */
    function get_d($id, $getType = 'main', $isInit = false) {
        $rs = parent::get_d($id);

        //���ӱ�
        if ($getType != 'main') {
            $applyDetailDao = new model_finance_payablesapply_detail();
            $rs['detail'] = $applyDetailDao->getDetail($id);

            if ($isInit == 'view') {
                $sconfigDao = new model_common_securityUtil ('purchasecontract');
                $rs['detail'] = $sconfigDao->md5Rows($rs['detail'], 'objId');
                $rs['detail'] = $applyDetailDao->initView($rs['detail'], $rs);
            } else if ($isInit == 'edit') {
                $rs['detail'] = $applyDetailDao->initEdit($rs['detail']);
            }
        }

        return $rs;
    }

    /**
     * ��дgetAuditing
     * @param $id
     * @return bool|mixed
     */
    function getAuditing_d($id) {
        $rs = parent::get_d($id);

        $applyDetailDao = new model_finance_payablesapply_detail();
        $rs['detail'] = $applyDetailDao->getDetail($id);

        $sconfigDao = new model_common_securityUtil ('purchasecontract');
        $rs['detail'] = $sconfigDao->md5Rows($rs['detail'], 'objId');
        $rs['detail'] = $applyDetailDao->initAuditing($rs['detail'], $rs);

        return $rs;
    }

    /**
     * ��ȡ����������Ϣ ��������
     * @param $id
     * @return bool|mixed
     */
    function getForPush_d($id) {
        $rs = parent::get_d($id);
        $applyDetailDao = new model_finance_payablesapply_detail();
        $applyDetailDao->searchArr = array('payapplyId' => $id);
        $applyDetailDao->groupBy = 'c.payapplyId,c.objId,c.objType';
        $rs['detail'] = $applyDetailDao->listBySqlId('select_push');

        return $rs;
    }


    /**
     * ���ݸ�������id�Զ��޸�״̬
     * @param null $id
     * @return null
     */
    function updateStatusByPayedMoney_d($id = null) {
        if (empty($id)) {
            return null;
        } else {
            $this->searchArr['id'] = $id;
            $this->sort = 'c.id';
            $this->groupBy = 'c.id';
            $rs = $this->listBySqlId('sum_payedmoney');
            $object = array();
            $object['id'] = $id;
            $object['actPayDate'] = '0000-00-00';
            $object['payedMoney'] = $rs[0]['thisPayedMoney'];
            if ($rs[0]['thisPayedMoney'] == 0 || $rs[0]['thisPayedMoney'] == null) {//Ϊ0ʱ�޸�״̬Ϊδ����
                $object['status'] = 'FKSQD-01';
            } else if ($rs[0]['payMoney'] > $rs[0]['thisPayedMoney']) {//������С���踶���ʱ��Ϊ���ָ���
                $object['status'] = 'FKSQD-02';
            } else {//�������ߵ���Ϊ�Ѹ���
                $object['status'] = 'FKSQD-03';
            }
            return parent::edit_d($object, true);
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            // ���¼��� payMoney ��ֹǰ��ͳ��ʧЧ����0�����
            $payMoneyNew = 0;
            if (!empty($detail)) {
                foreach ($detail as $val){
                    $payMoneyNew = round(bcadd($payMoneyNew,$val['money'],5),2);
                }
                if($payMoneyNew > 0 && $object['payMoney'] != $payMoneyNew){
                    $object['payMoney'] = $payMoneyNew;
                }
            }

            // ��λ�ҽ�� = ������ * ����
            $object['payMoneyCur'] = $object['payMoney'] * $object['rate'];

            parent:: edit_d($object, true);

            $applyDetailDao = new model_finance_payablesapply_detail();
            $applyDetailDao->deleteDetail($object['id']);
            if (!empty($detail)) {
                $applyDetailDao->createBatch($detail, array('payapplyId' => $object['id']));
            }

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �رո������뵥����
     * @param $object
     * @return bool
     */
    function close_d($object) {
        $mail = $object['mail'];
        unset($object['mail']);

        try {
            $this->start_d();

            //�رո�������
            $rs = parent::edit_d($object);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }

        //�����ʼ� ,������Ϊ�ύʱ�ŷ���
        if (isset($mail) && $rs) {
            if ($mail['issend'] == 'y' && !empty($mail['TO_ID'])) {
                $this->thisMailClose_d($mail, $object);
            }
        }

        return $rs;
    }

    /**
     * �����رո������뵥����
     * @param $object
     * @return mixed
     */
    function batchClose_d($object) {
        $idStr = util_jsonUtil::strBuild($object['ids']);
        $sql = "
			UPDATE " . $this->tbl_name . "
			SET closeDate = '" . $object['closeDate'] . "', closeUser = '" . $object['closeUser'] .
            "', closeUserId = '" . $object['closeUserId'] .
            "',closeReason = '" . $object['closeReason'] .
            "',STATUS = '" . $object['status'] .
            "' WHERE id IN (" . $idStr . ") ";
        return $this->query($sql);
    }

    /**
     * ���͹ر��ʼ�֪ͨ
     * @param $emailArr
     * @param $object
     */
    function thisMailClose_d($emailArr, $object) {
        $content = $object['closeUser'] . '�Ѿ��رո�������  ' . $object['formNo'] .
            ',������ϸ��ϢΪ��<br/> id : ' . $object['id'] .
            ',<br/>��Ӧ�� ��' . $object['supplierName'] . ',<br/>�����' . $object['payMoney'] .
            ',<br/>�ر��ˣ�' . $object['closeUser'] .
            ',<br/>�ر����ڣ�' . $object['closeDate'] .
            ',<br/>�ر�ԭ��' . $object['closeReason'];
        $emailDao = new model_common_mail();
        $emailDao->mailClear('�رո�������', $emailArr['TO_ID'], $content);
    }

    /**
     * ��ȡĬ���ʼ�������
     * @param string $key
     * @return array
     */
    function getSendMen_d($key = 'payablesapply_close') {
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailArr = isset($mailUser[$key][0]) ? $mailUser[$key][0] : array('sendUserId' => '',
            'sendName' => '');
        return $mailArr;
    }

    /*******************�ⲿ��Ϣ��ȡ********************/
    /**
     * ���ݲɹ���ͬId��ȡ�����Ϣ����
     * @param $purAppId
     * @return bool|mixed
     */
    function getContractinfoById_d($purAppId) {
        //��ȡ������Ϣ
        $purchasecontract = new model_purchase_contract_purchasecontract();
        $row = $purchasecontract->find(array('id' => $purAppId), null, 'id,suppName,suppId,objCode,suppAddress,createId,createName,hwapplyNumb,paymentCondition,payRatio,allMoney,suppBankName,suppAccount');

        //��ȡ�����븶����
        //��ȡ�Ѹ�����
        $payablesapplyMoney = $this->getApplyMoneyByPur_d($purAppId, 'YFRK-01');
        $row['payablesapplyMoney'] = $payablesapplyMoney;

        //��ȡ�Ѹ�����
        $payablesDao = new model_finance_payables_payables();
        $payedMoney = $payablesDao->getPayedMoneyByPur_d($purAppId, 'YFRK-01');
        $row['payedMoney'] = $payedMoney;

        //��ȡ���ܷ�Ʊ���
        $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($purAppId);
        $row['handInvoiceMoney'] = $handInvoiceMoney;

        //��ȡ��ǰ��¼�˲���
        $otherDataDao = new model_common_otherdatas();
        $deptRows = $otherDataDao->getUserDatas($row['createId'], array('DEPT_NAME', 'DEPT_ID'));
        $row['deptName'] = $deptRows['DEPT_NAME'];
        $row['deptId'] = $deptRows['DEPT_ID'];

        return $row;
    }

    /**
     * ״̬��������ӱ�
     * @param $rs
     * @return array
     */
    function initPayApplyDetail_d($rs) {
        $str = ""; //���ص�ģ���ַ���
        $i = 0; //�б��¼���
        if ($rs) {
            $datadictArr = $this->getDatadicts('YFRK');
            foreach ($rs as $key => $val) {
                $i++;
                $objTypeArr = $this->getDatadictsStr($datadictArr ['YFRK'], $val ['objType']);
                $canApply = bcsub($val['allMoney'], $val['payablesapplyMoney'], 2);
                $str .= <<<EOT
                    <tr><td>$i</td>
                        <td>
                            <select class="selectmiddel" id="objTypeList$i" value="$val[objType]" name="payablesapply[detail][$i][objType]">
                                $objTypeArr
                            </select>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxtNormal" readonly="readonly" id="objCode$i" value="$val[hwapplyNumb]" name="payablesapply[detail][$i][objCode]"/>
                            <input type="hidden" id="objType$i" value="YFRK-01" name="payablesapply[detail][$i][objType]"/>
                            <input type="hidden" id="objId$i" value="$val[id]" name="payablesapply[detail][$i][objId]"/>
                        </td>
                        <td>
                            <input type="text" class="txtmiddle formatMoney" id="money$i" value="$canApply" name="payablesapply[detail][$i][money]" onblur="checkMax($i);countAll()"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[allMoney]" name="payablesapply[detail][$i][purchaseMoney]"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[payedMoney]" name="payablesapply[detail][$i][payedMoney]"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[handInvoiceMoney]" name="payablesapply[detail][$i][handInvoiceMoney]"/>
                        </td>
                    </tr>
EOT;
            }
        }
        return array(
            $str,
            $i
        );
    }

    /**
     * ���ݲɹ�����id��ȡ�����븶����
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyMoneyByPur_d($objId, $objType = 'YFRK-01') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id��ȡ���˿����ܺ�
     * @param $objId
     * @param string $objType
     * @param string $payFor
     * @return int
     */
    function getApplyBackMoney_d($objId, $objType = 'YFRK-01', $payFor = 'FKLX-03') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'payFor' => $payFor,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id��ȡ�����븶���� - �����˿����
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyMoneyByPurAll_d($objId, $objType = 'YFRK-01') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_listAll');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id��ȡ�����븶����
     * @param $objId
     * @param string $objType
     * @param $productId
     * @return int
     */
    function getApplyMoneyByPurProduct_d($objId, $objType = 'YFRK-01', $productId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dProductId' => $productId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType,d.productId';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id���嵥id��ȡ�����븶����
     * @param $objId
     * @param string $objType
     * @param $detailId
     * @return int
     */
    function getApplyMoneyByPurExpand1_d($objId, $objType = 'YFRK-01', $detailId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dExpand1' => $detailId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType,d.expand1';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * ���ݲɹ�����id���嵥id��ȡ�����븶���� - �����˿�
     * @param $objId
     * @param string $objType
     * @param $detailId
     * @return int
     */
    function getApplyMoneyByPurExpand1All_d($objId, $objType = 'YFRK-01', $detailId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dExpand1' => $detailId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//�����ù�˾����
        $this->groupBy = 'd.objId,d.objType,d.expand1';
        $rows = $this->list_d('sum_listAll');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * �鿴��������͸���������ϸ
     * @param $object
     * @return mixed
     */
    function getApplyAndDetail_d($object) {
        $this->searchArr = $object;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_history');
    }

    /**
     * ��ȡ�Ƿ��Ѵ��ڶ�Ӧδ��������뵥
     * @param $objId
     * @param string $objType
     * @param string $formType
     * @return int
     */
    function isExistence_d($objId, $objType = 'YFRK-01', $formType = 'pay') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-03,FKSQD-04,FKSQD-05'
        );
        if ($formType != 'pay') {
            $this->searchArr['payFor'] = 'FKLX-03';
        } else {
            $this->searchArr['noPayFor'] = 'FKLX-03';
        }
        $this->setCompany(0);//�����ù�˾����
        $rows = $this->list_d('select_detail');
        if (is_array($rows)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * �жϸ��������Ƿ���Ч  - ���ڷ��÷�̯����
     * @param $id
     * @return bool
     */
    function isEffective_d($id) {
        $obj = $this->find(array('id' => $id), null, 'ExaStatus,status');
        if ($obj['ExaStatus'] == '���' && ($obj['status'] != 'FKSQD-04' or $obj['status'] != 'FKSQD-05')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ���¸�������ķ��÷�̯���ͷ��÷�̯״̬
     * @param $id
     * @param int $shareMoney
     * @param string $updateKey
     * @return mixed
     */
    function updateShareInfo_d($id, $shareMoney = 0, $updateKey = 'id') {
        if ($shareMoney == 0) {
            $shareStatus = 0;
        } else {
            $rs = $this->find(array($updateKey => $id), null, 'payMoney');
            if ($rs['payMoney'] * 1 == $shareMoney * 1) {
                $shareStatus = 1;
            } else {
                $shareStatus = 2;
            }
        }
        return $this->update(array($updateKey => $id), array('shareStatus' => $shareStatus, 'shareMoney' => $shareMoney));
    }

    /**
     * ��Ⱦ��������
     * @param $rs
     * @return array
     */
    function initAddIngroup_d($rs) {
        $str = ""; //���ص�ģ���ַ���
        $i = 0; //�б��¼���
        if ($rs) {
            $j = 0; //����
            $mark = null; //�б��¼���

            $sconfigDao = new model_common_securityUtil ('purchasecontract');
            $rs = $sconfigDao->md5Rows($rs, 'id');
            $salesman = $salesmanId = null;
            foreach ($rs as $key => $val) {
                $objCode = empty($val['objCode']) ? '��' : $val['objCode'];
                if ($val['id'] != $mark) {
                    $thisDay = day_date;

                    if ($mark != $val['id'] && !empty($mark)) {//��Ⱦ�����������
                        $str .= <<<EOT
							<tr class="tr_odd">
								<td></td>
								<td class="innerTd">
									�ʼ�֪ͨ��
								</td>
								<td class="form_text_right " colspan="7" class="innerTd">
									<input type="text" class="txt" id="mailman$i" name="payables[$i][email][TO_NAME]" value="$salesman"/>
									<input type="hidden" id="mailmanId$i" name="payables[$i][email][TO_ID]" value="$salesmanId"/>
									<input type="hidden" name="payables[$i][email][issend]" value="y"/>
								</td>
							</tr>
							<tr class="tr_count">
								<td colspan="9"></td>
							</tr>
EOT;
                        $salesmanId = $val['salesmanId'];
                        $salesman = $val['salesman'];
                    } else {
                        $salesmanId = $val['salesmanId'];
                        $salesman = $val['salesman'];
                    }
                    switch ($val['payFor']) {
                        case 'FKLX-01' :
                            $payFor = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $payFor = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $payFor = 'CWYF-03';
                            break;
                        default :
                            $payFor = '';
                            break;
                    }
                    $i++;
                    $str .= <<<EOT
						<tr>
							<td>
								$i
							</td>
							<td>
								$val[formNo]
								<input type="hidden" name="payables[$i][payApplyNo]" value="$val[formNo]"/>
								<input type="hidden" name="payables[$i][payApplyId]" value="$val[id]"/>
								<input type="hidden" name="payables[$i][bank]" value="$val[bank]"/>
								<input type="hidden" name="payables[$i][account]" value="$val[account]"/>
								<input type="hidden" name="payables[$i][payType]" value="$val[payType]"/>
								<input type="hidden" name="payables[$i][remark]" value="$val[remark]"/>
								<input type="hidden" name="payables[$i][formType]" value="$payFor"/>
								<img src="images/icon/view.gif" title="������Ϣ" onclick="showModalWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id=$val[id]&skey=$val[skey_]',1);" />
								<img src="images/icon/search.gif" title="�������" onclick="showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');" />
							</td>
							<td>
								$val[deptName]
								<input type="hidden" value="$val[deptName]" name="payables[$i][deptName]"/>
								<input type="hidden" value="$val[deptId]" name="payables[$i][deptId]"/>
							</td>
							<td>
								$val[salesman]
								<input type="hidden" value="$val[salesman]" name="payables[$i][salesman]"/>
								<input type="hidden" value="$val[salesmanId]" name="payables[$i][salesmanId]"/>
							</td>
							<td>
								<input type="text" class="txtshort" value="$thisDay" name="payables[$i][formDate]"/>
							</td>
							<td>
								$val[supplierName]
								<input type="hidden" value="$val[supplierName]" name="payables[$i][supplierName]"/>
								<input type="hidden" value="$val[supplierId]" name="payables[$i][supplierId]"/>
								<input type="hidden" value="$val[payMoney]" name="payables[$i][amount]"/>
							</td>
							<td class="formatMoney">
								$val[payMoney]
							</td>
							<td>
								$objCode
								<input type="hidden" value="$val[objId]" name="payables[$i][detail][$j][objId]"/>
								<input type="hidden" value="$val[objCode]" name="payables[$i][detail][$j][objCode]"/>
								<input type="hidden" value="$val[objType]" name="payables[$i][detail][$j][objType]"/>
								<input type="hidden" value="$val[money]" name="payables[$i][detail][$j][money]"/>
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
						</tr>
EOT;
                    $mark = $val['id'];
                } else {
                    $j++;
                    $str .= <<<EOT
						<tr>
							<td colspan="7">
							</td>
							<td>
								$objCode
								<input type="hidden" value="$val[objId]" name="payables[$i][detail][$j][objId]"/>
								<input type="hidden" value="$val[objCode]" name="payables[$i][detail][$j][objCode]"/>
								<input type="hidden" value="$val[objType]" name="payables[$i][detail][$j][objType]"/>
								<input type="hidden" value="$val[money]" name="payables[$i][detail][$j][money]"/>
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
						</tr>
EOT;

                }
            }
            $str .= <<<EOT
				<tr class="tr_odd">
					<td></td>
					<td class="innerTd">
						�ʼ�֪ͨ��
					</td>
					<td class="form_text_right " colspan="7" class="innerTd">
						<input type="text" class="txt" id="mailman$i" name="payables[$i][email][TO_NAME]" value="$salesman"/>
						<input type="hidden" id="mailmanId$i" name="payables[$i][email][TO_ID]" value="$salesmanId"/>
						<input type="hidden" name="payables[$i][email][issend]" value="y"/>
					</td>
				</tr>
				<tr class="tr_count">
					<td colspan="9"></td>
				</tr>
EOT;
        }
        return array($str, $i);
    }

    /**
     * ��ѯ����������Ϣ
     * @param $object
     * @return mixed
     */
    function getApplyAndDetailO_d($object) {
        $this->searchArr = $object;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_detailcount');
    }

    /**
     * ��ѯ����������Ϣ - �¸����¼
     * @param $object
     * @return mixed
     */
    function getApplyAndDetailNew_d($object) {
        $this->searchArr = $object;
        $this->setCompany(0);
        return $this->list_d('select_detailcountNew');
    }

    /**
     * �������� - ��ʱ���� 2012-02-28
     * @param $object
     * @return array
     */
    function initAddIngroupO_d($object) {
        $newArr = array();
        if ($object) {
            $i = 0; //�б��¼���
            $j = 0; //����
            $mark = null; //�б��¼���
            foreach ($object as $key => $val) {
                if ($mark != $val['id']) {//�����������
                    $mark = $val['id'];//��¼ID
                    switch ($val['formType']) {//����������
                        case 'FKLX-01' :
                            $val['formType'] = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $val['formType'] = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $val['formType'] = 'CWYF-03';
                            break;
                        default :
                            break;
                    }
                    $i++;
                    $j = 0;
                    unset($val['id']);
                    $newArr['payables'][$i] = $val;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    $newArr['payables'][$i]['email']['issend'] = 'y';
                    $newArr['payables'][$i]['email']['TO_ID'] = $val['salesmanId'];
                    $newArr['payables'][$i]['email']['TO_NAME'] = $val['salesman'];
                } else {//����Ǵӱ��У��Դӱ���Ϣ���м���
                    $j++;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];
                }
            }
        }
        return $newArr;
    }

    /**
     * �������� - �� 2012-02-28
     * @param $object
     * @return array
     */
    function initAddIngroupNew_d($object) {
        $newArr = array();
        if ($object) {
            $i = 0; //�б��¼���
            $j = 0; //����
            $mark = null; //�б��¼���
            $objArr = array();
            foreach ($object as $key => $val) {
                if ($mark != $val['id']) {//�����������
                    $mark = $val['id'];//��¼ID
                    switch ($val['formType']) {//����������
                        case 'FKLX-01' :
                            $val['formType'] = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $val['formType'] = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $val['formType'] = 'CWYF-03';
                            break;
                        default :
                            break;
                    }
                    $i++;
                    $j = 0;
                    unset($val['id']);
                    $newArr['payables'][$i] = $val;
                    $newArr['payables'][$i]['email']['issend'] = 'y';
                    $newArr['payables'][$i]['email']['TO_ID'] = $val['salesmanId'];
                    $newArr['payables'][$i]['email']['TO_NAME'] = $val['salesman'];

                    /**
                     * ������ϸ����
                     */
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    if (!empty($val['sourceType'])) {
                        //���ò���
                        $newClass = $this->getClass($val['sourceType']);
                        if (!isset($objArr[$newClass])) {
                            $objArr[$newClass] = new $newClass();
                        }
                        $expandArr = $objArr[$newClass]->rebuildExpandArr_i($val);
                        //�ϲ���չ����
                        $newArr['payables'][$i]['detail'][$j] = array_merge($newArr['payables'][$i]['detail'][$j], $expandArr);
                    }

                    //�����������
                    unset($newArr['payables'][$i]['expand1']);
                    unset($newArr['payables'][$i]['expand2']);
                    unset($newArr['payables'][$i]['expand3']);
                    unset($newArr['payables'][$i]['productNo']);
                    unset($newArr['payables'][$i]['productName']);
                    unset($newArr['payables'][$i]['number']);
                    unset($newArr['payables'][$i]['objId']);
                    unset($newArr['payables'][$i]['objCode']);
                    unset($newArr['payables'][$i]['objType']);

                } else {//����Ǵӱ��У��Դӱ���Ϣ���м���
                    $j++;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    if (!empty($val['sourceType'])) {
                        //���ò���
                        $newClass = $this->getClass($val['sourceType']);

                        $expandArr = $objArr[$newClass]->rebuildExpandArr_i($val);
                        //�ϲ���չ����
                        $newArr['payables'][$i]['detail'][$j] = array_merge($newArr['payables'][$i]['detail'][$j], $expandArr);
                    }
                }
            }
        }
        return $newArr;
    }

    /**
     * �������ݴ���
     * @param $object
     * @return null|string
     */
    function detailDeald_d($object) {
        $equDao = new model_purchase_contract_equipment ();
        $mark = $str = null;
        foreach ($object as $key => $val) {
            if (!empty($val['objId'])) {
                if (empty($mark)) {
                    $str .= <<<EOT
						<tr>
							<td colspan="4" class="td_table">
								<table class="form_in_table">
									<thead>
										<tr>
											<td colspan="15" class="form_header">��������</td>
										</tr>
										<tr class="main_tr_header">
											<th width="25%">��������</th>
											<th>��λ</th>
											<th >��������</th>
											<th >���������</th>
											<th >����</th>
											<th >���</th>
											<th width="15%">�ɹ���;</th>
											<th width="15%">���벿��</th>
										</tr>
									</thead>
									<tbody>
EOT;
                    $mark = 1;
                }
            }
            $equs = $equDao->getEqusByContractId($val ['objId']);
            $str .= $this->showEquList($equs);
        }
        if ($mark == 1) {
            $str .= <<<EOT
					</tbody>
				</table>
			</td>
		</tr>
EOT;
        }
        return $str;
    }

    /**
     * ��ʾ�����б�
     * @param $rows
     * @return null|string
     */
    function showEquList($rows) {
        $str = null;
        if ($rows) {
            $i = 0;
            $interfObj = new model_common_interface_obj();
            foreach ($rows as $key => $val) {
                $i++;
                $purchTypeCn = $interfObj->typeKToC($val['purchType']); //��������
                $str .= <<<EOT
                    <tr>
                    <td>
                            $val[productName]
                        </td>
                        <td>
                            $val[units]
                        </td>
                        <td>
                            $val[amountAll]
                        </td>
                        <td>
                            $val[amountIssued]
                        </td>
                        <td class="formatMoney">
                            $val[applyPrice]
                        </td>
                        <td class="formatMoney">
                            $val[moneyAll]
                        </td>
                        <td>
                            $purchTypeCn
                        </td>
                        <td>
                            $val[applyDeptName]
                        </td>
                    </tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * ���Ӵ�ӡ����
     * @param $id
     * @param int $printTimes
     * @return mixed
     */
    function changePrintCount_d($id, $printTimes = 1) {
        $sql = 'update ' . $this->tbl_name . ' set printCount = printCount + ' . $printTimes . ' where id = ' . $id;

        $this->_db->query($sql);
        $selectSql = 'select printCount from ' . $this->tbl_name . ' where id = ' . $id;

        $rs = $this->_db->getArray($selectSql);

        return $rs[0]['printCount'];
    }

    /**
     * ���Ӵ�ӡ���� - ��id
     * @param $id
     * @param int $printTimes
     * @return int
     */
    function changePrintCountIds_d($id, $printTimes = 1) {
        $this->_db->query('update ' . $this->tbl_name . ' set printCount = printCount + ' . $printTimes .
            ',lastPrintTime = "' . date('Y-m-d H:i:s') . '" where id in(' . $id . ')');
        return 1;
    }

    /**
     * ���ݲɹ�����id��ȡ���������¼
     * @param $objId
     * @param $objType
     * @return mixed
     */
    function getApplyByPur_d($objId, $objType) {
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->searchArr['noExaStatus'] = BACK;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_history');
    }

    /**
     * ��ȡ�ʼ����ʹӱ���Ϣ - �ɹ���������ר��
     * @param $id
     * @return null|string
     */
    function getMailDetail_d($id) {
        $rs = $this->get_d($id, 'detail');
        $str = null;
        if (is_array($rs)) {
            $i = 0;
            $str = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>Դ������</b></td><td><b>Դ�����</b></td><td><b>������</b></td><td><b>Դ�����</b></td>";
            $str .= "<td><b>���ϱ��</b></td><td><b>��������</b></td><td><b>�����ͺ�</b></td><td><b>��λ</b></td><td><b>����</b></td><td><b>����</b></td><td><b>��˰�ϼ�</b></td></tr>";
            foreach ($rs['detail'] as $key => $val) {
                $i++;
                $str .= <<<EOT
					<tr><td>$i</td><td>�ɹ�����</td><td>$val[objCode]</td><td>$val[money]</td><td>$val[purchaseMoney]</td><td>$val[productNo]</td><td>$val[productName]</td><td>$val[productModel]</td><td>$val[unitName]</td><td>$val[number]</td><td>$val[price]</td><td>$val[allAmount]</td></tr>
EOT;
            }
            $str .= "</table>";
        }
        return $str;
    }

    /**
     * ��ʼ��������Ϣ
     * @param $rows
     * @return mixed
     */
    function initExaInfo_d($rows) {
        //id����
        $idArr = array();

        foreach ($rows as $key => $val) {
            if (empty($val['exaCode'])) {
                $idArr[] = $val['id'];
            } else {
                $idArr[] = $val['exaId'];
            }
        }

        $idStr = implode($idArr, ',');

        $otherDataDao = new model_common_otherdatas();
        $exaInfo = $otherDataDao->getIdsLastExaInfo_d($idStr, 'oa_finance_payablesapply,oa_sale_other,oa_sale_outsourcing');

        $exaInfoArr = array();
        //����������Ϣ����
        foreach ($exaInfo as $key => $val) {
            $cusStr = $val['pid'] . '-' . $val['code'];
            $exaInfoArr[$cusStr]['ExaUser'] = $val['USER_NAME'];
            $exaInfoArr[$cusStr]['ExaContent'] = $val['content'];
        }

        //����������Ϣ
        foreach ($rows as $key => $val) {

            if (empty($val['exaCode'])) {
                $cusStr = $val['id'] . '-' . $this->tbl_name;
            } else {
                $cusStr = $val['exaId'] . '-' . $val['exaCode'];
            }

            $rows[$key]['ExaUser'] = $exaInfoArr[$cusStr]['ExaUser'];
            $rows[$key]['ExaContent'] = $exaInfoArr[$cusStr]['ExaContent'];
        }

        return $rows;
    }

    /**
     * ��ѯ���Դ�ӡ����ĸ������뵥
     * @return mixed
     */
    function getPayablesapplyCanPay_d() {
        $this->searchArr = array(
            'status' => 'FKSQD-01',
            'ExaStatus' => AUDITED,
            'payForArr' => 'FKLX-01,FKLX-02',
            'payDateEnd' => day_date,
            'isEntrust' => 0
        );
		$this->sort = 'c.actPayDate DESC,c.id';
		$this->asc = 'DESC';
        return $this->list_d();
    }

    /**
     * ������ɺ�ҵ����
     * @param $spid
     * @return int
     * @throws Exception
     */
    function workflowCallBack($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        //��Ҫ���µ�����
        $updateArr = array();
        $updateArr['ExaUser'] = $folowInfo ['USER_NAME'];
        $updateArr['ExaUserId'] = $folowInfo['USER_ID'];
        $updateArr['ExaContent'] = $folowInfo['content'];

        //���뵥Id
        $condition = array('id' => $folowInfo ['objId']);

        $obj = $this->get_d($folowInfo ['objId']);
        try {
            $this->start_d();

            //�������ͨ������ί�и���
            if ($obj['ExaStatus'] == AUDITED && $obj['isEntrust']) {
                //���������Ϊ�Ѹ���
                $updateArr['status'] = 'FKSQD-03';
                $updateArr['actPayDate'] = $obj['payDate'];
                $updateArr['payedMoney'] = $obj['payMoney'];

                //���ɸ��
                $payablesDao = new model_finance_payables_payables();

                //��ȡ�������뵥����
                $rows = $this->getApplyAndDetailNew_d(array('ids' => $folowInfo ['objId']));
                $addObj = $this->initAddIngroupNew_d($rows);

                $payablesDao->addInGroup_d($addObj['payables'], true);
            }

            //����������Ϣ
            $this->update($condition, $updateArr);

            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            return 1;
        }
    }

    /**
     * �ύ����֧�� - Ŀǰ���ڸ����б��е��ύ����֧��
     * @param $id
     * @return mixed
     */
    function handUpPay_d($id) {
        //����״̬
        $rs = parent::edit_d(array('id' => $id, 'status' => 'FKSQD-01'), true);

        //�ʼ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailStr = $this->getMailType_d('payablesapply_handUpPay');
        $mailArr = isset($mailUser[$mailStr][0]) ? $mailUser[$mailStr][0] : array('sendUserId' => '', 'sendName' => '');

        $content = "��ã��û� ��" . $_SESSION['USERNAME'] . "�� �ѽ��������뵥 id : " . $id . " �ύ����֧������Ե��ݽ��и��";

        $emailDao = new model_common_mail();
        $emailDao->mailClear('��������ȷ��֧�� id:' . $id, $mailArr['sendUserId'], $content);

        return $rs;
    }

    /**
     * �ύ����֧��(��)
     * @param $obj
     * @return mixed
     */
    function handUpPay2_d($obj) {
        $emailArr = $obj['email'];
        if (!$emailArr['instruction']) {
            $emailArr['instruction'] = '��';
        }
        $rs = parent::edit_d(array('id' => $obj['id'], 'status' => 'FKSQD-01', 'isPay' => $obj['isPay'],
            'instruction' => $emailArr['instruction']), true);
        if (isset($emailArr)) {
            if ($emailArr['isEntrust'] == '1') {
                $payStr = '�õ���������֧��';
            } else {
                $payStr = '��Ե��ݽ��и���';
            }
            if (!empty($emailArr['TO_ID'])) {
                $this->mailDeal_d('handUpPayMail', $emailArr['TO_ID'], array(
                    'id' => $obj['id'], 'instruction' => $emailArr['instruction'],
                    'payStr' => $payStr
                ), null, true, $obj['businessBelong']);
            }
        }
        return $rs;
    }

    /**
     * ����Ԥ������ - ϵͳ�Զ����������뵥�ύ����֧��
     * @param $obj
     * @return bool
     */
    function autoHandUp_d($obj) {
        $rs = parent::edit_d(array('id' => $obj['id'], 'status' => 'FKSQD-01'), true);

        if ($obj['isEntrust'] == '1') {
            $payStr = '�õ���������֧��';
        } else {
            $payStr = '��Ե��ݽ��и���';
        }
        $this->mailDeal_d('autoHandUpPayMail', null, array(
            'id' => $obj['id'], 'createName' => $obj['createName'],
            'payStr' => $payStr
        ), null, true, $obj['businessBelong']);

        return $rs;
    }

    /**
     * �����˿��
     * @param $id
     * @return bool
     * @throws Exception
     */
    function applyRefund_d($id) {
        try {
            //�����뵥��״̬��Ϊ���˿�
            $this->update(array('id' => $id), array('status' => 'FKSQD-05'));

            //����ҵ����
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ɾ���˿��
     * @param $id
     * @return bool
     * @throws Exception
     */
    function applyDelRefund_d($id) {
        try {
            //�����뵥��״̬��Ϊ���˿�
            $this->update(array('id' => $id), array('status' => 'FKSQD-03'));

            //����ҵ����
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ������������������˵��������
     * @param $id
     * @return mixed
     */
    function getEntryDate_d($id) {
        $data = $this->_db->get_one("select max(auditDate) as entryDate from oa_stock_instock where
            purOrderId in ( select objId  from oa_finance_payablesapply_detail where payapplyId = {$id})");

        return $data['entryDate'] ? $data['entryDate'] : "��δ�����ʱ��";
    }

    /**
     * �жϲ����Ƿ���Ҫʡ��
     */
    function deptIsNeedProvince_d($deptId){
    	include (WEB_TOR."includes/config.php");
    	//���ŷ�����Ҫʡ�ݵĲ���
    	$otherPayNeedProvinceDept = isset($otherPayNeedProvinceDept) ? $otherPayNeedProvinceDept : null;
    	//����key����
    	$keyArr = array_keys($otherPayNeedProvinceDept);
    	if(in_array($deptId,$keyArr)){
    		return 1;
    	}else{
    		return 0;
    	}
    }

	/**
     * ֧����Ϣ
     * @param $id
     * @return mixed
     */
    function getPayinfo_d($id) {
        return $this->_db->getArray("SELECT * FROM oa_sale_otherpayapply WHERE contractId={$id}");
    }
}