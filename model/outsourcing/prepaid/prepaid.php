<?php

/**
 * @author Acan
 * @Date 2014年10月30日 11:03:15
 * @version 1.0
 * @description:外包预提 Model层
 */
class model_outsourcing_prepaid_prepaid extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_nprepaid";
        $this->sql_map = "outsourcing/prepaid/prepaidSql.php";
        parent::__construct();
    }

    /**
     * 根据Id获取信息
     */
    function getInfoById_d($id)
    {
        $this->searchArr = array('id' => $id);
        $personnelRow = $this->listBySqlId("select_default");
        return $personnelRow['0'];
    }

    /**
     * 获取列表数据
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $searchVal
     * @param $projectId
     * @return array|bool
     */
    function summaryList_d($beginYear, $beginMonth, $endYear, $endMonth, $searchVal, $projectId, $sortField = '', $sortOrder = '')
    {
        // 搜索条件拼接
        $searchVal = util_jsonUtil::iconvUTF2GB($searchVal);
        $mainSearch = $searchVal ? " AND (c.contractCode LIKE '%" . $searchVal .
            "%' OR c.signCode LIKE '%" . $searchVal . "%' OR c.projectCode LIKE '%" . $searchVal . "%')" : "";

        $beginSearch = $beginYear ? " AND i.yearMonthTS >= " . strtotime($beginYear . "-" . $beginMonth . '-1') : "";
        $endSearch = $endYear ? " AND i.yearMonthTS <= " . strtotime($endYear . "-" . $endMonth . '-1') : "";

        // 项目查询
        $projectSearch = $projectId ? " AND c.projectId = " . $projectId : "";

        // 主查询脚本
//        $sql = "SELECT
//                c.id,c.contractId,c.contractCode,c.signCode,c.taxRate,c.projectId,
//		        c.projectCode,c.projectName,c.businessType,c.area,
//                i.yearMonth,i.yearMonthTS,i.original,i.adjust,i.prepaidWithTax,
//                i.prepaid,i.remark,i.act,i.invoice,i.invoiceDT,i.pay,i.payDT,
//                i.diff
//		    FROM
//		        oa_outsourcing_nprepaid c
//                INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
//		    WHERE 1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . " ORDER BY c.id DESC, i.yearMonthTS DESC ";


        $orderBy = ($sortField != '')? " order by t2.{$sortField} {$sortOrder},t2.tid" : " order by  t1.id DESC, t1.yearMonthTS DESC ";//
        $sql = "select * from (
            SELECT c.id,c.contractId,c.contractCode,c.signCode,c.taxRate,c.projectId, c.projectCode,c.projectName,c.businessType,c.area, i.yearMonth,i.yearMonthTS,i.original,i.adjust,i.prepaidWithTax, i.prepaid,i.remark,i.act,i.invoice,i.invoiceDT,i.pay,i.payDT, i.diff FROM oa_outsourcing_nprepaid c INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId 
            WHERE 1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . " 
            ORDER BY c.id DESC, i.yearMonthTS DESC
            )t1
            left join 
            (SELECT
                t.id as tid,
                sum(t.prepaid) as subPrepaid,
                sum(t.prepaidWithTax) as subPrepaidWithTax,
                sum(t.pay) as subPay,
                (sum(t.prepaidWithTax) - sum(t.pay)) as unDeal
            from(
            select c.id,i.prepaidWithTax,i.prepaid,i.pay
            FROM
                oa_outsourcing_nprepaid c
            INNER JOIN oa_outsourcing_nprepaid_item i ON c.id = i.mainId
            WHERE
                1 " . $projectSearch . $mainSearch . $beginSearch . $endSearch . ")t
            group by t.id)t2 on t1.id = t2.tid {$orderBy}";

        $data = $this->_db->getArray($sql);

        if ($data) {
            // 数据处理
            $data = $this->summaryDeal_d($data);
        } else {
            $data = array();
        }

        return $data;
    }

    function summaryDeal_d($data)
    {
        // 映射表
        $keyArr = array();

        foreach ($data as $k => $v) {
            if (!isset($keyArr[$v['id']])) {
                $keyArr[$v['id']] = array(
                    'k' => $k, 'subPrepaid' => 0, 'subPrepaidWithTax' => 0, 'subPay' => 0
                );
            } else {
                $data[$k]['subPrepaid'] = $data[$k]['subPrepaidWithTax'] = $data[$k]['subPay'] = $data[$k]['unDeal'] = "";
                $data[$k]['contractCode'] = $data[$k]['signCode'] = $data[$k]['taxRate'] = "";
                $data[$k]['projectCode'] = $data[$k]['projectName'] = $data[$k]['businessType'] = $data[$k]['area'] = "";
            }
//            $keyArr[$v['id']]['subPrepaid'] = bcadd($keyArr[$v['id']]['subPrepaid'], $v['prepaid'], 2);
//            $keyArr[$v['id']]['subPrepaidWithTax'] = bcadd($keyArr[$v['id']]['subPrepaidWithTax'], $v['prepaidWithTax'], 2);
//            $keyArr[$v['id']]['subPay'] = bcadd($keyArr[$v['id']]['subPay'], $v['pay'], 2);
        }

//        foreach ($keyArr as $k => $v) {
//            $data[$v['k']]['subPrepaid'] = $v['subPrepaid'];
//            $data[$v['k']]['subPrepaidWithTax'] = $v['subPrepaidWithTax'];
//            $data[$v['k']]['subPay'] = $v['subPay'];
//            $data[$v['k']]['unDeal'] = $unDeal = bcsub($v['subPrepaidWithTax'], $v['subPay'], 2);
//            foreach ($data as $dk => $dv){
//                if($dv['id'] == $k && $dk != $v['k']){
//                    $data[$dk]['subPrepaid_x'] = $v['subPrepaid'];
//                    $data[$dk]['subPrepaidWithTax_x'] = $v['subPrepaidWithTax'];
//                    $data[$dk]['subPay_x'] = $v['subPay'];
//                    $data[$dk]['unDeal_x'] = $unDeal;
//
//                }
//            }
//        }

        // 汇总表
        $summaryAll = array(
            'contractCode' => '合计', 'taxRate' => '', 'subPrepaid' => 0, 'subPrepaidWithTax' => 0, 'unDeal' => 0, 'subPay' => 0,
            'original' => 0, 'adjust' => 0, 'act' => 0,'prepaid' => 0, 'prepaidWithTax' => 0,
            'pay' => 0, 'invoice' => 0, 'diff' => 0
        );

        // 汇总字段叠加
        foreach ($data as $v) {
            foreach ($summaryAll as $ki => $vi) {
                if (!in_array($ki, array('contractCode', 'taxRate'))) {
                    $summaryAll[$ki] = bcadd($summaryAll[$ki], $v[$ki], 2);
                }
            }
        }

        $data[] = $summaryAll;

        return $data;
    }

    /******************* S 导入导出系列 ************************/
    function excelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // 结果数组
        $tempArr = array(); // 临时结果数组
        $itemDao = new model_outsourcing_prepaid_prepaidMonth();
        $projectDao = new model_engineering_project_esmproject(); //工程项目
        $budgetDao = new model_engineering_budget_esmbudget(); // 实例化项目预算
        $projectIds = array(); // 项目id缓存
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                if (is_array($excelData)) {

                    // 表头
                    $titleRow = $excelData[0];
                    unset($excelData[0]);

                    // 先处理日期字段
                    foreach ($excelData as $k => $v){
                        if($v[7] != ''){// 月份字段
                            $patten = "/^\d{4}[\-](0?[1-9]|1[012])?$/";
                            if (!preg_match($patten, $v[7])){
                                $timeStr = bcmul(bcsub($v[7],25569,0),86400);
                                $excelData[$k][7] = date('Y-m', $timeStr);
                            }
                        }
                        if($v[15] != ''){// 收票日期
                            $patten = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
                            if (!preg_match($patten, $v[15])){
                                $timeStr = bcmul(bcsub($v[15],25569,0),86400);
                                $excelData[$k][15] = date('Y-m-d', $timeStr);
                            }
                        }
                        if($v[17] != ''){// 支付日期
                            $patten = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
                            if (!preg_match($patten, $v[17])){
                                $timeStr = bcmul(bcsub($v[17],25569,0),86400);
                                $excelData[$k][17] = date('Y-m-d', $timeStr);
                            }
                        }
                    }

                    //行数组循环
                    $dataRecord = array();
                    foreach ($excelData as $key => $val) {
                        $actNum = $key + 2;

                        if (empty($val[0])) {
                            continue;
                        } else {
                            // 格式化数组
                            $val = $this->formatArray_d($val, $titleRow);

                            // 是否存在未填
                            $hasEmpty = false;

                            // 必填校验
                            foreach ($this->needTitle as $k => $v) {
                                if (!isset($val[$v]) || !$val[$v]) {
                                    $resultArr[] = array(
                                        'docCode' => '第' . $actNum . '条数据', 'result' => '没有填写' . $k
                                    );
                                    $hasEmpty = true;
                                    break;
                                }
                            }
                            if ($hasEmpty) continue;

                            $project = $projectDao->find(array('projectCode' => $val['projectCode']), null, "id");
                            if (!$project) {
                                $resultArr[] = array(
                                    'docCode' => '第' . $actNum . '条数据', 'result' => '无效的项目编号' . $val['projectCode']
                                );
                                break;
                            }

                            $contractCode = '';
                            try {
                                // 查询是否存在已录合同
                                $contract = $this->find(array("contractCode" => $val['contractCode']), null, "id,contractCode,projectId");
                                $contractData = array(
                                    'contractCode' => $val['contractCode'], 'signCode' => $val['signCode'],
                                    'taxRate' => bcmul($val['taxRate'], 100, 2), 'projectCode' => $val['projectCode'],
                                    'projectName' => $val['projectName'], 'businessType' => $val['businessType'],
                                    'area' => $val['area'], 'projectId' => $project['id']
                                );
                                if ($contract) {
                                    $contractCode = $contract['contractCode'];
                                    $contractData['id'] = $contractId = $contract["id"];
                                    $this->edit_d($contractData);

                                    // 如果存在该外包合同的记录,则记录该合同的旧项目信息
                                    if(!isset($dataRecord[$contractCode])){
                                        $dataRecord[$contractCode]['oldProjectId'] = $contract["projectId"];
                                    }
                                } else {
                                    $contractId = $this->add_d($contractData);
                                }

                                if(isset($dataRecord[$contractCode])){
                                    $dataRecord[$contractCode]['newProjectId'] = $project['id'];
                                }

                                // 查询是否存在明细
                                $item = $itemDao->find(array("mainId" => $contractId, 'yearMonth' => $val['yearMonth']), null, "id");
                                $itemData = array(
                                    'yearMonth' => $val['yearMonth'], 'yearMonthTS' => strtotime($val['yearMonth'] . "-01"),
                                    'original' => $val['original'], 'adjust' => $val['adjust'], 'prepaidWithTax' => $val['prepaidWithTax'],
                                    'prepaid' => $val['prepaid'], 'remark' => $val['remark'], 'act' => $val['act'],
                                    'invoice' => $val['invoice'], 'invoiceDT' => $val['invoiceDT'], 'pay' => $val['pay'],
                                    'payDT' => $val['payDT'], 'diff' => $val['diff'], 'mainId' => $contractId,
                                    'projectId' => $project['id']
                                );
                                if ($item) $itemData['id'] = $item['id'];
                                $item ? $itemDao->edit_d($itemData, true) : $itemDao->add_d($itemData, true);

                                // 重新计算外包合同汇总 - 三阶段待办

                                // 项目外包决算重新计算
                                $projectIds[] = $project['id'];
                                $budgetDao->importByCostMainTain(array(
                                    'projectId' => $project['id'], 'projectCode' => $val['projectCode'],
                                    'projectName' => $val['projectName'], 'parentCostType' => '外包预算',
                                    'costType' => $val['businessType'], 'remark' => $val['remark'], 'budget' => 0,
                                    'fee' => $val['prepaid']
                                ));

                                $tempArr['result'] = '导入成功';
                            } catch (Exception $e) {
                                $tempArr['result'] = '更新失败' . $e->getMessage();
                            }
                        }
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        array_push($resultArr, $tempArr);
                    }

                    if(!empty($dataRecord)){
                        $projectIdsArr = array();
                        foreach ($dataRecord as $v){
                            if($v['newProjectId'] != $v['oldProjectId']){// 如果导入的项目ID与之前的不一致,则统一更新新旧项目的预决算信息
                                $projectIdsArr[] = $v['oldProjectId'];
                                $projectIdsArr[] = $v['newProjectId'];
                            }
                        }
                        $budgetDao->updateCostByProjectIds($projectIdsArr);
                    }

                    return $resultArr;
                } else {
                    msg("文件不存在可识别数据!");
                }
            } else {
                msg("上传文件类型不是EXCEL!");
            }
        }
    }

    // 导入标题
    private $importTitle = array(
        // 主表
        '外包合同号' => 'contractCode', '签约合同号' => 'signCode', '税率' => 'taxRate', '项目编号' => 'projectCode',
        '项目名称' => 'projectName', '合作方式' => 'businessType', '区域' => 'area',

        // 明细表
        '月份' => 'yearMonth', '原始' => 'original', '补差额' => 'adjust', '预提' => 'prepaidWithTax',
        '预提(不含税)' => 'prepaid', '备注' => 'remark', '实际' => 'act', '收票' => 'invoice', '收票日期' => 'invoiceDT',
        '支付' => 'pay', '支付日期' => 'payDT', '预提与实际差额' => 'diff'
    );

    // 必填标题
    private $needTitle = array(
        '外包合同号' => 'contractCode', '项目编号' => 'projectCode', '合作方式' => 'businessType', '月份' => 'yearMonth',
        '预提(不含税)' => 'prepaid'
    );

    /**
     * 匹配excel字段
     * @param $data
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($data, $titleRow)
    {
        // 构建新的数组
        foreach ($titleRow as $k => $v) {
            // 如果存在已定义内容，则进行键值替换
            if (isset($this->importTitle[$v])) {
                // 格式化更新数组
                $data[$this->importTitle[$v]] = trim($data[$k]);
            }
            // 处理完成后，删除该项
            unset($data[$k]);
        }
        return $data;
    }
}