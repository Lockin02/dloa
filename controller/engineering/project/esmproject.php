<?php

/**
 * @author Show
 * @Date 2011年11月24日 星期四 17:20:15
 * @version 1.0
 * @description:工程项目(oa_esm_project)控制层
 */
class controller_engineering_project_esmproject extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmproject";
        $this->objPath = "engineering_project";
        parent::__construct();
    }

    /**
     * 跳转到工程项目
     */
    function c_page()
    {
        $this->assign('editProjectLimit', $this->service->this_limit['项目修改权限']);
        $this->assign('openCloseLimit', $this->service->this_limit['项目开启关闭权限']);
        $this->assignFunc($this->service->getVersionInfo_d());
        $this->view('list');
    }

    /**
     * 在建项目列表
     */
    function c_pageJson()
    {
        $service = $this->service;
        $rows = null;
        $noLimit = false;
        if(isset($_POST['noLimit']) && $_POST['noLimit'] == '1'){
            $noLimit = true;
            $service->setCompany(0);
        }else{
            $service->setCompany(1);# 设置此列表启用公司
        }

        # 默认指向表的别称是
        $service->setComLocal(array(
            "c" => $service->tbl_name
        ));

        //办事处权限部分
        $officeArr = array();
        $sysLimit = $service->this_limit['办事处'];

        //省份权限
        $proLimit = $service->this_limit['省份权限'];

        //服务经理权限
        $manArr = array();

        // 决算版本提取
        $searchItem = $_POST;
        $feeBeginDate = $feeEndDate = $incomeBeginDate = $incomeEndDate = '';
        if (isset($searchItem['advArr'])) {
            foreach ($searchItem['advArr'] as $k => $v) {
                if (in_array($v['searchField'], array('feeBeginDate', 'feeEndDate', 'incomeBeginDate', 'incomeEndDate'))) {
                    $$v['searchField'] = $v['value'];
                    unset($searchItem['advArr'][$k]);
                }
            }
            if (empty($searchItem['advArr'])) {
                unset($searchItem['advArr']);
            }
        }

        //项目属性权限
        $attributeLimit = $service->this_limit['项目属性权限'];
        if ($attributeLimit != "") {
            if (strpos($attributeLimit, ';;') === false && !$noLimit) {
                $searchItem['attributes'] = $attributeLimit;
            }
        }

        // 产品线权限
        $newProLineLimit = $service->this_limit['产品线'];
        if ($newProLineLimit != "") {
            if (strpos($newProLineLimit, ';;') === false && !$noLimit) {
                $searchItem['newProLines'] = $newProLineLimit;
            }
        }

        //办事处 － 全部 处理
        if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false ||
            strpos($attributeLimit, ';;') !== false || strpos($newProLineLimit, ';;') !== false || $noLimit
        ) {
            $service->getParam($searchItem); //设置前台获取的参数信息
            $rows = $service->pageBySqlId('select_defaultAndFee');
        }
        else {//如果没有选择全部，则进行权限查询并赋值
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
            //办事处经理权限
            $officeIds = $service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }
            //服务经理权限
            $manager = $service->getProvincesAndLines_d();
            if (!empty($manager)) {
                $manArr = $manager;
            }
            if (!empty($officeArr) || !empty($manArr)) {
                $service->getParam($searchItem); //设置前台获取的参数信息

                $sqlStr = "sql: and (";
                //办事处脚本构建
                if ($officeArr) {
                    $sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }
                //省份脚本构建(经理或销售区域负责人)
                if ($manArr) {
                    if ($officeArr) $sqlStr .= " or ";
                    if (!empty($proLimit)) {//判断是否有省份权限
                        $proArr = explode(",", $proLimit);
                        $proStr = "";
                        foreach ($proArr as $val) {
                            $proStr .= "'" . $val . "',";
                        }
                        $proStr = substr($proStr, 0, strlen($proStr) - 1);
                        if (!empty($manArr)) {//存在经理权限
                            foreach ($manArr as $val) {
                                if (!in_array($val['province'], $proArr)) {
                                    $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                                }
                            }
                        }
                        $sqlStr .= "(c.province in (" . $proStr . "))";
                    } else {
                        if (!empty($manArr)) {//存在经理权限
                            foreach ($manArr as $val) {
                                $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                            }
                        }
                        $sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 3);
                    }
                    $sqlStr .= " ";
                }

                $sqlStr .= " )";
                $service->searchArr['mySearchCondition'] = $sqlStr;

                $rows = $service->pageBySqlId('select_defaultAndFee');
            } else if (!empty($proLimit)) {
                $service->getParam($searchItem);
                $service->searchArr['mySearchCondition'] = "sql: and c.province in (" . util_jsonUtil::strBuild($proLimit) . ")";
                $rows = $service->pageBySqlId('select_defaultAndFee');
            } else if ($attributeLimit != "" || $newProLineLimit != "") {
                $service->getParam($searchItem); //设置前台获取的参数信息
                $rows = $service->pageBySqlId('select_defaultAndFee');
            }
        }
        $arr = array();
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        // 用于实时列表导出项目预决算,组件getListSql有点问题,所以放到$_SESSION里
        $_SESSION['engineering_project_esmproject_listSql'] = base64_encode($service->listSql);
        //扩展数据处理
        if ($rows && !$noLimit) {
            $productDao = new model_contract_contract_product();
            $conProjectDao = new model_contract_conproject_conproject();
            $conDao = new model_contract_contract_contract();
            // 如果包含决算版本字段都不为空，则加入历史数据的处理
            if ($feeBeginDate != '' && $feeEndDate != '') {
                $rows = $service->historyFeeDeal_d($rows, $feeBeginDate, $feeEndDate);
            } else {
                //试用预算，试用决算
                $rows = $service->PKFeeDeal_d($rows,1);
            }

            if ($incomeBeginDate != '' && $incomeEndDate != '') {
                $rows = $service->historyIncomeDeal_d($rows, $incomeBeginDate, $incomeEndDate);
            } else {
                // 其余信息处理
                foreach ($rows as $k => $v) {
                    // 设置项目类型，如果该值没有传入，则默认工程项目
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';
                    // 只有合同项目才加入这些处理
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //执行区域
                        $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                            'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                        $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                        //总成本
                        $conArr = $conDao->get_d($v['contractId']);
                        $revenue = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 1); //项目营收;
                        $earningsType = $v['incomeTypeName']; //收入确认方式
                        $estimates = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //项目概算
                        // 租赁合同
                        if ($conArr['contractType'] == 'HTLX-ZLHT') {
                            $days = abs($conDao->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //日期天数
                            $estimates = round(bcmul($days, bcdiv($estimates, 720, 9), 9), 2);
                        }
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//发货进度
                        $schedule = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //项目进度
                        $shipCostT = $conProjectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//发货成本;

                        // 判断关联合同是否存在不开票的开票类型,
                        $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
                        $isNoInvoiceCont = false;
                        foreach ($invoiceCodeArr as $Arrk => $Arrv){
                            if($Arrv == "HTBKP"){
                                $isNoInvoiceCont = true;
                            }
                        }

                        //项目实时状况
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney'] || $conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  ($isNoInvoiceCont)? 100 : round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//开票进度-计算
                        }
                        // 租赁合同进度
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $otherCost = $conProjectDao->getPotherCost($v['projectCode']);
                        $proportion = $conProjectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $workRate = round($proportion, 2);
                        $feeCostbx = $conProjectDao->getFeeCostBx($conArr, $workRate);//报销支付成本
                        $shipCost = $conProjectDao->getShipCost($schedule,$invoiceExe,$DeliverySchedule,$shipCostT,$estimates,$earningsType,null,$conArr); //计提发货成本;
                        $finalCost = $otherCost + $feeCostbx + $shipCost;//项目决算

                        $rows[$k]['feeAll'] = $finalCost;//总成本
                        $rows[$k]['curIncome'] = $revenue;
                        $rows[$k]['estimates'] = $estimates;
                        $projectProcess = $conProjectDao->getSchedule($v['contractId'], $conArr, $v,$isNoInvoiceCont); //项目进度;
                        $projectProcess = sprintf("%.4f",round($projectProcess,2));
                        $rows[$k]['projectProcess'] = ($isNoInvoiceCont)? 100 : $projectProcess; //项目进度;
                        $rows[$k]['shipCostT'] = $conProjectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//发货成本;
                        $rows[$k]['shipCost'] =  $shipCost;
                        $rows[$k]['feeProcess'] = round($finalCost/$estimates,2)*100; //费用进度;
                        $rows[$k]['equCost'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //存货核算
                        $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //发货进度;
                        $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //项目合同额
                        $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //项目毛利
                        $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                        $rows[$k]['projectRate'] = $workRate;
                        $rows[$k]['projectMoney'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 3); //税后项目金额
                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //状态
                    }

                    $rows[$k]['projectProcess'] = sprintf("%.2f",$rows[$k]['projectProcess']);
                }
            }

            //加密部分
            $rows = $this->sconfig->md5Rows($rows);
            //列表金额处理
            $rows = $this->filterWithoutFieldRebuild('金额权限', $rows, 'list');
        } else if($rows && isset($_POST['contractCodeFullSearch']) && $noLimit){
            $conDao = new model_contract_contract_contract();
            foreach ($rows as $k => $v) {
                // 设置项目类型，如果该值没有传入，则默认工程项目
                $pType = isset($v['pType']) ? $v['pType'] : 'esm';
                if ($pType == 'esm') {
                    $rows[$k] = $this->service->contractDeal($v);
                } else if ($v['pType'] == "pro") {
                    $conArr = $conDao->get_d($v['contractId']);
                    $rows[$k]['planEndDate'] = $conArr['outstockDate'];
                }
            }
        }
        $arr['collection'] = $rows;
        $arr['sql'] = $this->service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 项目汇总呈现
     */
    function c_showPage()
    {
        $t = isset($_GET['t']) && $_GET['t'] ? $_GET['t'] : 0;
        $isNew = isset($_GET['isNew']) && $_GET['isNew'] ? $_GET['isNew'] : 0;
        if ($t) {
            $this->assignFunc($_GET);
            $this->assign('year', date('Y', strtotime("last month")));
            $this->assign('month', date('m', strtotime("last month")));
            $this->view($isNew ? "showPage2" : "showPage");
        } else {
            $this->assign('noticeLimit', isset($this->service->this_limit['管理通知']) ?
                $this->service->this_limit['管理通知'] : "0");
            $this->assign('checkLimit', isset($this->service->this_limit['数据检查']) ?
                $this->service->this_limit['数据检查'] : "0");
            $this->view("show");
        }
    }

    /**
     * 获取项目Id
     */
    function c_getShowProjectIds()
    {
        // 参数过滤
        $searchItem = $_POST;
        $k = $searchItem['k'];
        unset($searchItem['k']);
        // 数据查询
        $this->service->getParam($searchItem);
        $rows = $this->service->list_d();

        // 项目id缓存
        $ids = array();
        $codes = array();

        // 循环取出项目id
        foreach ($rows as $v) {
            $ids[] = $v['id'];
            $codes[] = $v['projectCode'];
        }

        echo util_jsonUtil::encode(array(
            'k' => $k,
            'ids' => implode(',', $ids),
            'codes' => implode(',', $codes)
        ));
    }

    /**
     * 执行预警计数
     */
    function c_showCount()
    {
        // 参数过滤
        $searchItem = $_POST;
        $k = $searchItem['k'];
        unset($searchItem['k']);
        $t = $searchItem['t'];
        unset($searchItem['t']);

        // 数据查询
        $this->service->getParam($searchItem);
        $rows = $this->service->list_d();

        $prepare = 0; // 筹备
        $building = 0; // 在建
        $completed = 0; // 完成
        $stop = 0; // 暂停
        $closed = 0; // 已关闭
        $errorClosed = 0; // 异常关闭
        $unClose = 0; // 逾期未关闭数量
        $feeOutOfLimit = 0; // 超支数量
        $negativeExgross = 0; // 负毛利数量
        $lowExgross = 0; // 低毛利数量
        $CPIWarning = 0; // CPI预警
        $SPIWarning = 0; // SPI预警
        $statusArr = array('GCXMZT01', 'GCXMZT02', 'GCXMZT04', 'GCXMZT00');

        // 数据处理
        if (!empty($rows)) {
            // 年头时间戳、年底时间戳
            $year = date('Y');

            foreach ($rows as $v) {
                if ($t == 4) {
                    switch ($v['status']) {
                        case 'GCXMZT00' : // 逾期未关闭项目
                            $unClose++;
                            break;
                        case 'GCXMZT01' : // 筹备
                            $prepare++;
                            break;
                        case 'GCXMZT02' : // 在建
                            $building++;
                            break;
                        case 'GCXMZT05' : // 暂停
                            $stop++;
                            break;
                        case 'GCXMZT03' : // 关闭
                            // 同一年的才统计
                            if (date('Y', strtotime($v['planEndDate'])) == $year) {
                                $closed++;
                            }
                            break;
                        case 'GCXMZT06' : // 异常关闭
                            // 同一年的才统计
                            if (date('Y', strtotime($v['planEndDate'])) == $year) {
                                $errorClosed++;
                            }
                            break;
                        case 'GCXMZT04' : // 完工
                            $completed++;
                            break;
                    }
                } else {
                    // 如果项目不属于这些状态，则直接跳过
                    if (in_array($v['status'], $statusArr) === false) {
                        continue;
                    }

                    $v = $this->service->feeDeal($v);
                    $v = $this->service->contractDeal($v);

                    // 超支
                    if ($v['feeAll'] > $v['budgetAll']) {
                        $feeOutOfLimit++;
                    }

                    if ($v['exgross'] != '-') {
                        // 低毛利
                        if ($v['exgross'] < 0) {
                            $negativeExgross++;
                        }
                        if ($v['budgetExgross'] != '-') {
                            if ($v['exgross'] < $v['budgetExgross']) {
                                $lowExgross++;
                            }
                        }
                    }

                    // CPI
                    if ($v['CPI'] < 0.8 && $v['feeAll'] > 0) {
                        $CPIWarning++;
                    }
                    // SPI
                    if ($v['SPI'] < 0.8 && $v['projectProcess'] > 0) {
                        $SPIWarning++;
                    }
                }
            }
        }

        echo json_encode(array(
            'k' => $k,
            'prepare' => $prepare,
            'building' => $building,
            'completed' => $completed,
            'closed' => $closed,
            'stop' => $stop,
            'unClose' => $unClose,
            'count' => $prepare + $building + $completed + $closed + $stop + $unClose + $errorClosed,
            'feeOutOfLimit' => $feeOutOfLimit,
            'negativeExgross' => $negativeExgross,
            'lowExgross' => $lowExgross,
            'CPIWarning' => $CPIWarning,
            'SPIWarning' => $SPIWarning
        ));
    }

    /**
     * 执行预警明细
     */
    function c_showDetail()
    {
        $this->assignFunc(array_merge(array(
            'year' => '',
            'month' => '',
            'officeId' => '',
            'province' => '',
            'ids' => '',
            'projectCodes' => '',
            'chkCode' => '',
        ), $_GET));
        $this->view("showDetail");
    }

    /**
     * 预警数据列表
     */
    function c_showDetailJson()
    {
        $chkCode = isset($_POST['chkCode'])? $_POST['chkCode'] : '';
        // 参数过滤
        $searchItem = $_POST;
        $t = $searchItem['t'];
        unset($searchItem['t']);

        $rst = array();

        switch ($t) {
            case '0' :
                $searchItem['status'] = 'GCXMZT00';
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 4 :
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 5 :
                $searchItem['status'] = 'GCXMZT01';
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 6 :
                $searchItem['status'] = 'GCXMZT02';
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 7 :
                $searchItem['status'] = 'GCXMZT04';
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 8 :
                $searchItem['status'] = 'GCXMZT03';
                $searchItem['planEndYear'] = date('Y');
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 11 :
                $this->service->getParam($searchItem);
                $tmp = $this->service->list_d();
                $rst = array();
                $year = date('Y');
                foreach ($tmp as $k => $v) {
                    if ($v['status'] == 'GCXMZT03' && date("Y", strtotime($v['planEndDate'])) != $year) {

                    } else {
                        $rst[] = $v;
                    }
                }
                break;
            case 12 :
                $searchItem['status'] = 'GCXMZT05';
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            case 1 :
            case 2 :
            case 3 :
            case 9 :
            case 10 :
                $searchItem['statusArr'] = 'GCXMZT01,GCXMZT02,GCXMZT04,GCXMZT00';
                $this->service->getParam($searchItem);
                $rows = $this->service->list_d();

                // 数据处理
                if (!empty($rows)) {

                    foreach ($rows as $v) {
                        $v = $this->service->feeDeal($v);
                        $v = $this->service->contractDeal($v);

                        // 低毛利
                        if ($t == 1 && $v['budgetAll'] < $v['feeAll']) {
                            $rst[] = $v;
                        } elseif ($t == 2 && $v['exgross'] != '-' && $v['grossProfit'] < 0) {
                            $rst[] = $v;
                        } elseif ($t == 3 && $v['exgross'] != '-' && $v['budgetExgross'] != '-' && ($v['exgross'] < $v['budgetExgross'])) {
                            $rst[] = $v;
                        } elseif ($t == 9 && $v['CPI'] < 0.8 && $v['feeAll'] > 0) {
                            $rst[] = $v;
                        } elseif ($t == 10 && $v['SPI'] < 0.8 && $v['projectProcess'] > 0) {
                            $rst[] = $v;
                        }
                    }
                }
                break;
            case 99 :
                $errorIds = isset($_SESSION[$searchItem['ids']])? $_SESSION[$searchItem['ids']] : $searchItem['ids'];
                $rows = $this->service->_db->getArray("select * from oa_contract_project where id in ($errorIds);");
                if ($rows) {
                    $conProjectDao = new model_contract_conproject_conproject();
                    $conDao = new model_contract_contract_contract();
                    // 其余信息处理
                    foreach ($rows as $k => $v) {
                        //总成本
                        $conArr = $conDao->get_d($v['contractId']);
                        $earningsType = $v['earningsTypeName']; //收入确认方式
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//发货进度

                        //项目实时状况
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney'] || $conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//开票进度-计算
                        }

                        // 租赁合同进度
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $rows[$k]['proId'] = $v['id'];
                        $rows[$k]['officeName'] = $conProjectDao->getProLineName($v['contractId'], $v['proLineCode']);// 执行区域
                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //状态
                    }
                }
                $rst = $rows;
                break;
            case 100 :
                // 查看产品项目
                $ids = explode(",",$searchItem['ids']);
                foreach ($ids as $k => $v){
                    $ids[$k] = "c".$v;
                }
                $searchArr['idArr'] = $ids;
                $this->service->getParam($searchArr);
                $rows = $this->service->listBySqlId("select_defaultAndFee");
                if ($rows) {
                    $productDao = new model_contract_contract_product();
                    $conProjectDao = new model_contract_conproject_conproject();
                    $conDao = new model_contract_contract_contract();
                    // 其余信息处理
                    foreach ($rows as $k => $v) {
                        //总成本
                        $conArr = $conDao->get_d($v['contractId']);
                        $earningsType = $v['incomeTypeName']; //收入确认方式
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//发货进度

                        //项目实时状况
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney']){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//开票进度-计算
                        }

                        // 租赁合同进度
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //状态
                    }
                }
                $rst = $rows;
                break;
            case 101 :
                $ids = $searchItem['ids'];
                if( $ids == "undefined" || $ids == "" ){
                    $rst = array();
                }else{
                    $sql = "SELECT
                        id,
                        areaName,
                        contractCode,
                        contractName,
                        prinvipalName,
                        CASE state
                    WHEN 0 THEN
                        '保存'
                    WHEN 1 THEN
                        '审批中'
                    WHEN 2 THEN
                        '执行中'
                    WHEN 3 THEN
                        '已关闭'
                    WHEN 4 THEN
                        '已完成'
                    WHEN 7 THEN
                        '异常关闭'
                    END as statusName
                    FROM
                    oa_contract_contract WHERE id in ({$ids});";
                    $rst = $this->service->_db->getArray($sql);
                }
                break;
            case 102 :
                $ids = isset($_SESSION[$searchItem['ids']])? $_SESSION[$searchItem['ids']] : '';
                if( $ids == "undefined" || $ids == "" ){
                    $rst = array();
                }else{
                    $sql = "SELECT
                        id,
                        areaName,
                        contractCode,
                        contractName,
                        prinvipalName,
                        CASE state
                    WHEN 0 THEN
                        '保存'
                    WHEN 1 THEN
                        '审批中'
                    WHEN 2 THEN
                        '执行中'
                    WHEN 3 THEN
                        '已关闭'
                    WHEN 4 THEN
                        '已完成'
                    WHEN 7 THEN
                        '异常关闭'
                    END as statusName
                    FROM
                    oa_contract_contract WHERE id in ({$ids});";
                    $rst = $this->service->_db->getArray($sql);
                }
                break;
            case '103':
                $ids = isset($_SESSION[$searchItem['ids']])? $_SESSION[$searchItem['ids']] : $searchItem['ids'];
                $searchItem['ids'] = $ids;
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
                break;
            default :
                $ids = isset($_SESSION[$searchItem['ids']])? $_SESSION[$searchItem['ids']] : $searchItem['ids'];
                $searchItem['ids'] = $ids;
                $this->service->getParam($searchItem);
                $rst = $this->service->list_d();
        }
        foreach ($rst as $k => $v){
            $objId = isset($v['id'])? $v['id'] : '';
            $chkRemarkMan = $chkRemark = "";
            if(!empty($objId)){
                $chkSql = "select * from oa_objchk_remarks_records where objId = '{$objId}' and chkCode = '{$chkCode}';";
                $record = $this->service->_db->get_one($chkSql);
                if($record){
                    $chkRemarkMan = $record['createName'];
                    $chkRemark = $record['remarks'];
                }
            }

            $rst[$k]['chkRemarkMan'] = $chkRemarkMan;
            $rst[$k]['chkRemark'] = $chkRemark;
        }

        $rst = $this->sconfig->md5Rows($rst);

        //数据加入安全码
        echo util_jsonUtil::encode($rst);
    }

    /**
     * 保存驾驶舱检查操作内的备注信息
     */
    function c_saveChkRemarks(){
        $objId = isset($_POST['objId'])? $_POST['objId'] : '';
        $chkCode = isset($_POST['chkCode'])? $_POST['chkCode'] : '';
        $content = isset($_POST['content'])? $_POST['content'] : '';
        $content = util_jsonUtil::iconvUTF2GB($content);
        $backArr = array(
            "msg" => "ok",
            "recordMan" => $_SESSION['USER_NAME']
        );
        if(!empty($objId) && !empty($chkCode)){
            $chkSql = "select * from oa_objchk_remarks_records where objId = '{$objId}' and chkCode = '{$chkCode}';";
            $oldRecord = $this->service->_db->get_one($chkSql);
            $nowTime = time();
            if($oldRecord && $oldRecord['id'] > 0){// 编辑
                $backArr['recordId'] = $oldRecord['id'];
                $updateSql = "update oa_objchk_remarks_records set remarks = '{$content}',createId = '{$_SESSION['USER_ID']}',createName='{$_SESSION['USER_NAME']}',createTime='{$nowTime}' where id = '{$oldRecord['id']}';";
                $this->service->query($updateSql);
            }else{// 新增
                $insertSql = "insert into oa_objchk_remarks_records set objId = '{$objId}',chkCode = '{$chkCode}',remarks = '{$content}',createId = '{$_SESSION['USER_ID']}',createName='{$_SESSION['USER_NAME']}',createTime='{$nowTime}'";
                $result = $this->service->_db->query($insertSql);
                if (FALSE != $result) { // 获取当前新增的ID
                    $id = $this->service->_db->insert_id();
                }
                $backArr['recordId'] = $id;
            }
        }
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * 重写权限过滤数组 - 因为有一个非系统的字段要过滤
     * 字段权限控制:用于对字段的过滤 - 包括列表和表单
     * @param string $key 第一个参数是权限名称
     * @param array $rows 第二个参数是需要过滤的数组
     * @param string $type 第三个参数是过滤类型： form => 表单(默认) ，list => 列表
     * @return mixed
     */
    function filterWithoutFieldRebuild($key, $rows, $type = 'form')
    {
        //加入一个判断,如果当前登录人是服务经理、区域经理，则不过滤此内容
        $rangeDao = new model_engineering_officeinfo_range();
        if ($rangeDao->userIsManager_d($_SESSION['USER_ID'])) {
            return $rows;
        }

        //定义过滤的数组字段
        $filterArr = array(
            'earnedValue', 'estimates',
            'budgetAll', 'budgetField', 'budgetOutsourcing', 'budgetOther',
            'budgetPerson', 'budgetPeople', 'budgetDay', 'budgetEqu', 'budgetPK',
            'feeAll', 'feeField', 'feeOutsourcing', 'feeOther', 'feeFieldImport', 'feeFlights', 'feePK',
            'feePerson', 'feePeople', 'feeDay', 'feeEqu', 'contractMoney', 'invoiceMoney', 'incomeMoney'
        );
        //update by chengl 2012-02-01 加入另外模块权限判断
        $limit = $this->service->this_limit;
        $limitArr = isset($limit[$key]) ? explode(',', $limit[$key]) : array();
        if ($type == 'form') {
            //如果是项目经理,则不需要过滤此权限,但是要对合同金额做特殊处理
            $managerArr = explode(',', $rows['managerId']);
            if (in_array($_SESSION['USER_ID'], $managerArr)) {
                $rows['contractMoney'] = '******';
                $rows['contractRate'] = $rows['reserveEarnings'] = '******';
                $rows['invoiceMoney'] = $rows['invoiceProcess'] = '******';
                $rows['incomeMoney'] = $rows['incomeProcess'] = '******';
            } else {
                foreach ($rows as $k => $v) {
                    if (in_array($k, $filterArr)) {
                        if (!in_array($k, $limitArr)) {
                            $rows[$k] = '******';
                        }
                    }
                }
                if (!in_array('contractMoney', $limitArr)) {
                    $rows['contractRate'] = $rows['reserveEarnings'] = '******';
                }
                if (!in_array('invoiceMoney', $limitArr)) {
                    $rows['invoiceProcess'] = '******';
                }
                if (!in_array('incomeMoney', $limitArr)) {
                    $rows['incomeProcess'] = '******';
                }
            }
        } elseif ($type == 'list') {
            foreach ($rows as $k => $v) {
                foreach ($v as $myKey => $myVal) {
                    if (in_array($myKey, $filterArr)) {
                        if (!in_array($myKey, $limitArr)) {
                            $rows[$k][$myKey] = '******';
                        }
                    }
                }
                if (!in_array('contractMoney', $limitArr)) {
                    $rows[$k]['contractRate'] = $rows[$k]['reserveEarnings'] = '******';
                }
                if (!in_array('invoiceMoney', $limitArr)) {
                    $rows[$k]['invoiceProcess'] = '******';
                }
                if (!in_array('incomeMoney', $limitArr)) {
                    $rows[$k]['incomeProcess'] = '******';
                }
            }
        }
        return $rows;
    }

    /**
     * 在建项目列表
     */
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->pageBySqlId();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 选择表格
     */
    function c_pageSelect()
    {
        $this->view('listselect');
    }

    /**
     * 表格方法
     */
    function c_jsonSelect()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->pageBySqlId();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 新增方法
     */
    function c_toAddProject()
    {
        $object = $_GET;
        //调用策略
        $newClass = $this->service->getClass($object['contractType']);
        $initObj = new $newClass();

        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        //获取对应业务信息
        $obj = $this->service->getObjInfo_d($object, $initObj);
        $this->assignFunc($object);
        $this->assignFunc($obj);

        // 执行区域
        if ($object['contractType'] == 'GCXMYD-04' && $obj['productLine']) {
            $availableProductLine = explode(',', $obj['productLine']);
            $productLineDict = $this->getDatadicts(array('GCSCX'));
            $productLineDict = $productLineDict['GCSCX'];
            $productLineUse = array();
            foreach ($productLineDict as $k => $v) {
                if (!in_array($v['dataCode'], $availableProductLine))
                    unset($productLineDict[$k]);
                else
                    array_push($productLineUse, $v['dataCode']);
            }
            $str = '';

            foreach ($productLineDict as $k => $v) {
                $eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' .
                    $v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
                $str .= '<option ' . $eStr . ' value="' . $v ['dataCode'] . '" title="' . $v ['remark'] . '">';
                $str .= $v ['dataName'];
                $str .= '</option>';
            }
            $this->assign('productLineUse', implode(',', $productLineUse));
            $this->assign('productLine', $str);
        }else{
            //产品线
            $this->showDatadicts(array('productLine' => 'GCSCX'), null);
        }

        //外包类型
        $this->showDatadicts(array('outsourcing' => 'WBLX'), null, true);
        //类别
        $this->showDatadicts(array('category' => 'XMLB'), null, true);

        // 板块处理
        if ($obj['module']) {
            $moduleDatadict = $this->getDatadicts(array('HTBK'));
            $moduleDatadict = $moduleDatadict['HTBK'];
            $moduleCode = "";
            foreach ($moduleDatadict as $v) {
                if ($v['dataCode'] == $obj['module']) {
                    $moduleCode = $v['expand1'];
                    break;
                }
            }
            $this->assign('moduleCode', $moduleCode);
        }

        //源单类型处理
        $this->assign('contractTypeCN', $this->getDataNameByCode($object['contractType']));

        //项目属性获取
        $thisAttribute = $this->service->getAttributeCode($object['contractType']);
        $this->assign('attributeCN', $this->getDataNameByCode($thisAttribute));
        $this->assign('attribute', $thisAttribute);
        //产品线获取
        if ($obj['newProLineStr']) {
            $newProLineStr = $obj['newProLineStr'];
            //试用项目产品不允许存在不同的产品线
            if (strstr($newProLineStr, ',')) {
                $newProLineArr = explode(',', $newProLineStr);
                $newProLineStr = $newProLineArr['0'];
            }
            $this->assign('newProLine', $newProLineStr);
            $this->assign('newProLineName', $this->getDataNameByCode($newProLineStr));
        }

        $this->view($this->service->getBusinessCode($object['contractType']) . '-add', true);
    }

    /**
     * 新增方法
     */
    function c_addProject()
    {
        $this->checkSubmit();
        if ($this->service->addProject_d($_POST[$this->objName], true)) {
            msgRf('保存成功');
        }
    }

    //查看页面
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $service = $this->service;
        $obj = $service->get_d($_GET['id']);
        $obj['estimatesRate'] = ($obj['estimatesRate'] == '')? 0.00 : bcmul($obj['estimatesRate'],1,2);
        $obj['pkEstimatesRate'] = ($obj['pkEstimatesRate'] == '')? 0.00 : bcmul($obj['pkEstimatesRate'],1,2);

        //金额设置
        $obj = $service->feeDeal($obj);
        // 合同附加信息
        $obj = $service->contractDeal($obj);

        //列表数据权限处理
        $obj = $this->filterWithoutFieldRebuild('金额权限', $obj, 'form');
        $obj['projectProcess'] = sprintf("%.2f",$obj['projectProcess']);

        $this->assignFunc($obj);
        $this->display('view');
    }

    /**
     * 初始化对象
     */
    function c_toViewAudit()
    {
        $this->permCheck(); //安全校验
        $service = $this->service;
        $obj = $service->get_d($_GET['id']);

        //金额设置
        $obj = $service->feeDeal($obj);
        // 合同附加信息
        $obj = $service->contractDeal($obj);

        $this->assignFunc($obj);
        $this->display('viewaudit');
    }

    /**
     * 编辑页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);

        // 概算占比
        if($obj['estimatesRate'] == ''){
            $obj['estimatesRate'] = 0;
            $infoArr = $this->service->findAll(array("contractId" => $obj['contractId'],"contractType" => $obj['contractType']),null,"id,estimatesRate");
            if($infoArr){
                foreach ($infoArr as $arr){
                    $obj['estimatesRate'] = bcadd($obj['estimatesRate'],$arr['estimatesRate'],2);
                }
            }
            $obj['estimatesRate'] = bcsub(100,$obj['estimatesRate'],2);
            $obj['estimatesRate'] = ($obj['estimatesRate'] < 0)? 0 : $obj['estimatesRate'];
        }else{
            $obj['estimatesRate'] = bcmul($obj['estimatesRate'],1,2);
        }

        // PK成本占比
        if($obj['pkEstimatesRate'] == ''){
            $obj['pkEstimatesRate'] = 0;
            $infoArr = $this->service->findAll(array("contractId" => $obj['contractId'],"contractType" => $obj['contractType']),null,"id,pkEstimatesRate");
            if($infoArr){
                foreach ($infoArr as $arr){
                    $obj['pkEstimatesRate'] = bcadd($obj['pkEstimatesRate'],$arr['estimatesRate'],2);
                }
            }
            $obj['pkEstimatesRate'] = bcsub(100,$obj['pkEstimatesRate'],2);
            $obj['pkEstimatesRate'] = ($obj['pkEstimatesRate'] < 0)? 0 : $obj['pkEstimatesRate'];
        }else{
            $obj['pkEstimatesRate'] = bcmul($obj['pkEstimatesRate'],1,2);
        }

        //金额设置
        $obj = $this->service->feeDeal($obj);
        // 合同附加信息
        $obj = $this->service->contractDeal($obj);
        $this->assignFunc($obj);

        $RateTr = ($obj['contractType'] == 'GCXMYD-01')?
            "<tr>
                    <td class=\"form_text_left_three\">工作占比：</td>
                    <td class=\"form_text_right\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                    <td class=\"form_text_left_three\">概算占比：</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[estimatesRate]\" id=\"estimatesRate\" data-orgval='{$obj['estimatesRate']}' value='{$obj['estimatesRate']}' onblur=\"chkRateIsAvalible(this)\"/> %
                    </td>
                    <td class=\"form_text_left_three\">PK成本占比：</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[pkEstimatesRate]\" id=\"pkEstimatesRate\" data-orgval='{$obj['pkEstimatesRate']}' value='{$obj['pkEstimatesRate']}' onblur=\"chkRateIsAvalible(this,'pk')\"/> %
                    </td>
                </tr>" :
            "<tr>
                    <td class=\"form_text_left_three\">工作占比：</td>
                    <td class=\"form_text_right\" colspan=\"3\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                </tr>";
        $this->assign("rateTr",$RateTr);
        //外包类型
        $this->showDatadicts(array('outsourcing' => 'WBLX'), $obj['outsourcing'], true);

        $this->view('edit');
    }

    /**
     * 修改对象
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            $esmObj = $this->service->find(array("id"=>$object['id']),null,"contractId");
            // 重新更新关联同一源单的项目的概算
            if($esmObj){
                $this->service->updateContractMoney_d($esmObj['contractId']);
            }

            // 返回更新结果
            $skey = $this->md5Row($object['id'], null, null);
            $msg = $_POST["msg"] ? $_POST["msg"] : '编辑成功！';
            $url = "?model=engineering_project_esmproject&action=toEdit&id=" . $object['id'] . "&skey=" . $skey;
            echo "<script>parent.opener.show_page();</script>";
            msgGo($msg, $url);
        }
    }

    /**
     * 初始化对象
     */
    function c_toEditRight()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);

        if($obj['estimatesRate'] == ''){
            $obj['estimatesRate'] = 0;
            $infoArr = $this->service->findAll(array("contractId" => $obj['contractId'],"contractType" => $obj['contractType']),null,"id,estimatesRate");
            if($infoArr){
                foreach ($infoArr as $arr){
                    $obj['estimatesRate'] = bcadd($obj['estimatesRate'],$arr['estimatesRate'],2);
                }
            }
            $obj['estimatesRate'] = bcsub(100,$obj['estimatesRate'],2);
            $obj['estimatesRate'] = ($obj['estimatesRate'] < 0)? 0 : $obj['estimatesRate'];
        }else{
            $obj['estimatesRate'] = bcmul($obj['estimatesRate'],1,2);
        }

        if($obj['pkEstimatesRate'] == ''){
            $obj['pkEstimatesRate'] = 0;
            $infoArr = $this->service->findAll(array("contractId" => $obj['contractId'],"contractType" => $obj['contractType']),null,"id,pkEstimatesRate");
            if($infoArr){
                foreach ($infoArr as $arr){
                    $obj['pkEstimatesRate'] = bcadd($obj['pkEstimatesRate'],$arr['pkEstimatesRate'],2);
                }
            }
            $obj['pkEstimatesRate'] = bcsub(100,$obj['pkEstimatesRate'],2);
            $obj['pkEstimatesRate'] = ($obj['pkEstimatesRate'] < 0)? 0 : $obj['pkEstimatesRate'];
        }else{
            $obj['pkEstimatesRate'] = bcmul($obj['pkEstimatesRate'],1,2);
        }
        
        //金额设置
        $obj = $this->service->feeDeal($obj);
        // 合同附加信息
        $obj = $this->service->contractDeal($obj);

        $this->assignFunc($obj);
        //外包类型
        $this->showDatadicts(array('outsourcing' => 'WBLX'), $obj['outsourcing'], true);
        // 状态
        if ($obj['status'] == 'GCXMZT00') {
            $this->showDatadicts(array('statusStr' => 'GCXMZT'), $obj['status'], false, array('dataCodeArr' => 'GCXMZT00,GCXMZT04'));
        } else {
            $this->showDatadicts(array('statusStr' => 'GCXMZT'), $obj['status'], false);
        }
        //类别
        $this->showDatadicts(array('category' => 'XMLB'), $obj['category'], true);
        // 执行区域
        $this->showDatadicts(array('productLine' => 'GCSCX'), $obj['productLine'], true);

        $RateTr = ($obj['contractType'] == 'GCXMYD-01')?
            "<tr>
                    <td class=\"form_text_left_three\">工作占比：</td>
                    <td class=\"form_text_right\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                    <td class=\"form_text_left_three\">概算占比：</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[estimatesRate]\" id=\"estimatesRate\" data-orgval='{$obj['estimatesRate']}' value='{$obj['estimatesRate']}' onblur=\"chkRateIsAvalible(this)\"/> %
                    </td>
                    <td class=\"form_text_left_three\">PK成本占比：</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[pkEstimatesRate]\" id=\"pkEstimatesRate\" data-orgval='{$obj['pkEstimatesRate']}' value='{$obj['pkEstimatesRate']}' onblur=\"chkRateIsAvalible(this,'pk')\"/> %
                    </td>
                </tr>" :
            "<tr>
                    <td class=\"form_text_left_three\">工作占比：</td>
                    <td class=\"form_text_right\" colspan=\"3\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                </tr>";
        $this->assign("rateTr",$RateTr);

        //判断是否要进入策略部分
        $this->view('editright');
    }

    /**
     * 修改对象
     */
    function c_editRight()
    {
        if ($this->service->editRight_d($_POST[$this->objName])) {
            msgRf('编辑成功！');
        }
    }

    /**
     * 工程项目tab页 - 查看
     */
    function c_viewTab()
    {
        $this->assign('id', $_GET['id']);
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('projectCode', $obj['projectCode']);
        $this->assign('skey', $_GET['skey']);
        $this->display('viewtab');
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = true)
    {
        if ($this->service->add_d($_POST[$this->objName], $isAddInfo)) {
            msg('添加成功！');
        }
    }

    /**
     * 异步编辑方法
     */
    function c_ajaxEdit()
    {
        echo $this->service->edit_d($_POST) ? 1 : 0;
    }

    /**
     * 暂停项目
     */
    function c_toStop()
    {
        $this->permCheck(); //安全校验
        //判断是否存在未完成的变更申请或者状态报告
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('项目正在变更或者含有未提交审核的周报，请等待相关业务处理完后再执行本操作。');
            exit();
        }

        //获取默认发送人
        $mailUser = $this->service->getMailUser_d('esmprojectStop');
        $this->assignFunc($mailUser);

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('stop');
    }

    /**
     * 项目关闭
     */
    function c_stop()
    {
        if ($this->service->stop_d($_POST[$this->objName])) {
            msg('暂停成功');
        }
    }

    /**
     * 取消暂停
     */
    function c_toCancelStop()
    {
        $this->permCheck(); //安全校验
        //判断是否存在未完成的变更申请或者状态报告
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('项目正在变更或者含有未提交审核的周报，请等待相关业务处理完后再执行本操作。');
            exit();
        }

        //获取默认发送人
        $mailUser = $this->service->getMailUser_d('esmprojectCancelStop');
        $this->assignFunc($mailUser);

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('cancelstop');
    }

    /**
     * 取消暂停
     */
    function c_cancelStop()
    {
        if ($this->service->cancelStop_d($_POST[$this->objName])) {
            msg('取消成功');
        }
    }

    /**
     * 项目关闭页面
     */
    function c_toClose()
    {
        $this->permCheck(); //安全校验
        //判断是否存在未完成的变更申请或者状态报告
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('项目正在变更或者含有未提交审核的周报，请等待相关业务处理完后再执行本操作。');
            exit();
        }

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('close');
    }

    /**
     * 项目关闭
     */
    function c_close()
    {
        if ($this->service->close_d($_POST[$this->objName])) {
            msg('关闭成功');
        }
    }

    /**
     * 异步获取合同剩余工作占比 - 取消业务编号，关联id
     */
    function c_getWorkrateCanUse()
    {
        $usedWorkrate = $this->service->getAllWorkRateByType_d($_POST['contractId'], $_POST['contractType'], $_POST['productLine']);
        if (empty($usedWorkrate)) {
            echo '100.00';
        } else {
            echo bcsub(100, $usedWorkrate, 2);
        }
    }

    /**
     * 异步获取项目数
     */
    function c_getProjectNum()
    {
        echo $this->service->getProjectNum_d($_POST['contractId'], $_POST['contractType']);
    }

    /**
     * 获取权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 批量获取权限
     */
    function c_getLimitArr()
    {
        echo util_jsonUtil::encode($this->service->this_limit);
    }

    /**
     * 判断项目是否已经关闭
     */
    function c_isClose()
    {
        echo $this->service->isClose_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 判断项目号是否已经存在
     */
    function c_checkIsRepeat()
    {
        echo $this->service->find(array('projectCode' => $_POST['projectCode']), null, 'id') ? 1 : 0;
    }

    /******************* 审批完成处理 *********************/
    /**
     * 审批完成调用方法 - 项目在建审批
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 审批完成调用方法 - 项目完工审批
     */
    function c_dealAfterCompleteAudit()
    {
        $this->service->dealAfterCompleteAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /*************************************************************************************************/
    /**
     * 我的工程项目
     */
    function c_myProjectListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['findManager'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId();

        foreach ($rows as $k => $v){
            $rows[$k]['projectProcess'] = sprintf("%.2f",$v['projectProcess']);
        }

        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     *跳转我的工程项目列表
     */
    function c_myProject()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
     * 填报日志用的项目json
     */
    function c_logProjectJson()
    {
        $this->service->getParam($_POST); //设置前台获取的参数信息
        $this->service->groupBy = 'c.id';
        $this->service->searchArr['mIsCanRead'] = 1;
        $this->service->searchArr['logMember'] = $_SESSION['USER_ID'];
        $rows = $this->service->pageBySqlId('select_memberlist');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $this->service->count;
        $arr['page'] = $this->service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 工程项目编辑tab页
     */
    function c_editTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('skey', $_GET['skey']);
        $this->view('edittab');
    }

    /**
     * 工程项目管理tab页
     */
    function c_manageTab()
    {
        $this->assign('id', $_GET['id']);
        $obj = $this->service->get_d($_GET['id']);
        //获取项目的变更状态（待提交）
        $projectChange = new model_engineering_change_esmchange();
        $State = $projectChange->getState_d($obj['id']);
        $this->assign('skey', $_GET['skey']);
        $this->assign('isSubmit', $State);
        $this->assign('projectCode', $obj['projectCode']);
        $this->view('manageTab');
    }

    /************************* 审批查看 *************************************************/
    /**
     * 立项审批查看页面
     */
    function c_auditNewTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('skey', $_GET['skey']);
        $this->view('auditNewTab');
    }

    /*******************导入导出部分**********************************/
    /**
     * 导出excel
     */
    function c_exportExcel()
    {
        set_time_limit(0); // 设置不超时
        $service = $this->service;
        $service->sort = 'c.updateTime ';
        $rows = null;
        $service->setCompany(1);# 设置此列表启用公司
        # 默认指向表的别称是
        $service->setComLocal(array(
            "c" => $service->tbl_name
        ));

        // 决算版本提取
        $searchItem = $_REQUEST;
        $feeBeginDate = $feeEndDate = $incomeBeginDate = $incomeEndDate = '';
        foreach ($searchItem as $k => $v) {
            if (in_array($k, array('feeBeginDate', 'feeEndDate', 'incomeBeginDate', 'incomeEndDate'))) {
                $$k = $v;
                unset($searchItem[$k]);
            }
        }

        //办事处权限部分
        $officeArr = array();
        $sysLimit = $service->this_limit['办事处'];

        //省份权限
        $proLimit = $service->this_limit['省份权限'];

        //项目属性权限
        $attributeLimit = $service->this_limit['项目属性权限'];
        if ($attributeLimit != "") {
            if (strpos($attributeLimit, ';;') === false) {
                $searchItem['attributes'] = $attributeLimit;
            }
        }

        // 产品线权限
        $newProLineLimit = $service->this_limit['产品线'];
        if ($newProLineLimit != "") {
            if (strpos($newProLineLimit, ';;') === false) {
                $searchItem['newProLines'] = $newProLineLimit;
            }
        }

        //服务经理权限
        $manArr = array();

        //办事处 － 全部 处理
        if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false ||
            strpos($attributeLimit, ';;') !== false || strpos($newProLineLimit, ';;') !== false
        ) {
            $service->getParam($searchItem); //设置前台获取的参数信息
            $rows = $service->list_d('select_defaultAndFee');
        } else {//如果没有选择全部，则进行权限查询并赋值
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
            //办事处经理权限
            $officeIds = $service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }
            //服务经理权限
            $manager = $service->getProvincesAndLines_d();
            if (!empty($manager)) {
                $manArr = $manager;
            }
            if (!empty($officeArr) || !empty($manArr)) {
                $service->getParam($searchItem); //设置前台获取的参数信息

                $sqlStr = "sql: and (";
                //办事处脚本构建
                if ($officeArr) {
                    $sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }
                //省份脚本构建(经理或销售区域负责人)
                if ($manArr) {
                    if ($officeArr) $sqlStr .= " or ";
                    if (!empty($proLimit)) {//判断是否有省份权限
                        $proArr = explode(",", $proLimit);
                        $proStr = "";
                        foreach ($proArr as $val) {
                            $proStr .= "'" . $val . "',";
                        }
                        $proStr = substr($proStr, 0, strlen($proStr) - 1);
                        if (!empty($manArr)) {//存在经理权限
                            foreach ($manArr as $val) {
                                if (!in_array($val['province'], $proArr)) {
                                    $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                                }
                            }
                        }
                        $sqlStr .= "(c.province in (" . $proStr . "))";
                    } else {
                        if (!empty($manArr)) {//存在经理权限
                            foreach ($manArr as $val) {
                                $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                            }
                        }
                        $sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 3);
                    }
                    $sqlStr .= " ";
                }

                $sqlStr .= " )";
                $service->searchArr['mySearchCondition'] = $sqlStr;

                $rows = $service->list_d('select_defaultAndFee');
            } else if (!empty($proLimit)) {
                $service->getParam($searchItem);
                $service->searchArr['mySearchCondition'] = "sql: and c.province in (" . util_jsonUtil::strBuild($proLimit) . ")";
                $rows = $service->list_d('select_defaultAndFee');
            } else if ($attributeLimit != "" || $newProLineLimit != "") {
                $service->getParam($searchItem); //设置前台获取的参数信息
                $rows = $service->pageBySqlId('select_defaultAndFee');
            }
        }

        if (is_array($rows)) {
            // 如果包含决算版本字段都不为空，则加入历史数据的处理
            if ($feeBeginDate != '' && $feeEndDate != '') {
                $rows = $service->historyFeeDeal_d($rows, $feeBeginDate, $feeEndDate);
            } else {
                //试用预算，试用决算
                $rows = $service->PKFeeDeal_d($rows,1);
            }

            if ($incomeBeginDate != '' && $incomeEndDate != '') {
                $rows = $service->historyIncomeDeal_d($rows, $incomeBeginDate, $incomeEndDate);
            } else {
                //列表毛利率显示应该与查看页面保持一致
                $conDao = new model_contract_contract_contract();
                $conprojectDao = new model_contract_conproject_conproject();
                $productDao = new model_contract_contract_product();
                foreach ($rows as $k => $v) {
                    // 设置项目类型，如果该值没有传入，则默认工程项目
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';

                    // 只有合同项目才加入这些处理
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //执行区域
                        $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                            'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                        $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                        //总成本
                        $conArr = $conDao->get_d($v['contractId']);
                        $revenue = ($v['revenue'] != '')? $v['revenue'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 1); //项目营收;
                        $earningsType = $v['incomeTypeName']; //收入确认方式
                        $estimates = $conprojectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //项目概算
                        // 租赁合同
                        if ($conArr['contractType'] == 'HTLX-ZLHT') {
                            $days = abs($conDao->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //日期天数
                            $estimates = round(bcmul($days, bcdiv($estimates, 720, 9), 9), 2);
                        }

                        // 加上保护期过滤
                        $esmdeadlineDao = new model_engineering_baseinfo_esmdeadline();
                        $thisMonthData = $esmdeadlineDao->getCurrentSaveDateRange();
                        $spcialArr = array();
                        if(!empty($thisMonthData) && isset($thisMonthData['startDate']) && isset($thisMonthData['endDate']) && (isset($thisMonthData["inRange"]) && $thisMonthData["inRange"] == "1")){
                            $spcialArr['needFilt'] = true;
                            $spcialArr['saveDateRange'] = array($thisMonthData['startDate'],$thisMonthData['endDate']);
                        }else{
                            $spcialArr['needFilt'] = false;
                        }

                        $DeliverySchedule = ($v['deliverySchedule'] != '')? $v['deliverySchedule'] : $conprojectDao->getFHJD($v,$spcialArr);//发货进度
                        //项目实时状况
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney']){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//开票进度-计算
                        }

                        // 租赁合同进度
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $shipCostT = $conprojectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//发货成本;
                        $txaRate = $conprojectDao->getTxaRate($conArr); //综合税点
                        $schedule = ($v['proschedule'] != '')? $v['proschedule'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 0, $spcialArr); //项目进度
                        $otherCost = $conprojectDao->getPotherCost($v['projectCode']);
                        $proportion = $conprojectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $workRate = round($proportion, 2);
                        $feeCostbx = $conprojectDao->getFeeCostBx($conArr, $workRate);//报销支付成本
                        $shipCost = ($v['shipCost'] != '')? $v['shipCost'] : $conprojectDao->getShipCost($schedule,$invoiceExe,$DeliverySchedule,$shipCostT,$estimates,$earningsType,null,$conArr); //计提发货成本;
                        $finalCost = $shipCost + $feeCostbx + $otherCost;//项目决算

                        $rows[$k]['feeAll'] = $finalCost;
                        $rows[$k]['curIncome'] = $revenue;
                        $rows[$k]['estimates'] = $estimates;
                        $rows[$k]['projectProcess'] = $projectProcess = ($v['projectProcess'] != '')? $v['projectProcess'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 0, $spcialArr); //项目进度;
                        $rows[$k]['shipCostT'] = $shipCostT;
                        $rows[$k]['shipCost'] =  $shipCost;
                        $rows[$k]['feeProcess'] = round($finalCost/$estimates,2)*100; //费用进度;
                        $rows[$k]['equCost'] = $conprojectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //存货核算
                        $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //发货进度;
                        $rows[$k]['reserveEarnings'] = $conprojectDao->reserveEarnings($conArr, $txaRate, $schedule, $v, $earningsType);
                        $rows[$k]['projectMoneyWithTax'] = $conprojectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //项目合同额
//                        $rows[$k]['grossProfit'] = $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 2) - $otherCost - $feeCostbx - $shipCost; //项目毛利
                        $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //项目毛利
                        $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                        $rows[$k]['projectRate'] = $conprojectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $rows[$k]['exgross'] = round(1 - ($finalCost / $revenue), 4) * 100; //当前毛利率
                        $rows[$k]['feeOther'] = $otherCost;//外包其他
                        $rows[$k]['budgetAll'] = $estimates;//总预算
                        $rows[$k]['earningsType'] = $earningsType;//收入确认方式
                        $rows[$k]['feeCostbx'] = sprintf("%.2f",$feeCostbx);
                        $rows[$k]['statusName'] = $conprojectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //状态
                    }
                }
            }
            //列表金额处理 -- 如果存在区域权限，则要做金额权限验证
            $rows = $this->filterWithoutFieldRebuild('金额权限', $rows, 'list');
        }
        if (isset($_GET['excelType'])) {
            model_engineering_util_esmexcelutil::exportProject07($rows);
        } else {
            model_engineering_util_esmexcelutil::exportProject($rows);
        }
    }

    /**
     * 导出汇总
     */
    function c_exportSummary()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            set_time_limit(0); // 设置不超时
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            $rows = $this->service->_db->getArray($sql);

            //扩展数据处理
            if ($rows) {
                //试用预算，试用决算
                $rows = $this->service->PKFeeDeal_d($rows,1);

                // 其余信息处理
                foreach ($rows as $k => $v) {
                    // 设置项目类型，如果该值没有传入，则默认工程项目
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';

                    // 只有合同项目才加入这些处理
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //收入值处理
                        $rows[$k]['curIncome'] = $this->service->getCurIncomeByPro($v);
                        //成本（总决算）处理
                        $rows[$k]['feeAll'] = $this->service->getFeeAllByPro($v);
                    }
                }
                //列表金额处理
                $rows = $this->filterWithoutFieldRebuild('金额权限', $rows, 'list');
                $colCode = $_GET['colCode'];
                $colName = $_GET['colName'];
                $head = array_combine(explode(',', $colCode), explode(',', $colName));
                model_finance_common_financeExcelUtil::export2ExcelUtil($head, $rows, '项目汇总表', array(
                    'lineRate', 'projectRate', 'contractRate', 'projectProcess', 'budgetExgross', 'exgross', 'feeAllProcess'
                ));
            } else {
                echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
            }
        } else {
            echo util_jsonUtil::iconvGB2UTF('查询后再导出数据');
        }
    }

    /**
     * 导入excel
     */
    function c_toExcelIn()
    {
        $this->display('excelin');
    }

    /**
     * 导入excel
     */
    function c_excelIn()
    {
        ini_set("memory_limit", "1024M");
        $actionType = $_POST['actionType'];
        switch ($actionType) {
            case 0:
                $resultArr = $this->service->addExecelData_d();
                break;
            case 1:
                $resultArr = $this->service->updateProjectFee_d();
                break;
            case 2:
                $resultArr = $this->service->updateProjectInfo_d();
                break;
            case 3:
                $resultArr = $this->service->updateProjectOtherFee_d();
                break;
            case 4:
                $resultArr = $this->service->updateProjectOtherBudget_d();
                break;
            case 5:
                $resultArr = $this->service->updateProjectOtherBudget_d('budgetOutsourcing');//外包预决算
                break;
            case 6:
                $resultArr = $this->service->updateProjectPersonBudget_d();//人力预决算
                break;
            case 7:
                $resultArr = $this->service->updateProjectFeeEqu_d();      //设备决算
                break;
            case 8:
                $resultArr = $this->service->updateProjectShipCost_d(0);      //发货成本
                break;
            case 9:
                $resultArr = $this->service->updateProjectShipCost_d(1);      //其他成本
                break;
            case 10:
                $resultArr = $this->service->conprojectExcel();      //合同项目导入
                break;
            default:
                $resultArr = array();
        }

        $title = '项目导入结果列表';
        $head = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }
    /*******************导入导出部分**********************************/

    /**
     * 获取项目的范围id
     */
    function c_getRangeId()
    {
        echo $this->service->getRangeId_d($_POST['projectId']);
    }

    /**
     * 完成结束时间
     */
    function c_toFinish()
    {
        $row = $this->service->get_d($_GET['id']);
        $this->assignFunc($row);

        // 邮件通知处理
        $rangeInfo = $this->service->getRangeInfo_d($_GET['id']);

        // 合并人员数组
        $userIdArray = array();
        if ($rangeInfo['mainManagerId']) $userIdArray[] = $rangeInfo['mainManagerId'];
        if ($rangeInfo['managerId']) $userIdArray[] = $rangeInfo['managerId'];
        if ($rangeInfo['headId']) $userIdArray[] = $rangeInfo['headId'];
        if ($rangeInfo['assistantId']) $userIdArray[] = $rangeInfo['assistantId'];

        $userIdArray = array_unique(explode(',', implode(',', $userIdArray)));
        $userIds = implode(',', $userIdArray);
        $this->assign('TO_ID', $userIds);

        // 合并人员数组
        $userNameArray = array();
        if ($rangeInfo['mainManager']) $userNameArray[] = $rangeInfo['mainManager'];
        if ($rangeInfo['managerName']) $userNameArray[] = $rangeInfo['managerName'];
        if ($rangeInfo['head']) $userNameArray[] = $rangeInfo['head'];
        if ($rangeInfo['assistant']) $userNameArray[] = $rangeInfo['assistant'];

        $userNameArray = array_unique(explode(',', implode(',', $userNameArray)));
        $userNameS = implode(',', $userNameArray);
        $this->assign('TO_NAME', $userNameS);

        $this->view('finish', true);
    }

    /**
     * 完成项目
     */
    function c_finish()
    {
        $this->checkSubmit();
        if ($this->service->finish_d($_POST)) {
            msg('提交成功');
        }
    }

    /**
     * 更新项目
     */
    function c_toUpdateProject()
    {
        //年部分
        $thisYear = $year = date("Y");
        $yearStr = NULL;
        while (2005 < $year) {
            $yearStr .= "<option value='$year'>" . $year . "年</option>";
            $year--;
        }
        $this->assign('yearStr', $yearStr);

        //月部分
        $thisMonth = date('n');
        $month = 12;
        $monthStr = NULL;
        while ($month > 0) {
            if ($thisMonth == $month)
                $monthStr .= "<option value='$month' selected='selected'>" . $month . "月</option>";
            else
                $monthStr .= "<option value='$month'>" . $month . "月</option>";
            $month--;
        }
        $this->assign('monthStr', $monthStr);
        //当前日期
        $this->assign('currentDate', day_date);
        $this->assign('thisYear', $thisYear);
        $this->assign('thisMonth', $thisMonth);
        $this->showDatadicts(array('status' => 'GCXMZT'));

        $showDataModify = ($this->service->this_limit["产品项目-数据操作"] == 1)? "style=''" : "style='display:none;'";
        $this->assign('showDataModify', $showDataModify);

        $this->view('updateProject');
    }

    /**
     * 更新毛利率
     */
    function c_updateExgross()
    {
        $rs = $this->service->updateExgross_d($_POST);
        if ($rs) {
            echo util_jsonUtil::iconvGB2UTF($rs);
        } else {
            echo 0;
        }
    }

    /**
     * 测试
     */
    function c_test()
    {
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $datas['rows'] = $this->service->page_d();
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $this->service->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $datas['statusArr'] = $this->showDatadicts(array('status' => 'GCXMZT'), isset($datas['status']) ? $datas['status'] : '', false, false, true);
        $datas['productLineArr'] = $this->showDatadicts(array('productLine' => 'GCSCX'), isset($datas['productLine']) ? $datas['productLine'] : '', false, false, true);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'test', $datas);
    }

    /**
     * 项目提交验证
     */
    function c_submitCheck()
    {
        echo util_jsonUtil::encode($this->service->submitCheck_d($_POST['id']));
    }

    /**
     * 获取项目信息
     */
    function c_ajaxGetProject()
    {
        $obj = $this->service->get_d($_REQUEST['id']);
        $obj = $this->service->feeDeal($obj);
        $obj = $this->service->contractDeal($obj);
        echo util_jsonUtil::encode($obj);
    }

    /**
     * 开关项目 - 管理员用
     */
    function c_toOpenClose()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('openclose');
    }

    /**
     * 开关项目
     * @throws Exception
     */
    function c_openClose()
    {
        $this->checkSubmit();
        if ($this->service->openClose_d($_POST[$this->objName])) {
            msg('提交成功');
        }
    }

    /****************************** PK项目相关方法 ******************************/
    /**
     * PK项目信息
     */
    function c_toPKList()
    {
        $this->assign('projectId', $_GET['id']);
        $this->view('pk-list');
    }

    /**
     * PK项目信息Json
     */
    function c_PKInfoJson()
    {
        $PKInfo = $this->service->getPKInfo_d($_POST['projectId'],null,1);
        //数据加入安全码
        $PKInfo = $this->sconfig->md5Rows($PKInfo);
        echo util_jsonUtil::encode($PKInfo);
    }

    /**
     * 验证试用项目实施周期及预算是否超出原PK申请时的设置
     */
    function c_isPKOverproof()
    {
        $service = $this->service;

        $object = $service->get_d($_POST['id']);
        //调用策略
        $newClass = $this->service->getClass($object['contractType']);
        $initObj = new $newClass();
        //获取对应业务信息
        $robj = $service->getRawObjInfo_d($object, $initObj);
        if (strtotime($object['planBeginDate']) < strtotime($robj['beginDate']) || strtotime($object['planEndDate']) > strtotime($robj['closeDate'])) {
            echo 1;    //实施周期不合法
        } elseif ($object['budgetAll'] > $robj['affirmMoney']) {
            echo 2; //预算不合法
        }
    }

    /**
     * 验证项目是否为PK项目
     */
    function c_isPK()
    {
        echo $this->service->isPK_d($_POST['projectId']) ? 1 : 0;
    }

    /***************************** 免录日志处理 ***************************/
    /**
     * 检验项目是否可以做免录日志处理
     */
    function c_checkCanWithoutLog()
    {
        echo util_jsonUtil::iconvGB2UTF($this->service->checkCanWithoutLog_d($_POST['projectId']));
    }

    /**
     * 更新合同相关字段
     */
    function c_updateProjectFields()
    {
        echo $this->service->updateProjectFields_d($_POST['projectCode']);
    }

    /**
     *
     * 获取当前登陆人所在项目的待审核日志
     */
    function c_getWaitAuditLog()
    {
        echo $this->service->getWaitAuditLog_d();
    }

    /**
     *
     * 获取当前登陆人所在项目的待审核日志
     */
    function c_getWaitSubReport()
    {
        echo $this->service->getWaitSubReport_d();
    }

    /**
     * ajax 检查同一合同的各个项目占比累计是否超过100%
     */
    function c_ajaxChkEstimateRate(){
        $enterRate = isset($_POST['rate'])? $_POST['rate'] : 0;
        $contratId = isset($_POST['contratId'])? $_POST['contratId'] : 0;
        $contractType = isset($_POST['contractType'])? $_POST['contractType'] : 0;
        $projectId = isset($_POST['projectId'])? $_POST['projectId'] : 0;
        $productLine = isset($_POST['productLine'])? $_POST['productLine'] : '';
        $chkType = isset($_POST['chkType'])? $_POST['chkType'] : '';
        $chkArr = ($chkType == 'pk')? $this->service->findAll(" contractId = '{$contratId}' and contractType = '{$contractType}' and newProLine = '{$productLine}' and id <> '{$projectId}'",null,"id,estimatesRate,pkEstimatesRate") :
            $this->service->findAll(" contractId = '{$contratId}' and contractType = '{$contractType}' and id <> '{$projectId}'",null,"id,estimatesRate,pkEstimatesRate");
        $existRateSum = 0;
        if($chkArr){
            foreach ($chkArr as $val){
                $existRateSum = ($chkType == "pk")? bcadd($existRateSum,$val['pkEstimatesRate'],3) : bcadd($existRateSum,$val['estimatesRate'],3);
            }
        }

        $existRateSum = bcadd($existRateSum,$enterRate,2);
        if($existRateSum > 100){
            echo "fail";
        }else{
            echo "ok";
        }
    }

    /**
     * 异步获取合同剩余PK成本占比
     */
    function c_getPkrateCanUse(){
        $enterRate = isset($_POST['rate'])? $_POST['rate'] : 0;
        $contratId = isset($_POST['contratId'])? $_POST['contratId'] : 0;
        $contractType = isset($_POST['contractType'])? $_POST['contractType'] : 0;
        $projectId = isset($_POST['projectId'])? $_POST['projectId'] : 0;
        $productLine = isset($_POST['productLine'])? $_POST['productLine'] : '';
        $chkType = isset($_POST['chkType'])? $_POST['chkType'] : '';
        $chkArr = $this->service->findAll(" contractId = '{$contratId}' and contractType = '{$contractType}' and newProLine = '{$productLine}' and id <> '{$projectId}'",null,"id,estimatesRate,pkEstimatesRate");
        $existRateSum = 0;
        if($chkArr){
            foreach ($chkArr as $val){
                $existRateSum = ($chkType == "pk")? bcadd($existRateSum,$val['pkEstimatesRate'],3) : bcadd($existRateSum,$val['estimatesRate'],3);
            }
        }
        $PkrateCanUse = round(bcsub(100,$existRateSum,3),2);
        echo $PkrateCanUse;
    }
}