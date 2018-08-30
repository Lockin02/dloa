<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:14:21
 * @version 1.0
 * @description:]合同收款计划 Model层
 */
class model_contract_contract_receiptplan extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_receiptplan";
        $this->sql_map = "contract/contract/receiptplanSql.php";
        parent::__construct();
    }

    /**
     * 根据合同ID 获取从表数据
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
     * 根据合同ID 获取可核销数据
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
     * 新增核销记录处理
     */
    function dealAdd_d($id, $checkMoney, $checkDate, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //获取当前付款条件内容
            $obj = $this->get_d($id);
            //构建新对象
            $object = array('id' => $id);

            //可分配金额
            $canIncomeCheckMoney = $isRed ? $obj['incomMoney'] : bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2);
            $canInvoiceCheckMoney = $isRed ? $obj['invoiceMoney'] : bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2);
            //扣款或到款核销判断
            switch($incomeType){
                case 0 :
                    $object['incomMoney'] = $isRed ? bcsub($obj['incomMoney'], $checkMoney, 2) : bcadd($obj['incomMoney'], $checkMoney, 2);
                    $canCheckMoney = $canIncomeCheckMoney;
                    $anotherCheckMoney = $canInvoiceCheckMoney;
                    break;
                case 1 :
                    if($deductDispose == 'badMoney'){//坏账处理只受(合同金额-到款核销金额=可扣款金额)这一条件影响
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

            //如果大于可核销金额，弹出异常
            if ($checkMoney > $canCheckMoney) {
                throw new Exception("已超出付款条件【" . $obj['paymentterm'] . "】剩余可核销金额");
            } else {
                if($isRed){
                    if($obj['isCom'] == 1){ // 如果当前核销记录已经完成，则清除奖惩期信息
                        $object['isCom'] = 0;
                        $object['comDate'] = '0000-00-00';
                        $object['periodId'] = '0';
                        $object['periodName'] = '';
                    }
                }else{
                    //如果完全核销，变更付款条件状态
                    if ($checkMoney == $canCheckMoney && $anotherCheckMoney == 0) {
                        $object['isCom'] = 1;
                        $object['comDate'] = $checkDate;

                        //获取奖惩期信息
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
     * 编辑核销处理
     */
    function dealEdit_d($id, $diffMoney, $checkDate, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //获取当前付款条件内容
            $obj = $this->get_d($id);
            //构建新对象
            $object = array('id' => $id);

            //如果是已经完成的核销,需要变大金额，则弹出异常 - 蓝字
            if (!$isRed && $obj['isCom'] == 1 && $diffMoney > 0) {
                throw new Exception("付款条件【" . $obj['paymentterm'] . "】已核销完成,不能增加核销金额");
            }

            //可分配金额
            $canIncomeCheckMoney = $isRed ?
                $obj['incomMoney'] : bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2);
            $canInvoiceCheckMoney = $isRed ?
                $obj['invoiceMoney'] : bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2);
            //扣款或到款核销判断
            switch($incomeType){
                case 0 :
                    $object['incomMoney'] = $isRed ? bcsub($obj['incomMoney'], $diffMoney, 2) : bcadd($obj['incomMoney'], $diffMoney, 2);
                    $canCheckMoney = $canIncomeCheckMoney;
                    $anotherCheckMoney = $canInvoiceCheckMoney;
                    break;
                case 1 :
                    if($deductDispose == 'badMoney'){//坏账处理只受(合同金额-到款核销金额=可扣款金额)这一条件影响
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

            //如果大于可核销金额，弹出异常
            if ($diffMoney > $canCheckMoney) {
                throw new Exception("已超出付款条件【" . $obj['paymentterm'] . "】剩余可核销金额");
            } else {
                //如果完全核销，变更付款条件状态
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
                //奖惩期判断处理
                if ($object['isCom'] == 1) {
                    //获取奖惩期信息
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
     * 删除核销记录
     */
    function dealDelete_d($id, $checkMoney, $incomeType = 0, $isRed = 0, $deductDispose = '') {
        try {
            //获取当前付款条件内容
            $obj = $this->get_d($id);

            $object = array(
                'id' => $id, 'isCom' => 0, 'comDate' => '0000-00-00',
                'periodId' => '0', 'periodName' => ''
            );
            $canIncomeCheckMoney = $isRed ?
                bcsub($obj['money'], bcadd($obj['incomMoney'], $obj['deductMoney'], 2), 2) : $obj['incomMoney']; // 获取可核销金额，非红字暂无小
            $canInvoiceCheckMoney = $isRed ?
                bcsub($obj['money'], bcadd($obj['invoiceMoney'], $obj['deductMoney'], 2), 2) : $obj['invoiceMoney']; // 获取可核销金额，非红字暂无小
            //扣款或到款核销判断
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
            // 红字核销的时候,如果完全核销，变更付款条件状态
            if ($isRed && $checkMoney == $canCheckMoney && $anotherCheckMoney == 0) {
                $object['isCom'] = 1;
                $object['comDate'] = day_date;

                //获取奖惩期信息
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
	 * 更新回款计划
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
                    //计算条款金额总和
                    $pl += $obj['payment'][$k]['money'];
                }
            }
            //判读条款金额总和与合同额是否有差异
            if($pl != $obj['contractMoney']){
                //差异调至最后一条
                $pc = count($obj['payment']) - 1;
                $obj['payment'][$pc]['money'] = $obj['payment'][$pc]['money'] + $obj['contractMoney'] - $pl;
            }
			$this->createBatch($obj['payment'], array (
				'contractId' => $obj['contractId']
			), 'paymentterm');

			//更新合同检验状态
			$contractDao = new model_contract_contract_contract();
			$contractDao->update(array('id' => $obj['contractId']), array('fcheckStatus' => '已录入','fcheckName' => $_SESSION['USER_NAME'],'fcheckId' => $_SESSION['USER_ID'],'fcheckDate' => date("Y-m-d H:i:s")));

            // 删除原来的扣款核销记录
            $incomeCheckDao = new model_finance_income_incomecheck();
            $incomeCheckDao->delete(array (
                'contractId' => $obj['contractId'],
                'incomeType' => 1
            ));

            // 重新分配扣款核销记录
            $deductArr = $this->_db->getArray("select * from oa_contract_deduct where ExaStatus = '完成' and contractId = {$obj['contractId']}");
            foreach ($deductArr as $deduct){
                $incomeCheck = $this->autoInitCheck_d(array($obj['contractId'] => $deduct['deductMoney']), 1);
                if ($incomeCheck) {
                    // 去掉未到款金额
                    foreach ($incomeCheck as $k => $v){
                        unset($incomeCheck[$k]['unIncomMoney']);
                    }

                    //核销处理
                    $incomeCheck = util_arrayUtil::setArrayFn(
                        array('incomeId' => $deduct['id'], 'incomeNo' => $deduct['contractCode'],"remark" => "条款更新触发系统自动核销",'incomeType' => 1),
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
	 * 转换时间戳
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
     * 导入新增
     */
    function importAdd_d($obj, $conId, $cMoney) {
        $obj['payDT'] = $this->transitionTime($obj['payDT']);
        try {
            $this->start_d();

            //根据回款条款 更新回款条款id
            $payConfigDao = new model_contract_config_payconfig();
            $obj['paymenttermId'] = $payConfigDao->findIdBypayName($obj['paymentterm']);

            // 处理进度百分比
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

            //更新合同检验状态
			$contractDao = new model_contract_contract_contract();
			$contractDao->update(array('id' => $conId), array('fcheckStatus' => '已录入','fcheckName' => $_SESSION['USER_NAME'],'fcheckId' => $_SESSION['USER_ID'],'fcheckDate' => date("Y-m-d H:i:s")));

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 导入后，判断并调平条款金额
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
            //判读条款金额总和与合同额是否有差异
            if($pl != $ca[0]['contractMoney']){
                //差异调至最后一条
                $pc = count($tmpArr) - 1;
                $m = $tmpArr[$pc]['money'] + $ca[0]['contractMoney'] - $pl;
                $updateSql = "update oa_contract_receiptplan set money = '".$m."' where id = '".$tmpArr[$pc]['id']."'";
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * 自动生成核销记录
     * $incomeType 0 到款核销 | 1 扣款核销 | 2 开票核销
     * $isRed 0 蓝字 | 1 红字
     */
    function autoInitCheck_d($contractArray, $incomeType = 0, $isRed = 0) {
        if (!empty($contractArray)) {
            $checkArray = array(); // 返回的自动核销数组
            foreach ($contractArray as $k => $v) {
                // 查询合同的可核销记录
                $rows = $this->getListForCheck_d($k, $incomeType);
                if ($rows) {
                    foreach ($rows as $vi) {
                        if (($isRed == 0 && $vi['isCom']) || $v == 0) continue; // 完成的部分 以及 合同金额为0 时忽略
                        // check row
                        $thisCheckArray = array(
                            'contractId' => $vi['contractId'], 'contractName' => $vi['contractName'],
                            'contractCode' => $vi['contractCode'], 'payConId' => $vi['id'],
                            'payConName' => $vi['paymentterm'], 'checkDate' => day_date, 'remark' => '系统自动核销'
                        );
                        switch ($incomeType) {
                            case 0 :
                                $canCheckMoney = $isRed ?
                                    $vi['incomMoney'] : bcsub($vi['money'], bcadd($vi['incomMoney'], $vi['deductMoney'], 2), 2); // 计算到款可核销金额
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                            case 1 :
                                if($isRed){
                                    $canCheckMoney = $vi['deductMoney']; // 计算扣款可核销金额
                                }else{
                                    $canCheckMoneyIncome = bcsub($vi['money'], bcadd($vi['incomMoney'], $vi['deductMoney'], 2), 2); // 计算到款可核销金额
                                    $canCheckMoneyInvoice = bcsub($vi['money'], bcadd($vi['invoiceMoney'], $vi['deductMoney'], 2), 2); // 计算开票可核销金额
                                    $canCheckMoney = min($canCheckMoneyIncome,$canCheckMoneyInvoice); // 计算扣款可核销金额
                                }
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                            case 2 :
                                $canCheckMoney = $isRed ?
                                    $vi['invoiceMoney'] : bcsub($vi['money'], bcadd($vi['invoiceMoney'], $vi['deductMoney'], 2), 2); // 计算开票可核销金额
                                $thisCheckArray['checkMoney'] = min($canCheckMoney,$v); // select the min
                                break;
                        }
                        if($thisCheckArray['checkMoney'] > 0){
                            $v = bcsub($v,$thisCheckArray['checkMoney'],2); // 该合同剩余
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
     * 更新开票超期提醒
     */
    function updateInvoiceRemind_d($conditions) {
        $arr = $this->find(array('id' => $conditions['id']), null, 'invoiceRemind');
        $this->update($conditions, array('invoiceRemind' => $arr['invoiceRemind'] + 1));
    }

    /**
     * 更新到款超期提醒
     */
    function updateIncomeRemind_d($conditions) {
        $arr = $this->find(array('id' => $conditions['id']), null, 'incomeRemind');
        $this->update($conditions, array('incomeRemind' => $arr['incomeRemind'] + 1));
    }

    /**
     * 更新超期原因
     */
    function updateDealReason_d($arr) {
        $this->update(array("id" => $arr['id']), array('incomeReason' => $arr['dealReason']));
    }
}