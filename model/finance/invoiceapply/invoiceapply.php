<?php

/**
 * ��Ʊ����model����
 */
class model_finance_invoiceapply_invoiceapply extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoiceapply";
        $this->sql_map = "finance/invoiceapply/invoiceapplySql.php";
        parent::__construct();
    }

    /********************�²��Բ���ʹ��************************/
    private $relatedStrategyArr = array(//��ͬ����������������,������Ҫ���������׷��
        'KPRK-01' => 'model_finance_invoiceapply_strategy_salesorder', //���۶���
        'KPRK-02' => 'model_finance_invoiceapply_strategy_salesorder', //���۶���
        'KPRK-03' => 'model_finance_invoiceapply_strategy_service', //�����ͬ
        'KPRK-04' => 'model_finance_invoiceapply_strategy_service', //�����ͬ
        'KPRK-05' => 'model_finance_invoiceapply_strategy_rental', //���޺�ͬ
        'KPRK-06' => 'model_finance_invoiceapply_strategy_rental', //���޺�ͬ
        'KPRK-07' => 'model_finance_invoiceapply_strategy_rdproject', //�з���ͬ
        'KPRK-08' => 'model_finance_invoiceapply_strategy_rdproject',  //�з���ͬ
        'KPRK-09' => 'model_finance_invoiceapply_strategy_other', //���������
        'KPRK-10' => 'model_finance_invoiceapply_strategy_accessorder', //���������
        'KPRK-11' => 'model_finance_invoiceapply_strategy_repairapply', //ά�����뵥
        'KPRK-12' => 'model_finance_invoiceapply_strategy_contract' //��Ӫ��ͬ
    );

    private $relatedCode = array(
        'KPRK-01' => 'salesorder',
        'KPRK-02' => 'salesorder',
        'KPRK-03' => 'service',
        'KPRK-04' => 'service',
        'KPRK-05' => 'rental',
        'KPRK-06' => 'rental',
        'KPRK-07' => 'rdproject',
        'KPRK-08' => 'rdproject',
        'KPRK-09' => 'other',
        'KPRK-10' => 'accessorder',
        'KPRK-11' => 'repairapply',
        'KPRK-12' => 'contract'
    );

    /**
     * ��Ʊ���ͷ��ض�Ӧ������
     * @param $thisVal
     * @return null|string
     */
    function rtTypeClass($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-02' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-03' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-04' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-05' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-06' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-07' :
                $val = 'rdproject_yxrdproject_rdproject';
                break;
            case 'KPRK-08' :
                $val = 'rdproject_yxrdproject_rdproject';
                break;
            case 'KPRK-09' :
                $val = 'contract_other_other';
                break;
            case 'KPRK-10' :
                $val = 'service_accessorder_accessorder';
                break;
            case 'KPRK-11' :
                $val = 'service_repair_repairapply';
                break;
            case 'KPRK-12' :
                $val = 'contract_contract_contract';
                break;
            default :
                $val = $thisVal;
                break;
        }
        return $val;
    }

    /**
     * ��Ʊ���͹���
     * @param $thisVal
     * @return null|string
     */
    function rtPostVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-02' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-03' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-04' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-05' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-06' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-07' :
                $val = "'KPRK-07','KPRK-08'";
                break;
            case 'KPRK-08' :
                $val = "'KPRK-07','KPRK-08'";
                break;
            case 'KPRK-09' :
                $val = "'KPRK-09'";
                break;
            case 'KPRK-10' :
                $val = "'KPRK-10'";
                break;
            case 'KPRK-11' :
                $val = "'KPRK-11'";
                break;
            case 'KPRK-12' :
                $val = "'KPRK-12'";
                break;
            default :
                $val = '';
                break;
        }
        return $val;
    }

    /**
     * ��Ʊ���͹���
     * @param $thisVal
     * @return null|string
     */
    function rtPostVla2($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-02' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-03' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-04' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-05' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-06' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-07' :
                $val = 'KPRK-07,KPRK-08';
                break;
            case 'KPRK-08' :
                $val = 'KPRK-07,KPRK-08';
                break;
            case 'KPRK-09' :
                $val = 'KPRK-09';
                break;
            case 'KPRK-10' :
                $val = 'KPRK-10';
                break;
            case 'KPRK-11' :
                $val = 'KPRK-11';
                break;
            case 'KPRK-12' :
                $val = 'KPRK-12';
                break;
            default :
                $val = '';
                break;
        }
        return $val;
    }

    /**
     * ���ر�
     * @param $thisVal
     * @return null|string
     */
    function rtTableName($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'oa_sale_order';
                break;
            case 'KPRK-02' :
                $val = 'oa_sale_order';
                break;
            case 'KPRK-03' :
                $val = 'oa_sale_service';
                break;
            case 'KPRK-04' :
                $val = 'oa_sale_service';
                break;
            case 'KPRK-05' :
                $val = 'oa_sale_lease';
                break;
            case 'KPRK-06' :
                $val = 'oa_sale_lease';
                break;
            case 'KPRK-07' :
                $val = 'oa_sale_rdproject';
                break;
            case 'KPRK-08' :
                $val = 'oa_sale_rdproject';
                break;
            case 'KPRK-09' :
                $val = 'oa_sale_other';
                break;
            case 'KPRK-10' :
                $val = 'oa_service_accessorder';
                break;
            case 'KPRK-11' :
                $val = 'oa_service_repair_apply';
                break;
            case 'KPRK-12' :
                $val = 'oa_contract_contract';
                break;
            default :
                $val = '';
                break;
        }
        return $val;

    }

    private $mailStatus = array(
        '��', '��'
    );

    private $stampStatus = array(
        '��', '��'
    );

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

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
     * ����ֵ�����ʼ�״̬
     * @param $thisVal
     * @return mixed
     */
    public function getMailStatus($thisVal) {
        return $this->mailStatus[$thisVal];
    }

    /**
     * ����ֵ���ظ���״̬
     * @param $thisVal
     * @return mixed
     */
    public function getStampStatus($thisVal) {
        return $this->stampStatus[$thisVal];
    }

    /**
     * ��ȡ������Ϣ
     * @param $obj
     * @param iinvoiceapply $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, iinvoiceapply $strategy) {
        //��Ⱦ����
        return $strategy->getObjInfo_d($obj);
    }

    /**
     * ��ȡ������Ϣ - �鿴�޸�ʱʹ��
     * @param $obj
     * @param $perm
     * @param iinvoiceapply $strategy
     * @return array
     */
    public function getObjInfoInit_d($obj, $perm, iinvoiceapply $strategy) {
        //��ȡ����
        $rs = $strategy->getObjInfoInit_d($obj);
        //��Ʊ����ids
        $planIds = null;

        //��Ⱦ����
        if ($perm == 'view') {
            return $strategy->initView_d($rs);
        } else {
            $rs = $strategy->initEdit_d($rs);
            return array($rs, $planIds);
        }
    }

    /**
     * �ص�����
     * @param $obj
     * @param iinvoiceapply $strategy
     * @return mixed
     */
    public function businessDeal_i($obj, iinvoiceapply $strategy) {
        return $strategy->businessDeal_i($obj, $this);
    }

    /**
     * �ʼ����û�ȡ
     * @param int $thisVal
     * @return array
     */
    function getMailInfo_d($thisVal = 0) {
        $thisKey = $thisVal ? 'financeInvoiceapplyOffSite' : 'financeInvoiceapply'; // �ʼ����û�ȡ
        include(WEB_TOR . "model/common/mailConfig.php");
        return isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
            'sendName' => '');
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'invoiceType', 'customerType', 'objType'
    );

    /***************************************************************************************************
     * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
     *************************************************************************************************/

    /**
     * ��ʾ��Ʊ������ʷ�б�ģ��
     * @param $hisApplys
     * @return string
     */
    function showhisapplylist($hisApplys) {
        if ($hisApplys) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            $datadictDao = new model_system_datadict_datadict();
            foreach ($hisApplys as $val) {
                $i++;
                $invoiceType = $datadictDao->getDataNameByCode($val['invoiceType']);
                $str .= <<<EOT
					<tr height="28px">
						<td>$i</td>
						<td><a href="?model=finance_invoiceapply_invoiceapply&action=init&id=$val[id]&perm=view">$val[applyNo]</a></td>
						<td>$val[applyDate]</td>
						<td>$invoiceType</td>
						<td class="formatMoney">$val[invoiceMoney]</td>
						<td class="formatMoney">$val[payedAmount]</td>
						<td>{$val['ExaStatus']}</td>
					</tr>
EOT;
            }
        } else {
            $str = '<tr height="28px"><td colspan="7">����</td></tr>';
        }
        return $str;
    }

    /***************************************************************************************************
     * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
     *************************************************************************************************/

    /**
     * ��дadd_d
     * @param $object
     * @param null $act
     * @return bool
     */
    function add_d($object, $act = null) {
        $codeRuleDao = new model_common_codeRule();
        $otherDataDao = new model_common_otherdatas();
        $userInfo = $otherDataDao->getUserDatas($_SESSION['USER_ID']);

        if ($rs = $this->infoCheck_d($object)) {
            return $rs;
        }

        try {
            $this->start_d();
            //�Զ�������Ʊ���뵥��
            $object ['applyNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'KPSQ');
            $object ['applyDate'] = day_date;
            $object ['ExaStatus'] = WAITAUDIT;
            $apply ['status'] = 'KPTZ-00';
            $apply ['status'] = 'KPTZ-00';

            //��ȡ��Ʊ������ϸ
            if (isset($object['invoiceDetail'])) {
                $detailRows = $object['invoiceDetail'];
                unset($object['invoiceDetail']);
            }

            //��ȡ�ۿ���Ϣ
            if (isset($object['deductinfo'])) {
                $deductinfoRows = $object['deductinfo'];
                unset($object['deductinfo']);
            }

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //��������������
            $object['salemanArea'] = $userInfo['areaname'];
            if (empty($object['managerId'])) {
                $managerArr = $otherDataDao->getManager($_SESSION['USER_ID']);
                $object['managerId'] = $managerArr['managerId'];
                $object['managerName'] = $managerArr['managerName'];
            }

            //���뿪Ʊ����������Ϣ
            $newId = parent::add_d($object, true);

            //���뿪Ʊ��ϸ
            if (isset($detailRows)) {
                $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
                $invoiceDetailDao->createBatch($detailRows, array('invoiceApplyId' => $newId), 'productName');
            }

            //����ۿ���Ϣ
            if (isset($deductinfoRows)) {
                $deductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
                $deductinfoDao->createBatch($deductinfoRows, array('invoiceApplyId' => $newId), 'grade');
            }

            //ҵ����Ϣ����
            $newClass = $this->getClass($object['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($object, $initObj);
            }

            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
            if ($act == 'audit') {
                $emailDao = new model_common_mail();
                $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '¼��', $object['applyNo'], $object['TO_ID']);
            }

            //���¸���������ϵ
            $this->updateObjWithFile($newId, $object['orderCode']);
            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������޸Ŀ�Ʊ����
     * @param $object
     * @param null $act
     * @return bool
     */
    function edit_d($object, $act = null) {
        $rs = $this->infoCheck_d($object);
        if ($rs) {
            return $rs;
        }

        try {
            $this->start_d();

            //�޸Ŀ�Ʊ��ϸ
            $detailRows = $object['invoiceDetail'];
            unset($object['invoiceDetail']);

            //��ȡ�ۿ���Ϣ
            if (isset($object['deductinfo'])) {
                $deductinfoRows = $object['deductinfo'];
                unset($object['deductinfo']);
            }

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            $this->deleteDetail_d($object['id']);

            if (!empty($detailRows)) {
                $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
                $invoiceDetailDao->createBatch($detailRows, array('invoiceApplyId' => $object['id']), 'productName');
            }

            //����ۿ���Ϣ
            $deductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
            $deductinfoDao->delete(array('invoiceApplyId' => $object['id']));
            if (isset($deductinfoRows)) {
                $deductinfoDao->createBatch($deductinfoRows, array('invoiceApplyId' => $object['id']), 'grade');
            }

            //�ʼ�����
            if ($act == 'audit') {
                $emailDao = new model_common_mail();
                $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '�ύ', $object['applyNo'], $object['TO_ID']);
            }
            //�޸���������
            parent::edit_d($object, true);

            //ҵ����Ϣ����
            $newClass = $this->getClass($object['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($object, $initObj);
            }

            $this->commit_d();
            return $object['id'];
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Դ���༭����
     * @param $object
     * @return mixed
     */
    function orgEdit_d($object) {
        //�����ֵ䴦��
        $object = $this->processDatadict($object);
        return parent::edit_d($object, true);
    }

    /**
     * ��ȡ��Ʊ����
     * @param $id
     * @param null $perm
     * @return bool|mixed
     */
    function get_d($id, $perm = null) {
        $apply = parent::get_d($id);

        $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
        $detailRow = $invoiceDetailDao->getDetailByInvoiceApplyId($id);

        $invoiceDeductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
        $DeductinfoRow = $invoiceDeductinfoDao->getDeductinfoByInvoiceApplyId($id);

        if ($perm == 'view') {
            $arr = $invoiceDetailDao->rowsToView($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToView($DeductinfoRow);
        } else if ($perm == 'audit') {
            $arr = $invoiceDetailDao->rowsToView($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToView($DeductinfoRow);
        } else if ($perm == 'register') {
            $arr = $invoiceDetailDao->rowsToRegister($detailRow);
            $apply['allThisMoney'] = $arr[2];
            $apply['softMoney'] = $arr[3];
            $apply['hardMoney'] = $arr[4];
            $apply['repairMoney'] = $arr[5];
            $apply['serviceMoney'] = $arr[6];
            $apply['equRenalMoney'] = $arr[7];
            $apply['spaceRentalMoney'] = $arr[8];
            $apply['otherMoney'] = $arr[9];
        } else {
            $arr = $invoiceDetailDao->rowsToEdit($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToEdit($DeductinfoRow);
        }
        $apply['detail'] = $arr[0];
        $apply['invnumber'] = $arr[1];
        $apply['deductinfo'] = $arr2[0];
        $apply['invnumber2'] = $arr2[1];
        return $apply;
    }

    /**
     * ��ȡ��Ʊ�������Ϣ
     * @param $id
     * @return bool|mixed
     */
    function getInfo_d($id) {
        return parent::get_d($id);
    }

    /**
     * ����ҵ����Ϣ��ȡ��Ʊ������Ϣ
     * @param $objCode
     * @param $objType
     * @param null $applyNo
     * @return mixed
     */
    function getInvoiceapplyByObj($objCode, $objType, $applyNo = null) {
        $this->searchArr = array('objCode' => $objCode, 'objType' => $objType);
        if (!empty($applyNo)) {
            $this->searchArr['noApplyNo'] = $applyNo;
        }
        return $this->list_d();
    }

    /**
     * ���ݿ�Ʊ����id��ȡ��ؿ�Ʊ
     * @param $id
     * @return array
     */
    function getInvoicesByApplyId_d($id) {
        $invoiceDao = new model_finance_invoice_invoice ();
        $invoices = $invoiceDao->getInvoicesByApplyId($id);

        $sconfigDao = new model_common_securityUtil ('invoice');
        $invoices = $sconfigDao->md5Rows($invoices);
        return $invoiceDao->showapplylist($invoices);
    }

    /**
     * ɾ����Ʊ��ϸ
     * @param $applyId
     * @return mixed
     */
    function deleteDetail_d($applyId) {
        return $this->_db->query('delete from oa_finance_invoiceapply_detail where invoiceApplyId=' . $applyId);
    }

    /**
     * ��ȡҵ����������뿪Ʊ�����ѿ�Ʊ���
     * @param $obj
     * @return int
     */
    function getApplyedMoney_d($obj) {
        $objs = $this->rtPostVla($obj['objType']);
        $sql = "select if(sum(c.invoiceMoney) is null,0,sum(c.invoiceMoney)) as applyedMoney from oa_finance_invoiceapply c where c.ExaStatus <> '���' and c.objId = '" . $obj['objId'] . "' and c.objType in (" . $objs . ")";
        $rs = $this->_db->getArray($sql);
        return is_array($rs) ? $rs[0]['applyedMoney'] : 0;
    }

    /**
     * ���ؿ�Ʊ����Ϳ�Ʊ��¼�е����ֵ
     * @param $obj
     * @return mixed
     */
    function getMaxInvoiceMoney_d($obj) {
        //��������
        $applyedMoney = $this->getApplyedMoney_d($obj);

        //�ѿ�Ʊ���
        $invoiceDao = new model_finance_invoice_invoice();
        $invoicedMoney = $invoiceDao->getInvoicedMoney_d($obj);

        //��ȡ��Ʊ���
        $returnMoney = $invoiceDao->getReturnMoney_d($obj);

        //ʵ�ʿ����� = ������ - ����Ʊ
        $actCanApply = bcsub($applyedMoney, $returnMoney, 2);

        return max($actCanApply, $invoicedMoney);
    }

    /**
     * ��ȡ��ͬ��Ϣ
     * @param $objId
     * @param $objType
     * @return array
     */
    function getContractInfo_d($objId, $objType) {
        $receviableDao = new model_finance_receviable_receviable();
        return $receviableDao->getInvoiceAndIncome_d($objId, $objType);
    }

    /**
     * ��ͬ��Ϣת�ɱ����ʾ
     * @param $row
     * @param $object
     * @return null|string
     */
    function showContractInfo($row, $object) {
        $str = null;
        if (is_array($row)) {
            $financeNeedReceviable = bcsub($row['invoiceMoney'], $row['incomeMoney']);
            $contractNeedReceviable = bcsub($object['contAmount'], $row['incomeMoney']);
            if ($object['invoiceMoney'] >= $object['contAmount']) {
                $red = "red";
            } else {
                $red = null;
            }
            $str = <<<EOT
				<tr>
					<td>$object[objCode]</td>
					<td class="formatMoney">$object[contAmount]</td>
					<td><span  class="formatMoney $red">$row[invoiceMoney]</span></td>
					<td class="formatMoney">$row[incomeMoney]</td>
					<td class="formatMoney">$financeNeedReceviable</td>
					<td class="formatMoney">$contractNeedReceviable</td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * ������֤����
     * @param $object
     * @return bool|string
     */
    function infoCheck_d($object) {
        $object['customerName'] = trim($object['customerName']);
        $object['linkMan'] = trim($object['linkMan']);
        $object['linkPhone'] = trim($object['linkPhone']);
        $object['unitAddress'] = trim($object['unitAddress']);
        $object['linkAddress'] = trim($object['linkAddress']);

        if (empty($object['customerName'])) {
            return '�ͻ�����û����д,���벻�ɹ�';
        }
        if (empty($object['linkMan'])) {
            return '��ϵ��û����д,���벻�ɹ�';
        }
        if (empty($object['linkPhone'])) {
            return '�绰û����д,���벻�ɹ�';
        }

        if (empty($object['unitAddress']) && empty($object['linkAddress'])) {
            return '��Ʊ��λ��ַ��Ʊ�ʼĵ�ַ������дһ��';
        }

        if ($object['invoiceMoney'] == 0) {
            return '��Ʊ������Ϊ0,���벻�ɹ�,�絥������ؽ�����ݲ��ܹ��Զ�����,��������������';
        }

        $allInvoiceMoney = 0;
        $allAmount = 0;

        foreach ($object['invoiceDetail'] as $val) {
            $rowAllMoney = 0;//�н��
            $val['productName'] = trim($val['productName']);
            if (empty($val['productName'])) {
                return '��Ʊ������ϸ�еĻ�Ʒ����û����д,���벻�ɹ�';
            }

            if ($val['amount'] == 0 || empty($val['amount'])) {
                return '��Ʊ����Ϊ0,���벻�ɹ�';
            }

            if (empty($val['psTyle'])) {
                return '��Ʊ������ϸ�еĲ�Ʒ��������û����д,���벻�ɹ�';
            }

            $rowAllMoney = bcadd($rowAllMoney, $val['softMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['hardMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['repairMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['serviceMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['equRentalMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['spaceRentalMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['otherMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['dsEnergyCharge'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['dsWaterRateMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['houseRentalFee'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['installationCost'], 2);
            if ($rowAllMoney == 0) {
                return '��Ʊ������ϸ�д��ڵ��н��Ϊ0,���벻�ɹ�,�絥������ؽ�����ݲ��ܹ��Զ�����,��������������';
            }
            $allAmount = bcadd($allAmount, $val['amount'], 2);
            $allInvoiceMoney = bcadd($allInvoiceMoney, $rowAllMoney, 2);
        }
        if ($allInvoiceMoney != $object['invoiceMoney']) {
            return '��Ʊ������ϸ�ܽ���뵥�������ܽ���,���벻�ɹ�,�絥������ؽ�����ݲ��ܹ��Զ�����,��������������';
        }
        return false;
    }

    /************************** �º�ͬ������ **************************/
    /**
     * ��ȡ��Ʊ������
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyedMoneyNew_d($objId, $objType = 'KPRK-12') {
        $sql = "SELECT SUM(invoiceLocal) AS invoiceLocal FROM " . $this->tbl_name .
            ' WHERE objId = ' . $objId . ' AND objType ="' . $objType . '"';
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['invoiceLocal']) {
            return $rs[0]['invoiceLocal'];
        } else {
            return 0;
        }
    }

    /**
     * ɾ��
     * @param $id
     * @return bool
     */
    function deletes_d($id) {
        try {
            $this->start_d();
            //�鿴����ֶ�
            $obj = $this->find(array('id' => $id), null, 'objId,objType');

            $this->deletes($id);

            //ҵ����Ϣ����
            $newClass = $this->getClass($obj['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($obj, $initObj);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Ĭ��������˾��ȡ
     * @return bool|mixed
     */
    function getDefaultExpressCom_d() {
        $logisticsDao = new model_mail_logistics_logistics();
        $rs = $logisticsDao->find(array('isDefault' => 1), null, 'companyName,id');
        if ($rs) {
            return $rs;
        } else {
            return $logisticsDao->find(null, 'id desc', 'companyName,id');
        }
    }

    /**
     * �����ɹ����ڸ����б������Ϣ
     * @param $spid
     * @return int
     * @throws Exception
     */
    function dealAfterAudit_d($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];
        $userId = $folowInfo['Enter_user'];

        $object = $this->getInfo_d($objId);
        if ($object['isNeedStamp'] == "1") {

            if ($userId == $object['createId']) {
                $userName = $object['createName'];
            } else {
                $userName = $object['principalName'];
            }

            //��������
            $object = array(
                "contractId" => $object['id'],
                "contractCode" => $object['applyNo'],
                "contractType" => 'HTGZYD-06',
                "signCompanyName" => $object['customerName'],
                "signCompanyId" => $object['customerId'],
                "contractMoney" => 0,
                "applyUserId" => $userId,
                "applyUserName" => $userName,
                "applyDate" => day_date,
                "stampType" => $object['stampType'],
                "status" => 0
            );
            $stampDao = new model_contract_stamp_stamp();
            $stampDao->addStamps_d($object, true);
            return 1;
        }
        return 1;
    }
}