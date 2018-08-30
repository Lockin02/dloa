<?php

/**
 * @author Show
 * @Date 2012��10��11�� ������ 10:01:33
 * @version 1.0
 * @description:������������ Model��
 */
class model_finance_expense_exsummary extends model_base
{

    function __construct()
    {
        $this->tbl_name = "cost_summary_list";
        $this->sql_map = "finance/expense/exsummarySql.php";
        parent:: __construct();
    }

    /******************* ���ò��� ******************/

    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    // ���ñ�������
    private $detailTypeArr = array(
        '1' => '���ŷ���',
        '2' => '��ͬ��Ŀ����',
        '3' => '�з�����',
        '4' => '��ǰ����',
        '5' => '�ۺ����'
    );

    // ���ط�������
    function rtDetailType($thisVal)
    {
        if (isset($this->detailTypeArr[$thisVal])) {
            return $this->detailTypeArr[$thisVal];
        } else {
            return $thisVal;
        }
    }

    /********************* ��ɾ�Ĳ� ******************/

    /**
     * @param $object
     * @return bool
     * @throws Exception
     */
    function add_d($object)
    {
        try {
            //������Ϣ����
            $object['SubDept'] = '';
            $object['Acc'] = '';
            $object['AccBank'] = '';
            unset($object['xm_sid']);
            $object['ProjectNo'] = empty($object['ProjectNO']) ? '' : $object['ProjectNO'];
            $object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
            $object['CostClientType'] = $object['Purpose'];
            $object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];
            $object['CheckAmount'] = $object['Amount'];
            $object['UpdateDT'] = $object['InputDate'];
            $object['Area'] = $_SESSION['AREA'];
            $object['isNew'] = 1;
            $object['Status'] = '�༭';
            $object['ExaStatus'] = '�༭';

            $newId = parent::add_d($object);

            return $newId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $object
     * @return mixed
     * @throws Exception
     */
    function edit_d($object)
    {
        //��ȡ��ǰ��¼��Ϣ
        $obj = $this->find(array('BillNo' => $object['BillNo']), null, 'ID');

        try {
            //������Ϣ����
            $object['SubDept'] = '';
            $object['Acc'] = '';
            $object['AccBank'] = '';
            unset($object['xm_sid']);
            $object['ProjectNo'] = empty($object['ProjectNO']) ? '' : $object['ProjectNO'];
            $object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
            $object['CostClientType'] = $object['Purpose'];
            $object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];
            $object['CheckAmount'] = $object['Amount'];
            $object['UpdateDT'] = $object['InputDate'];
            $object['Area'] = $_SESSION['AREA'];
            $object['isNew'] = 1;
            $object['Status'] = '�༭';
            $object['ExaStatus'] = '�༭';

            $this->update(array('BillNo' => $object['BillNo']), $object);

            return $obj['ID'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ�������ݷ���
     * @param $id
     * @return bool|mixed
     */
    function getInfo_d($id)
    {
        //��ȡ����λ��Ϣ
        $obj = $this->get_d($id);

        //������ϸ��ʵ����
        $expensedetailDao = new model_finance_expense_expensedetail();
        //������ϸ
        $obj['expensedetail'] = $expensedetailDao->getBillDetail_d($obj['BillNo']);
        $obj['expensedetail'] = $expensedetailDao->initBillDetailView_d($obj['expensedetail']);

        //��̯��ϸ��ʵ����
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        //��̯��ϸ
        $obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);
        $obj['expensecostshare'] = $expensecostshareDao->initBillDetailView_d($obj['expensecostshare']);

        //��Ʊ��ϸ
        $expenseinvDao = new model_finance_expense_expenseinv();
        $obj['expenseinv'] = $expenseinvDao->getInvDetail_d($obj['BillNo']);
        $obj['expenseinv'] = $expenseinvDao->initInvDetailView_d($obj['expenseinv']);

        //��Ʊ��
        $exbillDao = new model_finance_expense_exbill();
        $exbillObj = $exbillDao->find(array('ConBillNo' => $obj['BillNo']), null, 'BillNo');
        $obj['BillListNo'] = $exbillObj['BillNo'];

        return $obj;
    }

    /**
     * ��ȡ�������ݷ���
     * @param $id
     * @return bool|mixed
     */
    function getInfoEdit_d($id)
    {
        $condition = array('id' => $id);

        //��ȡ����λ��Ϣ
        $obj = $this->find($condition);
        if ($obj) {
            //��ȡ������Ϣ
            $expenseDao = new model_finance_expense_expense();
            $expenseObjs = $expenseDao->findAll(array('BillNo' => $obj['BillNo']));
            //ҵ����Ϣ����
            $obj['CostManName'] = $expenseObjs[0]['CostManName'];
            $obj['CostDepartName'] = $expenseObjs[0]['CostDepartName'];
            $obj['CostManCom'] = $expenseObjs[0]['CostManCom'];
            $obj['CostBelongCom'] = $expenseObjs[0]['CostBelongCom'];
            $obj['CostBelongDeptName'] = $expenseObjs[0]['CostBelongDeptName'];
            $obj['chanceId'] = $expenseObjs[0]['chanceId'];
            $obj['chanceCode'] = $expenseObjs[0]['chanceCode'];
            $obj['chanceName'] = $expenseObjs[0]['chanceName'];
            $obj['contractId'] = $expenseObjs[0]['contractId'];
            $obj['contractCode'] = $expenseObjs[0]['contractCode'];
            $obj['contractName'] = $expenseObjs[0]['contractName'];
            $obj['customerName'] = $expenseObjs[0]['customerName'];
            $obj['customerId'] = $expenseObjs[0]['customerId'];
            $obj['projectName'] = $expenseObjs[0]['projectName'];
            $obj['projectId'] = $expenseObjs[0]['projectId'];
            $obj['ProjectNO'] = $expenseObjs[0]['ProjectNO'];
            $obj['proManagerName'] = $expenseObjs[0]['proManagerName'];
            $obj['proManagerId'] = $expenseObjs[0]['proManagerId'];
            $obj['province'] = $expenseObjs[0]['province'];
            $obj['city'] = $expenseObjs[0]['city'];
            $obj['CustomerType'] = $expenseObjs[0]['CustomerType'];
            $obj['customerDept'] = $expenseObjs[0]['customerDept'];
            $obj['invoiceNumber'] = $expenseObjs[0]['invoiceNumber'];

            //������ϸ��ʵ����
            $expensedetailDao = new model_finance_expense_expensedetail();
            //������ϸ
            $obj['expensedetail'] = $expensedetailDao->getBillDetail_d($obj['BillNo']);
            $obj['expensedetail'] = $expensedetailDao->initBillDetailViewEdit_d($obj['expensedetail']);

            //��̯��ϸ��ʵ����
            $expensecostshareDao = new model_finance_expense_expensecostshare();
            //��̯��ϸ
            $obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);
            $obj['expensecostshare'] = $expensecostshareDao->initBillDetailViewEdit_d($obj['expensecostshare']);

            //��Ʊ��ϸ
            $expenseinvDao = new model_finance_expense_expenseinv();
            $invoiceNumber = 0;
            $obj['expenseinv'] = $expenseinvDao->getInvDetail_d($obj['BillNo']);
            foreach ($obj['expenseinv'] as $inv){// ����ͳ�Ʒ�Ʊ����
                $invoiceNumber += $inv['invoiceNumber'];
            }
            $obj['expenseinv'] = $expenseinvDao->initInvDetailViewEdit_d($obj['expenseinv']);
            $obj['invoiceNumber'] = $invoiceNumber;

            //��Ʊ��
            $exbillDao = new model_finance_expense_exbill();
            $exbillObj = $exbillDao->find(array('ConBillNo' => $obj['BillNo']), null, 'BillNo');
            $obj['BillListNo'] = $exbillObj['BillNo'];
            return $obj;
        }
    }

    /**
     * ���ݵ��ݱ��ɾ�����ܱ�����
     * @param $BillNo
     * @return bool
     */
    function delByBillNo_d($BillNo)
    {
        try {
            $this->start_d();
            //ɾ�����ܱ�
            $this->delete(array('BillNo' => $BillNo));

            //���»��ܱ���Ϣ
            $expenseDao = new model_finance_expense_expense();
            $expenseDao->clearBillNoInfo_d($BillNo);

            //����������ϸ
            $expenseassDao = new model_finance_expense_expenseass();
            $expenseassDao->clearBillNoInfo_d($BillNo);

            //���·�Ʊ��ϸ
            $expenseinvDao = new model_finance_expense_expenseinv();
            $expenseinvDao->clearBillNoInfo_d($BillNo);

            //���·�Ʊ��ϸ
            $exbillDao = new model_finance_expense_exbill();
            $exbillDao->delete(array('ConBillNo' => $BillNo));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������ɺ�����
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];
        $object = $this->get_d($objId);
        // ����ʱ��Ҫ���µ��ݵĲ���
        $updateInfo = array();
        switch ($object['ExaStatus']) {
            case AUDITED : // �������
                //��ȡ��������Ϣ
                $expenseDao = new model_finance_expense_expense();
                $expenseObj = $expenseDao->getByBillNo_d($object['BillNo']);

                //��������Ƶ��ݣ��������Ŀ��Ϣ����
                if ($expenseObj['isPush'] == 1 && $expenseObj['projectId']) {

                    // ���������1��������⳵���ֵĽӿ�
                    if ($expenseObj['relDocType'] == 1 && $expenseObj['relDocId']) {
                        $registerDao = new model_outsourcing_vehicle_register();
                        $relDocIdArr = explode(',', $expenseObj['relDocId']);
                        $relDocArr = array();
                        foreach ($relDocIdArr as $k => $v) {
                            if ($k == 0) {
                                $relDocArr[] = array(
                                    'id' => $v,
                                    'money' => $expenseObj['Amount'],
                                    'payType' => 0
                                );
                            } else {
                                $relDocArr[] = array(
                                    'id' => $v,
                                    'money' => -1,
                                    'payType' => 0
                                );
                            }
                        }
                        $registerDao->dealAfterPay_d($relDocArr);
                    } else {
                        $esmcostdetailStr = $expenseDao->getEsmCostDetail_d($object['BillNo']);

                        // �����ø��³��ѱ�������
                        if ($esmcostdetailStr) {
                            $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
                            $esmcostdetailDao->updateCost_d($esmcostdetailStr, '4');

                            $esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
                            $esmcostdetailInvDao->updateCostInvoice_d($esmcostdetailStr, '4');

                            //������Ա����Ŀ����
                            if ($expenseObj['projectId']) {
                                //��ȡ��ǰ��Ŀ�ķ���
                                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($expenseObj['projectId'], $expenseObj['CostMan']);

                                //������Ա������Ϣ
                                $esmmemberDao = new model_engineering_member_esmmember();
                                $esmmemberDao->update(
                                    array('projectId' => $expenseObj['projectId'], 'memberId' => $expenseObj['CostMan']),
                                    $projectCountArr
                                );
                            }
                        }
                    }
                }
                $updateInfo['isEffected'] = 1;
                break;
            case AUDITING : // ������
                //δ���ʱ,�ж��Ƿ񵽴��������
                $stepInfo = $otherdatas->getAuditngStepInfo($folowInfo['task']);
                if ($stepInfo['isReceive'] == '1') {
                    $updateInfo['isFinAudit'] = 1;
                }
                $updateInfo['isEffected'] = 1;
                break;
            case BACK;
                $updateInfo['isFinAudit'] = 0;
                $updateInfo['isEffected'] = 0;
                // ���δͨ������ȥԤ����еľ������
                $budgetDao = new model_finance_budget_budget();
                $budgetDao->subFinal($objId);
                break;
        }
        // ���µ�����Ϣ
        if (!empty($updateInfo)) $this->update(array('id' => $objId), $updateInfo);

        return true;
    }

    /**
     * ��ȡͳ��ֵ
     * @param $userId ����
     * @param $areaId ��������
     * @return array
     */
    function getStatistic($userId, $areaId)
    {
        //��ǰ���
        $year = date("Y");
        //�����������
        $feeArr = array();
        //������������������pk�ķ���
        $sql = "SELECT
					'userFee' AS 'type',
					l.salesAreaId,
					SUM(d.CostMoney*d.days) AS fee
				FROM
					cost_detail d
				LEFT JOIN cost_summary_list l ON d.BillNo = l.BillNo
				WHERE
					l.DetailType IN (4, 5)
				AND l.`Status` = '���'
				AND (
					l.ProjectNo = ''
					OR l.ProjectNo IS NULL
				)
				AND DATE_FORMAT(l.PayDT, '%Y') = '{$year}'
				AND l.salesAreaId = '{$areaId}'
				AND l.feeManId = '{$userId}'
				GROUP BY
					l.salesAreaId
				UNION ALL
				SELECT
					'areaFee' AS 'type',
					l.salesAreaId,
					SUM(d.CostMoney*d.days) AS fee
				FROM
					cost_detail d
				LEFT JOIN cost_summary_list l ON d.BillNo = l.BillNo
				WHERE
					l.DetailType IN (4, 5)
				AND l.`Status` = '���'
				AND (
					l.ProjectNo = ''
					OR l.ProjectNo IS NULL
				)
				AND DATE_FORMAT(l.PayDT, '%Y') = '{$year}'
				AND l.salesAreaId = '{$areaId}'
				GROUP BY
					l.salesAreaId";
        $rs = $this->_db->getArray($sql);
        if (!empty($rs)) {
            foreach ($rs as $v) {
                if ($v['type'] == 'userFee') {
                    if (isset($feeArr['userFee'][$v['salesAreaId']]['fee'])) {
                        $feeArr['userFee'][$v['salesAreaId']]['fee'] = bcadd($feeArr['userFee'][$v['salesAreaId']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['userFee'][$v['salesAreaId']]['fee'] = $v['fee'];
                    }
                } elseif ($v['type'] == 'areaFee') {
                    if (isset($feeArr['areaFee'][$v['salesAreaId']]['fee'])) {
                        $feeArr['areaFee'][$v['salesAreaId']]['fee'] = bcadd($feeArr['areaFee'][$v['salesAreaId']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['areaFee'][$v['salesAreaId']]['fee'] = $v['fee'];
                    }
                }
            }
        }
        //���÷�̯������������pk�ķ���
        $sql = "SELECT
					'userFee' AS 'type',
					salesAreaId,
					SUM(costMoney) AS fee
				FROM
					oa_finance_cost
				WHERE
					detailType IN (4, 5)
				AND auditStatus = 1
				AND isTemp = 0 
				AND isDel = 0
				AND costTypeName <> 'Ͷ�걣֤��'
				AND shareObjType <> 'FTDXLX-05'
				AND DATE_FORMAT(auditDate, '%Y') = '{$year}'
				AND salesAreaId = '{$areaId}'
				AND feeManId = '{$userId}'
				GROUP BY
					salesAreaId
				UNION ALL
				SELECT
					'areaFee' AS 'type',
					salesAreaId,
					SUM(costMoney) AS fee
				FROM
					oa_finance_cost
				WHERE
					detailType IN (4, 5)
				AND auditStatus = 1
				AND isTemp = 0 
				AND isDel = 0
				AND costTypeName <> 'Ͷ�걣֤��'
				AND shareObjType <> 'FTDXLX-05'
				AND DATE_FORMAT(auditDate, '%Y') = '{$year}'
				AND salesAreaId = '{$areaId}'
				GROUP BY
					salesAreaId";
        $rs = $this->_db->getArray($sql);
        if (!empty($rs)) {
            foreach ($rs as $v) {
                if ($v['type'] == 'userFee') {
                    if (isset($feeArr['userFee'][$v['salesAreaId']]['fee'])) {
                        $feeArr['userFee'][$v['salesAreaId']]['fee'] = bcadd($feeArr['userFee'][$v['salesAreaId']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['userFee'][$v['salesAreaId']]['fee'] = $v['fee'];
                    }
                } elseif ($v['type'] == 'areaFee') {
                    if (isset($feeArr['areaFee'][$v['salesAreaId']]['fee'])) {
                        $feeArr['areaFee'][$v['salesAreaId']]['fee'] = bcadd($feeArr['areaFee'][$v['salesAreaId']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['areaFee'][$v['salesAreaId']]['fee'] = $v['fee'];
                    }
                }
            }
        }
        //����
        $sql = "SELECT
					'userFee' AS 'type',
					p.areaCode,
					SUM(e.costAct) AS fee
				FROM
					oa_present_equ e
				LEFT JOIN oa_present_present p ON e.presentId = p.id
				WHERE
					p.ExaStatus IN ('���','���������')
				AND e.isTemp = 0
				AND e.isDel = 0
				AND DATE_FORMAT(p.ExaDT, '%Y') = '{$year}'
				AND p.areaCode = '{$areaId}'
				AND p.feeManId = '{$userId}'
				GROUP BY
					p.areaCode
				UNION ALL
				SELECT
					'areaFee' AS 'type',
					p.areaCode,
					SUM(e.costAct) AS fee
				FROM
				oa_present_equ e
				LEFT JOIN oa_present_present p ON e.presentId = p.id
				WHERE
					p.ExaStatus IN ('���','���������')
				AND e.isTemp = 0
				AND e.isDel = 0
				AND DATE_FORMAT(p.ExaDT, '%Y') = '{$year}'
				AND p.areaCode = '{$areaId}'
				GROUP BY
					p.areaCode";
        $rs = $this->_db->getArray($sql);
        if (!empty($rs)) {
            foreach ($rs as $v) {
                if ($v['type'] == 'userFee') {
                    if (isset($feeArr['userFee'][$v['areaCode']]['fee'])) {
                        $feeArr['userFee'][$v['areaCode']]['fee'] = bcadd($feeArr['userFee'][$v['areaCode']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['userFee'][$v['areaCode']]['fee'] = $v['fee'];
                    }
                } elseif ($v['type'] == 'areaFee') {
                    if (isset($feeArr['areaFee'][$v['areaCode']]['fee'])) {
                        $feeArr['areaFee'][$v['areaCode']]['fee'] = bcadd($feeArr['areaFee'][$v['areaCode']]['fee'], $v['fee'], 2);
                    } else {
                        $feeArr['areaFee'][$v['areaCode']]['fee'] = $v['fee'];
                    }
                }
            }
        }
        //���ۺ�ͬ
//		$sql = "SELECT
//					'userFee' AS 'type',
//					areaCode,
//					SUM(contractMoney) AS contractMoney
//				FROM
//					oa_contract_contract
//				WHERE
//					ExaStatus = '���'
//				AND isTemp = 0
//				AND DATE_FORMAT(ExaDTOne, '%Y') = '{$year}'
//				AND areaCode = '{$areaId}'
//				AND prinvipalId = '{$userId}'
//				GROUP BY
//					areaCode
//				UNION ALL
//				SELECT
//					'areaFee' AS 'type',
//					areaCode,
//					SUM(contractMoney) AS contractMoney
//				FROM
//					oa_contract_contract
//				WHERE
//					ExaStatus = '���'
//				AND isTemp = 0
//				AND DATE_FORMAT(ExaDTOne, '%Y') = '{$year}'
//				AND areaCode = '{$areaId}'
//				GROUP BY
//					areaCode";
        $sql = "SELECT
					'userFee' AS 'type',
					areaCode,
					SUM(contractMoney) AS contractMoney
				FROM
					oa_contract_contract
				WHERE
					ExaStatus IN ('���','���������')
				AND isTemp = 0
				AND DATE_FORMAT(ExaDTOne, '%Y') = '{$year}'
				AND areaCode = '{$areaId}'
				AND prinvipalId = '{$userId}'
				GROUP BY
					areaCode
				UNION ALL
				SELECT
					'areaFee' AS 'type',
					areaCode,
					SUM(contractMoney) AS contractMoney
				FROM
					oa_contract_contract
				WHERE
					ExaStatus IN ('���','���������')
				AND isTemp = 0
				AND DATE_FORMAT(ExaDTOne, '%Y') = '{$year}'
				AND areaCode = '{$areaId}'
				GROUP BY
					areaCode";
        $rs = $this->_db->getArray($sql);
        if (!empty($rs)) {
            foreach ($rs as $v) {
                if ($v['type'] == 'userFee') {
                    $feeArr['userFee'][$v['areaCode']]['contract'] = $v['contractMoney'];
                } elseif ($v['type'] == 'areaFee') {
                    $feeArr['areaFee'][$v['areaCode']]['contract'] = $v['contractMoney'];
                }
            }
        }

        return $feeArr;
    }

    /**
     * ��ȡͳ��ֵ(���ˣ����򵥶���ȡ)
     * @param $searchType   ��������(����/����)
     * @param $ext_condition ���ӹ�������
     * @param $year_val
     * @param $personFilter ��Ա���˸���
     * @return array
     */
    function getStatisticSpl($searchType, $ext_condition, $year_val = '', $personFilter = '',$isBig = 0, $bigExtraSql = "")
    {
        //��ǰ���
        $year = ($year_val == '') ? '' : $year_val;
        $year_condition = ($year_val == '') ? " > 0" : " = '{$year}'";

        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // ���˲����õķ�����
        $expensefilterSql = $filterType ?
            " AND CostTypeId IN(SELECT CostTypeID FROM cost_type WHERE CostTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . "))" : "";
        $costSharefilterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // ��ȡ����������۲�����Ϣ
        $CostBelongDeptIds = '';
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('SALEDEPT');
        if($matchConfigItem && is_array($matchConfigItem)){
            $CostBelongDeptIds = isset($matchConfigItem[0]['belongDeptIds'])? $matchConfigItem[0]['belongDeptIds'] : '';
        }
        if($CostBelongDeptIds != ''){
            $CostBelongDeptIds = rtrim($CostBelongDeptIds,",");
            $expensefilterSql .= " AND (CostBelongDeptId in({$CostBelongDeptIds})) ";// ��������
            $costSharefilterSql .= " AND (belongDeptId in({$CostBelongDeptIds})) ";// ��̯����
        }

        // ���������Ա���ˣ���ƴ������
        $conFilterSql = "";
        $feeManFilterSql = "";
        if ($personFilter) {
            $conFilterSql = " AND prinvipalName LIKE '%$personFilter%'";
            $feeManFilterSql = " AND feeMan LIKE '%$personFilter%'";
        }

        //�����������
        $area_sql = "SELECT a.exeDeptName,a.exeDeptCode,re.module,re.moduleName,t.*,'{$year}' AS thisYear, CONCAT(t.id, '|', '{$year}') AS detailId FROM (
            SELECT
                b.id,b.id AS SalesAreaId, b.areaName AS SalesArea, t.totalFee, t.totalContract
            FROM
                oa_system_region b
            LEFT JOIN
                (
                    SELECT
                        d.salesAreaId AS SalesAreaId, d.SalesArea AS SalesArea,
                        sum(d.fee) AS totalFee, sum(d.contract) AS totalContract
                    FROM
                        (
                        SELECT
                            l.salesAreaId, l.salesArea, ROUND(SUM(d.CostMoney * d.days), 2) AS 'fee',0 AS 'contract'
                        FROM
                            cost_detail d
                            LEFT JOIN
                            cost_summary_list l ON d.BillNo = l.BillNo
                        WHERE
                            isNew = 1 AND l.DetailType IN (4, 5) AND l.STATUS = '���' AND l.salesAreaId
                            $expensefilterSql $feeManFilterSql AND (l.ProjectNo = '' OR l.ProjectNo IS NULL)
                            AND DATE_FORMAT(l.PayDT, '%Y') {$year_condition}
                        GROUP BY l.salesAreaId
                        UNION ALL
                        SELECT
                            salesAreaId, salesArea, SUM(costMoney) AS 'fee',0 AS 'contract'
                        FROM
                            oa_finance_cost
                        WHERE
                            detailType IN (4 , 5) AND auditStatus = 1 AND isTemp = 0 AND isDel = 0 $costSharefilterSql
                            AND shareObjType <> 'FTDXLX-05' AND DATE_FORMAT(auditDate, '%Y') {$year_condition} $feeManFilterSql
                        GROUP BY salesAreaId
                        UNION ALL
                        SELECT
                            salesAreaId,salesArea,SUM(costAmount) AS 'fee',0 AS 'contract'
                        FROM
                            oa_sales_costrecord
                        WHERE
                             belongYear {$year_condition}
                        GROUP BY salesAreaId
                        UNION ALL
                        SELECT
                            p.areaCode as 'salesAreaId', p.areaName as 'salesArea', SUM(e.costAct) AS 'fee',0 AS 'contract'
                        FROM
                            oa_present_equ e
                            LEFT JOIN
                            oa_present_present p ON e.presentId = p.id
                        WHERE
                            p.ExaStatus IN ('���' , '���������') AND e.isTemp = 0 AND e.isDel = 0
                            AND DATE_FORMAT(p.ExaDT, '%Y') {$year_condition} $feeManFilterSql
                        GROUP BY p.areaCode
                        UNION ALL
                        select cmt.salesAreaId,cmt.salesArea,cmt.fee,cmt.contract from(
                            SELECT
                                    m.areaCode as 'salesAreaId',r.areaName as 'salesArea', 0 AS 'fee', SUM(contractMoney) AS 'contract',u.USER_NAME as prinvipalName
                            FROM
                                    oa_bi_conproduct_month m
                                    left join user u on u.USER_ID = m.prinvipalId
                                    left join oa_system_region r on r.id = m.areaCode
                            WHERE
                                storeYear {$year_condition}
                            GROUP BY m.areaCode
                        ) cmt where 1=1 $conFilterSql 
                    ) d where d.salesAreaId <> '' group by d.salesAreaId
                ) t
                on b.id = t.salesAreaId
                WHERE b.isStart = '0'
            ) t
            LEFT JOIN
            (
                SELECT salesAreaId,exeDeptCode,exeDeptName FROM oa_system_saleperson WHERE isUse = 0 and exeDeptCode <> '' GROUP BY salesAreaId, exeDeptCode
            ) a ON t.SalesAreaId = a.salesAreaId
            LEFT JOIN oa_system_region re on re.id = a.salesAreaId
            where 1=1 " . $ext_condition;

        $user_sql = "select a.exeDeptName,a.exeDeptCode,re.module,re.moduleName,t.SalesArea, t.feeManId, t.SalesAreaId, t.feeMan, t.totalFee,
                t.totalContract,'{$year}' as thisYear
            from
            (
            select
                d.feeManId, d.SalesAreaId, d.feeMan, d.SalesArea,
                sum(d.fee) as totalFee, sum(d.contract) as totalContract
            from (
                SELECT
                    'userFee' AS 'type', 'fee' AS 'fee_type', l.salesAreaId,
                    l.feeManId, salesArea, feeMan, SUM(d.CostMoney * d.days) AS fee, 0 as contract
                FROM
                    cost_detail d
                LEFT JOIN cost_summary_list l ON d.BillNo = l.BillNo
                WHERE
                    isNew = 1 AND l.DetailType IN (4, 5) AND l.STATUS = '���' AND l.salesAreaId
                    $expensefilterSql $feeManFilterSql
                    AND (l.ProjectNo = '' OR l.ProjectNo IS NULL)
                    AND DATE_FORMAT(l.PayDT, '%Y') {$year_condition}
                GROUP BY l.salesAreaId , l.feeManId UNION ALL
                SELECT
                    'userFee' AS 'type', 'fee' AS 'fee_type', salesAreaId, feeManId, salesArea,
                    feeMan, SUM(costMoney) AS fee, 0 as contract
                FROM
                    oa_finance_cost
                WHERE
                    detailType IN (4 , 5) AND auditStatus = 1 AND isTemp = 0 AND isDel = 0 $costSharefilterSql
                    $feeManFilterSql
                        AND shareObjType <> 'FTDXLX-05' AND DATE_FORMAT(auditDate, '%Y') {$year_condition}
                GROUP BY salesAreaId , feeManId
                UNION ALL
                SELECT
                    'userFee' AS 'type', 'fee' AS 'fee_type', salesAreaId, costManId AS 'feeManId',
                    salesArea, costMan AS 'feeMan', SUM(costAmount) AS 'fee',0 AS 'contract'
                FROM
                    oa_sales_costrecord
                WHERE
                     belongYear {$year_condition}
                GROUP BY salesAreaId,costManId
                UNION ALL
                SELECT
                    'userFee' AS 'type', 'fee' AS 'fee_type', p.areaCode as 'salesAreaId', p.feeManId AS 'feeManId',
                    p.areaName as 'salesArea', p.feeMan AS 'feeMan', SUM(e.costAct) AS fee, 0 as contract
                FROM
                    oa_present_equ e
                LEFT JOIN oa_present_present p ON e.presentId = p.id
                WHERE
                    p.ExaStatus IN ('���' , '���������') AND e.isTemp = 0 AND e.isDel = 0
                    AND DATE_FORMAT(p.ExaDT, '%Y') {$year_condition} $feeManFilterSql
                GROUP BY p.areaCode , p.feeManId
                UNION ALL
                select 'userFee' AS 'type', 'contract' AS 'fee_type',cmt.salesAreaId,cmt.feeManId, cmt.salesArea, cmt.feeMan,cmt.fee,cmt.contract from(
                    SELECT
                        m.areaCode as 'salesAreaId',r.areaName as 'salesArea', 0 AS 'fee', SUM(contractMoney) AS 'contract'
                        ,m.prinvipalId as 'feeManId',u.USER_NAME as 'prinvipalName',u.USER_NAME as 'feeMan'
                    FROM
                        oa_bi_conproduct_month m
                        left join user u on u.USER_ID = m.prinvipalId
                        left join oa_system_region r on r.id = m.areaCode
                    WHERE
                        storeYear {$year_condition}
                    GROUP BY m.areaCode , m.prinvipalId
                ) cmt where 1=1 $conFilterSql 
            ) d
            where
                d.feeManId <> ''
            group by d.feeManId , d.SalesAreaId) t
            LEFT JOIN
            (
                SELECT salesAreaId,exeDeptCode,exeDeptName FROM oa_system_saleperson WHERE isUse = 0 and exeDeptCode <> '' GROUP BY salesAreaId, exeDeptCode
            ) a ON t.SalesAreaId = a.salesAreaId
            LEFT JOIN oa_system_region re on re.id = a.salesAreaId
            where 1=1 " . $ext_condition;
        $sql = ($searchType == 'byMan') ? $user_sql : $area_sql;

        // ���ݴ���ͳ��
        if($isBig == 1){
            $sql = "select T.exeDeptName,T.exeDeptCode,T.module,T.moduleName,SUM(T.totalFee) AS totalFee,SUM(T.totalContract) AS totalContract,T.thisYear,CONCAT(T.exeDeptCode,'|',T.thisYear) AS exeDepFilter from ({$sql})T where T.exeDeptCode <> '' {$bigExtraSql}";

            // ������������ű����ڻ���,������ĵ���ʹ��
            $_SESSION['exsummary_mainSql'] = "";
            $sqlObj = explode("limit",$sql);
            $_SESSION['exsummary_mainSql'] = $sqlObj[0];
        }

        $result_arr = $this->_db->getArray($sql);

//        echo $sql;die();
//        echo"<pre>";print_r($result_arr);exit();
        return $result_arr;
    }

    /**
     * ���ݱ�����ID,��ȡ�䵥����Դ
     * @param $id
     * @return string
     */
    function getSourceType($id){
        $sourceType = '';
        $sql = "select l.id,l.allregisterId,w.isImptSubsidy from cost_summary_list l left join wf_task w on (w.code = 'cost_summary_list' and w.Pid = l.id) where l.id = '{$id}';";
        $data = $this->_db->get_one($sql);
        if($data){
            if($data['isImptSubsidy'] == '��'){
                $sourceType = '����';
            }else if($data['allregisterId'] > 0){
                $sourceType = '�⳵';
            }else{
                $sourceType = '���˴���';
            }
        }
        return $sourceType;
    }

    /**
     * ��ȡ��ʱ���տ��˻�����Ϣ
     * @param $BillNo
     * @return mixed
     */
    function getBillBaseInfo($BillNo){
        $sql="select
                l.billno, u.user_name,l.payee, h.account, h.acccard, h.usercard, l.isNew
            from cost_summary_list l
                left join user u on (l.costman=u.user_id)
                left join hrms h on (h.user_id=u.user_id)
                left join department d on (d.dept_id = u.dept_id )
                left join xm x on (x.projectno=l.projectno)
                left join cost_detail_assistant a on (l.billno=a.billno)
            where l.billno='".$BillNo."'
                group by l.billno ";
        $billrow=$this->_db->get_one($sql);

        if(empty($billrow['payee'])){
            $billrow['payeeIsEmpty'] = '1';
            $billrow['payeeName'] = $billrow['user_name'];
            $billrow['payee'] = $billrow['user_name'].'('.$billrow['usercard'].')';
        }else{
            $billrow['payeeName'] = $billrow['payee'];
        }

        // �⳵�Ǽǻ������ɵı�����������˾�����ͣ���ʱ����Ҫ���⴦��
        $rentalCarPayInfo = $this->_db->get_one("SELECT
                pt.payType,pt.payTypeCode,pt.bankName,pt.bankAccount,pt.bankReceiver
            FROM
                cost_summary_list l
            LEFT JOIN oa_contract_rentcar_expensetmp et ON l.id = et.expenseId
            LEFT JOIN oa_contract_rentcar_payinfos pt ON et.payInfoId = pt.id
            WHERE
	          l.billNo = '{$BillNo}';");
        if($rentalCarPayInfo && $rentalCarPayInfo['payTypeCode'] == "BXFSJ"){
            $billrow['account'] = $rentalCarPayInfo['bankAccount'];
            $billrow['acccard'] = $rentalCarPayInfo['bankName'];
            $billrow['payee'] = $rentalCarPayInfo['bankReceiver'];
            $billrow['payeeName'] = $rentalCarPayInfo['bankReceiver'];
            $billrow['payeeIsEmpty'] = '0';
        }

        // ��ȡ�޸ļ�¼, ������滻����һ���޸ĵ�ֵ
        $chkSql = "select * from oa_billcheck_change_records where billNo = '{$BillNo}';";
        $billrowChangeRcd=$this->_db->get_one($chkSql);
        if($billrowChangeRcd){
            $billrow['payee'] = empty($billrowChangeRcd['newPayeeVal'])? $billrow['payee'] : $billrowChangeRcd['newPayeeVal'];// �˻�����
            $billrow['payeeIsEmpty'] = empty($billrowChangeRcd['newPayeeVal'])? '1' : '0';
            $billrow['account'] = empty($billrowChangeRcd['newAccountVal'])? $billrow['account'] : $billrowChangeRcd['newAccountVal'];// �˻�
            $billrow['acccard'] = empty($billrowChangeRcd['newAccountCardVal'])? $billrow['acccard'] : $billrowChangeRcd['newAccountCardVal'];// �˻�����(����)
            $billrow['payeeName'] = $billrow['payee'];
        }

        return $billrow;
    }
}