<?php

/**
 * @author Show
 * @Date 2011��11��24�� ������ 17:20:15
 * @version 1.0
 * @description:������Ŀ(oa_esm_project)���Ʋ�
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
     * ��ת��������Ŀ
     */
    function c_page()
    {
        $this->assign('editProjectLimit', $this->service->this_limit['��Ŀ�޸�Ȩ��']);
        $this->assign('openCloseLimit', $this->service->this_limit['��Ŀ�����ر�Ȩ��']);
        $this->assignFunc($this->service->getVersionInfo_d());
        $this->view('list');
    }

    /**
     * �ڽ���Ŀ�б�
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
            $service->setCompany(1);# ���ô��б����ù�˾
        }

        # Ĭ��ָ���ı����
        $service->setComLocal(array(
            "c" => $service->tbl_name
        ));

        //���´�Ȩ�޲���
        $officeArr = array();
        $sysLimit = $service->this_limit['���´�'];

        //ʡ��Ȩ��
        $proLimit = $service->this_limit['ʡ��Ȩ��'];

        //������Ȩ��
        $manArr = array();

        // ����汾��ȡ
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

        //��Ŀ����Ȩ��
        $attributeLimit = $service->this_limit['��Ŀ����Ȩ��'];
        if ($attributeLimit != "") {
            if (strpos($attributeLimit, ';;') === false && !$noLimit) {
                $searchItem['attributes'] = $attributeLimit;
            }
        }

        // ��Ʒ��Ȩ��
        $newProLineLimit = $service->this_limit['��Ʒ��'];
        if ($newProLineLimit != "") {
            if (strpos($newProLineLimit, ';;') === false && !$noLimit) {
                $searchItem['newProLines'] = $newProLineLimit;
            }
        }

        //���´� �� ȫ�� ����
        if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false ||
            strpos($attributeLimit, ';;') !== false || strpos($newProLineLimit, ';;') !== false || $noLimit
        ) {
            $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ
            $rows = $service->pageBySqlId('select_defaultAndFee');
        }
        else {//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
            //���´�����Ȩ��
            $officeIds = $service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }
            //������Ȩ��
            $manager = $service->getProvincesAndLines_d();
            if (!empty($manager)) {
                $manArr = $manager;
            }
            if (!empty($officeArr) || !empty($manArr)) {
                $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ

                $sqlStr = "sql: and (";
                //���´��ű�����
                if ($officeArr) {
                    $sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }
                //ʡ�ݽű�����(�����������������)
                if ($manArr) {
                    if ($officeArr) $sqlStr .= " or ";
                    if (!empty($proLimit)) {//�ж��Ƿ���ʡ��Ȩ��
                        $proArr = explode(",", $proLimit);
                        $proStr = "";
                        foreach ($proArr as $val) {
                            $proStr .= "'" . $val . "',";
                        }
                        $proStr = substr($proStr, 0, strlen($proStr) - 1);
                        if (!empty($manArr)) {//���ھ���Ȩ��
                            foreach ($manArr as $val) {
                                if (!in_array($val['province'], $proArr)) {
                                    $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                                }
                            }
                        }
                        $sqlStr .= "(c.province in (" . $proStr . "))";
                    } else {
                        if (!empty($manArr)) {//���ھ���Ȩ��
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
                $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ
                $rows = $service->pageBySqlId('select_defaultAndFee');
            }
        }
        $arr = array();
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        // ����ʵʱ�б�����ĿԤ����,���getListSql�е�����,���Էŵ�$_SESSION��
        $_SESSION['engineering_project_esmproject_listSql'] = base64_encode($service->listSql);
        //��չ���ݴ���
        if ($rows && !$noLimit) {
            $productDao = new model_contract_contract_product();
            $conProjectDao = new model_contract_conproject_conproject();
            $conDao = new model_contract_contract_contract();
            // �����������汾�ֶζ���Ϊ�գ��������ʷ���ݵĴ���
            if ($feeBeginDate != '' && $feeEndDate != '') {
                $rows = $service->historyFeeDeal_d($rows, $feeBeginDate, $feeEndDate);
            } else {
                //����Ԥ�㣬���þ���
                $rows = $service->PKFeeDeal_d($rows,1);
            }

            if ($incomeBeginDate != '' && $incomeEndDate != '') {
                $rows = $service->historyIncomeDeal_d($rows, $incomeBeginDate, $incomeEndDate);
            } else {
                // ������Ϣ����
                foreach ($rows as $k => $v) {
                    // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';
                    // ֻ�к�ͬ��Ŀ�ż�����Щ����
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //ִ������
                        $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                            'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                        $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                        //�ܳɱ�
                        $conArr = $conDao->get_d($v['contractId']);
                        $revenue = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 1); //��ĿӪ��;
                        $earningsType = $v['incomeTypeName']; //����ȷ�Ϸ�ʽ
                        $estimates = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //��Ŀ����
                        // ���޺�ͬ
                        if ($conArr['contractType'] == 'HTLX-ZLHT') {
                            $days = abs($conDao->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //��������
                            $estimates = round(bcmul($days, bcdiv($estimates, 720, 9), 9), 2);
                        }
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//��������
                        $schedule = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //��Ŀ����
                        $shipCostT = $conProjectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//�����ɱ�;

                        // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
                        $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
                        $isNoInvoiceCont = false;
                        foreach ($invoiceCodeArr as $Arrk => $Arrv){
                            if($Arrv == "HTBKP"){
                                $isNoInvoiceCont = true;
                            }
                        }

                        //��Ŀʵʱ״��
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney'] || $conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  ($isNoInvoiceCont)? 100 : round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����-����
                        }
                        // ���޺�ͬ����
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $otherCost = $conProjectDao->getPotherCost($v['projectCode']);
                        $proportion = $conProjectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $workRate = round($proportion, 2);
                        $feeCostbx = $conProjectDao->getFeeCostBx($conArr, $workRate);//����֧���ɱ�
                        $shipCost = $conProjectDao->getShipCost($schedule,$invoiceExe,$DeliverySchedule,$shipCostT,$estimates,$earningsType,null,$conArr); //���ᷢ���ɱ�;
                        $finalCost = $otherCost + $feeCostbx + $shipCost;//��Ŀ����

                        $rows[$k]['feeAll'] = $finalCost;//�ܳɱ�
                        $rows[$k]['curIncome'] = $revenue;
                        $rows[$k]['estimates'] = $estimates;
                        $projectProcess = $conProjectDao->getSchedule($v['contractId'], $conArr, $v,$isNoInvoiceCont); //��Ŀ����;
                        $projectProcess = sprintf("%.4f",round($projectProcess,2));
                        $rows[$k]['projectProcess'] = ($isNoInvoiceCont)? 100 : $projectProcess; //��Ŀ����;
                        $rows[$k]['shipCostT'] = $conProjectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//�����ɱ�;
                        $rows[$k]['shipCost'] =  $shipCost;
                        $rows[$k]['feeProcess'] = round($finalCost/$estimates,2)*100; //���ý���;
                        $rows[$k]['equCost'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //�������
                        $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //��������;
                        $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //��Ŀ��ͬ��
                        $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //��Ŀë��
                        $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                        $rows[$k]['projectRate'] = $workRate;
                        $rows[$k]['projectMoney'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 3); //˰����Ŀ���
                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //״̬
                    }

                    $rows[$k]['projectProcess'] = sprintf("%.2f",$rows[$k]['projectProcess']);
                }
            }

            //���ܲ���
            $rows = $this->sconfig->md5Rows($rows);
            //�б����
            $rows = $this->filterWithoutFieldRebuild('���Ȩ��', $rows, 'list');
        } else if($rows && isset($_POST['contractCodeFullSearch']) && $noLimit){
            $conDao = new model_contract_contract_contract();
            foreach ($rows as $k => $v) {
                // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
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
     * ��Ŀ���ܳ���
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
            $this->assign('noticeLimit', isset($this->service->this_limit['����֪ͨ']) ?
                $this->service->this_limit['����֪ͨ'] : "0");
            $this->assign('checkLimit', isset($this->service->this_limit['���ݼ��']) ?
                $this->service->this_limit['���ݼ��'] : "0");
            $this->view("show");
        }
    }

    /**
     * ��ȡ��ĿId
     */
    function c_getShowProjectIds()
    {
        // ��������
        $searchItem = $_POST;
        $k = $searchItem['k'];
        unset($searchItem['k']);
        // ���ݲ�ѯ
        $this->service->getParam($searchItem);
        $rows = $this->service->list_d();

        // ��Ŀid����
        $ids = array();
        $codes = array();

        // ѭ��ȡ����Ŀid
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
     * ִ��Ԥ������
     */
    function c_showCount()
    {
        // ��������
        $searchItem = $_POST;
        $k = $searchItem['k'];
        unset($searchItem['k']);
        $t = $searchItem['t'];
        unset($searchItem['t']);

        // ���ݲ�ѯ
        $this->service->getParam($searchItem);
        $rows = $this->service->list_d();

        $prepare = 0; // �ﱸ
        $building = 0; // �ڽ�
        $completed = 0; // ���
        $stop = 0; // ��ͣ
        $closed = 0; // �ѹر�
        $errorClosed = 0; // �쳣�ر�
        $unClose = 0; // ����δ�ر�����
        $feeOutOfLimit = 0; // ��֧����
        $negativeExgross = 0; // ��ë������
        $lowExgross = 0; // ��ë������
        $CPIWarning = 0; // CPIԤ��
        $SPIWarning = 0; // SPIԤ��
        $statusArr = array('GCXMZT01', 'GCXMZT02', 'GCXMZT04', 'GCXMZT00');

        // ���ݴ���
        if (!empty($rows)) {
            // ��ͷʱ��������ʱ���
            $year = date('Y');

            foreach ($rows as $v) {
                if ($t == 4) {
                    switch ($v['status']) {
                        case 'GCXMZT00' : // ����δ�ر���Ŀ
                            $unClose++;
                            break;
                        case 'GCXMZT01' : // �ﱸ
                            $prepare++;
                            break;
                        case 'GCXMZT02' : // �ڽ�
                            $building++;
                            break;
                        case 'GCXMZT05' : // ��ͣ
                            $stop++;
                            break;
                        case 'GCXMZT03' : // �ر�
                            // ͬһ��Ĳ�ͳ��
                            if (date('Y', strtotime($v['planEndDate'])) == $year) {
                                $closed++;
                            }
                            break;
                        case 'GCXMZT06' : // �쳣�ر�
                            // ͬһ��Ĳ�ͳ��
                            if (date('Y', strtotime($v['planEndDate'])) == $year) {
                                $errorClosed++;
                            }
                            break;
                        case 'GCXMZT04' : // �깤
                            $completed++;
                            break;
                    }
                } else {
                    // �����Ŀ��������Щ״̬����ֱ������
                    if (in_array($v['status'], $statusArr) === false) {
                        continue;
                    }

                    $v = $this->service->feeDeal($v);
                    $v = $this->service->contractDeal($v);

                    // ��֧
                    if ($v['feeAll'] > $v['budgetAll']) {
                        $feeOutOfLimit++;
                    }

                    if ($v['exgross'] != '-') {
                        // ��ë��
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
     * ִ��Ԥ����ϸ
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
     * Ԥ�������б�
     */
    function c_showDetailJson()
    {
        $chkCode = isset($_POST['chkCode'])? $_POST['chkCode'] : '';
        // ��������
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

                // ���ݴ���
                if (!empty($rows)) {

                    foreach ($rows as $v) {
                        $v = $this->service->feeDeal($v);
                        $v = $this->service->contractDeal($v);

                        // ��ë��
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
                    // ������Ϣ����
                    foreach ($rows as $k => $v) {
                        //�ܳɱ�
                        $conArr = $conDao->get_d($v['contractId']);
                        $earningsType = $v['earningsTypeName']; //����ȷ�Ϸ�ʽ
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//��������

                        //��Ŀʵʱ״��
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney'] || $conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����-����
                        }

                        // ���޺�ͬ����
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $rows[$k]['proId'] = $v['id'];
                        $rows[$k]['officeName'] = $conProjectDao->getProLineName($v['contractId'], $v['proLineCode']);// ִ������
                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //״̬
                    }
                }
                $rst = $rows;
                break;
            case 100 :
                // �鿴��Ʒ��Ŀ
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
                    // ������Ϣ����
                    foreach ($rows as $k => $v) {
                        //�ܳɱ�
                        $conArr = $conDao->get_d($v['contractId']);
                        $earningsType = $v['incomeTypeName']; //����ȷ�Ϸ�ʽ
                        $DeliverySchedule = $conProjectDao->getFHJD($v);//��������

                        //��Ŀʵʱ״��
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney']){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����-����
                        }

                        // ���޺�ͬ����
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //״̬
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
                        '����'
                    WHEN 1 THEN
                        '������'
                    WHEN 2 THEN
                        'ִ����'
                    WHEN 3 THEN
                        '�ѹر�'
                    WHEN 4 THEN
                        '�����'
                    WHEN 7 THEN
                        '�쳣�ر�'
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
                        '����'
                    WHEN 1 THEN
                        '������'
                    WHEN 2 THEN
                        'ִ����'
                    WHEN 3 THEN
                        '�ѹر�'
                    WHEN 4 THEN
                        '�����'
                    WHEN 7 THEN
                        '�쳣�ر�'
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

        //���ݼ��밲ȫ��
        echo util_jsonUtil::encode($rst);
    }

    /**
     * �����ʻ�ռ������ڵı�ע��Ϣ
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
            if($oldRecord && $oldRecord['id'] > 0){// �༭
                $backArr['recordId'] = $oldRecord['id'];
                $updateSql = "update oa_objchk_remarks_records set remarks = '{$content}',createId = '{$_SESSION['USER_ID']}',createName='{$_SESSION['USER_NAME']}',createTime='{$nowTime}' where id = '{$oldRecord['id']}';";
                $this->service->query($updateSql);
            }else{// ����
                $insertSql = "insert into oa_objchk_remarks_records set objId = '{$objId}',chkCode = '{$chkCode}',remarks = '{$content}',createId = '{$_SESSION['USER_ID']}',createName='{$_SESSION['USER_NAME']}',createTime='{$nowTime}'";
                $result = $this->service->_db->query($insertSql);
                if (FALSE != $result) { // ��ȡ��ǰ������ID
                    $id = $this->service->_db->insert_id();
                }
                $backArr['recordId'] = $id;
            }
        }
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * ��дȨ�޹������� - ��Ϊ��һ����ϵͳ���ֶ�Ҫ����
     * �ֶ�Ȩ�޿���:���ڶ��ֶεĹ��� - �����б�ͱ�
     * @param string $key ��һ��������Ȩ������
     * @param array $rows �ڶ�����������Ҫ���˵�����
     * @param string $type �����������ǹ������ͣ� form => ��(Ĭ��) ��list => �б�
     * @return mixed
     */
    function filterWithoutFieldRebuild($key, $rows, $type = 'form')
    {
        //����һ���ж�,�����ǰ��¼���Ƿ������������򲻹��˴�����
        $rangeDao = new model_engineering_officeinfo_range();
        if ($rangeDao->userIsManager_d($_SESSION['USER_ID'])) {
            return $rows;
        }

        //������˵������ֶ�
        $filterArr = array(
            'earnedValue', 'estimates',
            'budgetAll', 'budgetField', 'budgetOutsourcing', 'budgetOther',
            'budgetPerson', 'budgetPeople', 'budgetDay', 'budgetEqu', 'budgetPK',
            'feeAll', 'feeField', 'feeOutsourcing', 'feeOther', 'feeFieldImport', 'feeFlights', 'feePK',
            'feePerson', 'feePeople', 'feeDay', 'feeEqu', 'contractMoney', 'invoiceMoney', 'incomeMoney'
        );
        //update by chengl 2012-02-01 ��������ģ��Ȩ���ж�
        $limit = $this->service->this_limit;
        $limitArr = isset($limit[$key]) ? explode(',', $limit[$key]) : array();
        if ($type == 'form') {
            //�������Ŀ����,����Ҫ���˴�Ȩ��,����Ҫ�Ժ�ͬ��������⴦��
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
     * �ڽ���Ŀ�б�
     */
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->pageBySqlId();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * ѡ����
     */
    function c_pageSelect()
    {
        $this->view('listselect');
    }

    /**
     * ��񷽷�
     */
    function c_jsonSelect()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->pageBySqlId();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * ��������
     */
    function c_toAddProject()
    {
        $object = $_GET;
        //���ò���
        $newClass = $this->service->getClass($object['contractType']);
        $initObj = new $newClass();

        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        //��ȡ��Ӧҵ����Ϣ
        $obj = $this->service->getObjInfo_d($object, $initObj);
        $this->assignFunc($object);
        $this->assignFunc($obj);

        // ִ������
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
            //��Ʒ��
            $this->showDatadicts(array('productLine' => 'GCSCX'), null);
        }

        //�������
        $this->showDatadicts(array('outsourcing' => 'WBLX'), null, true);
        //���
        $this->showDatadicts(array('category' => 'XMLB'), null, true);

        // ��鴦��
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

        //Դ�����ʹ���
        $this->assign('contractTypeCN', $this->getDataNameByCode($object['contractType']));

        //��Ŀ���Ի�ȡ
        $thisAttribute = $this->service->getAttributeCode($object['contractType']);
        $this->assign('attributeCN', $this->getDataNameByCode($thisAttribute));
        $this->assign('attribute', $thisAttribute);
        //��Ʒ�߻�ȡ
        if ($obj['newProLineStr']) {
            $newProLineStr = $obj['newProLineStr'];
            //������Ŀ��Ʒ��������ڲ�ͬ�Ĳ�Ʒ��
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
     * ��������
     */
    function c_addProject()
    {
        $this->checkSubmit();
        if ($this->service->addProject_d($_POST[$this->objName], true)) {
            msgRf('����ɹ�');
        }
    }

    //�鿴ҳ��
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $service = $this->service;
        $obj = $service->get_d($_GET['id']);
        $obj['estimatesRate'] = ($obj['estimatesRate'] == '')? 0.00 : bcmul($obj['estimatesRate'],1,2);
        $obj['pkEstimatesRate'] = ($obj['pkEstimatesRate'] == '')? 0.00 : bcmul($obj['pkEstimatesRate'],1,2);

        //�������
        $obj = $service->feeDeal($obj);
        // ��ͬ������Ϣ
        $obj = $service->contractDeal($obj);

        //�б�����Ȩ�޴���
        $obj = $this->filterWithoutFieldRebuild('���Ȩ��', $obj, 'form');
        $obj['projectProcess'] = sprintf("%.2f",$obj['projectProcess']);

        $this->assignFunc($obj);
        $this->display('view');
    }

    /**
     * ��ʼ������
     */
    function c_toViewAudit()
    {
        $this->permCheck(); //��ȫУ��
        $service = $this->service;
        $obj = $service->get_d($_GET['id']);

        //�������
        $obj = $service->feeDeal($obj);
        // ��ͬ������Ϣ
        $obj = $service->contractDeal($obj);

        $this->assignFunc($obj);
        $this->display('viewaudit');
    }

    /**
     * �༭ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);

        // ����ռ��
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

        // PK�ɱ�ռ��
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

        //�������
        $obj = $this->service->feeDeal($obj);
        // ��ͬ������Ϣ
        $obj = $this->service->contractDeal($obj);
        $this->assignFunc($obj);

        $RateTr = ($obj['contractType'] == 'GCXMYD-01')?
            "<tr>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[estimatesRate]\" id=\"estimatesRate\" data-orgval='{$obj['estimatesRate']}' value='{$obj['estimatesRate']}' onblur=\"chkRateIsAvalible(this)\"/> %
                    </td>
                    <td class=\"form_text_left_three\">PK�ɱ�ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[pkEstimatesRate]\" id=\"pkEstimatesRate\" data-orgval='{$obj['pkEstimatesRate']}' value='{$obj['pkEstimatesRate']}' onblur=\"chkRateIsAvalible(this,'pk')\"/> %
                    </td>
                </tr>" :
            "<tr>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\" colspan=\"3\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                </tr>";
        $this->assign("rateTr",$RateTr);
        //�������
        $this->showDatadicts(array('outsourcing' => 'WBLX'), $obj['outsourcing'], true);

        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            $esmObj = $this->service->find(array("id"=>$object['id']),null,"contractId");
            // ���¸��¹���ͬһԴ������Ŀ�ĸ���
            if($esmObj){
                $this->service->updateContractMoney_d($esmObj['contractId']);
            }

            // ���ظ��½��
            $skey = $this->md5Row($object['id'], null, null);
            $msg = $_POST["msg"] ? $_POST["msg"] : '�༭�ɹ���';
            $url = "?model=engineering_project_esmproject&action=toEdit&id=" . $object['id'] . "&skey=" . $skey;
            echo "<script>parent.opener.show_page();</script>";
            msgGo($msg, $url);
        }
    }

    /**
     * ��ʼ������
     */
    function c_toEditRight()
    {
        $this->permCheck(); //��ȫУ��
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
        
        //�������
        $obj = $this->service->feeDeal($obj);
        // ��ͬ������Ϣ
        $obj = $this->service->contractDeal($obj);

        $this->assignFunc($obj);
        //�������
        $this->showDatadicts(array('outsourcing' => 'WBLX'), $obj['outsourcing'], true);
        // ״̬
        if ($obj['status'] == 'GCXMZT00') {
            $this->showDatadicts(array('statusStr' => 'GCXMZT'), $obj['status'], false, array('dataCodeArr' => 'GCXMZT00,GCXMZT04'));
        } else {
            $this->showDatadicts(array('statusStr' => 'GCXMZT'), $obj['status'], false);
        }
        //���
        $this->showDatadicts(array('category' => 'XMLB'), $obj['category'], true);
        // ִ������
        $this->showDatadicts(array('productLine' => 'GCSCX'), $obj['productLine'], true);

        $RateTr = ($obj['contractType'] == 'GCXMYD-01')?
            "<tr>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[estimatesRate]\" id=\"estimatesRate\" data-orgval='{$obj['estimatesRate']}' value='{$obj['estimatesRate']}' onblur=\"chkRateIsAvalible(this)\"/> %
                    </td>
                    <td class=\"form_text_left_three\">PK�ɱ�ռ�ȣ�</td>
                    <td class=\"form_text_right\">
                        <input type=\"text\" class=\"txtmin\" name=\"esmproject[pkEstimatesRate]\" id=\"pkEstimatesRate\" data-orgval='{$obj['pkEstimatesRate']}' value='{$obj['pkEstimatesRate']}' onblur=\"chkRateIsAvalible(this,'pk')\"/> %
                    </td>
                </tr>" :
            "<tr>
                    <td class=\"form_text_left_three\">����ռ�ȣ�</td>
                    <td class=\"form_text_right\" colspan=\"3\">
                        {$obj['workRate']}&nbsp;%
                    </td>
                </tr>";
        $this->assign("rateTr",$RateTr);

        //�ж��Ƿ�Ҫ������Բ���
        $this->view('editright');
    }

    /**
     * �޸Ķ���
     */
    function c_editRight()
    {
        if ($this->service->editRight_d($_POST[$this->objName])) {
            msgRf('�༭�ɹ���');
        }
    }

    /**
     * ������Ŀtabҳ - �鿴
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
     * �����������
     */
    function c_add($isAddInfo = true)
    {
        if ($this->service->add_d($_POST[$this->objName], $isAddInfo)) {
            msg('��ӳɹ���');
        }
    }

    /**
     * �첽�༭����
     */
    function c_ajaxEdit()
    {
        echo $this->service->edit_d($_POST) ? 1 : 0;
    }

    /**
     * ��ͣ��Ŀ
     */
    function c_toStop()
    {
        $this->permCheck(); //��ȫУ��
        //�ж��Ƿ����δ��ɵı���������״̬����
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('��Ŀ���ڱ�����ߺ���δ�ύ��˵��ܱ�����ȴ����ҵ���������ִ�б�������');
            exit();
        }

        //��ȡĬ�Ϸ�����
        $mailUser = $this->service->getMailUser_d('esmprojectStop');
        $this->assignFunc($mailUser);

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('stop');
    }

    /**
     * ��Ŀ�ر�
     */
    function c_stop()
    {
        if ($this->service->stop_d($_POST[$this->objName])) {
            msg('��ͣ�ɹ�');
        }
    }

    /**
     * ȡ����ͣ
     */
    function c_toCancelStop()
    {
        $this->permCheck(); //��ȫУ��
        //�ж��Ƿ����δ��ɵı���������״̬����
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('��Ŀ���ڱ�����ߺ���δ�ύ��˵��ܱ�����ȴ����ҵ���������ִ�б�������');
            exit();
        }

        //��ȡĬ�Ϸ�����
        $mailUser = $this->service->getMailUser_d('esmprojectCancelStop');
        $this->assignFunc($mailUser);

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('cancelstop');
    }

    /**
     * ȡ����ͣ
     */
    function c_cancelStop()
    {
        if ($this->service->cancelStop_d($_POST[$this->objName])) {
            msg('ȡ���ɹ�');
        }
    }

    /**
     * ��Ŀ�ر�ҳ��
     */
    function c_toClose()
    {
        $this->permCheck(); //��ȫУ��
        //�ж��Ƿ����δ��ɵı���������״̬����
        if ($this->service->isNotDone_d($_GET['id'])) {
            msg('��Ŀ���ڱ�����ߺ���δ�ύ��˵��ܱ�����ȴ����ҵ���������ִ�б�������');
            exit();
        }

        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);
        $this->view('close');
    }

    /**
     * ��Ŀ�ر�
     */
    function c_close()
    {
        if ($this->service->close_d($_POST[$this->objName])) {
            msg('�رճɹ�');
        }
    }

    /**
     * �첽��ȡ��ͬʣ�๤��ռ�� - ȡ��ҵ���ţ�����id
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
     * �첽��ȡ��Ŀ��
     */
    function c_getProjectNum()
    {
        echo $this->service->getProjectNum_d($_POST['contractId'], $_POST['contractType']);
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ������ȡȨ��
     */
    function c_getLimitArr()
    {
        echo util_jsonUtil::encode($this->service->this_limit);
    }

    /**
     * �ж���Ŀ�Ƿ��Ѿ��ر�
     */
    function c_isClose()
    {
        echo $this->service->isClose_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �ж���Ŀ���Ƿ��Ѿ�����
     */
    function c_checkIsRepeat()
    {
        echo $this->service->find(array('projectCode' => $_POST['projectCode']), null, 'id') ? 1 : 0;
    }

    /******************* ������ɴ��� *********************/
    /**
     * ������ɵ��÷��� - ��Ŀ�ڽ�����
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ������ɵ��÷��� - ��Ŀ�깤����
     */
    function c_dealAfterCompleteAudit()
    {
        $this->service->dealAfterCompleteAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /*************************************************************************************************/
    /**
     * �ҵĹ�����Ŀ
     */
    function c_myProjectListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     *��ת�ҵĹ�����Ŀ�б�
     */
    function c_myProject()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
     * ���־�õ���Ŀjson
     */
    function c_logProjectJson()
    {
        $this->service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * ������Ŀ�༭tabҳ
     */
    function c_editTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('skey', $_GET['skey']);
        $this->view('edittab');
    }

    /**
     * ������Ŀ����tabҳ
     */
    function c_manageTab()
    {
        $this->assign('id', $_GET['id']);
        $obj = $this->service->get_d($_GET['id']);
        //��ȡ��Ŀ�ı��״̬�����ύ��
        $projectChange = new model_engineering_change_esmchange();
        $State = $projectChange->getState_d($obj['id']);
        $this->assign('skey', $_GET['skey']);
        $this->assign('isSubmit', $State);
        $this->assign('projectCode', $obj['projectCode']);
        $this->view('manageTab');
    }

    /************************* �����鿴 *************************************************/
    /**
     * ���������鿴ҳ��
     */
    function c_auditNewTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('skey', $_GET['skey']);
        $this->view('auditNewTab');
    }

    /*******************���뵼������**********************************/
    /**
     * ����excel
     */
    function c_exportExcel()
    {
        set_time_limit(0); // ���ò���ʱ
        $service = $this->service;
        $service->sort = 'c.updateTime ';
        $rows = null;
        $service->setCompany(1);# ���ô��б����ù�˾
        # Ĭ��ָ���ı����
        $service->setComLocal(array(
            "c" => $service->tbl_name
        ));

        // ����汾��ȡ
        $searchItem = $_REQUEST;
        $feeBeginDate = $feeEndDate = $incomeBeginDate = $incomeEndDate = '';
        foreach ($searchItem as $k => $v) {
            if (in_array($k, array('feeBeginDate', 'feeEndDate', 'incomeBeginDate', 'incomeEndDate'))) {
                $$k = $v;
                unset($searchItem[$k]);
            }
        }

        //���´�Ȩ�޲���
        $officeArr = array();
        $sysLimit = $service->this_limit['���´�'];

        //ʡ��Ȩ��
        $proLimit = $service->this_limit['ʡ��Ȩ��'];

        //��Ŀ����Ȩ��
        $attributeLimit = $service->this_limit['��Ŀ����Ȩ��'];
        if ($attributeLimit != "") {
            if (strpos($attributeLimit, ';;') === false) {
                $searchItem['attributes'] = $attributeLimit;
            }
        }

        // ��Ʒ��Ȩ��
        $newProLineLimit = $service->this_limit['��Ʒ��'];
        if ($newProLineLimit != "") {
            if (strpos($newProLineLimit, ';;') === false) {
                $searchItem['newProLines'] = $newProLineLimit;
            }
        }

        //������Ȩ��
        $manArr = array();

        //���´� �� ȫ�� ����
        if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false ||
            strpos($attributeLimit, ';;') !== false || strpos($newProLineLimit, ';;') !== false
        ) {
            $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ
            $rows = $service->list_d('select_defaultAndFee');
        } else {//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
            //���´�����Ȩ��
            $officeIds = $service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }
            //������Ȩ��
            $manager = $service->getProvincesAndLines_d();
            if (!empty($manager)) {
                $manArr = $manager;
            }
            if (!empty($officeArr) || !empty($manArr)) {
                $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ

                $sqlStr = "sql: and (";
                //���´��ű�����
                if ($officeArr) {
                    $sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }
                //ʡ�ݽű�����(�����������������)
                if ($manArr) {
                    if ($officeArr) $sqlStr .= " or ";
                    if (!empty($proLimit)) {//�ж��Ƿ���ʡ��Ȩ��
                        $proArr = explode(",", $proLimit);
                        $proStr = "";
                        foreach ($proArr as $val) {
                            $proStr .= "'" . $val . "',";
                        }
                        $proStr = substr($proStr, 0, strlen($proStr) - 1);
                        if (!empty($manArr)) {//���ھ���Ȩ��
                            foreach ($manArr as $val) {
                                if (!in_array($val['province'], $proArr)) {
                                    $sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
                                }
                            }
                        }
                        $sqlStr .= "(c.province in (" . $proStr . "))";
                    } else {
                        if (!empty($manArr)) {//���ھ���Ȩ��
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
                $service->getParam($searchItem); //����ǰ̨��ȡ�Ĳ�����Ϣ
                $rows = $service->pageBySqlId('select_defaultAndFee');
            }
        }

        if (is_array($rows)) {
            // �����������汾�ֶζ���Ϊ�գ��������ʷ���ݵĴ���
            if ($feeBeginDate != '' && $feeEndDate != '') {
                $rows = $service->historyFeeDeal_d($rows, $feeBeginDate, $feeEndDate);
            } else {
                //����Ԥ�㣬���þ���
                $rows = $service->PKFeeDeal_d($rows,1);
            }

            if ($incomeBeginDate != '' && $incomeEndDate != '') {
                $rows = $service->historyIncomeDeal_d($rows, $incomeBeginDate, $incomeEndDate);
            } else {
                //�б�ë������ʾӦ����鿴ҳ�汣��һ��
                $conDao = new model_contract_contract_contract();
                $conprojectDao = new model_contract_conproject_conproject();
                $productDao = new model_contract_contract_product();
                foreach ($rows as $k => $v) {
                    // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';

                    // ֻ�к�ͬ��Ŀ�ż�����Щ����
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //ִ������
                        $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                            'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                        $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                        //�ܳɱ�
                        $conArr = $conDao->get_d($v['contractId']);
                        $revenue = ($v['revenue'] != '')? $v['revenue'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 1); //��ĿӪ��;
                        $earningsType = $v['incomeTypeName']; //����ȷ�Ϸ�ʽ
                        $estimates = $conprojectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //��Ŀ����
                        // ���޺�ͬ
                        if ($conArr['contractType'] == 'HTLX-ZLHT') {
                            $days = abs($conDao->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //��������
                            $estimates = round(bcmul($days, bcdiv($estimates, 720, 9), 9), 2);
                        }

                        // ���ϱ����ڹ���
                        $esmdeadlineDao = new model_engineering_baseinfo_esmdeadline();
                        $thisMonthData = $esmdeadlineDao->getCurrentSaveDateRange();
                        $spcialArr = array();
                        if(!empty($thisMonthData) && isset($thisMonthData['startDate']) && isset($thisMonthData['endDate']) && (isset($thisMonthData["inRange"]) && $thisMonthData["inRange"] == "1")){
                            $spcialArr['needFilt'] = true;
                            $spcialArr['saveDateRange'] = array($thisMonthData['startDate'],$thisMonthData['endDate']);
                        }else{
                            $spcialArr['needFilt'] = false;
                        }

                        $DeliverySchedule = ($v['deliverySchedule'] != '')? $v['deliverySchedule'] : $conprojectDao->getFHJD($v,$spcialArr);//��������
                        //��Ŀʵʱ״��
                        if($conArr['contractMoney'] === $conArr['uninvoiceMoney']){
                            $invoiceExe = 100;
                        }else{
                            $invoiceExe =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����-����
                        }

                        // ���޺�ͬ����
                        $date1 = strtotime($conArr['beginDate']);
                        $date2 = strtotime($conArr['endDate']);
                        $date3 = strtotime(date("Y-m-d"));
                        $allDays = ($date2 - $date1) / 86400 + 1;
                        $finishDays = ($date3 - $date1) / 86400 + 1;
                        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                        $shipCostT = $conprojectDao->getFinalCost($v['projectCode'],$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,2);//�����ɱ�;
                        $txaRate = $conprojectDao->getTxaRate($conArr); //�ۺ�˰��
                        $schedule = ($v['proschedule'] != '')? $v['proschedule'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 0, $spcialArr); //��Ŀ����
                        $otherCost = $conprojectDao->getPotherCost($v['projectCode']);
                        $proportion = $conprojectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $workRate = round($proportion, 2);
                        $feeCostbx = $conprojectDao->getFeeCostBx($conArr, $workRate);//����֧���ɱ�
                        $shipCost = ($v['shipCost'] != '')? $v['shipCost'] : $conprojectDao->getShipCost($schedule,$invoiceExe,$DeliverySchedule,$shipCostT,$estimates,$earningsType,null,$conArr); //���ᷢ���ɱ�;
                        $finalCost = $shipCost + $feeCostbx + $otherCost;//��Ŀ����

                        $rows[$k]['feeAll'] = $finalCost;
                        $rows[$k]['curIncome'] = $revenue;
                        $rows[$k]['estimates'] = $estimates;
                        $rows[$k]['projectProcess'] = $projectProcess = ($v['projectProcess'] != '')? $v['projectProcess'] : $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 0, $spcialArr); //��Ŀ����;
                        $rows[$k]['shipCostT'] = $shipCostT;
                        $rows[$k]['shipCost'] =  $shipCost;
                        $rows[$k]['feeProcess'] = round($finalCost/$estimates,2)*100; //���ý���;
                        $rows[$k]['equCost'] = $conprojectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //�������
                        $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //��������;
                        $rows[$k]['reserveEarnings'] = $conprojectDao->reserveEarnings($conArr, $txaRate, $schedule, $v, $earningsType);
                        $rows[$k]['projectMoneyWithTax'] = $conprojectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //��Ŀ��ͬ��
//                        $rows[$k]['grossProfit'] = $conprojectDao->getSchedule($v['contractId'], $conArr, $v, 2) - $otherCost - $feeCostbx - $shipCost; //��Ŀë��
                        $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //��Ŀë��
                        $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                        $rows[$k]['projectRate'] = $conprojectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                        $rows[$k]['exgross'] = round(1 - ($finalCost / $revenue), 4) * 100; //��ǰë����
                        $rows[$k]['feeOther'] = $otherCost;//�������
                        $rows[$k]['budgetAll'] = $estimates;//��Ԥ��
                        $rows[$k]['earningsType'] = $earningsType;//����ȷ�Ϸ�ʽ
                        $rows[$k]['feeCostbx'] = sprintf("%.2f",$feeCostbx);
                        $rows[$k]['statusName'] = $conprojectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //״̬
                    }
                }
            }
            //�б���� -- �����������Ȩ�ޣ���Ҫ�����Ȩ����֤
            $rows = $this->filterWithoutFieldRebuild('���Ȩ��', $rows, 'list');
        }
        if (isset($_GET['excelType'])) {
            model_engineering_util_esmexcelutil::exportProject07($rows);
        } else {
            model_engineering_util_esmexcelutil::exportProject($rows);
        }
    }

    /**
     * ��������
     */
    function c_exportSummary()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            set_time_limit(0); // ���ò���ʱ
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            $rows = $this->service->_db->getArray($sql);

            //��չ���ݴ���
            if ($rows) {
                //����Ԥ�㣬���þ���
                $rows = $this->service->PKFeeDeal_d($rows,1);

                // ������Ϣ����
                foreach ($rows as $k => $v) {
                    // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
                    $pType = isset($v['pType']) ? $v['pType'] : 'esm';

                    // ֻ�к�ͬ��Ŀ�ż�����Щ����
                    if ($pType == 'esm') {
                        $rows[$k] = $this->service->contractDeal($v);
                    } else if ($v['pType'] == "pro") {
                        //����ֵ����
                        $rows[$k]['curIncome'] = $this->service->getCurIncomeByPro($v);
                        //�ɱ����ܾ��㣩����
                        $rows[$k]['feeAll'] = $this->service->getFeeAllByPro($v);
                    }
                }
                //�б����
                $rows = $this->filterWithoutFieldRebuild('���Ȩ��', $rows, 'list');
                $colCode = $_GET['colCode'];
                $colName = $_GET['colName'];
                $head = array_combine(explode(',', $colCode), explode(',', $colName));
                model_finance_common_financeExcelUtil::export2ExcelUtil($head, $rows, '��Ŀ���ܱ�', array(
                    'lineRate', 'projectRate', 'contractRate', 'projectProcess', 'budgetExgross', 'exgross', 'feeAllProcess'
                ));
            } else {
                echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
            }
        } else {
            echo util_jsonUtil::iconvGB2UTF('��ѯ���ٵ�������');
        }
    }

    /**
     * ����excel
     */
    function c_toExcelIn()
    {
        $this->display('excelin');
    }

    /**
     * ����excel
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
                $resultArr = $this->service->updateProjectOtherBudget_d('budgetOutsourcing');//���Ԥ����
                break;
            case 6:
                $resultArr = $this->service->updateProjectPersonBudget_d();//����Ԥ����
                break;
            case 7:
                $resultArr = $this->service->updateProjectFeeEqu_d();      //�豸����
                break;
            case 8:
                $resultArr = $this->service->updateProjectShipCost_d(0);      //�����ɱ�
                break;
            case 9:
                $resultArr = $this->service->updateProjectShipCost_d(1);      //�����ɱ�
                break;
            case 10:
                $resultArr = $this->service->conprojectExcel();      //��ͬ��Ŀ����
                break;
            default:
                $resultArr = array();
        }

        $title = '��Ŀ�������б�';
        $head = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $head);
    }
    /*******************���뵼������**********************************/

    /**
     * ��ȡ��Ŀ�ķ�Χid
     */
    function c_getRangeId()
    {
        echo $this->service->getRangeId_d($_POST['projectId']);
    }

    /**
     * ��ɽ���ʱ��
     */
    function c_toFinish()
    {
        $row = $this->service->get_d($_GET['id']);
        $this->assignFunc($row);

        // �ʼ�֪ͨ����
        $rangeInfo = $this->service->getRangeInfo_d($_GET['id']);

        // �ϲ���Ա����
        $userIdArray = array();
        if ($rangeInfo['mainManagerId']) $userIdArray[] = $rangeInfo['mainManagerId'];
        if ($rangeInfo['managerId']) $userIdArray[] = $rangeInfo['managerId'];
        if ($rangeInfo['headId']) $userIdArray[] = $rangeInfo['headId'];
        if ($rangeInfo['assistantId']) $userIdArray[] = $rangeInfo['assistantId'];

        $userIdArray = array_unique(explode(',', implode(',', $userIdArray)));
        $userIds = implode(',', $userIdArray);
        $this->assign('TO_ID', $userIds);

        // �ϲ���Ա����
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
     * �����Ŀ
     */
    function c_finish()
    {
        $this->checkSubmit();
        if ($this->service->finish_d($_POST)) {
            msg('�ύ�ɹ�');
        }
    }

    /**
     * ������Ŀ
     */
    function c_toUpdateProject()
    {
        //�겿��
        $thisYear = $year = date("Y");
        $yearStr = NULL;
        while (2005 < $year) {
            $yearStr .= "<option value='$year'>" . $year . "��</option>";
            $year--;
        }
        $this->assign('yearStr', $yearStr);

        //�²���
        $thisMonth = date('n');
        $month = 12;
        $monthStr = NULL;
        while ($month > 0) {
            if ($thisMonth == $month)
                $monthStr .= "<option value='$month' selected='selected'>" . $month . "��</option>";
            else
                $monthStr .= "<option value='$month'>" . $month . "��</option>";
            $month--;
        }
        $this->assign('monthStr', $monthStr);
        //��ǰ����
        $this->assign('currentDate', day_date);
        $this->assign('thisYear', $thisYear);
        $this->assign('thisMonth', $thisMonth);
        $this->showDatadicts(array('status' => 'GCXMZT'));

        $showDataModify = ($this->service->this_limit["��Ʒ��Ŀ-���ݲ���"] == 1)? "style=''" : "style='display:none;'";
        $this->assign('showDataModify', $showDataModify);

        $this->view('updateProject');
    }

    /**
     * ����ë����
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
     * ����
     */
    function c_test()
    {
        //����
        $datas = $this->service->getParam($_REQUEST);
        $datas['rows'] = $this->service->page_d();
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $this->service->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $datas['statusArr'] = $this->showDatadicts(array('status' => 'GCXMZT'), isset($datas['status']) ? $datas['status'] : '', false, false, true);
        $datas['productLineArr'] = $this->showDatadicts(array('productLine' => 'GCSCX'), isset($datas['productLine']) ? $datas['productLine'] : '', false, false, true);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'test', $datas);
    }

    /**
     * ��Ŀ�ύ��֤
     */
    function c_submitCheck()
    {
        echo util_jsonUtil::encode($this->service->submitCheck_d($_POST['id']));
    }

    /**
     * ��ȡ��Ŀ��Ϣ
     */
    function c_ajaxGetProject()
    {
        $obj = $this->service->get_d($_REQUEST['id']);
        $obj = $this->service->feeDeal($obj);
        $obj = $this->service->contractDeal($obj);
        echo util_jsonUtil::encode($obj);
    }

    /**
     * ������Ŀ - ����Ա��
     */
    function c_toOpenClose()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('openclose');
    }

    /**
     * ������Ŀ
     * @throws Exception
     */
    function c_openClose()
    {
        $this->checkSubmit();
        if ($this->service->openClose_d($_POST[$this->objName])) {
            msg('�ύ�ɹ�');
        }
    }

    /****************************** PK��Ŀ��ط��� ******************************/
    /**
     * PK��Ŀ��Ϣ
     */
    function c_toPKList()
    {
        $this->assign('projectId', $_GET['id']);
        $this->view('pk-list');
    }

    /**
     * PK��Ŀ��ϢJson
     */
    function c_PKInfoJson()
    {
        $PKInfo = $this->service->getPKInfo_d($_POST['projectId'],null,1);
        //���ݼ��밲ȫ��
        $PKInfo = $this->sconfig->md5Rows($PKInfo);
        echo util_jsonUtil::encode($PKInfo);
    }

    /**
     * ��֤������Ŀʵʩ���ڼ�Ԥ���Ƿ񳬳�ԭPK����ʱ������
     */
    function c_isPKOverproof()
    {
        $service = $this->service;

        $object = $service->get_d($_POST['id']);
        //���ò���
        $newClass = $this->service->getClass($object['contractType']);
        $initObj = new $newClass();
        //��ȡ��Ӧҵ����Ϣ
        $robj = $service->getRawObjInfo_d($object, $initObj);
        if (strtotime($object['planBeginDate']) < strtotime($robj['beginDate']) || strtotime($object['planEndDate']) > strtotime($robj['closeDate'])) {
            echo 1;    //ʵʩ���ڲ��Ϸ�
        } elseif ($object['budgetAll'] > $robj['affirmMoney']) {
            echo 2; //Ԥ�㲻�Ϸ�
        }
    }

    /**
     * ��֤��Ŀ�Ƿ�ΪPK��Ŀ
     */
    function c_isPK()
    {
        echo $this->service->isPK_d($_POST['projectId']) ? 1 : 0;
    }

    /***************************** ��¼��־���� ***************************/
    /**
     * ������Ŀ�Ƿ��������¼��־����
     */
    function c_checkCanWithoutLog()
    {
        echo util_jsonUtil::iconvGB2UTF($this->service->checkCanWithoutLog_d($_POST['projectId']));
    }

    /**
     * ���º�ͬ����ֶ�
     */
    function c_updateProjectFields()
    {
        echo $this->service->updateProjectFields_d($_POST['projectCode']);
    }

    /**
     *
     * ��ȡ��ǰ��½��������Ŀ�Ĵ������־
     */
    function c_getWaitAuditLog()
    {
        echo $this->service->getWaitAuditLog_d();
    }

    /**
     *
     * ��ȡ��ǰ��½��������Ŀ�Ĵ������־
     */
    function c_getWaitSubReport()
    {
        echo $this->service->getWaitSubReport_d();
    }

    /**
     * ajax ���ͬһ��ͬ�ĸ�����Ŀռ���ۼ��Ƿ񳬹�100%
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
     * �첽��ȡ��ͬʣ��PK�ɱ�ռ��
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