<?php

class controller_contractTool_contractTool_contractTool extends controller_base_action
{

    function __construct() {
        $this->objName = "contractTool";
        $this->objPath = "contractTool_contractTool";
        //		$this->lang="contract";//语言包模块
        parent :: __construct();
    }

    /**
     * 导图
     */
    function c_toMap() {
        $this->view("map");
    }

    function c_toMap2() {
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'map2');
    }

    function c_xx() {
        $this->view("xx");
    }

    /**
     * 筹建中的合同
     */
    function c_buildContract() {
        //数据
        $datas = $this->service->getParam($_REQUEST);
        if (!isset ($datas['finishStatus'])) {
            $datas['finishStatus'] = '3';
//            $datas['finishStatus'] = '0';
        }
        if(!empty($_GET['finishStatus'])){
        	$datas['finishStatus'] = $_GET['finishStatus'];
        }
        if(!empty($_GET['isIncome'])){
            $datas['isIncome'] = $_GET['isIncome'];
        }

        $conDao = new model_contract_contract_contract();
        $date = $_GET['date'];
        $conditions = $_REQUEST;

        if ($date && strlen($date) == 7) {
            $conditions['ExaYearMonth'] = $_GET['date'];
            unset($conditions['date']);
        } else if ($date && strlen($date) == 4) {
            $conditions['ExaYear'] = $_GET['date'];
            unset($conditions['date']);
        }
        $conDao->sort = 'c.ExaDTOne';
        $conDao->asc = true;

        $datas['rows'] = $conDao->buildContract_d($conditions);
        if ($datas['rows']) {
            //验收条款
            $checkaccept = new model_contract_checkaccept_checkaccept();
            $receiptplan = new model_contract_contract_receiptplan();
            foreach ($datas['rows'] as $key => $val) {
                $contractId = array('contractId' => $val['id']);
                $checkacceptRows = $checkaccept->findAll($contractId);
                $datas['rows'][$key]['checkaccept'] = $checkacceptRows;
                //获取收款条款
                $receiptplanRow = $receiptplan->findAll($contractId);
                $datas['rows'][$key]['receiptplan'] = $receiptplanRow;
            }
        }
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'buildlist', $datas);
    }

    /**
     * 验收合同
     */
    function c_accepting() {
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->buildContract_d($_REQUEST);
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'acceptinglist', $datas);
    }

    /**
     * 查看合同
     */
    function c_viewContract() {
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->viewContract_d($_GET['id']);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'view', $datas);
    }

    /**
     * 合同交付
     */
    function c_deliveryContract() {
        //数据
        if (!isset($_REQUEST['state'])) {
            $_REQUEST['state'] = '2';
        }
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $date = $_GET['date'];
        $conditions = $_REQUEST;
        if ($date && strlen($date) == 7) {
            $conditions['ExaYearMonth'] = $_GET['date'];
            unset($conditions['date']);
        } else if ($date && strlen($date) == 4) {
            $conditions['ExaYear'] = $_GET['date'];
            unset($conditions['date']);
        }
        if ($conditions['state'] == 'all') {
            unset($conditions['state']);
        }
        $datas['rows'] = $conDao->deliveryContract_d($conditions);
        $outplan = new model_stock_outplan_outplan();
        $esmproject = new model_engineering_project_esmproject();
        $ship = new model_stock_outplan_ship();
        foreach ($datas['rows'] as $key => $val) {
            //交付部确认预计交付日期-发货数据
            $docId = array('docId' => $val['id'], 'docType' => 'oa_contract_contract');
            $outplanRow = $outplan->findAll($docId);
            if ($outplanRow) {
                $delayDetail = "";
                foreach ($outplanRow as $k => $v) {
                    $outplanRow[$k]['shipDate'] = $this->service->get_table_fields('oa_stock_ship', "planId='" . $v['id'] . "'", 'shipDate');
                    $delayDetail .= $v['delayDetail'] . "<br/>";
                }
            }
            $datas['rows'][$key]['outplanRow'] = $outplanRow;
            //交付部确认预计交付日期-工程项目数据
            $contractId = array('contractId' => $val['id'], 'contractType' => 'GCXMYD-01');
            $esmprojectRow = $esmproject->findAll($contractId);
            $datas['rows'][$key]['esmprojectRow'] = $esmprojectRow;
            //判断计划是否超期
            //			$datas['rows'][$key]['isExceed'] = $this->service->isExceed_d($datas['rows'][$key],$outplanRow,$esmprojectRow);
            $datas['rows'][$key]['delayDetail'] = $delayDetail;
            $delayDetail = "";
        }

        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);

        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'deliveryList', $datas);
    }


    /**
     * 待验收合同
     */
    function c_waitingAccept() {
        if (!isset($_REQUEST['checkStatus'])) {
            $_REQUEST['checkStatus'] = "未验收";
            $_REQUEST['confirmStatus'] = "已确认";
        }
        if($_REQUEST['checkStatus'] == '1'){
        	$_REQUEST['checkStatus'] = "未验收";
            unset($_REQUEST['confirmStatus']);
            unset($_GET['date']);
        }
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $checkaccept = new model_contract_checkaccept_checkaccept();
        $conDao = new model_contract_contract_contract();
        $date = $_GET['date'];
        $conditions = $_REQUEST;
        if ($date && strlen($date) == 7) {
            $conditions['ExaYearMonth'] = $_GET['date'];
            unset($conditions['date']);
        } else if ($date && strlen($date) == 4) {
            $conditions['ExaYear'] = $_GET['date'];
            unset($conditions['date']);
        }
        if ($conditions['checkStatus'] == 'all') {
            unset($conditions['checkStatus']);
        }
        if (!empty($conditions['contractName'])) {
            $conId = $conDao->find(array("contractName" => $conditions['contractName']), null, "id");
            $conditions['contractId'] = $conId['id'];
            echo $conditions['contractId'];
            unset($conditions['contractName']);
        }

        $checkaccept->getParam($conditions);
        $checkaccept->sort = "isFinish ASC,isOutDate DESC,c.checkDate";
        $checkaccept->asc = false;
        $checkaccept->initLimit();
        $datas['rows'] = $checkaccept->page_d('select_statistical');

        foreach ($datas['rows'] as $key => $val) {
            $datas['rows'][$key]['checkFile'] = $this->service->getFilesByObjId($val['id'], false, 'oa_contract_check');
            $datas['rows'][$key]['realEndDate'] = $val['completeDate'];
        }

        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $checkaccept->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'waitingList', $datas);
    }

    /**
     * 开票/到款合同
     */
    function c_invoiceContract() {

        if (!isset($_REQUEST['finishStatus'])) {
            $_REQUEST['finishStatus'] = 'all';
        }
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $conditions = $_REQUEST;
        if ($conditions['finishStatus'] != "" && $conditions['finishStatus'] != 'all') {
            if ($conditions['finishStatus'] == 1) {
                $customSql = " and r.incomMoney = r.money and r.invoiceMoney = r.money ";
            } else {
                $customSql = " and (r.incomMoney <> r.money or r.invoiceMoney <> r.money) ";
            }
        } else {
            unset($conditions['finishStatus']);
        }
        $date = $_GET['date'];
        	if($date&&strlen($date)==7){
        		$conditions['TdayYearMonth'] = $_GET['date'];
        		unset($conditions['date']);
        	}else if($date&&strlen($date)==4){
        		$conditions['TdayYear'] = $_GET['date'];
        		unset($conditions['date']);
        	}
        $conDao->getParam($conditions);
        $conDao->initLimit($customSql);
        $conDao->searchArr['Tday'] = "sql: and (Tday is not null or Tday !='')";
        if(empty($conDao->searchArr['mySearchCondition'])){
        	$conDao->searchArr['mySearchCondition']= "sql: $customSql";
        }
        $conDao->sort = "isFinishMoney ASC,isMoneyOutDate DESC,contractCode,r.Tday";
        $conDao->asc = false;
        $datas['rows'] = $conDao->pageBySqlId('select_financialTday');
        if ($datas['rows']) {
            $incomecheck = new model_finance_income_incomecheck();
            foreach ($datas['rows'] as $key => $val) {
                $payConId = array('payConId' => $val['id']);
                $incomecheckRow = $incomecheck->findAll($payConId);
                //获取收款日期
                $date = $this->service->receiptDate_d($incomecheckRow);
                $datas['rows'][$key]['receiptDate'] = $date;
                //开票日期
                $invoiceDate = $this->service->getInvoiceDate($val);
                $datas['rows'][$key]['invoiceDate'] = $invoiceDate;
//                $datas['rows'][$key]['actEndDate'] = $conDao->getTdayListEndDate($val['contractId'],$val['paymenttermId']);
                $datas['rows'][$key]['actEndDate'] = $val['completeDate'];
            }
        }
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'invoiceList', $datas);
    }

    /**
     * 待归档合同
     */
    function c_contractArchive() {
        if (!isset($_REQUEST['signStatusArr'])) {
            $_REQUEST['signStatusArr'] = '0,2';
        }

        $date = $_GET['date'];
        $conditions = $_REQUEST;
        $conditions['isTemp'] = '0';
        $conditions['states'] = '1,2,3,4,5,6,7';
        $conditions['ExaStatus'] = '完成';
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        if ($date && strlen($date) == 7) {
             $conditions['ExaYearMonth'] = $_GET['date'];
        } else if ($date && strlen($date) == 4) {
        	 $conditions['ExaYear'] = $_GET['date'];
        }
        $conDao->getParam($conditions);
        $conDao->initLimit();
        $conDao->sort = "isSigned ASC,isArchiveOutDate";

        $datas['rows'] = $conDao->pageBysqlId('select_buildList');
        foreach ($datas['rows'] as $key => $val) {
            $changeTime = $this->service->_db->getArray("SELECT max(changeTime) as changeTime FROM oa_contract_signin where objId= '" . $val['id'] . "' group by objId; ");
            $datas['rows'][$key]['changeTime'] = $changeTime[0]['changeTime'];
        }

        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'archiveList', $datas);
    }

    /**
     * 销售列表
     */
    function c_saleList() {
        //数据
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->buildContract_d($_REQUEST);
        foreach ($datas['rows'] as $key => $val) {
            switch ($val['state']) {
                case '0' :
                    $datas['rows'][$key]['stateName'] = '保存';
                case '1' :
                    $datas['rows'][$key]['stateName'] = '审批中';
                case '2' :
                    $datas['rows'][$key]['stateName'] = '执行中';
                case '3' :
                    $datas['rows'][$key]['stateName'] = '已关闭';
                case '4' :
                    $datas['rows'][$key]['stateName'] = '已完成';
                case '7' :
                    $datas['rows'][$key]['stateName'] = '异常关闭';
            }
            $datas['rows'][$key]['view'] = $conDao->viewContract_d($val['id']);

            $changeTime = $this->service->_db->getArray("SELECT min(checkDate) as checkDate FROM oa_contract_check where contractId= '" . $val['id'] . "' group by contractId; ");
            $datas['rows'][$key]['checkDate'] = $changeTime[0]['checkDate'];

            $changeTime = $this->service->_db->getArray("SELECT max(changeTime) as changeTime FROM oa_contract_signin where objId= '" . $val['id'] . "' group by objId; ");
            $datas['rows'][$key]['changeTime'] = $changeTime[0]['changeTime'];


        }

        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);

        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'saleList', $datas);
    }

    /**
     * 合同关闭
     */
    function c_closeContract() {
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $conDao->getParam($_REQUEST);
        $conDao->searchArr['states'] = "3,7";
        $conDao->initLimit();
        $conDao->sort = "c.ExaDTOne";
        $datas['rows'] = $conDao->pageBysqlId('select_buildList');
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'closeList', $datas);
    }

    /**
     * 验收条款查看列表
     */
    function c_checkacceptList() {
        $contractId = $_GET['id'];
        $conDao = new model_contract_checkaccept_checkaccept();
        $datas['rows'] = $conDao->findAll(array("contractId" => $contractId));
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'checkacceptList', $datas);
    }

    /**
     * 收款条款查看列表
     */
    function c_receiptplanList() {
        $contractId = $_GET['id'];
        $contract = new model_contract_contract_contract();
        $row = $contract->get_d($contractId);
        $conDao = new model_contract_contract_receiptplan();
        $datas['rows'] = $conDao->findAll(array("contractId" => $contractId), 'isfinance');
        foreach ($datas['rows'] as $key => $val) {
            $datas['rows'][$key]['contractCode'] = $row['contractCode'];
        }
        //分页
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'receiptplanList', $datas);
    }

    /**
     * 跳转到合同管理权限控制页面
     */
    function c_toLimitContract() {
        $html = $this->service->getlimitList_d();
        $this->assign('html', $html);
        $this->view("limitContract");
    }

    /**
     * 合同管理权限控制
     */
    function c_limitContract() {
        $data = $_POST[$this->objName];
        $result = $this->service->limitContract_d($data);
        if ($result) {
            msgGo('赋权成功！', '?model=contractTool_contractTool_contractTool&action=toLimitContract');
        } else {
            msg('赋权失败！');
        }
    }

    /**
     *合同管理赋权
     */
    function c_toSetauthorizeInfo() {
        $userCode = $_POST['userCode'];
        $userName = $_POST['userName'];
        //处理产品
        if ($userName && $userCode) {
            $list = $this->service->toSetauthorizeInfo_d($userCode, $userName);
            $list = util_jsonUtil :: iconvGB2UTF($list);
            echo $list;
        } else {
            exit();
        }
    }


    /**************************合同平台相关导入************************************************/

    /**
     * 验收条款导入
     */
    function c_toImportcheckInfo() {
        $this->view('importcheckInfo');
    }

    /**
     * 付款条款导入
     */
    function c_toImportPayInfo() {
        $this->view('importPayInfo');
    }

    //验收导入
    function c_upExcelcheckInfo() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $objNameArr = array(
            0 => 'contractCode', //合同号
            1 => 'clause', //验收条款
            2 => 'dateCode', //验收节点
            3 => 'checkDateR', //预计时间
            4 => 'days', //缓冲天数
            5 => 'remark', //备注
            6 => 'clauseInfo', //
            7 => 'isCon', //
            23 => 'rowsIndex'
        );

        $this->c_addcheckInfo($objNameArr);

    }

    /**
     * 上传EXCEl并导入其数据
     */
    function c_addcheckInfo($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer ();
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"
            || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        ) {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData_07($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

                $arrinfo = array(); //导入信息
                $conDao = new model_contract_contract_contract();
                $checkDao = new model_contract_checkaccept_checkaccept();
                //处理客户借试用并保存信息
                foreach ($objectArr as $val) {
                    //判断合同是否存在
                    $conArr = $conDao->getContractInfoByCode($val['contractCode']);
                    $conId = $conArr['id'];
                    if (empty($conId)) {
                        $rowIndexStr = $val['rowsIndex'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "导入失败，合同信息不存在"));
                            }
                        }
                    } else {
                        $id = $checkDao->importAdd_d($val, $conId);
                        if ($id) {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "导入成功"));
                                }
                            }
                        } else {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "导入失败，未知原因"));
                                }
                            }
                        }
                    }
                }
                $result = array();
                foreach ($arrinfo as $value) {
                    $result[$value['rowsIndex']] = $value;
                }
                sort($result);
                if ($result) {
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "导入结果", array("行号", "相关信息", "验收条款", "结果"));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }
    }

    function sysSortArray($ArrayData, $KeyName1, $SortOrder1 = "SORT_ASC", $SortType1 = "SORT_REGULAR") {
        if (!is_array($ArrayData)) {
            return $ArrayData;
        }

        // Get args number.
        $ArgCount = func_num_args();

        // Get keys to sort by and put them to SortRule array.
        for ($I = 1; $I < $ArgCount; $I++) {
            $Arg = func_get_arg($I);
            if (!eregi("SORT", $Arg)) {
                $KeyNameList[] = $Arg;
                $SortRule[] = '$' . $Arg;
            } else {
                $SortRule[] = $Arg;
            }
        }

        // Get the values according to the keys and put them to array.
        foreach ($ArrayData AS $Key => $Info) {
            foreach ($KeyNameList AS $KeyName) {
                ${$KeyName}[$Key] = $Info[$KeyName];
            }
        }

        // Create the eval string and eval it.
        $EvalString = 'array_multisort(' . join(",", $SortRule) . ',$ArrayData);';
        eval ($EvalString);
        return $ArrayData;
    }


    //验收导入
    function c_upExcelPayInfo() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $objNameArr = array(
            0 => 'contractCode', //合同号
            1 => 'paymentterm', //回款条件
            2 => 'schedulePer', //进度百分比
            3 => 'paymentPer', //回款百分比
            4 => 'money', //计划付款金额
            5 => 'dateCode', //收款节点
            6 => 'payDT', //计划付款日期
            7 => 'dayNum', //缓冲天数
            8 => 'remark', //备注
            9 => 'conType', //
            10 => 'paymenttermInfo', //
            23 => 'rowsIndex'
        );

        $this->c_addPayInfo($objNameArr);

    }

    /**`
     * 上传EXCEl并导入其数据
     */
    function c_addPayInfo($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer ();
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"
            || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        ) {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData_07($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式

            $contractNum = array();
            //循环整理导入数组
            foreach($excelData as $key => $val){
                $contractNum[$val[0]] += 1;
            }
            //循环判断合同的回款条款条数是否大于15条
            $isCount = true;
            foreach($contractNum as $key => $val){
                $isCount = $this->getContractNum($key,$val);
                if(!$isCount){
                    exit("合同:".$key."回款条款已大于15条，请整理后重新导入");
                }
            }
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }
                $arrinfo = array(); //导入信息
                $conDao = new model_contract_contract_contract();
                $payDao = new model_contract_contract_receiptplan();
                //处理客户借试用并保存信息
                $conIds = "";
                $ctmp = 0;
                foreach ($objectArr as $val) {
                    //判断合同是否存在
                    $conArr = $conDao->getContractInfoByCode($val['contractCode']);
                    $conId = $conArr['id'];

                    $conMoney = $conArr['contractMoney'];
                    if (empty($conId)) {
                        $rowIndexStr = $val['rowsIndex'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "导入失败，合同信息不存在"));
                            }
                        }
                    } else {
                        $id = $payDao->importAdd_d($val, $conId, $conMoney);
                        if ($id) {
                            if($ctmp != $conId){
                                $conIds .= $conId.",";
                            }

                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "导入成功"));
                                }
                            }
                        } else {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "导入失败，未知原因"));
                                }
                            }
                        }
                    }
                }
                $incomeDao = new model_finance_income_incomecheck();
                $conIds = rtrim($conIds, ",");
                $idArr = explode(",",$conIds);
                $idArr = array_unique($idArr);
                $conIds = implode(",",$idArr);

                $payDao->initPayListMoney($conIds);
                $incomeDao->initData_d($conIds);
                $result = array();
                foreach ($arrinfo as $value) {
                    $result[$value['rowsIndex']] = $value;
                }
                sort($result);
                if ($result) {
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "导入结果", array("行号", "相关信息", "验收条款", "结果"));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }
    }

    //判断导入条款是否超过15条
    function getContractNum($contractCode,$num){
         $sql = "select count(r.id) as num from oa_contract_receiptplan r LEFT JOIN oa_contract_contract c on r.contractId=c.id where c.contractCode='".$contractCode."' and c.isTemp=0";
         $conNum = $this->service->_db->getArray($sql);
         $num += $conNum[0]['num'];

        if($num > 15){
            return false;
        }else{
            return true;
        }

    }

    /*
     *  延期原因填写列表 （暂只支持到款）
     */
    function c_delayReason() {
        $this->assign("userId", $_SESSION['USER_ID']);
        $this->view("dealyReason");
    }



    /**
     * 更新到款分配数据
     */
    function c_toUpdateIcomeView() {
        $this->view('updateIcomeView');
    }

    /**
     * 更新到款分配数据
     */
   function c_toUpdateIcomeData(){
       echo "更新需要耗时一小会儿，请稍等......";
       echo "<br/>";
       $incomeDao = new model_finance_income_incomecheck();
       echo "<br/>";
       if($incomeDao->initData_d()){
           echo "更新完成！请点击右上角关闭按钮后刷新列表数据";
       }else{
           echo "更新失败，请联系系统管理员";
       }


   }

   /******************************合同平台相关导出******************************/
   /**
    * 匹配excel字段
    */
   function formatArray_d($titleRow,$datas){
   	// 已定义标题
   	$definedTitle = array(
   			'合同建立时间' => 'ExaDTOne', '合同号' => 'contractCode', '合同名称' => 'contractName', '客户名称' => 'customerName',
   			'合同金额' => 'contractMoney', '期望交付日期' => 'deliveryDate', '预计纸质合同回收日期' => 'returnDate', '验收条款' => 'checkaccept',
   			'收款条款' => 'receiptplan','财务T日' => 'Tday', '标准交货日期' => 'standardDate', '预计交付日期' => 'shipPlanDate', '是否按计划交付' => 'isExceed', '实际发货日期' => 'outstockDate',
   			'交付完成日期' => 'outstockDate', '超期发货原因' => 'delayDetail', '实际发货日期或实际项目结束日期' => 'realEndDate', '预计验收日期' => 'checkDate',
   			'预计验收日期确认' => 'confirmStatus', '实际验收日期' => 'realCheckDate', '超期验收原因' => 'reason', '比例' => 'paymentPer', '金额' => 'money', 'T日确认' => 'Tday',
   			'超期开票原因' => 'invoiceReason', '超期收款原因' => 'incomeReason', '开票金额' => 'invoiceMoney', '已开票金额' => 'invoiceMoney', '到款金额' => 'incomMoney', '已到款金额' => 'incomeMoney', '剩余未开票金额' => 'getInvoiceMoney',
   			'应收账款余额' => 'getMoney', '扣款' => 'deductMoney', '最近一次开票日期' => 'invoiceDate','最近一次收款日期' => 'receiptDate', '纸质合同回收日期' => 'signDate',
   			'纸质合同签收时间' => 'isAcquiringDate', '归档时间' => 'changeTime', 'OA合同信息与纸质合同不一致原因' => 'differentReason'
   					);
   					// 日期处理的标题
   					$dateTitle = array(
   							'合同建立时间' => 'ExaDTOne', '期望交付日期' => 'deliveryDate', '预计纸质合同回收日期' => 'returnDate', '标准交货日期' => 'standardDate',
   							'预计交付日期' => 'shipPlanDate', '实际发货日期' => 'outstockDate', '实际发货日期或实际项目结束日期' => 'realEndDate', '预计验收日期' => 'checkDate',
   							'实际验收日期' => 'realCheckDate', '纸质合同回收日期' => 'signDate', '纸质合同签收时间' => 'isAcquiringDate', '归档时间' => 'changeTime'
   					);
   					// 金额处理的标题
   					$moneyTitle = array(
   							'合同金额' => 'contractMoney', '金额' => 'money', '开票金额' => 'invoiceMoney', '已开票金额' => 'invoiceMoney', '到款金额' => 'incomMoney', '已到款金额' => 'incomeMoney',
   							'剩余未开票金额' => 'getInvoiceMoney', '应收账款余额' => 'getMoney', '扣款' => 'deductMoney'
   					);
   					// 构建新的数组
   					$newArr = array();
   					foreach($titleRow as $key => $val){
   						// 如果存在已定义内容，则存进新数组
   						if(isset($definedTitle[$val])){
   							foreach ($datas as $k => $v){
   								$value = $v[$definedTitle[$val]];
   								// 日期数据处理
   								if(isset($dateTitle[$val]) && (empty($value) || $value == '0000-00-00')){
   									$value = '-';
   								}
   								// 金额数据处理
   								if(isset($moneyTitle[$val])){
   									$value = number_format($value);
   								}
   								$newArr[$k][$definedTitle[$val]] = $value;
   							}
   						}
   					}
   					return $newArr;
   }

   /**
    * 导出筹建中的合同
    */
   function c_exportBuildContract() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$conDao = new model_contract_contract_contract();
   	$date = $_REQUEST['date'];
   	$conditions = $_REQUEST;
   	if ($date && strlen($date) == 7) {
   		$conditions['ExaYearMonth'] = $_REQUEST['date'];
   		unset($conditions['date']);
   	} else if ($date && strlen($date) == 4) {
   		$conditions['ExaYear'] = $_REQUEST['date'];
   		unset($conditions['date']);
   	}
   	$conDao->sort = 'c.ExaDTOne';
   	$conDao->asc = true;
   	$rows = $conDao->buildContract_d($conditions,true);
   	if ($rows) {
   		//统一实例化
   		$checkaccept = new model_contract_checkaccept_checkaccept();
   		$receiptplan = new model_contract_contract_receiptplan();
   		foreach ($rows as $key => $val) {
   			//预计纸质合同回收日期,合同建立时间后3个月
   			$rows[$key]['returnDate'] = date("Y-m-d", strtotime("+3 month", strtotime($val['ExaDTOne'])));
   			$contractId = array('contractId' => $val['id']);
   			//验收条款
   			$checkacceptRows = $checkaccept->findAll($contractId);
   			foreach ($checkacceptRows as $k => $v){
   				$rows[$key]['checkaccept'].= $v['clause']."\n";
   			}
   			//收款条款
   			$receiptplanRow = $receiptplan->findAll($contractId);
   			foreach ($receiptplanRow as $k => $v){
   				if ($v['isfinance'] == 0) {
   					$rows[$key]['receiptplan'].= $v['paymentterm'] . "(" . $v['paymentPer'] . "%):" . $v['money']."\n";
                    $rows[$key]['Tday'].= $v['Tday'] . "\n";
   				}
   			}
   		}
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * 导出交付合同
    */
   function c_exportDeliveryContract() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$conDao = new model_contract_contract_contract();
   	$date = $_GET['date'];
   	$conditions = $_REQUEST;
   	if ($date && strlen($date) == 7) {
   		$conditions['ExaYearMonth'] = $_GET['date'];
   		unset($conditions['date']);
   	} else if ($date && strlen($date) == 4) {
   		$conditions['ExaYear'] = $_GET['date'];
   		unset($conditions['date']);
   	}
   	if ($conditions['state'] == 'all') {
   		unset($conditions['state']);
   	}
   	$rows = $conDao->deliveryContract_d($conditions,true);
   	$outplan = new model_stock_outplan_outplan();
   	$esmproject = new model_engineering_project_esmproject();
   	$ship = new model_stock_outplan_ship();
   	foreach ($rows as $key => $val) {
   		//交付部确认预计交付日期-发货数据
   		$docId = array('docId' => $val['id'], 'docType' => 'oa_contract_contract');
   		$outplanRow = $outplan->findAll($docId);
   		if ($outplanRow) {
   			$delayDetail = "";
   			foreach ($outplanRow as $k => $v) {
   				$outplanRow[$k]['shipDate'] = $this->service->get_table_fields('oa_stock_ship', "planId='" . $v['id'] . "'", 'shipDate');
   				$delayDetail .= $v['delayDetail'] . "\n";
   				$rows[$key]['shipPlanDate'].= $v['planCode'] . "\n(" . date("Y/m/d", (strtotime($v['shipPlanDate']))) . ")\n";
   			}
   		}
   		$rows[$key]['outplanRow'] = $outplanRow;
   		//交付部确认预计交付日期-工程项目数据
   		$contractId = array('contractId' => $val['id'], 'contractType' => 'GCXMYD-01');
   		$esmprojectRow = $esmproject->findAll($contractId);
   		foreach ($esmprojectRow as $k => $v){
   			$rows[$key]['shipPlanDate'].= $v['projectCode'] . "\n(" . date("Y/m/d", (strtotime($v['planBeginDate']))) . '~' . date("Y/m/d", (strtotime($v['planEndDate']))) . ")\n";
   		}
   		$rows[$key]['delayDetail'] = $delayDetail;
   		$delayDetail = "";
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * 导出待验收合同
    */
   function c_exportWaitingAccept() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$checkaccept = new model_contract_checkaccept_checkaccept();
   	$conDao = new model_contract_contract_contract();
   	$date = $_GET['date'];
   	$conditions = $_REQUEST;
   	if ($date && strlen($date) == 7) {
   		$conditions['ExaYearMonth'] = $_GET['date'];
   		unset($conditions['date']);
   	} else if ($date && strlen($date) == 4) {
   		$conditions['ExaYear'] = $_GET['date'];
   		unset($conditions['date']);
   	}
   	if ($conditions['checkStatus'] == 'all') {
   		unset($conditions['checkStatus']);
   	}
   	if (!empty($conditions['contractName'])) {
   		$conId = $conDao->find(array("contractName" => $conditions['contractName']), null, "id");
   		$conditions['contractId'] = $conId['id'];
   		echo $conditions['contractId'];
   		unset($conditions['contractName']);
   	}

   	$checkaccept->getParam($conditions);
   	$checkaccept->sort = "isFinish ASC,isOutDate DESC,c.checkDate";
   	$checkaccept->asc = false;
   	$checkaccept->initLimit();
   	$rows = $checkaccept->list_d('select_statistical');
   	foreach ($rows as $key => $val) {
   		//实际验收日期
   		if ($val['isOutDate'] == 1 && $val['isFinish'] == 0) {
   			$rows[$key]['realCheckDate'] = "超期未验收";
   		}
   		//验收条款
   		$rows[$key]['checkaccept'] = $val['clause'];
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * 导出开票/到款合同
    */
   function c_exportInvoiceContract() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$conDao = new model_contract_contract_contract();
   	$conditions = $_REQUEST;
   	if ($conditions['finishStatus'] != "" && $conditions['finishStatus'] != 'all') {
   		if ($conditions['finishStatus'] == 1) {
   			$customSql = " and r.incomMoney = r.money and r.invoiceMoney = r.money ";
   		} else {
   			$customSql = " and (r.incomMoney <> r.money or r.invoiceMoney <> r.money) ";
   		}
   	} else {
   		unset($conditions['finishStatus']);
   	}
   	$date = $_GET['date'];
   	$conDao->getParam($conditions);
   	$conDao->initLimit($customSql);
   	$conDao->searchArr['Tday'] = "sql: and (Tday is not null or Tday !='')";
   	if(empty($conDao->searchArr['mySearchCondition'])){
   		$conDao->searchArr['mySearchCondition']= "sql: $customSql";
   	}
   	$conDao->sort = "isFinishMoney ASC,isMoneyOutDate DESC,r.Tday";
   	$conDao->asc = false;
   	$rows = $conDao->listBysqlId('select_financialTday');
   	if ($rows) {
   		$incomecheck = new model_finance_income_incomecheck();
   		foreach ($rows as $key => $val) {
   			$payConId = array('payConId' => $val['id']);
   			$incomecheckRow = $incomecheck->findAll($payConId);
   			//获取收款日期
   			$rows[$key]['receiptDate'] = $this->service->receiptDate_d($incomecheckRow);
   			//收款条款
   			$rows[$key]['receiptplan'] = $val['paymentterm'];
   			//比例
   			$rows[$key]['paymentPer'] = $val['paymentPer']."%";
   			//剩余未开票金额
   			$rows[$key]['getInvoiceMoney'] = $val['money'] - $val['invoiceMoney'];
   			//应收账款余额
   			$rows[$key]['getMoney'] = $val['money'] - $val['incomMoney'];
   			//最近一次收款日期
   			$rows[$key]['receiptDate'] = substr($val['receiptDate'], 0, 10);
   		}
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * 导出关闭合同
    */
   function c_exportCloseContract() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$conDao = new model_contract_contract_contract();
   	$conDao->getParam($_REQUEST);
   	$conDao->searchArr['states'] = "3,7";
   	$conDao->initLimit();
   	$conDao->sort = "c.ExaDTOne";
   	$rows = $conDao->listBysqlId('select_buildList');
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * 导出待归档合同
    */
   function c_exportArchiveContract() {
   	//获取表头
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//获取数据
   	$conDao = new model_contract_contract_contract();
   	$conDao->getParam(array('states' => '1,2,3,4,5,6,7',
   			'isTemp' => '0',
   			'ExaStatus' => '完成',
   			'contractCode' => $_REQUEST['contractCode'],
   			'contractName' => $_REQUEST['contractName'],
   			'customerName' => $_REQUEST['customerName'],
   			'signStatusArr' => $_REQUEST['signStatusArr']
   	));
   	$conDao->initLimit();
   	$conDao->sort = "isSigned ASC,isArchiveOutDate";
   	$rows = $conDao->listBysqlId('select_buildList');
   	foreach ($rows as $key => $val) {
   		$changeTime = $this->service->_db->getArray("SELECT max(changeTime) as changeTime FROM oa_contract_signin where objId= '" . $val['id'] . "' group by objId; ");
   		$rows[$key]['changeTime'] = $changeTime[0]['changeTime'];
   		//预计纸质合同回收日期,合同建立时间后3个月
   		$rows[$key]['returnDate'] = date("Y-m-d", strtotime("+3 month", strtotime($val['ExaDTOne'])));
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }
}