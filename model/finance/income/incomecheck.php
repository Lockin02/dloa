<?php

/**
 * @author show
 * @Date 2013年8月13日 16:26:33
 * @version 1.0
 * @description:核销记录表 Model层
 */
class model_finance_income_incomecheck extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_income_check";
        $this->sql_map = "finance/income/incomecheckSql.php";
        parent:: __construct();
    }

    /**
     * 源单类型
     */
    function rtIncomeType($thisVal)
    {
        switch ($thisVal) {
            case '0':
                return '到款单';
                break;
            case '1':
                return '扣款申请';
                break;
            case '2':
                return '开票记录';
                break;
            default:
                break;
        }
    }

    /**
     * 状态
     */
    function rtStatus_d($thisVal)
    {
        return $thisVal == 1 ? '已审核' : '未审核';
    }

    /**
     * 红蓝字
     */
    function rtRed_d($thisVal)
    {
        return $thisVal == 1 ? '<span class="red">红字</span>' : '<span class="blue">蓝字</span>';
    }

    //核销处理
    function batchDeal_d($rows, $deductDispose = '')
    {
        //实例化付款条件类
        $receiptPlanDao = new model_contract_contract_receiptplan();
        try {
            $this->start_d();

            foreach ($rows as $val) {
                $isDelTag = isset($val ['isDelTag']) ? $val['isDelTag'] : NULL;
                $isRed = isset($val['isRed']) && $val['isRed'] == 1 ? $val['isRed'] : 0; // 红字
                if (empty ($val ['id']) && $isDelTag == 1) {

                } else if (empty ($val ['id'])) {
                    $id = parent::add_d($val, true);
                    $val ['id'] = $id;
                    $val['isAddAction'] = true;//标识是新增的

                    //获取最大勾稽日期作为完成日期
                    $maxDate = $this->getMaxCheckDate_d($val['payConId']);
                    //新增核销处理
                    $receiptPlanDao->dealAdd_d($val['payConId'], $val['checkMoney'], $maxDate, $val['incomeType'], $isRed, $deductDispose);
                } else if ($isDelTag == 1) {
                    parent::deletes($val ['id']);
                    //删除核销处理
                    $receiptPlanDao->dealDelete_d($val['payConId'], $val['checkMoney'], $val['incomeType'], $isRed, $deductDispose);
                } else {
                    //获取原记录，用于比较差异
                    $obj = $this->get_d($val['id']);
                    //保存新纪录
                    parent::edit_d($val, true);
                    //计算金额差异
                    $diffMoney = bcsub($val['checkMoney'], $obj['checkMoney'], 2);
                    //获取最大勾稽日期作为完成日期
                    $maxDate = $this->getMaxCheckDate_d($val['payConId']);
                    //编辑核销处理
                    $receiptPlanDao->dealEdit_d($val['payConId'], $diffMoney, $maxDate, $val['incomeType'], $isRed, $deductDispose);
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 付款计划最大的核销日期
     * @param $payConId int 付款条件id
     */
    function getMaxCheckDate_d($payConId)
    {
        $sql = "select max(checkDate) as maxCheckDate from " . $this->tbl_name . " where payConId = " . $payConId;
        $rs = $this->_db->getArray($sql);
        if ($rs) {
            return $rs[0]['maxCheckDate'];
        } else {
            return false;
        }
    }

    /**
     * 获取核销记录
     * @param $incomeId int 源单id
     * @param $incomeType int 源单类型
     */
    function getCheckList_d($incomeId, $incomeType = 0)
    {
        return $this->findAll(array('incomeId' => $incomeId, 'incomeType' => $incomeType));
    }

    /**
     * 获取需要初始化的合同ID信息
     * @return mixed
     */
    function getNeedContractIdList_d()
    {
        $sql = "SELECT contractId FROM oa_contract_receiptplan
            WHERE isDel = 0 AND isTemp = 0 AND contractId <> '' GROUP BY contractId";
        return $this->_db->getArray($sql);
    }

    /**
     * 初始化核销记录
     * @param string $contractIds
     * @return string
     * @throws Exception
     */
    function initData_d($contractIds = '')
    {
        set_time_limit(0); // 超时

        // 获取过滤脚本片段
        $filterSqlItem = $this->buildFilterSql_d($contractIds);

        // 初始化付款条件
        $this->_db->query("UPDATE oa_contract_receiptplan SET isCom = 0,comDate = NULL,incomMoney = 0,invoiceMoney = 0
            WHERE isTemp = 0 AND isDel = 0 " . $filterSqlItem['contractIdSql']);
        $this->_db->query("DELETE FROM oa_finance_income_check WHERE incomeType <> 1 " . $filterSqlItem['contractIdSql']);

        // 先更新付款条件完成状态
        $sql = "UPDATE oa_contract_receiptplan SET isCom = if(money - incomMoney - deductMoney = 0 AND money - invoiceMoney - deductMoney = 0,1,0)
            WHERE isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $this->_db->query($sql);

        // 获取付款条件
        $sql = "SELECT * FROM
            (
            SELECT
                    i.id,i.paymentterm,i.contractId,c.contractCode,c.contractName,i.isCom,i.money,
                    i.incomMoney,i.money - i.incomMoney - i.deductMoney AS canIncomMoney,
                    i.invoiceMoney,i.money - i.invoiceMoney - i.deductMoney AS canInvoiceMoney,
                    i.deductMoney,i.Tday, 1 AS sortNo
                FROM
                    oa_contract_receiptplan i INNER JOIN oa_contract_contract c ON i.contractId = c.id AND c.isTemp = 0
                WHERE i.isTemp = 0 AND i.isDel = 0 AND i.isCom = 0 AND i.isfinance = 0 " . $filterSqlItem['mainSql'] . "
                    AND Tday <> ''
            UNION ALL
            SELECT
                    i.id,i.paymentterm,i.contractId,c.contractCode,c.contractName,i.isCom,i.money,
                    i.incomMoney,i.money - i.incomMoney - i.deductMoney AS canIncomMoney,
                    i.invoiceMoney,i.money - i.invoiceMoney - i.deductMoney AS canInvoiceMoney,
                    i.deductMoney,i.Tday, 2 AS sortNo
                FROM
                    oa_contract_receiptplan i INNER JOIN oa_contract_contract c ON i.contractId = c.id AND c.isTemp = 0
                WHERE i.isTemp = 0 AND i.isDel = 0 AND i.isfinance = 0 AND i.isCom = 0 " . $filterSqlItem['mainSql'] . "
                    AND (Tday IS NULL OR Tday = '0000-00-00' OR Tday = '')
            ) c
            ORDER BY contractId, sortNo, Tday";
        $contractArray = $this->_db->getArray($sql);

        // 获取有付款条件的合同id
        $sql = "SELECT DISTINCT contractId FROM oa_contract_receiptplan
            WHERE isCom = 0 AND isDel = 0 AND isTemp = 0 AND isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $contractIdArray = $this->_db->getArray($sql);
        foreach ($contractIdArray as $k => $v) { // 格式化数组
            $contractIdArray[$k] = $v['contractId'];
        }

        // 获取未核销的到款
        $sql = "SELECT i.id,i.incomeNo,c.objId,c.money
            FROM oa_finance_income_allot c LEFT JOIN oa_finance_income i ON c.incomeId = i.id
            WHERE c.objType = 'KPRK-12' " . $filterSqlItem['objSql'] . "
            ORDER BY c.allotDate";
        $incomeArray = $this->_db->getArray($sql);
        $incomeArrayDeal = array(); // 转义后的到款数组
        foreach ($incomeArray as $v) {
            if (in_array($v['objId'], $contractIdArray)) {
                $incomeArrayDeal[$v['objId']][] = $v;
            }
        }
        unset($incomeArray);

        // 获取未核销的开票
        $sql = "SELECT id,invoiceCode AS incomeNo,objId,if(isRed = 1,-invoiceMoney,invoiceMoney) AS money
            FROM oa_finance_invoice c WHERE objType = 'KPRK-12' " . $filterSqlItem['objSql'] . " ORDER BY invoiceTime";
        $invoiceArray = $this->_db->getArray($sql);
        $invoiceArrayDeal = $invoiceArrayDealBck = array(); // 转义后的到款数组
        foreach ($invoiceArray as $v) {
            if (in_array($v['objId'], $contractIdArray)) {
                $invoiceArrayDeal[$v['objId']][] = $v;
            }
        }
        $invoiceArrayDealBck = $invoiceArrayDeal;
        unset($invoiceArray);
//        print_r($contractIdArray);
//        var_dump($invoiceArrayDeal);die();

        // 批量新增的数据
        $batchArray = array();

        // 核销处理
        foreach ($contractArray as $k => $v) {
            // 到款核销
            if (isset($incomeArrayDeal[$v['contractId']])) {
                foreach ($incomeArrayDeal[$v['contractId']] as $ki => $vi) {
                    $canCheckMoney = bcsub($contractArray[$k]['money'], bcadd($contractArray[$k]['incomMoney'], $contractArray[$k]['deductMoney'], 2), 2); // 剩余可核销
                    if ($canCheckMoney == 0 || $canCheckMoney < 0) continue;

                    $checkMoney = min($canCheckMoney, $vi['money']); // 当前可核销金额
                    // check row
                    $thisCheckArray = array(
                        'contractId' => $v['contractId'], 'contractName' => $v['contractName'],
                        'contractCode' => $v['contractCode'], 'payConId' => $v['id'],
                        'payConName' => $v['paymentterm'], 'checkDate' => day_date, 'remark' => '系统自动核销', 'isRed' => 0,
                        'incomeId' => $vi['id'], 'incomeType' => 0, 'incomeNo' => $vi['incomeNo'], 'checkMoney' => $checkMoney
                    );
                    $batchArray[] = $this->addCreateInfo($thisCheckArray);

                    // 扣除已核销金额到款金额
                    $incomeArrayDeal[$v['contractId']][$ki]['money'] = bcsub($incomeArrayDeal[$v['contractId']][$ki]['money'], $checkMoney, 2);
                    // 如果到款金额已完全分配，则撤销到款记录
                    if ($incomeArrayDeal[$v['contractId']][$ki]['money'] == 0) {
                        unset($incomeArrayDeal[$v['contractId']][$ki]);
                    }

                    // 添加到款金额
                    $contractArray[$k]['incomMoney'] = bcadd($contractArray[$k]['incomMoney'], $checkMoney, 2); // 叠加付款条件的已到款核销金额
                    // 如果付款条件剩余可核销金额为0的时候直接跳出
                    if ($checkMoney == $canCheckMoney) {
                        break;
                    }
                }
                if (empty($incomeArrayDeal[$v['contractId']])) unset($incomeArrayDeal[$v['contractId']]);
            }

            // 开票核销
            if (isset($invoiceArrayDeal[$v['contractId']])) {
                foreach ($invoiceArrayDeal[$v['contractId']] as $ki => $vi) {
                    $canCheckMoney = bcsub($contractArray[$k]['money'], bcadd($contractArray[$k]['invoiceMoney'], $contractArray[$k]['deductMoney'], 2), 2); // 剩余可核销
                    if ($canCheckMoney == 0 || $canCheckMoney < 0) continue;

                    $checkMoney = min($canCheckMoney, $vi['money']); // 当前可核销金额
                    // check row
                    $thisCheckArray = array(
                        'contractId' => $v['contractId'], 'contractName' => $v['contractName'],
                        'contractCode' => $v['contractCode'], 'payConId' => $v['id'],
                        'payConName' => $v['paymentterm'], 'checkDate' => day_date, 'remark' => '系统自动核销', 'isRed' => $vi['money'] > 0 ? 0 : 1,
                        'incomeId' => $vi['id'], 'incomeType' => 2, 'incomeNo' => $vi['incomeNo'], 'checkMoney' => abs($checkMoney)
                    );
                    $batchArray[] = $this->addCreateInfo($thisCheckArray);

                    // 扣除已核销金额开票
                    $invoiceArrayDeal[$v['contractId']][$ki]['money'] = bcsub($invoiceArrayDeal[$v['contractId']][$ki]['money'], $checkMoney, 2);
                    // 如果开票金额已完全分配，则撤销到款记录
                    if ($invoiceArrayDeal[$v['contractId']][$ki]['money'] == 0) {
                        unset($invoiceArrayDeal[$v['contractId']][$ki]);
                    }

                    // 添加开票金额
                    $contractArray[$k]['invoiceMoney'] = bcadd($contractArray[$k]['invoiceMoney'], $checkMoney, 2); // 叠加付款条件的已开票核销金额
                    // 如果付款条件剩余可核销金额为0的时候直接跳出
                    if ($checkMoney == $canCheckMoney) {
                        break;
                    }
                }
                if (empty($invoiceArrayDeal[$v['contractId']])) unset($invoiceArrayDeal[$v['contractId']]);
            }
        }
        // 新增核销记录
        $this->createBatch($batchArray);

        // 更新付款条件金额
        $sql = "UPDATE
            oa_contract_receiptplan i
            LEFT JOIN
            (
                SELECT
                    payConId,
                    SUM(IF(incomeType = 0,IF(isRed = 0,checkMoney,-checkMoney),0)) AS incomMoney,
                    SUM(IF(incomeType = 2,IF(isRed = 0,checkMoney,-checkMoney),0)) AS invoiceMoney
                FROM oa_finance_income_check WHERE incomeType IN(0,2) " . $filterSqlItem['contractIdSql'] . "
                GROUP BY payConId
            ) p ON i.id = p.payConId
            SET
                i.incomMoney = p.incomMoney,i.invoiceMoney = p.invoiceMoney
            WHERE i.isfinance = 0 AND p.payConId IS NOT NULL " . $filterSqlItem['itemSql'];
        $this->_db->query($sql);

        // 更新付款条件完成情况
        $sql = "UPDATE oa_contract_receiptplan
            SET isCom = if(money = incomMoney + deductMoney AND money = invoiceMoney + deductMoney,1,0),
                comDate = if(isCom = 1,'" . day_date . "',NULL) WHERE isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $this->_db->query($sql);

        // 初始化完成后, 根据合同所有的开票记录来更新一下相应的核销记录 (PMS 2981 在不更改原来处理逻辑的情况下,简单粗暴的解决方法)
        if(!empty($invoiceArrayDealBck)){
            $receiptPlanDao = new model_contract_contract_receiptplan();
            foreach ($invoiceArrayDealBck as $conId => $rows){
                foreach ($rows as $rk => $rv){
                    // 判断该开票记录是否已存在核销记录
                    $checkMoneyObj = $this->_db->get_one("select sum(checkMoney) as allcheckMoney from oa_finance_income_check where incomeType='2' and incomeId='{$rv['id']}';");
                    if(isset($checkMoneyObj['allcheckMoney']) && $checkMoneyObj['allcheckMoney'] == abs($rv['money'])){
                        continue;
                    }else{
                        //实例化付款条件类
                        $isRed = ($rv['money'] < 0)? 1 : 0;
                        $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($rv['objId'] => abs($rv['money'])), 2, $isRed);

                        //核销处理
                        if ($incomeCheck) {
                            $incomeCheck = util_arrayUtil::setArrayFn(
                                array('incomeId' => $rv['id'], 'incomeNo' => $rv['incomeNo'], 'incomeType' => 2, 'isRed' => $isRed),
                                $incomeCheck
                            );
                            $this->batchDeal_d($incomeCheck);
                        }
                    }
                }
            }
        }

        return 'ok';
    }

    /**
     * 返回合同过滤条件
     * @param string $contractIds
     * @return array
     */
    function buildFilterSql_d($contractIds = '')
    {
        if ($contractIds) {
            return array(
                'mainSql' => ' AND c.id IN(' . $contractIds . ')',
                'itemSql' => ' AND i.contractId IN(' . $contractIds . ')',
                'contractIdSql' => ' AND contractId IN(' . $contractIds . ')',
                'objSql' => ' AND c.objId IN(' . $contractIds . ')'
            );
        } else {
            return array(
                'mainSql' => ' AND 1',
                'itemSql' => ' AND 1',
                'contractIdSql' => ' AND 1',
                'objSql' => ' AND 1',
            );
        }
    }
}