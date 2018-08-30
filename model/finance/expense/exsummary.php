<?php

/**
 * @author Show
 * @Date 2012年10月11日 星期四 10:01:33
 * @version 1.0
 * @description:报销汇总主表 Model层
 */
class model_finance_expense_exsummary extends model_base
{

    function __construct()
    {
        $this->tbl_name = "cost_summary_list";
        $this->sql_map = "finance/expense/exsummarySql.php";
        parent:: __construct();
    }

    /******************* 配置部分 ******************/

    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    // 配置报销类型
    private $detailTypeArr = array(
        '1' => '部门费用',
        '2' => '合同项目费用',
        '3' => '研发费用',
        '4' => '售前费用',
        '5' => '售后费用'
    );

    // 返回费用类型
    function rtDetailType($thisVal)
    {
        if (isset($this->detailTypeArr[$thisVal])) {
            return $this->detailTypeArr[$thisVal];
        } else {
            return $thisVal;
        }
    }

    /********************* 增删改查 ******************/

    /**
     * @param $object
     * @return bool
     * @throws Exception
     */
    function add_d($object)
    {
        try {
            //其余信息处理
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
            $object['Status'] = '编辑';
            $object['ExaStatus'] = '编辑';

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
        //获取当前记录信息
        $obj = $this->find(array('BillNo' => $object['BillNo']), null, 'ID');

        try {
            //其余信息处理
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
            $object['Status'] = '编辑';
            $object['ExaStatus'] = '编辑';

            $this->update(array('BillNo' => $object['BillNo']), $object);

            return $obj['ID'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取汇总数据方法
     * @param $id
     * @return bool|mixed
     */
    function getInfo_d($id)
    {
        //获取本单位信息
        $obj = $this->get_d($id);

        //费用明细类实例化
        $expensedetailDao = new model_finance_expense_expensedetail();
        //费用明细
        $obj['expensedetail'] = $expensedetailDao->getBillDetail_d($obj['BillNo']);
        $obj['expensedetail'] = $expensedetailDao->initBillDetailView_d($obj['expensedetail']);

        //分摊明细类实例化
        $expensecostshareDao = new model_finance_expense_expensecostshare();
        //分摊明细
        $obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);
        $obj['expensecostshare'] = $expensecostshareDao->initBillDetailView_d($obj['expensecostshare']);

        //发票明细
        $expenseinvDao = new model_finance_expense_expenseinv();
        $obj['expenseinv'] = $expenseinvDao->getInvDetail_d($obj['BillNo']);
        $obj['expenseinv'] = $expenseinvDao->initInvDetailView_d($obj['expenseinv']);

        //发票单
        $exbillDao = new model_finance_expense_exbill();
        $exbillObj = $exbillDao->find(array('ConBillNo' => $obj['BillNo']), null, 'BillNo');
        $obj['BillListNo'] = $exbillObj['BillNo'];

        return $obj;
    }

    /**
     * 获取汇总数据方法
     * @param $id
     * @return bool|mixed
     */
    function getInfoEdit_d($id)
    {
        $condition = array('id' => $id);

        //获取本单位信息
        $obj = $this->find($condition);
        if ($obj) {
            //获取申请信息
            $expenseDao = new model_finance_expense_expense();
            $expenseObjs = $expenseDao->findAll(array('BillNo' => $obj['BillNo']));
            //业务信息附加
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

            //费用明细类实例化
            $expensedetailDao = new model_finance_expense_expensedetail();
            //费用明细
            $obj['expensedetail'] = $expensedetailDao->getBillDetail_d($obj['BillNo']);
            $obj['expensedetail'] = $expensedetailDao->initBillDetailViewEdit_d($obj['expensedetail']);

            //分摊明细类实例化
            $expensecostshareDao = new model_finance_expense_expensecostshare();
            //分摊明细
            $obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);
            $obj['expensecostshare'] = $expensecostshareDao->initBillDetailViewEdit_d($obj['expensecostshare']);

            //发票明细
            $expenseinvDao = new model_finance_expense_expenseinv();
            $invoiceNumber = 0;
            $obj['expenseinv'] = $expenseinvDao->getInvDetail_d($obj['BillNo']);
            foreach ($obj['expenseinv'] as $inv){// 重新统计发票数量
                $invoiceNumber += $inv['invoiceNumber'];
            }
            $obj['expenseinv'] = $expenseinvDao->initInvDetailViewEdit_d($obj['expenseinv']);
            $obj['invoiceNumber'] = $invoiceNumber;

            //发票单
            $exbillDao = new model_finance_expense_exbill();
            $exbillObj = $exbillDao->find(array('ConBillNo' => $obj['BillNo']), null, 'BillNo');
            $obj['BillListNo'] = $exbillObj['BillNo'];
            return $obj;
        }
    }

    /**
     * 根据单据编号删除汇总表名称
     * @param $BillNo
     * @return bool
     */
    function delByBillNo_d($BillNo)
    {
        try {
            $this->start_d();
            //删除汇总表
            $this->delete(array('BillNo' => $BillNo));

            //更新汇总表信息
            $expenseDao = new model_finance_expense_expense();
            $expenseDao->clearBillNoInfo_d($BillNo);

            //更新申请明细
            $expenseassDao = new model_finance_expense_expenseass();
            $expenseassDao->clearBillNoInfo_d($BillNo);

            //更新发票明细
            $expenseinvDao = new model_finance_expense_expenseinv();
            $expenseinvDao->clearBillNoInfo_d($BillNo);

            //更新发票明细
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
     * 审批完成后处理方法
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
        // 审批时需要更新单据的部分
        $updateInfo = array();
        switch ($object['ExaStatus']) {
            case AUDITED : // 审批完成
                //获取报销单信息
                $expenseDao = new model_finance_expense_expense();
                $expenseObj = $expenseDao->getByBillNo_d($object['BillNo']);

                //如果是下推单据，则进行项目信息更新
                if ($expenseObj['isPush'] == 1 && $expenseObj['projectId']) {

                    // 如果类型是1，则调用租车部分的接口
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

                        // 将费用更新成已报销费用
                        if ($esmcostdetailStr) {
                            $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
                            $esmcostdetailDao->updateCost_d($esmcostdetailStr, '4');

                            $esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
                            $esmcostdetailInvDao->updateCostInvoice_d($esmcostdetailStr, '4');

                            //计算人员的项目费用
                            if ($expenseObj['projectId']) {
                                //获取当前项目的费用
                                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($expenseObj['projectId'], $expenseObj['CostMan']);

                                //更新人员费用信息
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
            case AUDITING : // 审批中
                //未完成时,判断是否到达财务审批
                $stepInfo = $otherdatas->getAuditngStepInfo($folowInfo['task']);
                if ($stepInfo['isReceive'] == '1') {
                    $updateInfo['isFinAudit'] = 1;
                }
                $updateInfo['isEffected'] = 1;
                break;
            case BACK;
                $updateInfo['isFinAudit'] = 0;
                $updateInfo['isEffected'] = 0;
                // 审核未通过，减去预算表中的决算费用
                $budgetDao = new model_finance_budget_budget();
                $budgetDao->subFinal($objId);
                break;
        }
        // 更新单据信息
        if (!empty($updateInfo)) $this->update(array('id' => $objId), $updateInfo);

        return true;
    }

    /**
     * 获取统计值
     * @param $userId 个人
     * @param $areaId 销售区域
     * @return array
     */
    function getStatistic($userId, $areaId)
    {
        //当前年份
        $year = date("Y");
        //定义费用数组
        $feeArr = array();
        //报销单，不包含关联pk的费用
        $sql = "SELECT
					'userFee' AS 'type',
					l.salesAreaId,
					SUM(d.CostMoney*d.days) AS fee
				FROM
					cost_detail d
				LEFT JOIN cost_summary_list l ON d.BillNo = l.BillNo
				WHERE
					l.DetailType IN (4, 5)
				AND l.`Status` = '完成'
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
				AND l.`Status` = '完成'
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
        //费用分摊，不包含关联pk的费用
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
				AND costTypeName <> '投标保证金'
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
				AND costTypeName <> '投标保证金'
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
        //赠送
        $sql = "SELECT
					'userFee' AS 'type',
					p.areaCode,
					SUM(e.costAct) AS fee
				FROM
					oa_present_equ e
				LEFT JOIN oa_present_present p ON e.presentId = p.id
				WHERE
					p.ExaStatus IN ('完成','变更审批中')
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
					p.ExaStatus IN ('完成','变更审批中')
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
        //销售合同
//		$sql = "SELECT
//					'userFee' AS 'type',
//					areaCode,
//					SUM(contractMoney) AS contractMoney
//				FROM
//					oa_contract_contract
//				WHERE
//					ExaStatus = '完成'
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
//					ExaStatus = '完成'
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
					ExaStatus IN ('完成','变更审批中')
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
					ExaStatus IN ('完成','变更审批中')
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
     * 获取统计值(个人，区域单独获取)
     * @param $searchType   搜索类型(个人/区域)
     * @param $ext_condition 附加过滤条件
     * @param $year_val
     * @param $personFilter 人员过滤附加
     * @return array
     */
    function getStatisticSpl($searchType, $ext_condition, $year_val = '', $personFilter = '',$isBig = 0, $bigExtraSql = "")
    {
        //当前年份
        $year = ($year_val == '') ? '' : $year_val;
        $year_condition = ($year_val == '') ? " > 0" : " = '{$year}'";

        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // 过滤不适用的费用项
        $expensefilterSql = $filterType ?
            " AND CostTypeId IN(SELECT CostTypeID FROM cost_type WHERE CostTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . "))" : "";
        $costSharefilterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // 获取配置项的销售部门信息
        $CostBelongDeptIds = '';
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('SALEDEPT');
        if($matchConfigItem && is_array($matchConfigItem)){
            $CostBelongDeptIds = isset($matchConfigItem[0]['belongDeptIds'])? $matchConfigItem[0]['belongDeptIds'] : '';
        }
        if($CostBelongDeptIds != ''){
            $CostBelongDeptIds = rtrim($CostBelongDeptIds,",");
            $expensefilterSql .= " AND (CostBelongDeptId in({$CostBelongDeptIds})) ";// 报销过滤
            $costSharefilterSql .= " AND (belongDeptId in({$CostBelongDeptIds})) ";// 分摊过滤
        }

        // 如果存在人员过滤，则拼接条件
        $conFilterSql = "";
        $feeManFilterSql = "";
        if ($personFilter) {
            $conFilterSql = " AND prinvipalName LIKE '%$personFilter%'";
            $feeManFilterSql = " AND feeMan LIKE '%$personFilter%'";
        }

        //定义费用数组
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
                            isNew = 1 AND l.DetailType IN (4, 5) AND l.STATUS = '完成' AND l.salesAreaId
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
                            p.ExaStatus IN ('完成' , '变更审批中') AND e.isTemp = 0 AND e.isDel = 0
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
                    isNew = 1 AND l.DetailType IN (4, 5) AND l.STATUS = '完成' AND l.salesAreaId
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
                    p.ExaStatus IN ('完成' , '变更审批中') AND e.isTemp = 0 AND e.isDel = 0
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

        // 根据大区统计
        if($isBig == 1){
            $sql = "select T.exeDeptName,T.exeDeptCode,T.module,T.moduleName,SUM(T.totalFee) AS totalFee,SUM(T.totalContract) AS totalContract,T.thisYear,CONCAT(T.exeDeptCode,'|',T.thisYear) AS exeDepFilter from ({$sql})T where T.exeDeptCode <> '' {$bigExtraSql}";

            // 把主表的搜索脚本存于缓存,给后面的导出使用
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
     * 根据报销单ID,获取其单据来源
     * @param $id
     * @return string
     */
    function getSourceType($id){
        $sourceType = '';
        $sql = "select l.id,l.allregisterId,w.isImptSubsidy from cost_summary_list l left join wf_task w on (w.code = 'cost_summary_list' and w.Pid = l.id) where l.id = '{$id}';";
        $data = $this->_db->get_one($sql);
        if($data){
            if($data['isImptSubsidy'] == '是'){
                $sourceType = '补贴';
            }else if($data['allregisterId'] > 0){
                $sourceType = '租车';
            }else{
                $sourceType = '个人创建';
            }
        }
        return $sourceType;
    }

    /**
     * 获取打单时的收款人基本信息
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

        // 租车登记汇总生成的报销单（报销司机类型）打单时候需要特殊处理
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

        // 读取修改记录, 如果有替换最新一次修改的值
        $chkSql = "select * from oa_billcheck_change_records where billNo = '{$BillNo}';";
        $billrowChangeRcd=$this->_db->get_one($chkSql);
        if($billrowChangeRcd){
            $billrow['payee'] = empty($billrowChangeRcd['newPayeeVal'])? $billrow['payee'] : $billrowChangeRcd['newPayeeVal'];// 账户姓名
            $billrow['payeeIsEmpty'] = empty($billrowChangeRcd['newPayeeVal'])? '1' : '0';
            $billrow['account'] = empty($billrowChangeRcd['newAccountVal'])? $billrow['account'] : $billrowChangeRcd['newAccountVal'];// 账户
            $billrow['acccard'] = empty($billrowChangeRcd['newAccountCardVal'])? $billrow['acccard'] : $billrowChangeRcd['newAccountCardVal'];// 账户卡号(银行)
            $billrow['payeeName'] = $billrow['payee'];
        }

        return $billrow;
    }
}