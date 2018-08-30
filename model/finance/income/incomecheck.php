<?php

/**
 * @author show
 * @Date 2013��8��13�� 16:26:33
 * @version 1.0
 * @description:������¼�� Model��
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
     * Դ������
     */
    function rtIncomeType($thisVal)
    {
        switch ($thisVal) {
            case '0':
                return '���';
                break;
            case '1':
                return '�ۿ�����';
                break;
            case '2':
                return '��Ʊ��¼';
                break;
            default:
                break;
        }
    }

    /**
     * ״̬
     */
    function rtStatus_d($thisVal)
    {
        return $thisVal == 1 ? '�����' : 'δ���';
    }

    /**
     * ������
     */
    function rtRed_d($thisVal)
    {
        return $thisVal == 1 ? '<span class="red">����</span>' : '<span class="blue">����</span>';
    }

    //��������
    function batchDeal_d($rows, $deductDispose = '')
    {
        //ʵ��������������
        $receiptPlanDao = new model_contract_contract_receiptplan();
        try {
            $this->start_d();

            foreach ($rows as $val) {
                $isDelTag = isset($val ['isDelTag']) ? $val['isDelTag'] : NULL;
                $isRed = isset($val['isRed']) && $val['isRed'] == 1 ? $val['isRed'] : 0; // ����
                if (empty ($val ['id']) && $isDelTag == 1) {

                } else if (empty ($val ['id'])) {
                    $id = parent::add_d($val, true);
                    $val ['id'] = $id;
                    $val['isAddAction'] = true;//��ʶ��������

                    //��ȡ��󹴻�������Ϊ�������
                    $maxDate = $this->getMaxCheckDate_d($val['payConId']);
                    //������������
                    $receiptPlanDao->dealAdd_d($val['payConId'], $val['checkMoney'], $maxDate, $val['incomeType'], $isRed, $deductDispose);
                } else if ($isDelTag == 1) {
                    parent::deletes($val ['id']);
                    //ɾ����������
                    $receiptPlanDao->dealDelete_d($val['payConId'], $val['checkMoney'], $val['incomeType'], $isRed, $deductDispose);
                } else {
                    //��ȡԭ��¼�����ڱȽϲ���
                    $obj = $this->get_d($val['id']);
                    //�����¼�¼
                    parent::edit_d($val, true);
                    //���������
                    $diffMoney = bcsub($val['checkMoney'], $obj['checkMoney'], 2);
                    //��ȡ��󹴻�������Ϊ�������
                    $maxDate = $this->getMaxCheckDate_d($val['payConId']);
                    //�༭��������
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
     * ����ƻ����ĺ�������
     * @param $payConId int ��������id
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
     * ��ȡ������¼
     * @param $incomeId int Դ��id
     * @param $incomeType int Դ������
     */
    function getCheckList_d($incomeId, $incomeType = 0)
    {
        return $this->findAll(array('incomeId' => $incomeId, 'incomeType' => $incomeType));
    }

    /**
     * ��ȡ��Ҫ��ʼ���ĺ�ͬID��Ϣ
     * @return mixed
     */
    function getNeedContractIdList_d()
    {
        $sql = "SELECT contractId FROM oa_contract_receiptplan
            WHERE isDel = 0 AND isTemp = 0 AND contractId <> '' GROUP BY contractId";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ʼ��������¼
     * @param string $contractIds
     * @return string
     * @throws Exception
     */
    function initData_d($contractIds = '')
    {
        set_time_limit(0); // ��ʱ

        // ��ȡ���˽ű�Ƭ��
        $filterSqlItem = $this->buildFilterSql_d($contractIds);

        // ��ʼ����������
        $this->_db->query("UPDATE oa_contract_receiptplan SET isCom = 0,comDate = NULL,incomMoney = 0,invoiceMoney = 0
            WHERE isTemp = 0 AND isDel = 0 " . $filterSqlItem['contractIdSql']);
        $this->_db->query("DELETE FROM oa_finance_income_check WHERE incomeType <> 1 " . $filterSqlItem['contractIdSql']);

        // �ȸ��¸����������״̬
        $sql = "UPDATE oa_contract_receiptplan SET isCom = if(money - incomMoney - deductMoney = 0 AND money - invoiceMoney - deductMoney = 0,1,0)
            WHERE isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $this->_db->query($sql);

        // ��ȡ��������
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

        // ��ȡ�и��������ĺ�ͬid
        $sql = "SELECT DISTINCT contractId FROM oa_contract_receiptplan
            WHERE isCom = 0 AND isDel = 0 AND isTemp = 0 AND isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $contractIdArray = $this->_db->getArray($sql);
        foreach ($contractIdArray as $k => $v) { // ��ʽ������
            $contractIdArray[$k] = $v['contractId'];
        }

        // ��ȡδ�����ĵ���
        $sql = "SELECT i.id,i.incomeNo,c.objId,c.money
            FROM oa_finance_income_allot c LEFT JOIN oa_finance_income i ON c.incomeId = i.id
            WHERE c.objType = 'KPRK-12' " . $filterSqlItem['objSql'] . "
            ORDER BY c.allotDate";
        $incomeArray = $this->_db->getArray($sql);
        $incomeArrayDeal = array(); // ת���ĵ�������
        foreach ($incomeArray as $v) {
            if (in_array($v['objId'], $contractIdArray)) {
                $incomeArrayDeal[$v['objId']][] = $v;
            }
        }
        unset($incomeArray);

        // ��ȡδ�����Ŀ�Ʊ
        $sql = "SELECT id,invoiceCode AS incomeNo,objId,if(isRed = 1,-invoiceMoney,invoiceMoney) AS money
            FROM oa_finance_invoice c WHERE objType = 'KPRK-12' " . $filterSqlItem['objSql'] . " ORDER BY invoiceTime";
        $invoiceArray = $this->_db->getArray($sql);
        $invoiceArrayDeal = $invoiceArrayDealBck = array(); // ת���ĵ�������
        foreach ($invoiceArray as $v) {
            if (in_array($v['objId'], $contractIdArray)) {
                $invoiceArrayDeal[$v['objId']][] = $v;
            }
        }
        $invoiceArrayDealBck = $invoiceArrayDeal;
        unset($invoiceArray);
//        print_r($contractIdArray);
//        var_dump($invoiceArrayDeal);die();

        // ��������������
        $batchArray = array();

        // ��������
        foreach ($contractArray as $k => $v) {
            // �������
            if (isset($incomeArrayDeal[$v['contractId']])) {
                foreach ($incomeArrayDeal[$v['contractId']] as $ki => $vi) {
                    $canCheckMoney = bcsub($contractArray[$k]['money'], bcadd($contractArray[$k]['incomMoney'], $contractArray[$k]['deductMoney'], 2), 2); // ʣ��ɺ���
                    if ($canCheckMoney == 0 || $canCheckMoney < 0) continue;

                    $checkMoney = min($canCheckMoney, $vi['money']); // ��ǰ�ɺ������
                    // check row
                    $thisCheckArray = array(
                        'contractId' => $v['contractId'], 'contractName' => $v['contractName'],
                        'contractCode' => $v['contractCode'], 'payConId' => $v['id'],
                        'payConName' => $v['paymentterm'], 'checkDate' => day_date, 'remark' => 'ϵͳ�Զ�����', 'isRed' => 0,
                        'incomeId' => $vi['id'], 'incomeType' => 0, 'incomeNo' => $vi['incomeNo'], 'checkMoney' => $checkMoney
                    );
                    $batchArray[] = $this->addCreateInfo($thisCheckArray);

                    // �۳��Ѻ���������
                    $incomeArrayDeal[$v['contractId']][$ki]['money'] = bcsub($incomeArrayDeal[$v['contractId']][$ki]['money'], $checkMoney, 2);
                    // �������������ȫ���䣬���������¼
                    if ($incomeArrayDeal[$v['contractId']][$ki]['money'] == 0) {
                        unset($incomeArrayDeal[$v['contractId']][$ki]);
                    }

                    // ��ӵ�����
                    $contractArray[$k]['incomMoney'] = bcadd($contractArray[$k]['incomMoney'], $checkMoney, 2); // ���Ӹ����������ѵ���������
                    // �����������ʣ��ɺ������Ϊ0��ʱ��ֱ������
                    if ($checkMoney == $canCheckMoney) {
                        break;
                    }
                }
                if (empty($incomeArrayDeal[$v['contractId']])) unset($incomeArrayDeal[$v['contractId']]);
            }

            // ��Ʊ����
            if (isset($invoiceArrayDeal[$v['contractId']])) {
                foreach ($invoiceArrayDeal[$v['contractId']] as $ki => $vi) {
                    $canCheckMoney = bcsub($contractArray[$k]['money'], bcadd($contractArray[$k]['invoiceMoney'], $contractArray[$k]['deductMoney'], 2), 2); // ʣ��ɺ���
                    if ($canCheckMoney == 0 || $canCheckMoney < 0) continue;

                    $checkMoney = min($canCheckMoney, $vi['money']); // ��ǰ�ɺ������
                    // check row
                    $thisCheckArray = array(
                        'contractId' => $v['contractId'], 'contractName' => $v['contractName'],
                        'contractCode' => $v['contractCode'], 'payConId' => $v['id'],
                        'payConName' => $v['paymentterm'], 'checkDate' => day_date, 'remark' => 'ϵͳ�Զ�����', 'isRed' => $vi['money'] > 0 ? 0 : 1,
                        'incomeId' => $vi['id'], 'incomeType' => 2, 'incomeNo' => $vi['incomeNo'], 'checkMoney' => abs($checkMoney)
                    );
                    $batchArray[] = $this->addCreateInfo($thisCheckArray);

                    // �۳��Ѻ�����Ʊ
                    $invoiceArrayDeal[$v['contractId']][$ki]['money'] = bcsub($invoiceArrayDeal[$v['contractId']][$ki]['money'], $checkMoney, 2);
                    // �����Ʊ�������ȫ���䣬���������¼
                    if ($invoiceArrayDeal[$v['contractId']][$ki]['money'] == 0) {
                        unset($invoiceArrayDeal[$v['contractId']][$ki]);
                    }

                    // ��ӿ�Ʊ���
                    $contractArray[$k]['invoiceMoney'] = bcadd($contractArray[$k]['invoiceMoney'], $checkMoney, 2); // ���Ӹ����������ѿ�Ʊ�������
                    // �����������ʣ��ɺ������Ϊ0��ʱ��ֱ������
                    if ($checkMoney == $canCheckMoney) {
                        break;
                    }
                }
                if (empty($invoiceArrayDeal[$v['contractId']])) unset($invoiceArrayDeal[$v['contractId']]);
            }
        }
        // ����������¼
        $this->createBatch($batchArray);

        // ���¸����������
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

        // ���¸�������������
        $sql = "UPDATE oa_contract_receiptplan
            SET isCom = if(money = incomMoney + deductMoney AND money = invoiceMoney + deductMoney,1,0),
                comDate = if(isCom = 1,'" . day_date . "',NULL) WHERE isfinance = 0 " . $filterSqlItem['contractIdSql'];
        $this->_db->query($sql);

        // ��ʼ����ɺ�, ���ݺ�ͬ���еĿ�Ʊ��¼������һ����Ӧ�ĺ�����¼ (PMS 2981 �ڲ�����ԭ�������߼��������,�򵥴ֱ��Ľ������)
        if(!empty($invoiceArrayDealBck)){
            $receiptPlanDao = new model_contract_contract_receiptplan();
            foreach ($invoiceArrayDealBck as $conId => $rows){
                foreach ($rows as $rk => $rv){
                    // �жϸÿ�Ʊ��¼�Ƿ��Ѵ��ں�����¼
                    $checkMoneyObj = $this->_db->get_one("select sum(checkMoney) as allcheckMoney from oa_finance_income_check where incomeType='2' and incomeId='{$rv['id']}';");
                    if(isset($checkMoneyObj['allcheckMoney']) && $checkMoneyObj['allcheckMoney'] == abs($rv['money'])){
                        continue;
                    }else{
                        //ʵ��������������
                        $isRed = ($rv['money'] < 0)? 1 : 0;
                        $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($rv['objId'] => abs($rv['money'])), 2, $isRed);

                        //��������
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
     * ���غ�ͬ��������
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