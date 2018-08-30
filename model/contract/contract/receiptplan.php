<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:14:21
 * @version 1.0
 * @description:]��ͬ�տ�ƻ� Model��
 */
class model_contract_contract_receiptplan extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_receiptplan";
        $this->sql_map = "contract/contract/receiptplanSql.php";
        parent::__construct();
    }

    /**
     * ���ݺ�ͬID ��ȡ�ӱ�����
     */
    function getDetail_d($contractId) {
        $this->searchArr ['contractId'] = $contractId;
        $this->searchArr ['isDel'] = 0;
        $this->searchArr ['isTemp'] = 0;
        $this->searchArr ['isFi'] = 0;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * ���ݺ�ͬID ��ȡ�ɺ�������
     */
    function getListForCheck_d($contractId, $incomeType = 0) {
        $this->searchArr ['contractId'] = $contractId;
        $this->searchArr ['isDel'] = 0;
        $this->searchArr ['isTemp'] = 0;
        $this->searchArr ['isfinance'] = 0;
        $this->asc = $incomeType == 1 ? true : false;
        return $this->list_d('select_list');
    }

    /**
     * ����������¼����
     */
    function dealAdd_d($id, $checkMoney, $checkDate, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //��ȡ��ǰ������������
            $obj = $this->get_d($id);
            //�����¶���
            $object = array('id' => $id);

            //�ɷ�����
            $canIncomeCheckMoney = $isRed ? $obj['incomMoney'] : bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2);
            $canInvoiceCheckMoney = $isRed ? $obj['invoiceMoney'] : bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2);
            //�ۿ�򵽿�����ж�
            switch($incomeType){
                case 0 :
                    $object['incomMoney'] = $isRed ? bcsub($obj['incomMoney'], $checkMoney, 2) : bcadd($obj['incomMoney'], $checkMoney, 2);
                    $canCheckMoney = $canIncomeCheckMoney;
                    $anotherCheckMoney = $canInvoiceCheckMoney;
                    break;
                case 1 :
                    if($deductDispose == 'badMoney'){//���˴���ֻ��(��ͬ���-����������=�ɿۿ���)��һ����Ӱ��
                        $object['deductMoney'] = $isRed ? bcsub($obj['deductMoney'], $checkMoney, 2) : bcadd($obj['deductMoney'], $checkMoney, 2);
                        $canCheckMoney = $canIncomeCheckMoney;
                        $anotherCheckMoney = $canInvoiceCheckMoney;
                    }else{
                        $object['deductMoney'] = $isRed ? bcsub($obj['deductMoney'], $checkMoney, 2) : bcadd($obj['deductMoney'], $checkMoney, 2);
                        $canCheckMoney = min($canIncomeCheckMoney, $canInvoiceCheckMoney);
                        $anotherCheckMoney = max($canIncomeCheckMoney, $canInvoiceCheckMoney);
                    }
                    break;
                case 2 :
                    $object['invoiceMoney'] = $isRed ? bcsub($obj['invoiceMoney'], $checkMoney, 2) : bcadd($obj['invoiceMoney'], $checkMoney, 2);
                    $canCheckMoney = $canInvoiceCheckMoney;
                    $anotherCheckMoney = $canIncomeCheckMoney;
                    break;
                default :
                    $canCheckMoney = $anotherCheckMoney = 0;
            }

            //������ڿɺ����������쳣
            if ($checkMoney > $canCheckMoney) {
                throw new Exception("�ѳ�������������" . $obj['paymentterm'] . "��ʣ��ɺ������");
            } else {
                if($isRed){
                    if($obj['isCom'] == 1){ // �����ǰ������¼�Ѿ���ɣ��������������Ϣ
                        $object['isCom'] = 0;
                        $object['comDate'] = '0000-00-00';
                        $object['periodId'] = '0';
                        $object['periodName'] = '';
                    }
                }else{
                    //�����ȫ�����������������״̬
                    if ($checkMoney == $canCheckMoney && $anotherCheckMoney == 0) {
                        $object['isCom'] = 1;
                        $object['comDate'] = $checkDate;

                        //��ȡ��������Ϣ
                        if ($obj['Tday'] != '' && $obj['Tday'] != '0000-00-00') {
                            $periodConfigDao = new model_contract_config_periodconfig();
                            $periodObj = $periodConfigDao->getPeriod_d($obj['Tday'], $checkDate);
                            $object = array_merge($object, $periodObj);
                        }
                    }
                }
                return $this->edit_d($object, true);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �༭��������
     */
    function dealEdit_d($id, $diffMoney, $checkDate, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //��ȡ��ǰ������������
            $obj = $this->get_d($id);
            //�����¶���
            $object = array('id' => $id);

            //������Ѿ���ɵĺ���,��Ҫ�����򵯳��쳣 - ����
            if (!$isRed && $obj['isCom'] == 1 && $diffMoney > 0) {
                throw new Exception("����������" . $obj['paymentterm'] . "���Ѻ������,�������Ӻ������");
            }

            //�ɷ�����
            $canIncomeCheckMoney = $isRed ?
                $obj['incomMoney'] : bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2);
            $canInvoiceCheckMoney = $isRed ?
                $obj['invoiceMoney'] : bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2);
            //�ۿ�򵽿�����ж�
            switch($incomeType){
                case 0 :
                    $object['incomMoney'] = $isRed ? bcsub($obj['incomMoney'], $diffMoney, 2) : bcadd($obj['incomMoney'], $diffMoney, 2);
                    $canCheckMoney = $canIncomeCheckMoney;
                    $anotherCheckMoney = $canInvoiceCheckMoney;
                    break;
                case 1 :
                    if($deductDispose == 'badMoney'){//���˴���ֻ��(��ͬ���-����������=�ɿۿ���)��һ����Ӱ��
                        $object['deductMoney'] = $isRed ? bcsub($obj['deductMoney'], $diffMoney, 2) : bcadd($obj['deductMoney'], $diffMoney, 2);
                        $canCheckMoney = $canIncomeCheckMoney;
                        $anotherCheckMoney = $canInvoiceCheckMoney;
                    }else {
                        $object['deductMoney'] = $isRed ? bcsub($obj['deductMoney'], $diffMoney, 2) : bcadd($obj['deductMoney'], $diffMoney, 2);
                        $canCheckMoney = min($canIncomeCheckMoney, $canInvoiceCheckMoney);
                        $anotherCheckMoney = max($canIncomeCheckMoney, $canInvoiceCheckMoney);
                    }
                    break;
                case 2 :
                    $object['invoiceMoney'] = $isRed ? bcsub($obj['invoiceMoney'], $diffMoney, 2) : bcadd($obj['invoiceMoney'], $diffMoney, 2);
                    $canCheckMoney = $canInvoiceCheckMoney;
                    $anotherCheckMoney = $canIncomeCheckMoney;
                    break;
                default :
                    $canCheckMoney = $anotherCheckMoney = 0;
            }

            //������ڿɺ����������쳣
            if ($diffMoney > $canCheckMoney) {
                throw new Exception("�ѳ�������������" . $obj['paymentterm'] . "��ʣ��ɺ������");
            } else {
                //�����ȫ�����������������״̬
                if ($diffMoney == $canCheckMoney && $anotherCheckMoney == 0) {
                    $object['isCom'] = 1;
                    $object['comDate'] = $checkDate;
                } elseif ($obj['isCom'] == 1 && $diffMoney == 0) {
                    $object['comDate'] = $checkDate;
                } else {
                    $object['isCom'] = 0;
                    $object['comDate'] = '0000-00-00';
                    $object['periodId'] = '0';
                    $object['periodName'] = '';
                }
                //�������жϴ���
                if ($object['isCom'] == 1) {
                    //��ȡ��������Ϣ
                    if ($obj['Tday'] != '' && $obj['Tday'] != '0000-00-00') {
                        $periodConfigDao = new model_contract_config_periodconfig();
                        $periodObj = $periodConfigDao->getPeriod_d($obj['Tday'], $checkDate);
                        $object = array_merge($object, $periodObj);
                    }
                }
                return $this->edit_d($object, true);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ɾ��������¼
     */
    function dealDelete_d($id, $checkMoney, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //��ȡ��ǰ������������
            $obj = $this->get_d($id);

            $object = array(
                'id' => $id, 'isCom' => 0, 'comDate' => '0000-00-00',
                'periodId' => '0', 'periodName' => ''
            );
            $canIncomeCheckMoney = $isRed ?
                bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2) : $obj['incomMoney']; // ��ȡ�ɺ������Ǻ�������С
            $canInvoiceCheckMoney = $isRed ?
                bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2) : $obj['invoiceMoney']; // ��ȡ�ɺ������Ǻ�������С
            //�ۿ�򵽿�����ж�
            switch($incomeType){
                case 0 :
                    $object['incomMoney'] = $isRed ? bcadd($obj['incomMoney'], $checkMoney, 2) : bcsub($obj['incomMoney'], $checkMoney, 2);
                    $canCheckMoney = $canIncomeCheckMoney;
                    $anotherCheckMoney = $canInvoiceCheckMoney;
                    break;
                case 1 :
                    $object['deductMoney'] = $isRed ? bcadd($obj['deductMoney'], $checkMoney, 2) : bcsub($obj['deductMoney'], $checkMoney, 2);
                    $canCheckMoney = min($canIncomeCheckMoney, $canInvoiceCheckMoney);
                    $anotherCheckMoney = max($canIncomeCheckMoney, $canInvoiceCheckMoney);
                    break;
                case 2 :
                    $object['invoiceMoney'] = $isRed ? bcadd($obj['invoiceMoney'], $checkMoney, 2) : bcsub($obj['invoiceMoney'], $checkMoney, 2);
                    $canCheckMoney = $canInvoiceCheckMoney;
                    $anotherCheckMoney = $canIncomeCheckMoney;
                    break;
                default :
                    $canCheckMoney = $anotherCheckMoney = 0;
            }
            // ���ֺ�����ʱ��,�����ȫ�����������������״̬
            if ($isRed && $checkMoney == $canCheckMoney && $anotherCheckMoney == 0) {
                $object['isCom'] = 1;
                $object['comDate'] = day_date;

                //��ȡ��������Ϣ
                if ($obj['Tday'] != '' && $obj['Tday'] != '0000-00-00') {
                    $periodConfigDao = new model_contract_config_periodconfig();
                    $periodObj = $periodConfigDao->getPeriod_d($obj['Tday'], day_date);
                    $object = array_merge($object, $periodObj);
                }
            }
            return $this->edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

	/**
	 * ���»ؿ�ƻ�
	 */
	function updatePlan_d($obj){
		try {
			$this->start_d();
			$this->delete(array (
				'contractId' => $obj['contractId'],
				'isfinance' => $obj['isfinance']
			));
            $pl = 0;
            foreach ($obj['payment'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($obj['payment'][$k]);
                }else{
                    //�����������ܺ�
                    $pl += $obj['payment'][$k]['money'];
                }
            }
            //�ж��������ܺ����ͬ���Ƿ��в���
            if($pl != $obj['contractMoney']){
                //����������һ��
                $pc = count($obj['payment']) - 1;
                $obj['payment'][$pc]['money'] = $obj['payment'][$pc]['money'] + $obj['contractMoney'] - $pl;
            }
			$this->createBatch($obj['payment'], array (
				'contractId' => $obj['contractId']
			), 'paymentterm');

			//���º�ͬ����״̬
			$contractDao = new model_contract_contract_contract();
			$contractDao->update(array('id' => $obj['contractId']), array('fcheckStatus' => '��¼��','fcheckName' => $_SESSION['USER_NAME'],'fcheckId' => $_SESSION['USER_ID'],'fcheckDate' => date("Y-m-d H:i:s")));

            // ɾ��ԭ���Ŀۿ������¼
            $incomeCheckDao = new model_finance_income_incomecheck();
            $incomeCheckDao->delete(array (
                'contractId' => $obj['contractId'],
                'incomeType' => 1
            ));

            // ���·���ۿ������¼
            $deductArr = $this->_db->getArray("select * from oa_contract_deduct where ExaStatus = '���' and contractId = {$obj['contractId']}");
            foreach ($deductArr as $deduct){
                $incomeCheck = $this->autoInitCheck_d(array($obj['contractId'] => $deduct['deductMoney']), 1);
                if ($incomeCheck) {
                    // ȥ��δ������
                    foreach ($incomeCheck as $k => $v){
                        unset($incomeCheck[$k]['unIncomMoney']);
                    }

                    //��������
                    $incomeCheck = util_arrayUtil::setArrayFn(
                        array('incomeId' => $deduct['id'], 'incomeNo' => $deduct['contractCode'],"remark" => "������´���ϵͳ�Զ�����",'incomeType' => 1),
                        $incomeCheck
                    );
                    $incomeCheckDao->batchDeal_d($incomeCheck, $deduct['dispose']);
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
	 * ת��ʱ���
	 */
    function transitionTime($timestamp) {
        $time = "";
        if (!empty($timestamp)) {
            if (mktime(0, 0, 0, 1, $timestamp - 1, 1900) > '2000-01-01') {
                $wirteDate = mktime(0, 0, 0, 1, $timestamp - 1, 1900);
                $time = date("Y-m-d", $wirteDate);
            } else {
                $time = $timestamp;
            }

        }
        return $time;
    }

    /**
     * ��������
     */
    function importAdd_d($obj, $conId, $cMoney) {
        $obj['payDT'] = $this->transitionTime($obj['payDT']);
        try {
            $this->start_d();

            //���ݻؿ����� ���»ؿ�����id
            $payConfigDao = new model_contract_config_payconfig();
            $obj['paymenttermId'] = $payConfigDao->findIdBypayName($obj['paymentterm']);

            // ������Ȱٷֱ�
            if($obj['paymenttermId'] != ''){
                $dateCode = $payConfigDao->find(array('id'=>$obj['paymenttermId']),null,'dateCode');
                if($dateCode['dateCode'] != 'esmPercentage' && $dateCode['dateCode'] != 'shipPercentage' && $dateCode['dateCode'] != 'schePercentage'){
                    $obj['schedulePer'] = 0;
                }
            }

            if (empty($obj['money'])) {
                $obj['money'] = $cMoney * $obj['paymentPer'] / 100;
            }
            if (!empty($obj['payDT']) && !empty($obj['dayNum']) && $obj['payDT'] != '0000-00-00') {
                $datetime = strtotime($obj['payDT']);
                $datetime += $obj['dayNum'] * 24 * 3600;
                $date = date('Y-m-d', $datetime);
            } else {
                $date = "";
            }
            $obj['payDT'] = $date;
            $obj['contractId'] = $conId;

            $newId = parent :: add_d($obj);

            //���º�ͬ����״̬
			$contractDao = new model_contract_contract_contract();
			$contractDao->update(array('id' => $conId), array('fcheckStatus' => '��¼��','fcheckName' => $_SESSION['USER_NAME'],'fcheckId' => $_SESSION['USER_ID'],'fcheckDate' => date("Y-m-d H:i:s")));

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������жϲ���ƽ������
     */
    function initPayListMoney($ids){
        $idArr = explode(",",$ids);
        foreach($idArr as $k => $v){
            $sql = "select * from oa_contract_receiptplan WHERE  contractId = '".$v."'";
            $csql = "select contractMoney from oa_contract_contract where id = '".$v."'";
            $tmpArr = $this->_db->getArray($sql);
            $ca = $this->_db->getArray($csql);
            $pl = 0;
            foreach ($tmpArr as $k => $v) {
                $pl += $tmpArr[$k]['money'];
            }
            //�ж��������ܺ����ͬ���Ƿ��в���
            if($pl != $ca[0]['contractMoney']){
                //����������һ��
                $pc = count($tmpArr) - 1;
                $m = $tmpArr[$pc]['money'] + $ca[0]['contractMoney'] - $pl;
                $updateSql = "update oa_contract_receiptplan set money = '".$m."' where id = '".$tmpArr[$pc]['id']."'";
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * �Զ����ɺ�����¼
     * $incomeType 0 ������� | 1 �ۿ���� | 2 ��Ʊ����
     * $isRed 0 ���� | 1 ����
     */
    function autoInitCheck_d($contractArray, $incomeType = 0, $isRed = 0) {
        if (!empty($contractArray)) {
            $checkArray = array(); // ���ص��Զ���������
            foreach ($contractArray as $k => $v) {
                // ��ѯ��ͬ�Ŀɺ�����¼
                $rows = $this->getListForCheck_d($k, $incomeType);
                if ($rows) {
                    foreach ($rows as $vi) {
                        if (($isRed == 0 && $vi['isCom']) || $v == 0) continue; // ��ɵĲ��� �Լ� ��ͬ���Ϊ0 ʱ����
                        // check row
                        $thisCheckArray = array(
                            'contractId' => $vi['contractId'], 'contractName' => $vi['contractName'],
                            'contractCode' => $vi['contractCode'], 'payConId' => $vi['id'],
                            'payConName' => $vi['paymentterm'], 'checkDate' => day_date, 'remark' => 'ϵͳ�Զ�����'
                        );
                        switch ($incomeType) {
                            case 0 :
                                $canCheckMoney = $isRed ?
                                    $vi['incomMoney'] : bcsub($vi['money'], bcadd($vi['incomMoney'], $vi['deductMoney'], 2), 2); // ���㵽��ɺ������
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                            case 1 :
                                if($isRed){
                                    $canCheckMoney = $vi['deductMoney']; // ����ۿ�ɺ������
                                }else{
                                    $canCheckMoneyIncome = bcsub($vi['money'], bcadd($vi['incomMoney'], $vi['deductMoney'], 2), 2); // ���㵽��ɺ������
                                    $canCheckMoneyInvoice = bcsub($vi['money'], bcadd($vi['invoiceMoney'], $vi['deductMoney'], 2), 2); // ���㿪Ʊ�ɺ������
                                    $canCheckMoney = min($canCheckMoneyIncome,$canCheckMoneyInvoice); // ����ۿ�ɺ������
                                }
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                            case 2 :
                                $canCheckMoney = $isRed ?
                                    $vi['invoiceMoney'] : bcsub($vi['money'], bcadd($vi['invoiceMoney'], $vi['deductMoney'], 2), 2); // ���㿪Ʊ�ɺ������
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                        }
                        if($thisCheckArray['checkMoney'] > 0){
                            $v = bcsub($v,$thisCheckArray['checkMoney'],2); // �ú�ͬʣ��
                            array_push($checkArray, $thisCheckArray); // push array
                        }
                    }
                } else {
                    continue;
                }
            }
            return empty($checkArray) ? false : $checkArray;
        } else {
            return false;
        }
    }

    /**
     * ���¿�Ʊ��������
     */
    function updateInvoiceRemind_d($conditions) {
        $arr = $this->find(array('id' => $conditions['id']), null, 'invoiceRemind');
        $this->update($conditions, array('invoiceRemind' => $arr['invoiceRemind'] + 1));
    }

    /**
     * ���µ��������
     */
    function updateIncomeRemind_d($conditions) {
        $arr = $this->find(array('id' => $conditions['id']), null, 'incomeRemind');
        $this->update($conditions, array('incomeRemind' => $arr['incomeRemind'] + 1));
    }

    /**
     * ���³���ԭ��
     */
    function updateDealReason_d($arr) {
        $this->update(array("id" => $arr['id']), array('incomeReason' => $arr['dealReason']));
    }
}