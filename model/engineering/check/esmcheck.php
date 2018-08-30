<?php

/**
 * Created by PhpStorm.
 * User: Kuangzw
 * Date: 2017/8/31
 * Time: 14:43
 */
class model_engineering_check_esmcheck extends model_base
{

    function __construct()
    {
        parent::__construct();
    }

    // 检查项 - 扩展时在这里增加数据即可
    public $items = array(
        array('category' => '服务项目', 'item' => '预留营收', 'id' => 'serverReserveEarnings'),
        array('category' => '服务项目', 'item' => '营收', 'id' => 'serverCurIncome'),
        array('category' => '服务项目', 'item' => '总成本与分成本对比', 'id' => 'serverFeeAll'),
        array('category' => '服务项目', 'item' => '数据版本校验', 'id' => 'serverVersion'),
        array('category' => '服务项目', 'item' => '版块产线区域', 'id' => 'serverProLine'),
        array('category' => '服务项目', 'item' => '扣款校验', 'id' => 'serverDeductMoney'),
        array('category' => '服务项目', 'item' => 'PK占比校验', 'id' => 'pkEstimatesRateChk'),

        array('category' => '产品项目', 'item' => '预留营收', 'id' => 'productReserveEarnings'),
        array('category' => '产品项目', 'item' => '营收', 'id' => 'productCurIncome'),
        array('category' => '产品项目', 'item' => '总成本与分成本对比', 'id' => 'productFeeAll'),
        array('category' => '产品项目', 'item' => '数据版本校验', 'id' => 'productVersion'),
        array('category' => '产品项目', 'item' => '收入方式校验', 'id' => 'productIncomeType'),
        array('category' => '产品项目', 'item' => '版块产线区域', 'id' => 'productProLine'),
        array('category' => '产品项目', 'item' => '扣款校验', 'id' => 'productDeductMoney'),

        array('category' => '其他校验', 'item' => '自动更新', 'id' => 'otherAutoUpdate'),
        array('category' => '其他校验', 'item' => '产品金额校验', 'id' => 'otherProductMoney'),
        array('category' => '其他校验', 'item' => '产品区域及产线校验', 'id' => 'otherProductArea'),
        array('category' => '其他校验', 'item' => '概算校验', 'id' => 'otherEstimates'),

        array('category' => '合同检查', 'item' => '产品金额检查', 'id' => 'conProductCheck'),
        array('category' => '合同检查', 'item' => '项目金额检查', 'id' => 'conProjectCheck'),
        array('category' => '合同检查', 'item' => '预计开票检查', 'id' => 'conInvoiceCheck'),
        array('category' => '合同检查', 'item' => '实际开票检查', 'id' => 'conInvoiceTrueCheck')
    );

    /**
     * 将前端传入状态转义成脚本(服务项目)
     * @param $status
     * @return string
     */
    function esmStatus2Sql($status)
    {
        return $status == "all" ? "" : " AND c.status <> 'GCXMZT03' ";
    }

    /**
     * 将前端传入状态转义成脚本(产品项目)
     * @param $status
     * @return string
     */
    function conStatus2Sql($status)
    {
        return $status == "all" ? "" : " AND (c.proStatus <> '关闭') ";
    }

    /**
     * 默认结果返回
     * @param $id
     * @return array
     */
    function getDefaultReturn($id)
    {
        return array(
            "id" => $id,
            "checkNum" => 0,
            "correctNum" => 0,
            "errorNum" => 0,
            "errorProjectIds" => ''
        );
    }
    /**************************** 服务项目类检查 ***********************************/

    /**
     * 服务项目预留营收
     * @param $id
     * @param $status
     * @return array
     */
    function serverReserveEarnings($id, $status)
    {
        // 状态转义
        $statusSql = $this->esmStatus2Sql($status);
        // 返回结果生成
        $rst = $this->getDefaultReturn($id);

        $sql = "SELECT * FROM oa_esm_project c WHERE 1 " . $statusSql;
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            // 记录数取值
            $errorProjectIdArr = array();
            foreach ($data as $v) {
                // TODO 目前匹配的状态是合同状态，需要确认
                // 去除扣款、不开票金额后得合同金额
                $v['contractMoneyDeduct'] = bcsub($v['contractMoney'],
                    bcadd($v['contractDeduct'], $v['uninvoiceMoney'], 9), 9); // 合同金额（已经减去扣款同不开票金额）

                // 开票进度
                $v['invoiceProcess'] = $v['contractMoneyDeduct'] > 0 ?
                    bcmul(bcdiv($v['invoiceMoney'], $v['contractMoneyDeduct'], 9), 100, 9) :
                    0;

                // 开始预留营收计算
                if ($v['projectProcess'] <= 98 || $v['invoiceProcess'] >= 100) {
                    $reserveRate = 0;
                } else {
                    $reserveRate = $v['invoiceProcess'] < 98 ?
                        0.02 : bcsub(1, bcdiv(min($v['projectProcess'], $v['invoiceProcess']), 100, 9), 9);
                }

                $contractRate = $v['contractRate'] > 0 ? bcdiv($v['contractRate'], 100, 2) : 0;
                $reserveEarnings = $reserveRate > 0 ?
                    round(
                        bcmul(bcsub($v['projectMoney'], bcdiv($v['deductMoney'], bcadd(1, $contractRate, 9), 9), 9), $reserveRate, 9),
                        2)
                    : 0;

                $reserveEarnings = round($reserveEarnings, 2);

                // PMS 643 按到款以及开票确认收入的，目前系统有计算预留营收，需设置为0
                if($v['incomeType'] == 'SRQRFS-02' || $v['incomeType'] == 'SRQRFS-03'){
                    $reserveEarnings = 0;
                    $v['reserveEarnings'] = 0;
                }
                if ($reserveEarnings == $v['reserveEarnings'] ||
                    (bcsub($reserveEarnings,$v['reserveEarnings'],3) <= 0.01 && bcsub($reserveEarnings,$v['reserveEarnings'],3) >= -0.01)) {
                    $rst['correctNum'] += 1;
                } else {
                    $rst['errorNum'] += 1;
                    $errorProjectIdArr[] = $v['id'];
                }
            }
            $rst['checkNum'] = count($data);
            $rst['errorProjectIds'] = implode(',', $errorProjectIdArr);
        }

        // 返回结果
        return $rst;
    }

    function serverCurIncome($id, $status)
    {
        // 状态转义
        $statusSql = $this->esmStatus2Sql($status);
        // 返回结果生成
        $rst = $this->getDefaultReturn($id);

        $sql = "SELECT * FROM oa_esm_project c WHERE 1 " . $statusSql;
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            // 记录数取值
            $errorProjectIdArr = array();
            foreach ($data as $v) {

                // 项目收入处理 - 如果项目状态是异常关闭，那么项目营收为0
                if ($v['contractStatus'] == '7') {
                    $curIncome = 0;
                } else {
                    $contractRate = $v['contractRate'] > 0 ? bcdiv($v['contractRate'], 100, 4) : 0;
                    // 项目营收
                    if ($v['incomeType'] == 'SRQRFS-02') {
                        // 去除扣款、不开票金额后得合同金额
                        $v['contractMoneyDeduct'] = bcsub($v['contractMoney'],
                            bcadd($v['contractDeduct'], $v['uninvoiceMoney'], 9), 9); // 合同金额（已经减去扣款同不开票金额）

                        // 开票进度
                        $v['invoiceProcess'] = $v['contractMoneyDeduct'] > 0 ?
                            bcmul(bcdiv($v['invoiceMoney'], $v['contractMoneyDeduct'], 9), 100, 9) :
                            0;
                        // 按开票确认 = 开篇金额 * 合同额
                        $curIncome = bcmul($v['projectMoney'], bcdiv($v['invoiceProcess'], 100, 9), 9);
                        $curIncome = bcsub($curIncome, round(bcdiv($v['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    } else {
                        // 按进度确认 = 税后合同额 * 项目进度 - 项目扣款/（1 + 税点） - 营收预留
                        $curIncome = bcmul($v['projectMoney'], bcdiv($v['projectProcess'], 100, 9), 9);
                        $curIncome = bcsub($curIncome, round(bcdiv($v['deductMoney'], 1 + $contractRate, 9), 9), 9);
                        $curIncome = bcsub($curIncome, $v['reserveEarnings'], 9);
                    }
                }
                $curIncome = round($curIncome, 2);

                if ($curIncome == $v['curIncome'] || (bcsub($curIncome,$v['curIncome'],3) <= 0.1 && bcsub($curIncome,$v['curIncome'],3) >= -0.1)) {
                    $rst['correctNum'] += 1;
                } else {
                    $rst['errorNum'] += 1;
                    $errorProjectIdArr[] = $v['id'];
                }
            }
            $rst['checkNum'] = count($data);
            $rst['errorProjectIds'] = implode(',', $errorProjectIdArr);
        }

        // 返回结果
        return $rst;
    }

    /**
     * @param $id
     * @param $status
     * @return array
     */
    function serverFeeAll($id, $status)
    {
        // 状态转义
        $statusSql = $this->esmStatus2Sql($status);
        // 返回结果生成
        $rst = $this->getDefaultReturn($id);

        // 检测脚本
        $sql = "
            SELECT
                if(
                    round(
                        feeAll - round(
                            (
                                feePerson + feeSubsidy + feeSubsidyImport + feeField + feePayables + feeFieldImport + feeCar + feeFlights + feeEqu + feeEquDepr + feeEquImport + feeOutsourcing + feeOther + feePK
                            ),
                            3
                        )
                    ) > 0.03, 0 ,1
                )AS isEqual1,
                if(
                    round(
                        feeAll - round(
                            (
                                feePerson + feeSubsidy + feeSubsidyImport + feeField + feePayables + feeFieldImport + feeCar + feeFlights + feeEqu + feeEquDepr + feeEquImport + feeOutsourcing + feeOther + feePK
                            ),
                            3
                        )
                    ) < -0.03, 0 ,1
                )AS isEqual2,
                id
            FROM
                oa_esm_project c
            WHERE
                1 " . $statusSql;
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            // 记录数取值
            $errorProjectIdArr = array();
            foreach ($data as $v) {
                if ($v['isEqual1'] || $v['isEqual2']) {
                    $rst['correctNum'] += 1;
                } else {
                    $rst['errorNum'] += 1;
                    $errorProjectIdArr[] = $v['id'];
                }
            }
            $rst['checkNum'] = count($data);
            $rst['errorProjectIds'] = implode(',', $errorProjectIdArr);
        }

        // 返回结果
        return $rst;
    }

    function serverVersion($id, $status)
    {
        // 状态转义
        $statusSql = $this->esmStatus2Sql($status);
        // 返回结果生成
        $rst = $this->getDefaultReturn($id);

        // 获取最后一个版本数据
        $sql = "SELECT thisYear,thisMonth FROM oa_esm_records_fielddetail ORDER BY id DESC LIMIT 1";
        $lastVersionInfo = $this->_db->get_one($sql);

        if (!empty($lastVersionInfo)) {
            // 检验脚本
            $sql = "SELECT c.id,c.projectCode,c.feeAll,f.fee,f.projectId
                FROM
                    oa_esm_project c
                    LEFT JOIN
                    (
                        SELECT SUM(fee) AS fee,projectId FROM oa_esm_records_fielddetail
                        WHERE thisYear = " . $lastVersionInfo['thisYear'] . "
                            AND thisMonth = " . $lastVersionInfo['thisMonth'] . " GROUP BY projectId
                    ) f ON c.id = f.projectId
                WHERE 1 " . $statusSql;
            $data = $this->_db->getArray($sql);

            if (!empty($data)) {
                // 记录数取值
                $errorProjectIdArr = array();
                foreach ($data as $v) {
                    if (($v['feeAll'] - $v['fee'] < 0.03 && $v['feeAll'] - $v['fee'] > -0.03) || $v['projectId'] == NULL) {
                        $rst['correctNum'] += 1;
                    } else {
                        $rst['errorNum'] += 1;
                        $errorProjectIdArr[] = $v['id'];
                    }
                }
                $rst['checkNum'] = count($data);
                $rst['errorProjectIds'] = implode(',', $errorProjectIdArr);
            }
        }

        // 返回结果
        return $rst;
    }

    /**
     * 服务项目-版块产线区域
     * @param $id
     * @param $status
     * @return array
     */
    function serverProLine($id, $status)
    {
        // 状态转义
        $statusSql = $this->esmStatus2Sql($status);
        // 返回结果生成
        $rst = $this->getDefaultReturn($id);
        // 需要检验的字段
        $checkField = "module,moduleName,newProLine,newProLineName,productLine,productLineName,areaCode,areaName";

        // 检测脚本
        $sql = "SELECT id,$checkField FROM oa_esm_project c WHERE 1 " . $statusSql;
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            $checkFieldArr = explode(',', $checkField);
            $errorProjectIdArr = array();
            foreach ($data as $v) {
                $empty = false;
                foreach ($checkFieldArr as $vi) {
                    if (!$v[$vi]) {
                        $empty = true;
                        break;
                    }
                }
                if ($empty) {
                    $rst['errorNum'] += 1;
                    $errorProjectIdArr[] = $v['id'];
                } else {
                    $rst['correctNum'] += 1;
                }
            }
            $rst['checkNum'] = count($data);
            $rst['errorProjectIds'] = implode(',', $errorProjectIdArr);
        }

        // 返回结果
        return $rst;
    }

    /**
     * 服务项目-扣款校验
     * @param $id
     * @param $status
     * @return array
     */
    function serverDeductMoney($id, $status){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->esmStatus2Sql($status);
        $errorProjectIds = '';
        $checkNum = $correctNum = $errorNum = 0;

        $totalSql = "SELECT
                sum(t.num) as totalNum
            FROM (SELECT c.contractId,sum(c.deductMoney) AS totalDeduct,GROUP_CONCAT(c.id) as proIds,COUNT(c.id) as num FROM oa_esm_project c where 1=1 {$status} GROUP BY c.contractId) t
            LEFT JOIN oa_contract_contract ct ON t.contractId = ct.id
            WHERE ct.id IS NOT NULL";
        $chkResult = $this->_db->getArray($totalSql);
        if(is_array($chkResult) && isset($chkResult[0]['totalNum'])){
            $checkNum = $chkResult[0]['totalNum'];
        }

        $errorSql = "select tt.proIds,tt.num from(
            SELECT t.*,ct.deductMoney,(t.totalDeduct - ct.deductMoney) as diff
            FROM (SELECT c.contractId,sum(c.deductMoney) AS totalDeduct,GROUP_CONCAT(c.id) as proIds,COUNT(c.id) as num FROM oa_esm_project c where 1=1 and c.contractType <> 'GCXMYD-04' {$status} GROUP BY c.contractId) t
            LEFT JOIN oa_contract_contract ct ON t.contractId = ct.id
            LEFT JOIN (select contractId,sum(deductMoney) as conProjDeduct from oa_contract_project group by contractId) conp on conp.contractId = t.contractId
            WHERE ct.id IS NOT NULL and (
             round((t.totalDeduct - (ct.deductMoney - conp.conProjDeduct)),3)> 0.01 
             or round((t.totalDeduct - (ct.deductMoney - conp.conProjDeduct)),3) < -0.01)
             )tt";
        $chkErrorResult = $this->_db->getArray($errorSql);
        if(is_array($chkErrorResult)){
            foreach ($chkErrorResult as $k => $v){
                $errorNum += $v['num'];
                $errorProjectIds .= ($k == 0)? $v['proIds'] : ",".$v['proIds'];
            }
            $errorProjectIds = rtrim($errorProjectIds,",");

            if($checkNum > 0){
                $correctNum = bcsub($checkNum,$errorNum,0);
            }else{
                $correctNum = $checkNum;
            }

            if($checkNum <= 0){
                $errorProjectIds = '';
            }

            if($errorNum > 450){
                $_SESSION['deductMoneyEsmErrIds'] = $errorProjectIds;
                $errorProjectIds = "deductMoneyEsmErrIds";
            }

            // 返回结果
            return array(
                "id" => $id,
                "type" => "default",
                "checkNum" => $checkNum,
                "correctNum" => $correctNum,
                "errorNum" => $errorNum,
                "errorProjectIds" => $errorProjectIds
            );
        }else{
            // 返回结果
            return array(
                "id" => $id,
                "type" => "default",
                "checkNum" => $checkNum,
                "correctNum" => $checkNum,
                "errorNum" => 0,
                "errorProjectIds" => ''
            );
        }
    }

    /**
     * 服务项目-扣款校验
     * @param $id
     * @param $status
     * @return array
     */
    function pkEstimatesRateChk($id, $status){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->esmStatus2Sql($status);
        $errorProjectIds = '';
        $checkNum = $correctNum = $errorNum = 0;

        $sql = "SELECT count(id) as num FROM oa_esm_project c WHERE 1 " . $status;
        $data = $this->_db->get_one($sql);
        $checkNum = isset($data['num'])? $data['num'] : 0;

        $sql = "select * from (select group_concat(c.id) as ids,c.projectCode,c.contractId,c.contractType,sum(c.pkEstimatesRate) as totalPKEstimatesRate from oa_esm_project c where c.contractType = 'GCXMYD-01' {$status} group by c.contractId)t where t.totalPKEstimatesRate > 100;";
        $errorProjectIdsArr = array();
        $data = $this->_db->getArray($sql);

        foreach ($data as $k => $v){
            $idArr = explode(",",$v['ids']);
            $errorProjectIdsArr = array_merge($idArr,$errorProjectIdsArr);
        }
        $errorNum = count($errorProjectIdsArr);
        $errorProjectIds = implode(",",$errorProjectIdsArr);

        $correctNum = bcsub($checkNum,$errorNum);

        // 如果异常ID数量大于450时,用缓存记录ID字符串
        if($errorNum > 10){
            $_SESSION['pkEstimatesRateChkErrIds'] = $errorProjectIds;
            $errorProjectIds = "pkEstimatesRateChkErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "103",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**************************** 产品项目类检查 ***********************************/

    /**
     * @param $proArr
     * @return float
     */
    function getConReserveEarning($proArr)
    {
        $moneyCount = bcsub(bcsub($proArr['contractMoney'], $proArr['cdeductMoney'], 2), $proArr['uninvoiceMoney'], 2);
        $invoicePerc = ($moneyCount <= 0) ? 1 : round(bcdiv($proArr['invoiceMoney'], $moneyCount, 9), 10);
        $invoicePerc = $invoicePerc * 100;// 开票进度

        if ($proArr['proschedule'] <= 98) {// 进度<=98%时，预留营收为0
            $point = 0;
        } else {// 进度 > 98%时
            if ($invoicePerc >= 100) {// 开票进度 >= 1，预留营收为0%
                $point = 0;
            } else if ($invoicePerc < 98) {// 开票进度<98%，预留营收为2%
                $point = 0.02;
            } else {
                $point = 1 - (min($invoicePerc, $proArr['proschedule']) / 100);
            }
        }

        $reserveEarning = round(($proArr['rateMoney'] - $proArr['deductMoney'] / (1 + $proArr['txaRate'])) * $point, 2);// (税后合同额-扣款/(1+税点))*point
        return $reserveEarning;
    }

    /**
     * 预留营收
     * @param $id
     * @param $status
     * @return array
     */
    function productReserveEarnings($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "select c.*,if(ct.deductMoney is null,0,ct.deductMoney) as cdeductMoney from oa_contract_project c left join oa_contract_contract ct on c.contractId = ct.id where 1=1 and (esmProjectId is null or esmProjectId = '') {$status}";
        $projects = $this->_db->getArray($sql);

        $errorProjectIds = "";
        $checkNum = $correctNum = $errorNum = 0;
        if ($projects && is_array($projects)) {
            foreach ($projects as $k => $pro) {
                $reserveEarning = ($pro['earningsTypeCode'] == "HSFS-FPJD")? 0 : $this->getConReserveEarning($pro);// 获取当前预留营收 (当收入类型是“按发票进度”时,当前预留营收为0)
                $chkDiff = round(bcsub($pro['reserveEarnings'], $reserveEarning, 0), 0);
                $checkNum += 1;
                if ($chkDiff > 0.01 || $chkDiff < -0.01) {
                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                    $errorNum += 1;
                } else {
                    $correctNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 营收=项目进度*税后合同额-扣款/(1+税点)-预留营收
     * @param $id
     * @param $status
     * @return array
     */
    function productCurIncome($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "select c.id,c.proschedule,c.contractstatus,c.proMoney,c.point,c.deductMoney,c.txaRate,c.reserveEarnings,c.earnings from oa_contract_project c where 1=1 and (c.esmProjectId is null or c.esmProjectId = '') {$status}";
        $projects = $this->_db->getArray($sql);
        $checkErrorSql = "
          SELECT * FROM (
                SELECT
                    c.id,
                    c.contractstatus,
                    c.proschedule,
                    c.proMoney,
                    c.point,
                    c.deductMoney,
                    c.txaRate,
                    c.reserveEarnings,
                    c.revenue,
                    if(c.contractstatus = 7,
                    0,
                    (
                        (
                            (if((c.proMoney = c.deductMoney),100,c.proschedule) / 100) * (c.proMoney /(1 + c.point)) - (c.deductMoney / (1 + c.point))
                        ) - c.reserveEarnings
                    )) AS chekVal
                FROM
                    oa_contract_project c
                WHERE
                    1 = 1
                AND (
                    c.esmProjectId IS NULL
                    OR c.esmProjectId = ''
                )
                {$status}
          )t where 1=1 
          AND (
                round((t.chekVal - t.revenue),3) > 0.03
                or round((t.chekVal - t.revenue),3) < -0.03
          )";

        $errorProjectIds = "";
        $checkNum = $correctNum = $errorNum = 0;

        $errorProjects = $this->_db->getArray($checkErrorSql);
        $checkNum = count($projects);
        if($errorProjects && is_array($errorProjects)){
            $errorNum = count($errorProjects);
            foreach ($errorProjects as $pro){
                $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
            }
        }
        $correctNum = bcsub($checkNum,$errorNum,0);

//        if ($projects && is_array($projects)) {
//            foreach ($projects as $k => $pro) {
//                if ($pro['contractstatus'] != 7) {
//                    // $reserveEarning = $this->getConReserveEarning($pro);// 获取当前预留营收
//                    $curIncome = bcsub(bcmul(bcdiv($pro['proschedule'],100,9), bcdiv($pro['proMoney'], (1 + $pro['point']),9),9), bcdiv($pro['deductMoney'], (1 + $pro['point']), 9),9);// 项目进度*税后合同额-扣款/(1+税点)
//                    $curIncome = bcsub($curIncome, $pro['reserveEarnings']);//项目进度*税后合同额-扣款/(1+税点)-预留营收
//                } else {// 项目关联合同状态为异常关闭时，收入为0
//                    $curIncome = 0;
//                }
//
//                $chkDiff = round(bcsub($pro['earnings'], $curIncome, 0), 0);
//                $checkNum += 1;
//                if ($chkDiff > 0 || $chkDiff < 0) {
//                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
//                    $errorNum += 1;
//                } else {
//                    $correctNum += 1;
//                }
//            }
//        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 总成本与分成本对比
     * @param $id
     * @param $status
     * @return array
     */
    function productFeeAll($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "select c.id,c.recost,c.shipcost,c.othercost,c.feeAll from oa_contract_project c where 1=1 and (esmProjectId is null or esmProjectId = '') {$status}";
        $projects = $this->_db->getArray($sql);

        $errorProjectIds = "";
        $checkNum = $correctNum = $errorNum = 0;

        if ($projects && is_array($projects)) {
            foreach ($projects as $k => $pro) {
                $cost = bcadd(bcadd($pro['recost'], $pro['shipcost'], 3), $pro['othercost'], 3);

                $chkDiff = round(bcsub($pro['feeAll'], $cost, 2), 2);
                $checkNum += 1;
                if ($chkDiff > 0.03 || $chkDiff < -0.03) {
                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                    $errorNum += 1;
                } else {
                    $correctNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 数据版本校验
     * @param $id
     * @param $status
     * @return array
     */
    function productVersion($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "select c.id,c.revenue as earnings,c.feeAll as cost from oa_contract_project c where 1=1 and (esmProjectId is null or esmProjectId = '') {$status}";
        $projects = $this->_db->getArray($sql);

        $errorProjectIds = $emptyIds = "";
        $checkNum = $correctNum = $errorNum = 0;

        if ($projects && is_array($projects)) {
            foreach ($projects as $k => $pro) {
                $checkNum += 1;
                $chkSql = "
                    SELECT
                        r.earnings as rEarning,
                        r.cost as rCost
                    FROM
                        oa_contract_project_record r
                    WHERE
                    r.pid = {$pro['id']}
                    order by r.version desc limit 1
                ";
                $versionChkArr = $this->_db->getArray($chkSql);
                if ($versionChkArr && isset($versionChkArr[0]['rEarning'])) {
                    if (($pro['earnings'] != $versionChkArr[0]['rEarning']) || ($pro['cost'] != $versionChkArr[0]['rCost'])) {
                        $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                        $errorNum += 1;
                    } else {
                        $correctNum += 1;
                    }
                }else if($pro['earnings'] > 0 || $pro['cost'] > 0){// 当项目收入或成本有一个不为0的情况下,找不到对应版本才算错误
                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                    $errorNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorNumdsd" => $emptyIds,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 收入方式校验
     * @param $id
     * @param $status
     */
    function productIncomeType($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "select GROUP_CONCAT(id) as id,contractMoney,sum(proMoney) as proMoney,earningsTypeCode from oa_contract_project c where 1=1 and c.proLineCode <> 'HTCPX-ZXJS' and (esmProjectId is null or esmProjectId = '') {$status} GROUP BY c.contractId";
        $projects = $this->_db->getArray($sql);

        $errorProjectIds = "";
        $checkNum = $correctNum = $errorNum = 0;
        if ($projects && is_array($projects)) {
            foreach ($projects as $k => $pro) {
                $checkNum += 1;
                if (($pro['contractMoney'] != $pro['proMoney']) && $pro['earningsTypeCode'] == "HSFS-FPJD") {
                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                    $errorNum += 1;
                } else {
                    $correctNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    function productProLine($id, $status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $sql = "SELECT
                    c.id,
                    c.Module,
                    c.Modulename,
                    c.proLineCode,
                    c.proLineName,
                    c.officeid,
                    c.officename,
                    c.exeAreaid,
                    c.exeArea,
                    c.areacode,
                    c.areaname
                FROM
                    oa_contract_project c
                where
                1=1 AND (c.esmProjectId is null or c.esmProjectId = '') {$status};";
        $projects = $this->_db->getArray($sql);

        $errorProjectIds = "";
        $checkNum = $correctNum = $errorNum = 0;

        if ($projects && is_array($projects)) {
            foreach ($projects as $k => $pro) {
                $checkNum += 1;
                if ($pro['Module'] == '' || $pro['Modulename'] == '' ||
                    $pro['proLineCode'] == '' || $pro['proLineName'] == '' ||
                    $pro['officeid'] == '' || $pro['officename'] == '' ||
                    $pro['exeAreaid'] == '' || $pro['exeArea'] == '' ||
                    $pro['areacode'] == '' || $pro['areaname'] == ''
                ) {
                    $errorProjectIds .= ($errorProjectIds == "") ? $pro['id'] : "," . $pro['id'];
                    $errorNum += 1;
                } else {
                    $correctNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "100",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 扣款校验
     * @param $id
     * @param $status
     * @return array
     */
    function productDeductMoney($id, $status){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 状态转义
        $status = $this->conStatus2Sql($status);
        $errorProjectIds = '';
        $checkNum = $correctNum = $errorNum = 0;
        $totalSql = "select sum(tt.num) as totalNum from(
                select t.*,ct.deductMoney from (
                    select c.contractId,sum(c.deductMoney) as totalDeduct,GROUP_CONCAT(c.id) as proIds,count(c.id) as num from oa_contract_project c where 1=1 {$status} group by c.contractId
                )t left join oa_contract_contract ct on t.contractId = ct.id where ct.id is not null
            )tt";
        $chkResult = $this->_db->getArray($totalSql);
        if(is_array($chkResult) && isset($chkResult[0]['totalNum'])){
            $checkNum = $chkResult[0]['totalNum'];
        }

        $errorSql = "select tt.proIds,tt.num from(
                select t.*,ct.deductMoney from (
                    select c.contractId,sum(c.deductMoney) as totalDeduct,GROUP_CONCAT(c.id) as proIds,count(c.id) as num from oa_contract_project c where 1=1 {$status} group by c.contractId
                )t left join oa_contract_contract ct on t.contractId = ct.id 
                LEFT JOIN (select contractId,sum(deductMoney) as esmProjDeduct from oa_esm_project group by contractId) esmp on esmp.contractId = t.contractId
                where ct.id is not null and ((t.totalDeduct - (ct.deductMoney - esmp.esmProjDeduct)) > 0.01 or (t.totalDeduct - (ct.deductMoney - esmp.esmProjDeduct)) < -0.01)
            )tt";
        $chkErrorResult = $this->_db->getArray($errorSql);
        if(is_array($chkErrorResult)){
            foreach ($chkErrorResult as $k => $v){
                $errorNum += $v['num'];
                $errorProjectIds .= ($k == 0)? $v['proIds'] : ",".$v['proIds'];
            }
            $errorProjectIds = rtrim($errorProjectIds,",");

            if($checkNum > 0){
                $correctNum = bcsub($checkNum,$errorNum,0);
            }else{
                $correctNum = $checkNum;
            }

            if($checkNum <= 0){
                $errorProjectIds = '';
            }
        }

        if($errorNum > 450){
            $_SESSION['deductMoneyErrIds'] = $errorProjectIds;
            $errorProjectIds = "deductMoneyErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "99",
            "spcial" => "1",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**************************** 其他类检查 ***************************************/
    /**
     * 检查自动更新
     * @param $id
     * @param $status
     * @return array
     */
    function otherAutoUpdate($id,$status){
        $todayStr = date("Y-m-d");
        $chkArr = array('项目收入存档','决算数据存档-外包','决算数据存档-设备','决算数据存档-人力','决算数据存档-其他','决算数据存档-报销支','更新项目数据','更新报销支付','更新人力决算','更新设备决算','更新产品项目冗余数据','产品项目数据版本自动保存');

        $errorOptsStr = "没执行的定时任务:";
        $correctString = "已执行的定时任务:";
        $checkNum = $correctNum = $errorNum = 0;
        foreach ($chkArr as $k => $v){
            if($v == '产品项目数据版本自动保存'){
                $thisMonth = date('m');
                $thisDay = date('d');

                // 先检查当天日期是否在当月保护期后（是则检查日志,否则无需检查）
                $chkMon = $thisMonth * 1;
                $chkDay = $thisDay * 1;
                $isSaveTime = false;
                $sql = "select saveDayForPro from oa_esm_baseinfo_log_deadline where month = {$chkMon};";
                $chkArr= $this->_db->getArray($sql);
                if($chkArr && count($chkArr) > 0){
                    $saveDay = (isset($chkArr[0]['saveDayForPro']) && $chkArr[0]['saveDayForPro'] != '')? $chkArr[0]['saveDayForPro'] : 0;
                    $isSaveTime = ($chkDay >= $saveDay);
                }

                $chkSql = ($isSaveTime)? "SELECT operationType FROM `oa_esm_log` WHERE projectId = -1 and DATE_FORMAT(operationTime,'%Y-%m-%d') = '{$todayStr}' and userId = 'oazy' and operationType = '{$v}' limit 1;" : "select id from oa_contract_contract limit 1;";
            }else{
                $chkSql = "SELECT operationType FROM `oa_esm_log` WHERE projectId = -1 and DATE_FORMAT(operationTime,'%Y-%m-%d') = '{$todayStr}' and userId = 'oazy' and operationType = '{$v}' limit 1;";
            }
            $chkResult = $this->_db->getArray($chkSql);

            $checkNum += 1;
            if($chkResult){
                $correctNum += 1;
                $correctString .= "&#10【".$v."】";
            }else{
                $errorNum += 1;
                $errorOptsStr .= "&#10【".$v."】";
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "string",
            "correctString" => $correctString,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorString" => $errorOptsStr
        );
    }

    /**
     * 产品金额校验
     * @param $id
     * @param $status
     * @return array
     */
    function otherProductMoney($id,$status){
        $chkSql = "
        SELECT
            c.id,c.contractMoney,sum(p.money) as prosMoney
        FROM
            oa_contract_product p
        LEFT JOIN oa_contract_contract c ON p.contractId = c.id
        WHERE c.isTemp = 0 AND p.isDel = 0 AND c.areaName NOT IN ('鼎利集团结算','子公司','海外')
        GROUP BY p.contractId";
        $chkArr = $this->_db->getArray($chkSql);

        $errorContractIds = "";
        $checkNum = $correctNum = $errorNum = 0;
        if ($chkArr && is_array($chkArr)) {
            foreach ($chkArr as $k => $chk) {
                $contractMoney = ($chkArr[$k]['contractMoney'] == '')? 0 : $chkArr[$k]['contractMoney'];
                $prosMoney = ($chkArr[$k]['prosMoney'] == '')? 0 : $chkArr[$k]['prosMoney'];
                $checkNum += 1;
                if(($contractMoney - $prosMoney) > 0.01 || ($contractMoney - $prosMoney) < -0.01){
                    $errorNum += 1;
                    $errorContractIds .= ($errorContractIds == "")? $chk['id'] : ",".$chk['id'];
                }else{
                    $correctNum += 1;
                }
            }
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => "101",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorContractIds
        );
    }

    /**
     * 产品区域及产线校验
     * @param $id
     * @param $status
     * @return array
     */
    function otherProductArea($id,$status){
        $errorContractIds = "";
        $correctNum = $errorNum = 0;

        $totalChkSql = "SELECT count(c.id) as num FROM oa_contract_product p LEFT JOIN oa_contract_contract c ON p.contractId = c.id where c.isTemp = 0 AND p.isDel = 0  AND c.areaName NOT IN ('鼎利集团结算','子公司','海外');";
        $totalChk = $this->_db->getArray($totalChkSql);
        $checkNum = ($totalChk)? $totalChk[0]['num'] : 0;

        $errorChkSql = "
            SELECT
                c.id,p.exeDeptId,p.exeDeptName,p.newProLineCode,p.newProLineName
            FROM
                oa_contract_product p
            LEFT JOIN oa_contract_contract c ON p.contractId = c.id
            where 
            c.isTemp = 0 AND p.isDel = 0
            AND c.areaName NOT IN ('鼎利集团结算','子公司','海外')
            and (
                (p.exeDeptId = '' or p.exeDeptId is null)
                or (p.exeDeptName = '' or p.exeDeptName is null)
                or (p.newProLineCode = '' or p.newProLineCode is null)
                or (p.newProLineName = '' or p.newProLineName is null)
            )
        ";
        $errorChkArr = $this->_db->getArray($errorChkSql);
        $errorContractIdsArr = array();
        $correctNum = $checkNum;
        if($errorChkArr && is_array($errorChkArr)){
            foreach ($errorChkArr as $k => $v){
                $correctNum = bcsub($correctNum,1);
                $errorNum += 1;
                if(!in_array($v['id'],$errorContractIdsArr)){
                    $errorContractIdsArr[] = $v['id'];
                }
            }
            $errorContractIds = implode(",",$errorContractIdsArr);
            // $errorContNum = count($errorContractIdsArr);
            // $errorNum = "产品数: ".$errorNum."（合同数:".$errorContNum."）";
        }

        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['otherProductAreaErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "otherProductAreaErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "spcial" => "1",
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 概算校验
     * @param $id
     * @param $status
     * @return array
     */
    function otherEstimates($id,$status){
        $errorContractIds = "";
        $correctNum = $errorNum = 0;

        $notCostEstContractIdsSql = "
            SELECT
                c.id
            FROM
                oa_contract_contract c
            LEFT JOIN oa_contract_cost cc ON c.id = cc.contractId
            WHERE
                c.isTemp = 0
            AND (cc.id IS NULL);
        ";
        $notCostEstContractIds = '';
        $notCostEstContractIdsArr = $this->_db->getArray($notCostEstContractIdsSql);
        if(!empty($notCostEstContractIdsArr)){
            foreach ($notCostEstContractIdsArr as $k => $v){
                $notCostEstContractIds .= ($k == 0)? $v['id'] : ",".$v['id'];
            }
            $notCostEstContractIds = ($notCostEstContractIds == '')? '' : ' and t.contractId not in ('.$notCostEstContractIds.')';
        }

        $totalChkSql = "
        select COUNT(tt.contractId) as num from (
            select * from (
              select cp.contractId from oa_contract_project cp where (cp.esmprojectId = '' or cp.esmprojectId is null)
              union all
              select ep.contractId from oa_esm_project ep
        )t left join oa_contract_contract c on c.id = t.contractId where c.isTemp = 0 and t.contractId is not null {$notCostEstContractIds} group by t.contractId)tt";
        $totalChk = $this->_db->getArray($totalChkSql);
        $checkNum = ($totalChk)? $totalChk[0]['num'] : 0;

        $errorChkSql = "
        select tt.*,c.costEstimates,c.id,c.contractCode from (
            select t.contractId,sum(t.Estimates) as totalEstimates from (
                select cp.contractId,cp.Estimates from oa_contract_project cp where (cp.esmprojectId = '' or cp.esmprojectId is null)
                union all
                select ep.contractId,ep.Estimates from oa_esm_project ep
            )t where t.contractId is not null {$notCostEstContractIds} group by t.contractId
        )tt left join oa_contract_contract c on c.id = tt.contractId
        where c.isTemp = 0 and ((tt.totalEstimates - c.costEstimates) > 0.01 or (tt.totalEstimates - c.costEstimates) < -0.01)";
        $errorChkArr = $this->_db->getArray($errorChkSql);
        $errorContractIdsArr = array();
        if($errorChkArr && is_array($errorChkArr)){
            $correctNum = $checkNum;
            foreach ($errorChkArr as $k => $v){
                $correctNum = bcsub($correctNum,1);
                $errorNum += 1;
                if(!in_array($v['id'],$errorContractIdsArr)){
                    $errorContractIdsArr[] = $v['id'];
                }
            }
            $errorContractIds = implode(",",$errorContractIdsArr);
        }

        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['otherEstimatesErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "otherEstimatesErrIds";
        }
        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorSql" => $errorChkSql,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**************************** 合同类检查 ***************************************/

    /**
     * 产品金额检查
     * @param $id
     * @return array
     */
    function conProductCheck($id){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $conDao = new model_contract_contract_contract();
        $searchArr['isTemp'] = 0;
        $searchArr['noSpecilCustomerType'] = 1;
        $conDao->getParam($searchArr);
        $contractRows = $conDao->listBySqlId('select_gridinfo');
        $errorContractIds = "";
        $errorContractIdsArr = array();
        $checkNum = $correctNum = $errorNum = 0;

        // 开始检查
        if($contractRows){
            foreach ($contractRows as $row){
                $checkNum += 1;
                //产品金额
                $pMoneySql = "select sum(money) as pMoney from oa_contract_product where isDel = 0 and contractId='".$row['id']."'";
                $pMoneyArr = $this->_db->getArray($pMoneySql);
                if($row['contractMoney'] == $pMoneyArr[0]['pMoney']){
                    $correctNum += 1;
                }else{
                    $errorNum += 1;
                    $errorContractIdsArr[] = $row['id'];
                }
            }
            $errorContractIds = implode(",",$errorContractIdsArr);
        }

        // 如果异常ID数量大于450时,用缓存记录ID字符串
        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['conProductCheckErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "conProductCheckErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 项目金额检查
     * @param $id
     * @return array
     */
    function conProjectCheck($id){
        $errorContractIds = "";
        $errorContractIdsArr = array();
        $checkNum = $correctNum = $errorNum = 0;

        // 统计检查数量
        $countSql = "select count(id) as num from oa_contract_contract where isTemp = 0 and DATE_FORMAT(ExaDTOne,\"%Y\") >= 2015 and (customerTypeName not like '%子公司%' and customerTypeName not like '%母公司%' and customerTypeName not like '%内部结算%');";
        $countResult = $this->_db->get_one($countSql);
        $checkNum = ($countResult)? $countResult['num'] : 0;

        // 查询错误数据
        $chkErrorsSql = <<<EOT
        select c.customerTypeName,c.id,c.contractMoney,t.projectMoney from oa_contract_contract c left join(
            select c.contractId,sum(c.projectMoney) as projectMoney FROM
            (	
            select '' as customerTypeName,'' as ExaDTOne,c.id,c.id as proId,c.projectCode,c.contractId,c.contractCode,c.contractType,c.projectMoneyWithTax as projectMoney,
                            'esm' as pType,c.feeAll,c.feePK,c.budgetAll,c.curIncome,c.productLine,c.newProLine,c.workRate,c.incomeTypeName
                    from
                            oa_esm_project c
                            left join
                            (SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e on e.projectId = c.id
                    where 1
                    union ALL
                    select
                         cp.customerTypeName,cp.ExaDTOne,p.id,p.id as proId,p.projectCode,p.contractId,p.contractCode,'GCXMYD-01' as contractType,p.proMoney as projectMoney,
                         'pro' as pType,p.cost as feeAll,0 as feePK,p.budget as budgetAll,p.revenue as curIncome,
                         p.proLineCode as productLine,p.proLineCode as newProLine,null as workRate,p.earningsTypeName AS incomeTypeName
                    from oa_contract_project p
                    LEFT JOIN oa_contract_contract cp ON p.contractId = cp.id
                    where p.esmProjectId is null
            )c
            where 1 GROUP BY c.contractId)t on c.id = t.contractId where c.isTemp = 0 and t.projectMoney is not null and (t.projectMoney - c.contractMoney > 0.1 || t.projectMoney - c.contractMoney < -0.1)
                        and DATE_FORMAT(c.ExaDTOne,"%Y") >= 2015
						and (c.customerTypeName not like '%子公司%' and c.customerTypeName not like '%子公司%' and c.customerTypeName not like '%子公司%')
EOT;
        $chkErrorsResult = $this->_db->getArray($chkErrorsSql);

        if($chkErrorsResult){
            foreach ($chkErrorsResult as $errorArr){
                $errorNum += 1;
                $errorContractIdsArr[] = $errorArr['id'];
            }
        }

        $correctNum = bcsub($checkNum,$errorNum);
        $errorContractIds = implode(",",$errorContractIdsArr);

        // 如果异常ID数量大于450时,用缓存记录ID字符串
        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['conProjectCheckErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "conProjectCheckErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 预计开票检查
     * @param $id
     * @return array
     */
    function conInvoiceCheck($id){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $conDao = new model_contract_contract_contract();
        $searchArr['isTemp'] = 0;
        $searchArr['noSpecilCustomerType'] = 1;
        $conDao->getParam($searchArr);
        $contractRows = $conDao->listBySqlId('select_gridinfo');
        $errorContractIds = "";
        $errorContractIdsArr = array();
        $checkNum = $correctNum = $errorNum = 0;

        // 开始检查
        if($contractRows){
            foreach ($contractRows as $row){
                $checkNum += 1;
                $invoiceValueArr = explode(",", $row['invoiceValue']);
                $cMoney = 0;
                foreach ($invoiceValueArr as $k => $v) {
                    if (!empty($v)) {
                        $cMoney += $v;
                    } else {
                        unset($invoiceValueArr[$k]);
                    }
                }

                // 判断关联合同是否存在不开票的开票类型,
                $pass = false;
                $invoiceCodeArr = explode(",",$row['invoiceCode']);
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $pass = ture;
                    }
                }

                $cMoney = sprintf("%.3f", $cMoney);
                $contractMoney = sprintf("%.3f", $row['contractMoney']);

                if(bcsub($contractMoney,$cMoney,3) <= 0.1){
                    $correctNum += 1;
                }else if($pass){
                    $correctNum += 1;
                }else{
                    $errorNum += 1;
                    $errorContractIdsArr[] = $row['id'];
                }
            }
            $errorContractIds = implode(",",$errorContractIdsArr);
        }

        // 如果异常ID数量大于450时,用缓存记录ID字符串
        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['conInvoiceCheckErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "conInvoiceCheckErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }

    /**
     * 实际开票检查
     * @param $id
     * @return array
     */
    function conInvoiceTrueCheck($id){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
//        $conDao = new model_contract_contract_contract();
//        $searchArr['isTemp'] = 0;
//        $searchArr['noSpecilCustomerType'] = 1;
//        $conDao->getParam($searchArr);
//        $contractRows = $conDao->listBySqlId('select_gridinfo');
        $chkSql = <<<EOT
            select * from (
                select 
                sum(if(ep.projectProcess is null,0,if(ep.projectProcess >= 100,0,1))) as eprojectProcessNum,
                sum(if(cp.schedule is null,0,if(cp.schedule >= 100,0,1))) as cprojectProcessNum,
                c.uninvoiceMoney,c.invoiceValue,c.contractTypeName,c.outstockDate,c.isRenewed,c.productLineStr,c.exeDeptStr,c.trialprojectCost,c.trialprojectCostAll,c.trialprojectId,c.trialprojectName,c.trialprojectCode,c.isAcquiringDate,c.isAcquiring,r.areaPrincipal as AreaLeaderNow,c.id ,c.exgross,c.isSubApp,c.isSubAppChange,c.costEstimates,c.signContractType,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.isEngConfirm,c.engConfirm,c.engConfirmName,c.engConfirmId,c.engConfirmDate,c.costEstimatesTax,c.isSaleConfirm,c.saleConfirm,saleConfirmName,c.saleConfirmId,c.saleConfirmDate,c.isRdproConfirm,c.rdproConfirm,rdproConfirmName,
                c.rdproConfirmId,c.rdproConfirmDate,c.isRelConfirm,c.relConfrim,relConfirmName,c.relConfrimId,c.relConfirmDate,c.finalDate,c.preliminaryDate,c.shouldInvoiceDate,c.lastInvoiceDate,c.spaceRentalMoney,c.equRentalMoney,c.appNameStr,c.incomeAccounting,c.goodsTypeStr,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.winRate,c.signDate ,c.chanceName ,c.chanceId ,c.contractType,c.advance,c.delivery,c.progresspayment,c.progresspaymentterm,c.initialpayment,c.finalpayment,c.otherpaymentterm,c.otherpayment,c.Maintenance , c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,c.parentId ,c.customerContNum ,c.customerName ,c.customerId , c.signSubject,c.customerTypeName ,c.customerType ,c.customerProvince ,c.address ,
                c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId ,c.contractCity ,c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,c.invoiceType ,c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney,c.softMoney,c.hardMoney,c.serviceMoney,c.repairMoney ,c.deliveryDate ,c.standardDate ,c.beginDate ,c.endDate ,c.contractInputName ,c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne ,c.ExaDT ,c.closeName ,c.closeId ,c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus ,c.signName ,c.signNameId ,c.signDetail ,
                c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,c.areaName ,c.areaPrincipal ,c.badMoney, c.areaPrincipalId ,c.areaCode ,c.remark ,c.currency ,c.contractTempMoneyCur ,c.contractMoneyCur ,c.rate ,c.isBecome ,c.shipCondition, c.makeStatus ,c.dealStatus ,c.contractState ,c.state,c.contractSigner,c.contractSignerId,date_format(c.createTime,'%Y-%m-%d') as createDate, c.customTypeId,c.customTypeName,c.warnDate,c.isNeedStamp,c.stampType,c.isStamp,c.isNeedRestamp,c.projectRate,c.projectStatus,(c.contractMoney-(if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney)) - c.uninvoiceMoney) as surplusInvoiceMoney, (c.contractMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney))) as surOrderMoney, 
                ((if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney))) as surincomeMoney, c.deductMoney,c.projectProcessAll,c.processMoney,c.gross,c.rateOfGross,c.serviceconfirmMoneyAll,c.financeconfirmMoneyAll,c.deliveryCostsAll,c.invoiceCode,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr,c.checkFile,c.paperContract, date_format(c.ExaDTOne,'%Y') as ExaYear,date_format(c.ExaDTOne,'%m') as ExaMonth,quarter(c.ExaDTOne) as ExaQuarter,c.comPoint,c.conProgress,c.invoiceProgress,c.incomeProgress, c.proj_budgetAll,c.proj_curIncome,c.proj_feeAll,c.proj_conProgress,c.proj_gross,c.proj_rateOfGross,c.proj_comPoint,c.proj_icomeMoney,c.proj_incomeProgress,c.proj_invoiceProgress
                from oa_contract_contract c left join oa_system_region r on c.areaCode = r.id 
                left join oa_esm_project ep on ep.contractId = c.id
                 left join oa_contract_project cp on cp.contractId = c.id
                left join ( select objId, sum(if(isRed = 1,(-otherMoney),otherMoney)) as otherMoney, sum(if(isRed = 1,(-dsEnergyCharge),dsEnergyCharge)) as dsEnergyCharge, sum(if(isRed = 1,(-dsWaterRateMoney),dsWaterRateMoney)) as dsWaterRateMoney, sum(if(isRed = 1,(-houseRentalFee),houseRentalFee)) as houseRentalFee, sum(if(isRed = 1,(-installationCost),installationCost)) as installationCost from oa_finance_invoice where objType = 'KPRK-12' group by objId )i on i.objId = c.id 
                where 1=1 and(( c.isTemp='0')) and (c.invoiceCode not like '%HTBKP%') and(( (c.customerTypeName not like '%子公司%' and c.customerTypeName not like '%母公司%' and c.customerTypeName not like '%内部结算%')))  GROUP BY c.id order by c.id DESC
            )T where T.eprojectProcessNum = 0 and T.cprojectProcessNum = 0
EOT;
        $contractRows = $this->_db->getArray($chkSql);

        $errorContractIds = "";
        $errorContractIdsArr = array();
        $checkNum = $correctNum = $errorNum = 0;

        // 开始检查
        if($contractRows){
            foreach ($contractRows as $row) {
                $checkNum += 1;
                $compareMoney = $row['contractMoney'] - $row['deductMoney'] - $row['uninvoiceMoney'];
                // 判断关联合同是否存在不开票的开票类型,
                $pass = false;
                $invoiceCodeArr = explode(",",$row['invoiceCode']);
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $pass = ture;
                    }
                }
                //echo $row['invoiceMoney'] . " - ". $compareMoney;exit();
                $compareMoney = sprintf("%.3f", $compareMoney);
                $invoiceMoney = sprintf("%.3f", $row['invoiceMoney']);
                if(bcsub($invoiceMoney,$compareMoney,3) >= 0 || abs(bcsub($invoiceMoney,$compareMoney,3)) <= 0.3){
                    $correctNum += 1;
                }else if($pass || ($row['state'] != '4' && $row['state'] != '3')){
                    $correctNum += 1;
                }else{
                    $errorNum += 1;
                    $errorContractIdsArr[] = $row['id'];
                }
            }
            $errorContractIds = implode(",",$errorContractIdsArr);
        }

        // 如果异常ID数量大于450时,用缓存记录ID字符串
        $type = "101";
        $errorProjectIds = $errorContractIds;
        if(count($errorContractIdsArr) > 450){
            $_SESSION['conProjectCheckErrIds'] = $errorContractIds;
            $type = "102";
            $errorProjectIds = "conProjectCheckErrIds";
        }

        // 返回结果
        return array(
            "id" => $id,
            "type" => $type,
            "checkNum" => $checkNum,
            "correctNum" => $correctNum,
            "errorNum" => $errorNum,
            "errorProjectIds" => $errorProjectIds
        );
    }
}