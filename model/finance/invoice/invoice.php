<?php

/**
 * ��Ʊ�Ǽ�model����
 */
class model_finance_invoice_invoice extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoice";
        $this->sql_map = "finance/invoice/invoiceSql.php";
        parent::__construct();
    }

    public $chCode = array('1' => 'һ', '��', '��', '��');

    public $egCode = array('1' => 'One', 'Two', 'Three', 'Four');

    //��Ʊ��Ϣ�б�����������ȽϺ�תΪ�����ֶ�
    private $infoArr = array('invoiceUnitName' => '��Ʊ��λ', 'objType' => 'Դ������', 'objCode' => 'Դ�����',
        'invoiceType' => '��Ʊ����', 'invoiceTime' => '��Ʊ����', 'deptName' => '����',
        'salesman' => 'ҵ��Ա', 'invoiceMoney' => '��Ʊ���', 'softMoney' => '������',
        'hardMoney' => 'Ӳ�����', 'repairMoney' => 'ά�޽��', 'serviceMoney' => '������',
        'allAmount' => '��Ʊ����', 'invoiceNo' => '��Ʊ����', 'psType' => '��Ʒ/��������',
        'invoiceContent' => '��Ʒ����/������Ŀ', 'managerName' => '����',
        'invoiceUnitProvince' => '�ͻ�ʡ��', 'invoiceUnitType' => '�ͻ�����',
        'equRentalMoney' => '�豸���޽��', 'spaceRentalMoney' => '�������޽��', 'otherMoney' => '�������'
    );

    //��Ҫ���������
    private $needDealArr = array('KPRK-12');

    public $datadictInfo = array('invoiceType', 'objType', 'invoiceUnitType');

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'invoiceType', 'invoiceUnitType', 'objType'
    );

    /**
     * ��Ʊ���͹���
     * @param $thisVal
     * @return null|string
     */
    function rtPostVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-07' :
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
     * ��Ʊ���͹���
     * @param $thisVal
     * @return null|string
     */
    function rtPostVlaForSelect($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-07' :
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
    function rtTypeVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'all' :
                $val = 'ȫ����ͬ';
                break;
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = '���ۺ�ͬ(������ʱ��ͬ)';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = '�����ͬ(������ʱ��ͬ)';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = '���޺�ͬ(������ʱ��ͬ)';
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                $val = '�з���ͬ(������ʱ��ͬ)';
                break;
            case 'KPRK-09' :
                $val = '������ͬ';
                break;
            case 'KPRK-10' :
                $val = '�������';
                break;
            case 'KPRK-11' :
                $val = 'ά�����뵥';
                break;
            case 'KPRK-12' :
                $val = '������ͬ';
                break;
            default :
                $val = $thisVal;
                break;
        }
        return $val;
    }

    /**
     * ��Ʊ���ͷ��ض�Ӧ������
     * @param $thisVal
     * @return null|string
     */
    function rtTypeClass($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-07' :
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

    /***************************************************************************************************
     * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
     *************************************************************************************************/

    /**
     * ��Ʊ�Ǽ����У�һ��������ʾĳ����Ʊ����ķ�Ʊ��Ϣ
     * @param $rows
     * @return array
     */
    function showapplylist($rows) {
        $str = null; //���ص�ģ���ַ���
        $allMoney = 0;
        if ($rows) {
            $i = 0; //�б��¼���
            foreach ($rows as $val) {
                $i++;
                if ($val['isMail']) {
                    $actStr = <<<EOT
                        <a href="?model=finance_invoice_invoice&action=toEditInApply&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="�༭��Ʊ�Ǽ�" class="thickbox">�༭</a>
EOT;
                    $actStr .= " <a href='#' onclick='viewMailInfo(" . $val['id'] . ",\"" . $val['invoiceNo'] . "\",\"" . $val['skey_'] . "\");'>�ʼ���Ϣ</a>";
                } else {
                    $actStr = <<<EOT
                        <a href="?model=finance_invoice_invoice&action=toEditInApply&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="�༭��Ʊ�Ǽ�" class="thickbox">�༭</a>
EOT;
                    $actStr .= "  <a href='#' onclick='addMailInfo(" . $val['id'] . ",\"" . $val['invoiceNo'] . "\",\"" . $val['skey_'] . "\");'>¼���ʼ�</a>";
                }
                $str .= <<<EOT
						<tr>
							<td><input type="checkbox" name="datacb"  value="$val[id]" onclick="checkOne();"></td>
							<td>$i</td>
							<td><a href="?model=finance_invoice_invoice&action=init&perm=view&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=950" title="�鿴��Ʊ�Ǽ�" class="thickbox">$val[invoiceNo]</a></td>
							<td class="formatMoney">$val[softMoneyCur]</td>
							<td class="formatMoney">$val[hardMoneyCur]</td>
							<td class="formatMoney">$val[repairMoneyCur]</td>
							<td class="formatMoney">$val[serviceMoneyCur]</td>
							<td class="formatMoney">$val[equRentalMoneyCur]</td>
							<td class="formatMoney">$val[spaceRentalMoneyCur]</td>
							<td class="formatMoney">$val[otherMoneyCur]</td>
							
							<td class="formatMoney">$val[dsEnergyChargeCur]</td>
                            <td class="formatMoney">$val[dsWaterRateMoneyCur]</td>
                            <td class="formatMoney">$val[houseRentalFeeCur]</td>
                            <td class="formatMoney">$val[installationCostCur]</td>
							
							<td class="formatMoney">$val[invoiceMoneyCur]</td>
							<td>$val[createName]</td>
							<td>$val[invoiceTime]
                                <input type="hidden" id="invoiceMoney$i" value="$val[invoiceMoneyCur]"/>
                                <input type="hidden" id="isRed$val[id]" value="$val[isRed]"/>
                                <input type="hidden" id="isMail$val[id]" value="$val[isMail]"/>
                            </td>
							<td>
                                $actStr
							</td>
						</tr>
EOT;
                $allMoney = bcadd($allMoney, $val['invoiceMoneyCur'], 2);
            }
        } else {
            $str = '<tr><td colspan="20">���������Ϣ</td></tr>';
        }
        return array($str, $allMoney);
    }

    /**
     * ��ʾ��Ʊ������������ҳ��
     * @param $rows
     * @return null|string
     */
    function showInvoiceBatchDeal($rows) {
        $str = null;
        if ($rows) {
            $i = 0;
            $datadictArr = $this->getDatadicts("CWCPLX");
            foreach ($rows as $val) {
                $i++;
                $productLineStr = $this->getDatadictsStr($datadictArr ['CWCPLX'], $val ['productModel']);
                $str .= <<<EOT
					<tr>
						<td>$val[invoiceNo]</td>
						<td>$val[invoiceTime]</td>
						<td>$val[productName]</td>
						<td class="formatMoney">$val[softMoney]</td>
						<td class="formatMoney">$val[hardMoney]</td>
						<td class="formatMoney">$val[repairMoney]</td>
						<td class="formatMoney">$val[serviceMoney]</td>
						<td class="formatMoney">$val[equRentalMoney]</td>
						<td class="formatMoney">$val[spaceRentalMoney]</td>
						<td class="formatMoney">$val[otherMoney]</td>
						<td>
							<select name="invoice[$i][productModel]">
								$productLineStr
							</select>
							<input type="hidden" name="invoice[$i][id]" value="$val[detailId]"/>
							<input type="hidden" name="invoice[$i][invoiceId]" value="$val[id]"/>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /***************************************************************************************************
     * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
     *************************************************************************************************/

    /**
     * ���ݿ�Ʊ�����ȡ��Ʊ��¼�б�
     * @param $applyId
     * @return mixed
     */
    function getInvoicesByApplyId($applyId) {
        $this->setCompany(0);
        $this->searchArr = array("applyId" => $applyId);
        $this->asc = false;
        return parent::list_d();
    }

    /**
     * ���ݺ�ͬ��Ż�ȡ��Ʊ��Ϣ
     * @param $contractNumber
     * @return mixed
     */
    function getInvoicesByContractNumber($contractNumber) {
        $this->setCompany(0);
        $this->searchArr = array("contractNumber" => $contractNumber);
        return parent::list_d();
    }

    /**
     * ����Դ��id��Դ�����ͻ�ȡ��Ʊ��¼
     * @param $objId
     * @param string $objType
     * @return mixed
     */
    function getInvoices_d($objId, $objType = 'KPRK-01') {
        $this->setCompany(0);
        $this->searchArr = array(
            'objId' => $objId,
            'objType' => $objType
        );
        $this->sort = 'c.invoiceTime asc,c.createTime';
        $this->asc = false;
        return parent::list_d();
    }

    /**
     * ���ݿ�Ʊ����id����ȡ���뵥��Ϣ
     * @param $applyId
     * @return bool|mixed
     */
    function getInvoiceapply_d($applyId) {
        //��ȡ��Ʊ������Ϣ
        $applyDao = new model_finance_invoiceapply_invoiceapply ();
        $rows = $applyDao->get_d($applyId, 'register');
        //��ȡ�����˲�����Ϣ
        $otherDataDao = new model_common_otherdatas();
        $rs = $otherDataDao->getUserDatas($rows['createId'], array('DEPT_ID', 'DEPT_NAME'));
        $rows['deptId'] = $rs['DEPT_ID'];
        $rows['deptName'] = $rs['DEPT_NAME'];

        return $rows;
    }

    /**
     * ��дԭ��add_d����
     * @param $object
     * @return bool
     */
    function add_d($object) {
        $codeRuleDao = new model_common_codeRule();
        $otherDataDao = new model_common_otherdatas();

        try {
            $this->start_d();

            //��ȡ��Ʊ��ϸ
            $invoiceDetail = isset($object['invoiceDetail']) ? $object['invoiceDetail'] : null;
            unset($object['invoiceDetail']);

            //��ȡ�ʼļ�¼
            $emailArr = $object['email'];
            unset($object['email']);

            // ��������ۺ�ͬ����Ŀ�Ʊ, �ʼ���������Ӻ�ͬ������ PMS 651
            if(isset($object['objType']) && $object['objType'] == "KPRK-12"){
                $conDao = new model_contract_contract_contract();
                $conArr = $conDao->get_d($object['objId']);
                $toDoIdArr = explode(",",$emailArr['TO_ID']);
                if($conArr){
                    if(!in_array($conArr['prinvipalId'],$toDoIdArr)){
                        $emailArr['TO_NAME'] .= ($emailArr['TO_NAME'] == "")? $conArr['prinvipalName'] : ",".$conArr['prinvipalName'];
                        $emailArr['TO_ID'] .= ($emailArr['TO_ID'] == "")? $conArr['prinvipalId'] : ",".$conArr['prinvipalId'];
                    }
                }
            }

            //��Ʊ�������� create on 2013-08-14 by kuangzw
            if (isset($object['incomeCheck'])) {
                $incomeCheck = $object['incomeCheck'];
                unset($object['incomeCheck']);
            }

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            // �������ʱ����
            if (!empty($invoiceDetail) && $object['currency'] != '�����') {
                $invoiceDetailCur = $invoiceDetail; // ��Ӧ��������
                $this->dealCurrency_d($object, $invoiceDetail); // ��������;
            } else {
                $this->dealLocal_d($object); // ��������;
            }

            //��Ʊ��¼���
            $object['invoiceCode'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'KPJL');

            //��������������
            if (!isset($object['salemanArea']) || empty($object['salemanArea'])) {
                $userInfo = $otherDataDao->getUserDatas($object['salesmanId']);
                $object['salemanArea'] = $userInfo['areaname'];
            }
            if (!isset($object['managerId']) || $object['managerId'] == "") {
                $managerArr = $otherDataDao->getManager($object['salesmanId']);
                if ($managerArr) {
                    $object['managerId'] = $managerArr['managerId'];
                    $object['managerName'] = $managerArr['managerName'];
                }
            }

            $id = parent::add_d($object, true);

            //��ӷ�Ʊ��ϸ
            if (!empty($invoiceDetail)) {
                $invoiceDetailDao = new model_finance_invoice_invoiceDetail(); // ��Ʊ����ʵ����
                $invoiceDetailDao->createBatch($invoiceDetail, array('invoiceId' => $id));

                // �������ʱ����
                if ($object['currency'] != '�����') {
                    $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
                    $invoiceDetailCurDao->createBatch($invoiceDetailCur, array('invoiceId' => $id));
                }
            }

            // ��������ں�����¼�ҷ�̯����Ϊ��ͬ����ϵͳȥ�Զ�ƥ��ɺ�����������
            if (!empty($invoiceDetail) && empty($incomeCheck) && $object['objType'] == 'KPRK-12') {
                //ʵ��������������
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($object['objId'] => $object['invoiceMoney']), 2, $object['isRed']);
            }
            //��������
            if ($incomeCheck) {
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(
                    array('incomeId' => $id, 'incomeNo' => $object['invoiceCode'], 'incomeType' => 2, 'isRed' => $object['isRed']),
                    $incomeCheck
                );
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            // ���ִ�й켣��¼
            $proDao = new model_contract_conproject_conproject();
            $tracksDao = new model_contract_contract_tracks();
            $tracksObject = array(
                'contractId' => $object['objId'],//��ͬID
                'contractCode'=> $object['objCode'],//��ͬ���
                'exePortion' => $proDao->getConduleBycid($object['objId']),//��ִͬ�н���
                'modelName'=>'invoiceMoney',
                'operationName'=>'��Ʊ��¼',
                'result'=>$object['invoiceMoney'],
                'recordTime'=>$object['invoiceTime'],
                'expand1'=>$id,
                'expand2'=>'model_finance_invoice_invoice:add_d',
                'remarks'=>'expand1Ϊ��Ʊ��Ӧ��ID'
            );
            $recordId = $tracksDao->addRecord($tracksObject);

            //ҵ��������� - ����
            $this->busiessDeal_d($object);

            //�����ʼ�
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $this->thisMail_d($emailArr, $object);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������޸Ķ���
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            $this->start_d();

            //��ȡ��Ʊ��ϸ
            $invoiceDetail = isset($object['invoiceDetail']) ? $object['invoiceDetail'] : null;
            unset($object['invoiceDetail']);

            //��ȡ�ʼļ�¼
            $emailArr = $object['email'];
            unset($object['email']);

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $orgObj = $this->find(array('id' => $object['id']), null, implode(array_keys($this->infoArr), ','));
            }

            //�ؿ�������� create on 2013-08-14 by kuangzw
            if (isset($object['incomeCheck'])) {
                $incomeCheck = $object['incomeCheck'];
                unset($object['incomeCheck']);
            }

            // �������ʱ����
            if (!empty($invoiceDetail) && $object['currency'] != '�����') {
                $invoiceDetailCur = $invoiceDetail; // ��Ӧ��������
                $this->dealCurrency_d($object, $invoiceDetail); // ��������;
            } else {
                $this->dealLocal_d($object); // ��������;
            }

            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //��ӷ�Ʊ��ϸ
            if (!empty($invoiceDetail)) {
                $invoiceDetailDao = new model_finance_invoice_invoiceDetail(); // ��Ʊ����ʵ����
                $invoiceDetailDao->delete(array('invoiceId' => $object['id']));
                $invoiceDetailDao->createBatch($invoiceDetail, array('invoiceId' => $object['id']));

                // �������ʱ����
                if ($object['currency'] != '�����') {
                    $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
                    $invoiceDetailCurDao->delete(array('invoiceId' => $object['id']));
                    $invoiceDetailCurDao->createBatch($invoiceDetailCur, array('invoiceId' => $object['id']));
                }
            }

            // ��������ں�����¼�ҷ�̯����Ϊ��ͬ����ϵͳȥ�Զ�ƥ��ɺ�����������
            if (!empty($invoiceDetail) && empty($incomeCheck) && $object['objType'] == 'KPRK-12') {
                //ʵ��������������
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($object['objId'] => $object['invoiceMoney']), 2, $object['isRed']);
            }
            //��������
            if ($incomeCheck) {
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(
                    array('incomeId' => $object['id'], 'incomeNo' => $object['invoiceCode'], 'incomeType' => 2, 'isRed' => $object['isRed']),
                    $incomeCheck
                );
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            //ҵ��������� - ����
            $this->busiessDeal_d($object,true);

            //�����ʼ�
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {

                $newObj = $this->find(array('id' => $object['id']), null, implode(array_keys($this->infoArr), ','));

                $str = $this->changeArrToStr_d($orgObj, $newObj);

                $this->thisMailCustom_d($emailArr, $newObj, $str);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
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
            $obj = $this->find(array('id' => $id), null, 'objId,objType,applyId,invoiceTime,isRed,invoiceType,invoiceMoney');

            //��ȡ������¼
            $incomeCheckDao = new model_finance_income_incomecheck();
            $incomeCheckArr = $incomeCheckDao->getCheckList_d($id, '2');

            //ɾ��
            $this->deletes($id);

            //���¼���
            $this->sumInoivceApply_d($obj['applyId']);

            //ҵ��������� - ����
            $this->busiessDeal_d($obj,true);

            //�������
            if ($incomeCheckArr) {
                $incomeCheckArr = util_arrayUtil::setArrayFn(
                    array('isDelTag' => 1, 'isRed' => $obj['isRed']),
                    $incomeCheckArr
                );
                $incomeCheckDao->batchDeal_d($incomeCheckArr);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ����ת��������ַ���
     * @param $orgObj
     * @param $newObj
     * @return string
     */
    function changeArrToStr_d($orgObj, $newObj) {
        //�Ƚ��������
        $diffArr = array_diff_assoc($orgObj, $newObj);

        $str = "";
        $datadictDao = new model_system_datadict_datadict();
        if (is_array($diffArr)) {
            foreach ($diffArr as $key => $val) {
                if (in_array($key, $this->datadictInfo)) {
                    $newVal = $datadictDao->getDataNameByCode($newObj[$key]);
                    $orgVal = $datadictDao->getDataNameByCode($orgObj[$key]);
                    $str .= $this->infoArr[$key] . ' : ' . $orgVal . ' => ' . $newVal . '<br/>';
                } else {
                    $str .= $this->infoArr[$key] . ' : ' . $orgObj[$key] . ' => ' . $newObj[$key] . '<br/>';
                }
            }
        }
        return $str;
    }

    /**
     * �ʼ�����
     * @param $emailArr
     * @param $object
     * @param null $addMsg
     */
    function thisMailCustom_d($emailArr, $object, $addMsg = null) {
        $str = $_SESSION['USERNAME'] . " �޸��˷�Ʊ����Ϊ<font color='blue'>[ " . $object['invoiceNo'] . " ]</font>�Ŀ�Ʊ��¼,�޸���������:<br/><br/>" . $addMsg;

        $emailDao = new model_common_mail();
        $emailDao->mailClear('��Ʊ��¼', $emailArr['TO_ID'], $str);
    }

    /**
     * ��ȡ��Ʊ��ϸ
     * @param $id
     * @param null $perm
     * @return bool|mixed
     */
    function get_d($id, $perm = null) {
        //��ȡ������Ϣ
        $obj = parent::get_d($id);

        //��ȡ�ӱ���Ϣ
        $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
        $detailRow = $invoiceDetailDao->getDetailByInvoiceId($id);

        // ������ַ�����ң�����Ҫȡ�ȱ�Ʊ����
        $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
        $invoiceDetailCurRow = $invoiceDetailCurDao->getDetailByInvoiceId($id);

        //��Ⱦ�ӱ�
        switch ($perm) {
            case 'view':
                $arr = $invoiceDetailDao->detailView($detailRow);

                // ������ַ�����ң�����Ҫȡ�ȱ�Ʊ����
                $invoiceDetailCurShow = $invoiceDetailDao->detailView($invoiceDetailCurRow);
                $obj['invoiceDetailCur'] = $invoiceDetailCurShow[0];
                break;
            case 'register':
                $arr = $invoiceDetailDao->detailEdit($detailRow);
                break;
            case 'red':
                if ($obj['currency'] != '�����') {
                    $arr = $invoiceDetailDao->detailRed($invoiceDetailCurRow);
                    break;
                } else {
                    $arr = $invoiceDetailDao->detailRed($detailRow);
                    break;
                }
            case 'edit':
                if ($obj['currency'] != '�����') {
                    $arr = $invoiceDetailDao->detailEdit($invoiceDetailCurRow);
                    // ����ת��
                    list(
                        $obj['invoiceMoney'], $obj['softMoney'], $obj['hardMoney'], $obj['repairMoney'], $obj['serviceMoney'],
                        $obj['equRentalMoney'], $obj['spaceRentalMoney'], $obj['otherMoney']
                        ) =
                        array(
                            $obj['invoiceMoneyCur'], $obj['softMoneyCur'], $obj['hardMoneyCur'], $obj['repairMoneyCur'],
                            $obj['serviceMoneyCur'], $obj['equRentalMoneyCur'], $obj['spaceRentalMoneyCur'], $obj['otherMoneyCur']
                        );
                } else {
                    $arr = $invoiceDetailDao->detailEdit($detailRow);
                }
                break;
            default:
                $arr = $invoiceDetailDao->detailEdit($detailRow);
                break;
        }
        $obj['invoiceDetail'] = $arr[0];
        $obj['invnumber'] = $arr[1];

        return $obj;
    }

    /**
     * �޸ķ�Ʊ�ʼ�״̬
     * 0.δ�ʼ�
     * 1.���ʼ�
     * @param $id
     * @param $thisVal
     * @return mixed
     */
    function changeMailStatus_d($id, $thisVal) {
        return parent::edit_d(array('id' => $id, 'isMail' => $thisVal));
    }

    /**
     * ��֤�Ƿ��Ѿ����ں��ַ�Ʊ
     * @param $id
     * @return bool|mixed
     */
    function hasRedInvoice_d($id) {
        return $this->find(array('belongId' => $id));
    }

    /**
     * ���¼��㿪Ʊ����Ŀ�Ʊ���
     * @param $applyId
     * @return bool
     */
    function sumInoivceApply_d($applyId) {
        $this->setCompany(0);
        $this->searchArr = array('applyId' => $applyId, 'isRed' => 0);
        $rows = $this->list_d('sumApplyMoney');
        $invoiceMoney = $rows[0]['invoiceMoney'] != 0 ? $rows[0]['invoiceMoney'] : 0;

        $applyDao = new model_finance_invoiceapply_invoiceapply();
        $applyDao->updateField(array('id' => $applyId), 'payedAmount', $invoiceMoney);

        return true;
    }

    /**
     * ��ȡ��ͬ�Ŀ�Ʊ���ݽ��
     * @param $objCode
     * @param $type
     * @return array $invoiceMoney
     */
    function sumMoneyByObjCode_d($objCode, $type) {
        $invoiceMoney = '';
        if($objCode != ''){
            $sum_condition = '';
            switch($type){
               case 'red':
                   $sum_sql = "SUM(c.invoiceMoney) AS invoiceMoney";
                   $sum_condition = "and c.isRed = 1";
                   break;
               case 'blue':
                   $sum_sql = "SUM(c.invoiceMoney) AS invoiceMoney";
                   $sum_condition = "and c.isRed = 0";
                   break;
               default:
                   $sum_sql = "SUM(IF(isRed = 0,c.invoiceMoney,-c.invoiceMoney)) AS invoiceMoney";
                   break;
           }
           $sql = "SELECT
						".$sum_sql."
				    FROM
					    oa_finance_invoice c
					WHERE
						c.objCode = '".$objCode."' ".$sum_condition;
           $invoiceMoney = $this->_db->getArray($sql);
           $invoiceMoney[0]['sql'] = $sql;
        }
        return $invoiceMoney;
    }
    /**
     * ɾ�����ֿ�Ʊ - ���Ҽ�����ڶ�Ӧ��Ʊ�������Ʊ���
     * @param $id
     * @param $belongId
     * @return bool
     */
    function deletesRed_d($id, $belongId) {
        try {
            $this->start_d();

            //ɾ����¼
            $this->deletes($id);

            $blueObj = $this->find(array('id' => $belongId), null, 'applyId');

            //���¼���
            $this->sumInoivceApply_d($blueObj['applyId']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->commit_d();
            return false;
        }
    }

    /**
     * ���¼��㿪Ʊ�������Ʊ���
     * @param $applyId
     * @return bool
     */
    function sumReturnMoney_d($applyId) {
        $returnMoney = $this->get_table_fields($this->tbl_name, "applyId = '$applyId' and isRed = 1", 'sum(invoiceMoney)');

        $applyDao = new model_finance_invoiceapply_invoiceapply();
        $applyDao->updateField(array('id' => $applyId), 'returnMoney', $returnMoney);

        return true;
    }

    /**
     * �ʼ�����
     * @param $emailArr
     * @param $object
     * @param string $thisAct
     */
    function thisMail_d($emailArr, $object, $thisAct = '����') {
        $datadictDao = new model_system_datadict_datadict();
        $invoiceType = $datadictDao->getDataNameByCode($object['invoiceType']);
        $addMsg = '�Ѿ����� : ' . $invoiceType . ',<br/>��Ʊ���� �� ' . $object['invoiceNo'] . ',<br/>��Ʊ���Ϊ��' . $object['invoiceMoney'] . ',<br/>��Ʊ��λΪ��' . $object['invoiceUnitName'];
		if($object['objType'] == 'KPRK-12'){// ����Ƕ�����ͬ��������ͬ���ƣ���ͬ��
			$conDao = new model_contract_contract_contract();
			$rs = $conDao->find(array('id' => $object['objId']),null,'contractCode,contractName');
			if(!empty($rs)){
				$addMsg .= ',<br/>��ͬ : '.$rs['contractName'].'('.$rs['contractCode'].')';
			}
		}
        $emailDao = new model_common_mail();
        $emailDao->batchEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $thisAct, $object['invoiceNo'], $emailArr['TO_ID'], $addMsg, '1');
    }

    /**
     *  ��ȡĬ���ʼ�������
     */
    function getSendMen_d() {
        include(WEB_TOR . "model/common/mailConfig.php");
        return isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('sendUserId' => '',
            'sendName' => '');
    }

    /*****************************��Ʊ����ҳ��*****************************/
    /**
     * ����ϸ��Ŀ���ϵ�GRID��
     * @param $object
     * @param int $dealType
     * @param null $selectSql
     * @return array|mixed
     */
    public function rebuildList_d($object, $dealType = 1, $selectSql = null) {
        //��ȡ������id�����id��
        $ids = "";

        foreach ($object as $key => $val) {
            if ($key == 0) {
                $ids .= $val['id'];
            } else {
                $ids .= ',' . $val['id'];
            }
        }

        $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
        //����id�����ҿ�Ʊ��ϸ��Ϣ
        $detailRow = $invoiceDetailDao->getDetailByGroup_d($ids);
        //������ת��key=>array ��ʽ
        $detailRow = $this->changeIdForArr_d($detailRow);

        //�������
        $object = $this->mergeArr_d($object, $detailRow);

        if ($dealType == 1) {
            //ͳ�ƽ���������ͳ����
            $object = $this->countRows_d($object, $selectSql);
        }
        return $object;
    }

    /**
     * �ع��б�
     * ��������Ŀ����Ҫ��ȡ��ͬ��Ϣ
     * @param $object
     * @param int $dealType
     * @param null $selectSql
     * @return array
     */
    public function rebuildList2_d($object, $dealType = 1, $selectSql = null) {
        if ($dealType == 1) {
            //ͳ�ƽ���������ͳ����
            $object = $this->countRows_d($object, $selectSql);
        }
        return $object;
    }

    /**
     * �ع��б�
     * ����ϸ��Ŀ���ϵ�GRID��
     * @param $object
     * @return mixed
     */
    public function rebuildListNotMerge_d($object) {
        $customerDao = new model_customer_customer_customer();
        $customerArr = array();
        $dataArr = null;
        foreach ($object as $key => $val) {

            if (empty($val['customerType']) || empty($val['customerProvince'])) {
                if (isset($customerArr[$val['invoiceUnitId']])) {
                    $object[$key]['customerType'] = $customerArr[$val['invoiceUnitId']]['TypeOne'];
                    $object[$key]['customerProvince'] = $customerArr[$val['invoiceUnitId']]['Prov'];
                } else {
                    $customerArr[$val['invoiceUnitId']] = $customerDao->find(array('id' => $val['invoiceUnitId']), 'TypeOne,Prov');
                    $object[$key]['customerType'] = $customerArr[$val['invoiceUnitId']]['TypeOne'];
                    $object[$key]['customerProvince'] = $customerArr[$val['invoiceUnitId']]['Prov'];
                }
            }

            if (!isset($dataArr[$object[$key]['invoiceType']])) {
                $dataArr = $this->datadictArrSearch_d($dataArr, $object[$key]['invoiceType']);
            }
            $object[$key]['invoiceType'] = $dataArr[$object[$key]['invoiceType']];


            if (!isset($dataArr[$object[$key]['customerType']])) {
                $dataArr = $this->datadictArrSearch_d($dataArr, $object[$key]['customerType']);
            }
            $object[$key]['customerType'] = $dataArr[$object[$key]['customerType']];
        }

        return $object;
    }

    /**
     * �����ֵ��ѯ����
     * @param $dataArr
     * @param $dataCode
     * @return mixed
     */
    function datadictArrSearch_d($dataArr, $dataCode) {
        $datadictDao = new model_system_datadict_datadict();
        $rtName = $datadictDao->getDataNameByCode($dataCode);
        $dataArr[$dataCode] = $rtName;
        return $dataArr;
    }

    /**
     * ��������,�����ʽΪ:
     * array( 'id' => array(0 = '�豸����1.�豸����2' �� 1 => '����') ��
     * @param $object
     * @return array
     */
    private function changeIdForArr_d($object) {
        $objArr = array();
        foreach ($object as $val) {
            $objArr[$val['invoiceId']]['productName'] = $val['productName'];
            $objArr[$val['invoiceId']]['amount'] = $val['amount'];
            $objArr[$val['invoiceId']]['psType'] = $val['psType'];
        }
        return $objArr;
    }

    /**
     * �ϲ�����ʹӱ�����
     * @param $object
     * @param $objectDetail
     * @return mixed
     */
    private function mergeArr_d($object, $objectDetail) {
        foreach ($object as $key => $val) {
            if (isset($objectDetail[$val['id']])) {
                $object[$key] = array_merge($object[$key], $objectDetail[$val['id']]);
            }
        }
        return $object;
    }

    /**
     * ͳ�ƽ�������ͳ����
     * @param $object
     * @param $selectSql
     * @return array
     */
    private function countRows_d($object, $selectSql) {
        $thisPageArr = array(
            'id' => 'noId2',
            'amount' => 0,
            'softMoney' => 0,
            'hardMoney' => 0,
            'repairMoney' => 0,
            'serviceMoney' => 0,
            'equRentalMoney' => 0,
            'spaceRentalMoney' => 0,
            'otherMoney' => 0,
            'dsEnergyCharge' => 0,
            'dsWaterRateMoney' => 0,
            'houseRentalFee' => 0,
            'installationCost' => 0,
            'invoiceMoney' => 0
        );
        //��ҳͳ�ƺϼ�
        if (is_array($object)) {
            foreach ($object as $val) {
                $thisPageArr['amount'] = bcadd($thisPageArr['amount'], $val['allAmount'], 2);
                $thisPageArr['softMoney'] = bcadd($thisPageArr['softMoney'], $val['softMoney'], 2);
                $thisPageArr['hardMoney'] = bcadd($thisPageArr['hardMoney'], $val['hardMoney'], 2);
                $thisPageArr['repairMoney'] = bcadd($thisPageArr['repairMoney'], $val['repairMoney'], 2);
                $thisPageArr['serviceMoney'] = bcadd($thisPageArr['serviceMoney'], $val['serviceMoney'], 2);
                $thisPageArr['equRentalMoney'] = bcadd($thisPageArr['equRentalMoney'], $val['equRentalMoney'], 2);
                $thisPageArr['spaceRentalMoney'] = bcadd($thisPageArr['spaceRentalMoney'], $val['spaceRentalMoney'], 2);
                $thisPageArr['otherMoney'] = bcadd($thisPageArr['otherMoney'], $val['otherMoney'], 2);
                $thisPageArr['dsEnergyCharge'] = bcadd($thisPageArr['dsEnergyCharge'], $val['dsEnergyCharge'], 2);
                $thisPageArr['dsWaterRateMoney'] = bcadd($thisPageArr['dsWaterRateMoney'], $val['dsWaterRateMoney'], 2);
                $thisPageArr['houseRentalFee'] = bcadd($thisPageArr['houseRentalFee'], $val['houseRentalFee'], 2);
                $thisPageArr['installationCost'] = bcadd($thisPageArr['installationCost'], $val['installationCost'], 2);
                $thisPageArr['invoiceMoney'] = bcadd($thisPageArr['invoiceMoney'], $val['invoiceMoney'], 2);

            }
        }
        //��ѯ��¼�ϼ�
        unset($this->sort);
        $objArr = $this->listBySqlId($selectSql . '_sum');
        if (is_array($objArr)) {
            $rsArr = $objArr[0];
            $rsArr['thisAreaName'] = '�ϼ�';
            $rsArr['id'] = 'noId';
        } else {
            $rsArr = array(
                'id' => 'noId',
                'amount' => 0,
                'softMoney' => 0,
                'hardMoney' => 0,
                'repairMoney' => 0,
                'serviceMoney' => 0,
                'equRentalMoney' => 0,
                'spaceRentalMoney' => 0,
                'otherMoney' => 0,
                'dsEnergyCharge' => 0,
                'dsWaterRateMoney' => 0,
                'houseRentalFee' => 0,
                'installationCost' => 0,
                'invoiceMoney' => 0
            );
        }

        $object[] = $thisPageArr;
        $object[] = $rsArr;
        return $object;
    }

    /*****************************��Ʊ����ҳ��*****************************/

    /***************************��Ʊ���Ԥ������*******************************/
    /**
     * ��ȡ��ƱԤ��
     */
    function getYearPlan_d() {
        $yearPlan = new model_finance_invoice_yearPlan();
        return $yearPlan->getYearPlan_d();
    }

    /**
     * ��Ʊ���Ԥ��
     * @param $object
     * @return array
     */
    public function getInvoicePerView_d($object) {
        //��
        $year = $object['year'];

        //��Ʊ����
        $objType = $this->rtPostVla($object['objType']);

        //�����ۼƿ�Ʊ��� (�����ͷ�)
        $allInvoiceRows = $this->getAllInvoiceInTheYear_d($year, $objType);

        //���ȿ�Ʊ��� �� ���ݿ�Ʊ���ͷ��� (�����ͷ�)
        $invoicedGroupArr = $this->invoicedByType_d($year);

        //ת������Ϊ����δ�����
        $quarterRows = $this->quarterCanInvoice_d($invoicedGroupArr, $object);

        //�ϲ�δ�����
        $object = array_merge($object, $quarterRows);

        //�ϲ��ۼƿ�Ʊ���
        $object = array_merge($object, $allInvoiceRows);

        //�����ѿ���� - ͳ�Ƽ��Ƚ��
        $object['quarterArr'] = $this->getInvoiced_d($year, $objType);

        return $object;
    }

    /**
     * ��ȡȫ�꿪Ʊ���
     * @param $year
     * @param null $objType
     * @return mixed
     */
    public function getAllInvoiceInTheYear_d($year, $objType = null) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        if ($objType) {
            $this->searchArr['objTypes'] = $objType;
        }
        $rows = $this->listBySqlId('allInvoiceInTheYear');
        return $rows[0];
    }

    /**
     * ��ȡ�����ѿ����
     * @param $year
     * @param null $objType
     * @return mixed
     */
    public function getInvoiced_d($year, $objType = null) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        if ($objType) {
            $this->searchArr['objTypes'] = $objType;
        }
        $this->groupBy = 'QUARTER(c.invoiceTime)';
        $this->sort = 'QUARTER(c.invoiceTime)';
        $this->asc = false;
        return $this->listBySqlId('invoiced');
    }

    /**
     * ���ȿ�Ʊ����ת��HTML
     * @param $object
     * @return null|string
     */
    public function showQuarterList($object) {
        $i = 0;
        $str = null;
        $tempCode = null;
        foreach ($object as $val) {
            $i++;
            $trClass = ($i % 2) == 1 ? 'tr_odd' : 'tr_even';
            $tempCode = $this->chCode[$val['thisQuarter']];
            $str .= <<<EOT
				<tr class="$trClass">
		        	<td>��{$tempCode}����</td>
		        	<td class="formatMoney">{$val['softMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['hardMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['repairMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['serviceMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['equRentalMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['spaceRentalMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['otherMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['money_Quarter']}</td>
	        	</tr>
EOT;
        }
        return $str;
    }

    /**
     * �����ѿ���� ���ݿ�Ʊ���ͷ���
     * @param $year
     * @return mixed
     */
    public function invoicedByType_d($year) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        $this->groupBy = 'QUARTER(c.invoiceTime),c.invoiceType ';
        $this->sort = 'QUARTER(c.invoiceTime)';
        $this->asc = false;
        return $this->listBySqlId('invoicedByType');
    }

    /**
     * @param $object
     * @param $planObject
     * @return array
     */
    private function quarterCanInvoice_d($object, $planObject) {
        $tmp = null;
        $rs = array(
            'salesCan_QuarterOne' => $planObject['salesOne'], 'serviceCan_QuarterOne' => $planObject['serviceOne'],
            'salesCan_QuarterTwo' => $planObject['salesTwo'], 'serviceCan_QuarterTwo' => $planObject['serviceTwo'],
            'salesCan_QuarterThree' => $planObject['salesThree'], 'serviceCan_QuarterThree' => $planObject['serviceThree'],
            'salesCan_QuarterFour' => $planObject['salesFour'], 'serviceCan_QuarterFour' => $planObject['serviceFour']
        );
        foreach ($object as $val) {
            if ($val['invoiceType'] == 'normal' || $val['invoiceType'] == 'added') {
                $tmp = 'salesCan_Quarter' . $this->egCode[$val['thisQuarter']];
                $rs[$tmp] = bcsub($rs[$tmp], $val['money_Quarter'], 2);
            } else {
                $tmp = 'serviceCan_Quarter' . $this->egCode[$val['thisQuarter']];
                $rs[$tmp] = bcsub($rs[$tmp], $val['money_Quarter'], 2);

            }
        }
        return $rs;
    }

    /***************************��Ʊ���Ԥ������*******************************/

    /**************************************��Ʊ���벿��*********************************/
    /**
     * ��Ʊ���빦��
     * @param int $isCheck
     */
    function addExecelData_d($isCheck = 1) {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//�������
        $contArr = array();//��ͬ��Ϣ����
        $contDao = new model_contract_contract_contract();
        $customerArr = array();//�ͻ���Ϣ����
        $customerDao = new model_customer_customer_customer();
        $datadictArr = array();//�ͻ���Ϣ����
        $datadictDao = new model_system_datadict_datadict();
        $userArr = array();//��ͬ��Ϣ����
        $otherDataDao = new model_common_otherdatas();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5]) && empty($val[6]) && empty($val[7]) && empty($val[8]) && empty($val[9]) && empty($val[10]) && empty($val[11]) && empty($val[12])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            $val[0] = trim($val[0]);
                            //�жϵ�������
                            $invoiceTime = date('Y-m-d', (mktime(0, 0, 0, 1, $val[0] - 1, 1900)));
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�п�Ʊ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[1])) {
                            $val[1] = trim($val[1]);
                            //�ͻ�����
                            if (!isset($customerArr[$val[1]])) {
                                $rs = $customerDao->findCus($val[1]);
                                if (is_array($rs)) {
                                    $customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
                                    $prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
                                    $customerName = $val[1];
                                    $customerType = $customerArr[$val[1]]['typeOne'] = $rs[0]['TypeOne'];
                                    $areaName = $customerArr[$val[1]]['areaName'] = $rs[0]['AreaName'];
                                    $areaId = $customerArr[$val[1]]['areaId'] = $rs[0]['AreaId'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�ͻ�ϵͳ�в����ڴ˿ͻ�';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $customerId = $customerArr[$val[1]]['id'];
                                $prov = $customerArr[$val[1]]['prov'];
                                $customerType = $customerArr[$val[1]]['TypeOne'];
                                $areaName = $customerArr[$val[1]]['areaName'];
                                $areaId = $customerArr[$val[1]]['areaId'];
                                $customerName = $val[1];
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�пͻ�����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($isCheck == 1) {//����һ����Ҫ��֤��ͬ��
                            if (!empty($val[2])) {
                                $val[2] = trim($val[2]);
                                //������ͬ��������
                                $contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->getContractInfoByCode($val[2], 'main');
                                if (is_array($contArr[$val[2]])) {
                                    $orderId = $contArr[$val[2]]['id'];
                                    $rObjCode = $contArr[$val[2]]['objCode'];
                                    $orderCode = $val[2];
                                    $managerName = $contArr[$val[2]]['areaPrincipal'];
                                    $managerId = $contArr[$val[2]]['areaPrincipalId'];
                                    $areaName = $contArr[$val[2]]['areaName'];
                                    $areaId = $contArr[$val[2]]['areaCode'];
                                    $orderType = 'KPRK-12';

                                    if (empty($managerName)) {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!��Ӧ��ͬ��û�ж�Ӧ����������';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
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
                        } else {//����һ������Ҫ��֤�����ź�
                            $orderCode = trim($val[2]);
                            $orderId = $orderType = $rObjCode = $managerName = $managerId = "";
                        }

                        /**
                         * ��Ʊ��Ŀ�����ж�
                         */
                        if (!empty($val[3])) {
                            $val[3] = trim($val[3]);
                            $invoiceProduct = $val[3];
                            if (!isset($datadictArr[$val[3]])) {
                                $rs = $datadictDao->getCodeByName('KPXM', $val[3]);
                                if (!empty($rs)) {
                                    $invoiceProductCode = $datadictArr[$val[3]]['code'] = $rs;
                                } else {
                                    $invoiceProductCode = '';
                                }
                            } else {
                                $invoiceProductCode = $datadictArr[$val[3]]['code'];
                            }
                            //��Ʊ��Ŀ
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�п�Ʊ��Ŀ';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ʒ��������
                        if (!empty($val[4])) {
                            $val[4] = trim($val[4]);
                            if (!isset($datadictArr[$val[4]])) {
                                $rs = $datadictDao->getCodeByName('CPFWLX', $val[4]);
                                if (!empty($rs)) {
                                    $invoiceDetailType = $datadictArr[$val[4]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵĿ�Ʊ��Ʒ/��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $invoiceDetailType = $datadictArr[$val[4]]['code'];
                            }
                            //��Ʊ��Ŀ����
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�п�Ʊ��Ʒ/��������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ʊ���
                        if (!empty($val[9])) {
                            //��Ʊ���
                            $invoiceMoney = trim($val[9]);

                            if ($invoiceMoney >= 0) {
                                $isRed = 0;
                            } else {
                                $isRed = 1;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�п�Ʊ�ϼƽ���ϼƽ��Ϊ0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $invoiceMoney = abs($invoiceMoney);
                        $softMoney = empty($val[5]) ? 0 : sprintf("%f", abs(trim($val[5])));
                        $hardMoney = empty($val[6]) ? 0 : sprintf("%f", abs(trim($val[6])));
                        $repairMoney = empty($val[7]) ? 0 : sprintf("%f", abs(trim($val[7])));
                        $serviceMoney = empty($val[8]) ? 0 : sprintf("%f", abs(trim($val[8])));

                        $equRentalMoney = empty($val[14]) ? 0 : sprintf("%f", abs(trim($val[14])));
                        $spaceRentalMoney = empty($val[15]) ? 0 : sprintf("%f", abs(trim($val[15])));
                        $otherMoney = empty($val[16]) ? 0 : sprintf("%f", abs(trim($val[16])));

                        $dsEnergyCharge = empty($val[17]) ? 0 : sprintf("%f", abs(trim($val[17])));
                        $dsWaterRateMoney = empty($val[18]) ? 0 : sprintf("%f", abs(trim($val[18])));
                        $houseRentalFee = empty($val[19]) ? 0 : sprintf("%f", abs(trim($val[19])));
                        $installationCost = empty($val[20]) ? 0 : sprintf("%f", abs(trim($val[20])));

                        $softAndHard = bcadd($softMoney, $hardMoney, 2);
                        $repairAndService = bcadd($repairMoney, $serviceMoney, 2);
                        $equAndSpace = bcadd($equRentalMoney, $spaceRentalMoney, 2);
                        $thisAll = bcadd($repairAndService, $softAndHard, 2);
                        $thisAll = bcadd($thisAll, $equAndSpace, 2);
                        $thisAll = bcadd($thisAll, $otherMoney, 2);

                        $thisAll = bcadd($thisAll, $dsEnergyCharge, 2);
                        $thisAll = bcadd($thisAll, $dsWaterRateMoney, 2);
                        $thisAll = bcadd($thisAll, $houseRentalFee, 2);
                        $thisAll = bcadd($thisAll, $installationCost, 2);
                        if ($thisAll != $invoiceMoney) {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!�ܽ��ͷ�������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //���жϷ�Ʊ����
                        $invoiceNo = trim($val[10]);

                        //���ж�����
                        $amount = trim($val[11]);

                        if (!empty($val[12])) {
                            $val[12] = trim($val[12]);
                            if (!isset($datadictArr[$val[12]])) {
                                $rs = $datadictDao->getCodeByName('XSFP', $val[12]);
                                if (!empty($rs)) {
                                    $invoiceType = $datadictArr[$val[12]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵķ�Ʊ����';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $invoiceType = $datadictArr[$val[12]]['code'];
                            }
                            //��Ʊ��Ŀ����
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û���Ʊ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[13])) {//ҵ��Ա
                            $val[13] = trim($val[13]);
                            if (!isset($userArr[$val[13]])) {
                                $rs = $otherDataDao->getUserInfo($val[13]);
                                if (!empty($rs)) {
                                    $userArr[$val[13]] = $rs;
                                    $salesmanId = $userArr[$val[13]]['USER_ID'];
                                    $deptId = $userArr[$val[13]]['DEPT_ID'];
                                    $deptName = $userArr[$val[13]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ�ҵ��Ա����';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $salesmanId = $userArr[$val[13]]['USER_ID'];
                                $deptId = $userArr[$val[13]]['DEPT_ID'];
                                $deptName = $userArr[$val[13]]['DEPT_NAME'];
                            }
                            $salesman = $val[13];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����дҵ��Ա����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (empty($val[11])) {
                            $allAmount = 0;
                        } else {
                            $allAmount = $val[11];
                        }

                        //������˾
                        if (!empty($val[21])) {
                            $businessBelongName = trim($val[21]);
                            $branchDao = new model_deptuser_branch_branch();
                            $branchObj = $branchDao->find(array('NameCN' => $businessBelongName));
                            if (!empty($branchObj)) {
                                $businessBelong = $branchObj['NamePT'];
                                $formBelong = $branchObj['NamePT'];
                                $formBelongName = $branchObj['NameCN'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڸù�����˾';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û���������˾';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $inArr = array(
                            'invoiceNo' => $invoiceNo,
                            'invoiceTime' => $invoiceTime,
                            'invoiceUnitName' => $customerName,
                            'invoiceUnitId' => $customerId,
                            'contractUnitName' => $customerName,
                            'contractUnitId' => $customerId,
                            'invoiceType' => $invoiceType,
                            'isRed' => $isRed,
                            'objId' => $orderId,
                            'objCode' => $orderCode,
                            'objType' => $orderType,
                            'rObjCode' => $rObjCode,
                            'invoiceMoney' => $invoiceMoney,
                            'softMoney' => $softMoney,
                            'hardMoney' => $hardMoney,
                            'repairMoney' => $repairMoney,
                            'serviceMoney' => $serviceMoney,
                            'equRentalMoney' => $equRentalMoney,
                            'spaceRentalMoney' => $spaceRentalMoney,
                            'otherMoney' => $otherMoney,
                            'salesmanId' => $salesmanId,
                            'salesman' => $salesman,
                            'deptId' => $deptId,
                            'deptName' => $deptName,
                            'managerName' => $managerName,
                            'managerId' => $managerId,
                            'remark' => 'ϵͳ��������',
                            'invoiceContent' => $val[3],
                            'psType' => $val[4],
                            'allAmount' => $allAmount,
                            'invoiceUnitType' => $customerType,
                            'invoiceUnitProvince' => $prov,
                            'areaName' => $areaName,
                            'areaId' => $areaId,
                            'businessBelongName' => $businessBelongName,
                            'businessBelong' => $businessBelong,
                            'formBelongName' => $formBelongName,
                            'formBelong' => $formBelong,
                            'currency' => '�����',
                            'rate' => 1,
                            'invoiceDetail' => array(
                                array(
                                    'productName' => $invoiceProduct,
                                    'productId' => $invoiceProductCode,
                                    'amount' => $amount,
                                    'psType' => $invoiceDetailType,
                                    'softMoney' => $softMoney,
                                    'hardMoney' => $hardMoney,
                                    'serviceMoney' => $serviceMoney,
                                    'repairMoney' => $repairMoney,
                                    'equRentalMoney' => $equRentalMoney,
                                    'spaceRentalMoney' => $spaceRentalMoney,
                                    'otherMoney' => $otherMoney,
                                    'dsEnergyCharge' => $dsEnergyCharge,
                                    'dsWaterRateMoney' => $dsWaterRateMoney,
                                    'houseRentalFee' => $houseRentalFee,
                                    'installationCost' => $installationCost
                                )
                            )
                        );

                        try {
                            $this->start_d();

                            //���ݲ���
                            $this->add_d($inArr);

                            $this->commit_d();
                            $tempArr['result'] = '����ɹ�';
                            $tempArr['docCode'] = '��' . $actNum . '������';
                        } catch (Exception $e) {
                            $this->rollBack();
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
     * ��Ʊ������¹���
     */
    function editExecelData_d() {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//�������
        $userArr = array();//��ͬ��Ϣ����
        $otherDataDao = new model_common_otherdatas();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;

                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            $invoiceNo = $val[0];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʊ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[1])) {
                            if (!isset($userArr[$val[1]])) {
                                $rs = $otherDataDao->getUserInfo($val[1]);
                                if (!empty($rs)) {
                                    $userArr[$val[1]] = $rs;
                                    $salesmanId = $userArr[$val[1]]['USER_ID'];
                                    $deptId = $userArr[$val[1]]['DEPT_ID'];
                                    $deptName = $userArr[$val[1]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ�ҵ��Ա����';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $salesmanId = $userArr[$val[1]]['USER_ID'];
                                $deptId = $userArr[$val[1]]['DEPT_ID'];
                                $deptName = $userArr[$val[1]]['DEPT_NAME'];
                            }
                            $salesman = $val[1];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����дҵ��Ա����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $updateRows = array(
                            'invoiceNo' => $invoiceNo,
                            'salesman' => $salesman,
                            'salesmanId' => $salesmanId,
                            'deptId' => $deptId,
                            'deptName' => $deptName
                        );
                        $conditionArr = array(
                            'invoiceNo' => $invoiceNo
                        );
                        $this->update($conditionArr, $updateRows);
                        if ($this->_db->affected_rows() == 0) {
                            $tempArr['result'] = '���³ɹ��������µ���������Ϊ0';
                        } else {
                            $tempArr['result'] = '���³ɹ�';
                        }
                        $tempArr['docCode'] = '��' . $actNum . '������';
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
     * ��ͬ����ת��
     * @param $val
     * @param $thisType
     * @return string
     */
    function changeContType_d($val, $thisType) {
        if ($thisType == 1) {
            switch ($val) {
                case 'oa_sale_order':
                    return 'KPRK-01';
                    break;
                case 'oa_sale_service':
                    return 'KPRK-03';
                    break;
                case 'oa_sale_lease':
                    return 'KPRK-05';
                    break;
                case 'oa_sale_rdproject':
                    return 'KPRK-07';
                    break;
            }
        } else {
            switch ($val) {
                case 'oa_sale_order':
                    return 'KPRK-02';
                    break;
                case 'oa_sale_service':
                    return 'KPRK-04';
                    break;
                case 'oa_sale_lease':
                    return 'KPRK-06';
                    break;
                case 'oa_sale_rdproject':
                    return 'KPRK-08';
                    break;
            }
        }
    }

    /**
     * ��ȡ��Ʊ��Ϣ�Ϳ�Ʊ������Ϣ
     * @param $id
     * @return mixed
     */
    function getInvoiceAndApply_d($id) {
        $this->setCompany(0);
        $this->searchArr['id'] = $id;
        $rows = $this->listBySqlId('invoiceAndApply');
        return $rows[0];
    }

    /*************************��Ʊ����*************************/
    /**
     * ��Ʊ������������
     * @param $object
     * @return bool
     */
    function batchDealAct_d($object) {
        try {
            $this->start_d();

            $idArr = array();

            $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
            foreach ($object as $val) {
                $invoiceDetailDao->edit_d($val);
                array_push($idArr, $val['invoiceId']);
            }

            $idArr = array_unique($idArr);
            $this->changeStatus_d(implode($idArr, ','));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��Ʊ״̬�޸�
     * @param $ids
     * @param int $status
     * @return mixed
     */
    function changeStatus_d($ids, $status = 1) {
        return $this->_db->query("update oa_finance_invoice set status = $status where id in ( $ids )");
    }

    /**
     * ����Դ���ţ�Դ�����ͣ���ƱId
     * @param $objId
     * @param $type
     * @return bool|mixed
     */
    function getInvId_d($objId, $type) {
        $conditions = array(
            'objId' => $objId,
            'objType' => $type
        );
        return $this->find($conditions, $sort = null, 'id');
    }

    /**
     * ��ȡ��ͬ�ѿ�Ʊ���
     * @param $obj
     * @return int
     */
    function getInvoicedMoney_d($obj) {
        $this->setCompany(0);
        $this->searchArr = array('objId' => $obj['objId'], 'objTypes' => $this->rtPostVla($obj['objType']));
        $rs = $this->list_d('sumApplyMoney');
        if (is_array($rs)) {
            if (empty($rs[0]['allMoney'])) {
                return 0;
            }
            return $rs[0]['allMoney'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ���п�Ʊ���� -  ����С���ͽ��
     * @param $obj
     * @return array
     */
    function getInvoiceAllMoney_d($obj) {
        $this->setCompany(0);
        $this->searchArr = array('objId' => $obj['objId'], 'objTypes' => $this->rtPostVla($obj['objType']));
        $rs = $this->list_d('sumAllMoney');
        if ($rs[0]['invoiceMoney']) {
            return $rs[0];
        } else {
            return array(
                'invoiceMoney' => 0,
                'softMoney' => 0,
                'hardMoney' => 0,
                'repairMoney' => 0,
                'serviceMoney' => 0,
                'equRentalMoney' => 0,
                'spaceRentalMoney' => 0,
                'otherMoney' => 0
            );
        }
    }

    /**
     * ��ȡ��Ʊ���
     * @param $obj
     * @return int|string
     */
    function getReturnMoney_d($obj) {
        $objType = $this->rtPostVlaForSelect($obj['objType']);
        $rs = $this->get_table_fields($this->tbl_name, " isRed = 1 and objId = " . $obj['objId'] . " and objType in ( " . $objType . " )", "sum(invoiceMoney)");
        return $rs === null ? 0 : $rs;
    }

    /**
     * ����ҵ���Ż�ȡ��صĿ�Ʊ���
     * @param $rObjCodes
     * @return mixed
     */
    function getInvoiceMoneyByRObjCodes_d($rObjCodes) {
        $this->setCompany(0);
        $this->searchArr['rObjCodes'] = $rObjCodes;
        $this->groupBy = 'c.rObjCode';
        return $this->list_d('sumApplyMoneyrObjCode');
    }

    /************************ �º�ͬʹ�ò��� *********************/
    /**
     * ����ҵ������-����
     * @param $object
     * @param $special ���⴦���ʶ������ɾ��/�༭����
     */
    function busiessDeal_d($object,$special = false) {
        if (in_array($object['objType'], $this->needDealArr)) {
            $thisClass = $this->rtTypeClass($object['objType']);
            $thisClass = 'model_' . $thisClass;
            $innerObjDao = new $thisClass();

            //��ȡ�ѿ�Ʊ���
            $rs = $this->getInvoiceAllMoney_d($object);

            if ($rs['invoiceMoney'] == 0) {
                $innerObjDao->_db->query("UPDATE {$innerObjDao->tbl_name} SET invoiceMoney=0,softMoney=0,
                    hardMoney=0,repairMoney=0,serviceMoney=0,equRentalMoney=0,spaceRentalMoney=0,
                    lastInvoiceDate=NULL WHERE id = {$object['objId']}" );
            } else {
                //�����ѿ�Ʊ���
                $innerObjDao->update(array('id' => $object['objId']),
                    array(
                        'invoiceMoney' => $rs['invoiceMoney'],'softMoney' => $rs['softMoney'],
                        'hardMoney' => $rs['hardMoney'],'repairMoney' => $rs['repairMoney'],
                        'serviceMoney' => $rs['serviceMoney'],'equRentalMoney' => $rs['equRentalMoney'],
                        'spaceRentalMoney' => $rs['spaceRentalMoney'],
                        'lastInvoiceDate' => $rs['invoiceTime']
                    )
                );
            }
            // ���ú�ͬ�Զ��رշ���
            $innerObjDao->updateContractClose($object['objId']);
            
            // ���¿�Ʊ���뼰��ͬ��Ϣ�Ŀ�Ʊ���� add By Bingo 2015.9.2
            if(!empty($object['invoiceType'])){
            	$datadictDao = new model_system_datadict_datadict();
            	//������Ʊ�����봦��
            	if(!empty($object['applyId'])){
            		$invoiceapplyDao = new model_finance_invoiceapply_invoiceapply();
            		$invoiceapplyDao->update(array('id' => $object['applyId']),
            				array('invoiceType' => $object['invoiceType'], 'invoiceTypeName' => $object['invoiceTypeName']));
            	}
            	$rs = $datadictDao->find(array('dataCode' => $object['invoiceType']),null,'expand5');
            	if(!empty($rs['expand5'])){
            		$expand5 = trim($rs['expand5']);
            		$thisInvoiceMoney = 0;//���ο�Ʊ�����ܽ��
            		$otherInvoiceMoney = 0;//������Ʊ�����ܽ��
                    if($thisClass == "model_contract_contract_contract"){
                        $rs = $innerObjDao->find(array('id' => $object['objId']),null,'invoiceValue,invoiceCode,contractMoney');
                        $contractMoney = $rs['contractMoney'];//��ͬ���
                        $invoiceValueArr = $innerObjDao->makeInvoiceValueArr($rs);
                    }else{
                        $rs = $innerObjDao->find(array('id' => $object['objId']),null,'invoiceValue,contractMoney');
                        $contractMoney = $rs['contractMoney'];//��ͬ���
                        $rs = explode(",", $rs['invoiceValue']);
                        $invoiceValueArr['CPXSFP'] = $rs['0'];
                        $invoiceValueArr['CPZLFP'] = $rs['1'];
                        $invoiceValueArr['HTFWFP'] = $rs['2'];
                        $invoiceValueArr['HTCKFP'] = $rs['3'];
                    }
            		if($object['isRed'] == '1' || $special){//��ɫ�������⴦��
            			$otherInvoiceValue = 0;//��ͬ������Ʊ�����ܽ��
            			foreach ($invoiceValueArr as $k => $v){
            				if($expand5 != $k){
            					$otherInvoiceValue += $v;
            				}
            			}
            		}
            		// ��ȡ�ú�ͬ�ѿ�Ʊ����Ϣ,������ÿ�ֿ�Ʊ����ʣ��ɿ�Ʊ���
            		$sql = "SELECT
								SUM(IF(isRed = 0,c.invoiceMoney,-c.invoiceMoney)) AS invoiceMoney,
								d.expand5
							FROM
								oa_finance_invoice c
							INNER JOIN oa_system_datadict d ON c.invoiceType = d.dataCode
							WHERE
								c.objId = ".$object['objId']."
							GROUP BY
								d.expand5";
            		$invoiceInfo = $this->_db->getArray($sql);
            		if(!empty($invoiceInfo)){
            			foreach ($invoiceInfo as $v){
            				if($v['expand5'] == $expand5){
            					if($special){//���⴦��ʱ����ʵ�ʿ�ƱΪ׼
            						$thisInvoiceMoney = $v['invoiceMoney'];
            					}else{
            						// ������ο�Ʊ���͵���ʷ��Ʊ��¼�ܶ���ں�ͬ�ÿ�Ʊ���͵Ľ���Ҫ���º�ͬ�Ŀ�Ʊ���
            						$thisInvoiceMoney = $v['invoiceMoney'] > $invoiceValueArr[$expand5] ?
            							$v['invoiceMoney'] : $invoiceValueArr[$expand5];
            					}
            					// ����ѱ�������Ŀ�Ʊ���ӻ���
            					$invoiceValueArr[$expand5] += $object['invoiceMoney'];
            				}else{
            					$otherInvoiceMoney += $v['invoiceMoney'];
            				}
            				// �������Ʊ����ʣ��ɿ�Ʊ���
            				$invoiceValueArr[$v['expand5']] -= $v['invoiceMoney'];
            			}
            		}
            		$thisInvoiceValue = $invoiceValueArr[$expand5];//��ͬ�б��ο�Ʊ����ʣ����
            		$money = 0; // ���ڼ���ʵ�ʿ�Ʊ���ͬ��Ӧ��Ʊ�Ĳ��
            		if(empty($thisInvoiceValue)){
            			$money = $object['invoiceMoney'];
            		}elseif($object['invoiceMoney'] > $thisInvoiceValue){
            			$money = bcsub($object['invoiceMoney'], $thisInvoiceValue);
            		}
            		if($object['isRed'] == '1' || $special){//��ɫ�������⴦��
            			// Ӧ�Գ������༭ʱ���Ŀ�Ʊ����
            			// ��ɫ����������Ʊ���ͣ�ʵ�ʿ�Ʊ�ܶ����ͬ�ܶ�֮�䣬ȡ���ֵ
            			if($special && $object['isRed'] == '1' && $otherInvoiceMoney > $otherInvoiceValue){
            				$otherInvoiceValue = $otherInvoiceMoney;
            			// ��ɫ����������Ʊ���ͣ�ʵ�ʿ�Ʊ�ܶ����ͬ�ܶ�֮�䣬ȡС��ֵ
            			}elseif ($special && $object['isRed'] == '0' && $otherInvoiceValue - $otherInvoiceMoney < $money){
            				$otherInvoiceValue = $otherInvoiceMoney;
            			}
            			$invoiceMoney = $thisInvoiceMoney + $otherInvoiceValue;
            		}
            		if($money != 0){// ������ڲ��,�򽫲�۷�̯��������Ʊ������
            			foreach ($invoiceValueArr as $k => $v){
            				if($expand5 != $k){
            					if($money > $v){
            						$money = bcsub($money, $v);
            						$invoiceValueArr[$k] = '';
            					}else{
            						$invoiceValueArr[$k] = $v - $money;
            						break;
            					}
            				}
            			}
            		}
            		if(!empty($invoiceInfo)){
            			foreach ($invoiceInfo as $v){
            				if($v['expand5'] == $expand5){// �����ο�Ʊ����
            					if($special){//���⴦��
            						if($invoiceMoney < $contractMoney){//��Ʊ���С�ں�ͬ��
            							$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
            						}
            					}elseif($object['isRed'] == '1'){//��ɫ��
									//�����Ʊ�ܽ�����ͬ������ڵ��ڱ��ο�Ʊ���򱾴ο�Ʊ���ͽ��Ҫ��ȥ��ɫ���Ľ��
									if($invoiceMoney - $contractMoney >= $object['invoiceMoney']){
										$thisInvoiceMoney -= $object['invoiceMoney'];
									}else{//������ں�ͬ���ȥ������Ʊ���͵Ľ��
										$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
									}
								}
								$invoiceValueArr[$expand5] = $thisInvoiceMoney;
            				}else{
            					// �������ȥ�ĸ��ֿ�Ʊ���͵���ʷ��Ʊ���ӻ���
            					$invoiceValueArr[$v['expand5']] += $v['invoiceMoney'];
            					// Ӧ�Գ������༭��ɫ������Ʊ���͸�Ϊ��������
            					if($special && $object['isRed'] == '1' && $v['invoiceMoney'] > $invoiceValueArr[$v['expand5']]){
            						$invoiceValueArr[$v['expand5']] = $v['invoiceMoney'];
            					}
            				}
            			}
            		}
            		//�����ο�Ʊ����ʵ�ʽ��Ϊ0ʱ
            		if($thisInvoiceMoney == 0){
	            		if($invoiceMoney < $contractMoney){//������Ʊ���ͽ��С�ں�ͬ��
	            			$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
	            		}
	            		$invoiceValueArr[$expand5] = $thisInvoiceMoney;
            		}
            		$invoiceCodeArr = array();
            		foreach ($invoiceValueArr as $k => $v){
            			if(!empty($v)){
            				array_push($invoiceCodeArr, $k);
            			}else{
            			    unset($invoiceValueArr[$k]);
                        }
            		}
            		$invoiceCode = implode(',', $invoiceCodeArr);
            		$invoiceValue = implode(',', $invoiceValueArr);
            		$innerObjDao->update(array('id' => $object['objId']),
            				array('invoiceCode' => $invoiceCode, 'invoiceValue' => $invoiceValue));
            	}
            }
        }
    }


    /**
     * ����ҵ���Ż�ȡ��صĿ�Ʊ���
     * @param $objIds
     * @param $objType
     * @return mixed
     */
    function getInvoiceByIdsType_d($objIds, $objType) {
        $this->setCompany(0);
        $this->searchArr = array('objIds' => $objIds, 'objType' => $objType);
        $this->groupBy = 'c.objId';
        return $this->list_d('sumAllMoneyObjId');
    }

    /**
     * �������ת�������
     * @param $object
     * @param $invoiceDetail
     */
    function dealCurrency_d(&$object, &$invoiceDetail) {
        // ����ת��
        list(
            $object['invoiceMoneyCur'], $object['softMoneyCur'], $object['hardMoneyCur'], $object['repairMoneyCur'],
            $object['serviceMoneyCur'], $object['equRentalMoneyCur'], $object['spaceRentalMoneyCur'], $object['otherMoneyCur'],
            $object['dsEnergyChargeCur'], $object['dsWaterRateMoneyCur'], $object['houseRentalFeeCur'], $object['installationCostCur']
            ) =
            array(
                $object['invoiceMoney'], $object['softMoney'], $object['hardMoney'], $object['repairMoney'], $object['serviceMoney'],
                $object['equRentalMoney'], $object['spaceRentalMoney'], $object['otherMoney'],
                $object['dsEnergyCharge'], $object['dsWaterRateMoney'], $object['houseRentalFee'], $object['installationCost']
            );
        // ����ת����Ľ��
        $mark = 0; // ��־�Ƿ��ʼ��
        foreach ($invoiceDetail as $k => $v) {
            $invoiceDetail[$k]['softMoney'] = round(bcmul($v['softMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['hardMoney'] = round(bcmul($v['hardMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['repairMoney'] = round(bcmul($v['repairMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['serviceMoney'] = round(bcmul($v['serviceMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['equRentalMoney'] = round(bcmul($v['equRentalMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['spaceRentalMoney'] = round(bcmul($v['spaceRentalMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['otherMoney'] = round(bcmul($v['otherMoney'], $object['rate'], 6), 2);

            $invoiceDetail[$k]['dsEnergyCharge'] = round(bcmul($v['dsEnergyCharge'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['dsWaterRateMoney'] = round(bcmul($v['dsWaterRateMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['houseRentalFee'] = round(bcmul($v['houseRentalFee'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['installationCost'] = round(bcmul($v['installationCost'], $object['rate'], 6), 2);

            $object['softMoney'] = $mark ? bcadd($invoiceDetail[$k]['softMoney'], $object['softMoney'], 2) : $invoiceDetail[$k]['softMoney'];
            $object['hardMoney'] = $mark ? bcadd($invoiceDetail[$k]['hardMoney'], $object['hardMoney'], 2) : $invoiceDetail[$k]['hardMoney'];
            $object['repairMoney'] = $mark ? bcadd($invoiceDetail[$k]['repairMoney'], $object['repairMoney'], 2) : $invoiceDetail[$k]['repairMoney'];
            $object['serviceMoney'] = $mark ? bcadd($invoiceDetail[$k]['serviceMoney'], $object['serviceMoney'], 2) : $invoiceDetail[$k]['serviceMoney'];
            $object['equRentalMoney'] = $mark ? bcadd($invoiceDetail[$k]['equRentalMoney'], $object['equRentalMoney'], 2) : $invoiceDetail[$k]['equRentalMoney'];
            $object['spaceRentalMoney'] = $mark ? bcadd($invoiceDetail[$k]['spaceRentalMoney'], $object['spaceRentalMoney'], 2) : $invoiceDetail[$k]['spaceRentalMoney'];
            $object['otherMoney'] = $mark ? bcadd($invoiceDetail[$k]['otherMoney'], $object['otherMoney'], 2) : $invoiceDetail[$k]['otherMoney'];

            $object['dsEnergyCharge'] = $mark ? bcadd($invoiceDetail[$k]['dsEnergyCharge'], $object['dsEnergyCharge'], 2) : $invoiceDetail[$k]['dsEnergyCharge'];
            $object['dsWaterRateMoney'] = $mark ? bcadd($invoiceDetail[$k]['dsWaterRateMoney'], $object['dsWaterRateMoney'], 2) : $invoiceDetail[$k]['dsWaterRateMoney'];
            $object['houseRentalFee'] = $mark ? bcadd($invoiceDetail[$k]['houseRentalFee'], $object['houseRentalFee'], 2) : $invoiceDetail[$k]['houseRentalFee'];
            $object['installationCost'] = $mark ? bcadd($invoiceDetail[$k]['installationCost'], $object['installationCost'], 2) : $invoiceDetail[$k]['installationCost'];

            $mark = 1;// ��������
        }
        $object['invoiceMoney'] = bcadd($object['softMoney'], $object['hardMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['repairMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['serviceMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['equRentalMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['spaceRentalMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['otherMoney'], 2);

        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['dsEnergyCharge'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['dsWaterRateMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['houseRentalFee'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['installationCost'], 2);
    }

    /**
     * ���Ҵ���
     * @param $object
     */
    function dealLocal_d(&$object) {
        // ����ת��
        list(
            $object['invoiceMoneyCur'], $object['softMoneyCur'], $object['hardMoneyCur'], $object['repairMoneyCur'],
            $object['serviceMoneyCur'], $object['equRentalMoneyCur'], $object['spaceRentalMoneyCur'], $object['otherMoneyCur'],
            $object['dsEnergyChargeCur'], $object['dsWaterRateMoneyCur'], $object['houseRentalFeeCur'], $object['installationCostCur']
            ) =
            array(
                $object['invoiceMoney'], $object['softMoney'], $object['hardMoney'], $object['repairMoney'], $object['serviceMoney'],
                $object['equRentalMoney'], $object['spaceRentalMoney'], $object['otherMoney'],
                $object['dsEnergyCharge'], $object['dsWaterRateMoney'], $object['houseRentalFee'], $object['installationCost']
            );
    }
}