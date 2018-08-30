<?php

class controller_contractTool_contractTool_contractTool extends controller_base_action
{

    function __construct() {
        $this->objName = "contractTool";
        $this->objPath = "contractTool_contractTool";
        //		$this->lang="contract";//���԰�ģ��
        parent :: __construct();
    }

    /**
     * ��ͼ
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
     * �ｨ�еĺ�ͬ
     */
    function c_buildContract() {
        //����
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
            //��������
            $checkaccept = new model_contract_checkaccept_checkaccept();
            $receiptplan = new model_contract_contract_receiptplan();
            foreach ($datas['rows'] as $key => $val) {
                $contractId = array('contractId' => $val['id']);
                $checkacceptRows = $checkaccept->findAll($contractId);
                $datas['rows'][$key]['checkaccept'] = $checkacceptRows;
                //��ȡ�տ�����
                $receiptplanRow = $receiptplan->findAll($contractId);
                $datas['rows'][$key]['receiptplan'] = $receiptplanRow;
            }
        }
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'buildlist', $datas);
    }

    /**
     * ���պ�ͬ
     */
    function c_accepting() {
        //����
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->buildContract_d($_REQUEST);
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'acceptinglist', $datas);
    }

    /**
     * �鿴��ͬ
     */
    function c_viewContract() {
        //����
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->viewContract_d($_GET['id']);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'view', $datas);
    }

    /**
     * ��ͬ����
     */
    function c_deliveryContract() {
        //����
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
            //������ȷ��Ԥ�ƽ�������-��������
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
            //������ȷ��Ԥ�ƽ�������-������Ŀ����
            $contractId = array('contractId' => $val['id'], 'contractType' => 'GCXMYD-01');
            $esmprojectRow = $esmproject->findAll($contractId);
            $datas['rows'][$key]['esmprojectRow'] = $esmprojectRow;
            //�жϼƻ��Ƿ���
            //			$datas['rows'][$key]['isExceed'] = $this->service->isExceed_d($datas['rows'][$key],$outplanRow,$esmprojectRow);
            $datas['rows'][$key]['delayDetail'] = $delayDetail;
            $delayDetail = "";
        }

        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);

        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'deliveryList', $datas);
    }


    /**
     * �����պ�ͬ
     */
    function c_waitingAccept() {
        if (!isset($_REQUEST['checkStatus'])) {
            $_REQUEST['checkStatus'] = "δ����";
            $_REQUEST['confirmStatus'] = "��ȷ��";
        }
        if($_REQUEST['checkStatus'] == '1'){
        	$_REQUEST['checkStatus'] = "δ����";
            unset($_REQUEST['confirmStatus']);
            unset($_GET['date']);
        }
        //����
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

        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $checkaccept->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'waitingList', $datas);
    }

    /**
     * ��Ʊ/�����ͬ
     */
    function c_invoiceContract() {

        if (!isset($_REQUEST['finishStatus'])) {
            $_REQUEST['finishStatus'] = 'all';
        }
        //����
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
                //��ȡ�տ�����
                $date = $this->service->receiptDate_d($incomecheckRow);
                $datas['rows'][$key]['receiptDate'] = $date;
                //��Ʊ����
                $invoiceDate = $this->service->getInvoiceDate($val);
                $datas['rows'][$key]['invoiceDate'] = $invoiceDate;
//                $datas['rows'][$key]['actEndDate'] = $conDao->getTdayListEndDate($val['contractId'],$val['paymenttermId']);
                $datas['rows'][$key]['actEndDate'] = $val['completeDate'];
            }
        }
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'invoiceList', $datas);
    }

    /**
     * ���鵵��ͬ
     */
    function c_contractArchive() {
        if (!isset($_REQUEST['signStatusArr'])) {
            $_REQUEST['signStatusArr'] = '0,2';
        }

        $date = $_GET['date'];
        $conditions = $_REQUEST;
        $conditions['isTemp'] = '0';
        $conditions['states'] = '1,2,3,4,5,6,7';
        $conditions['ExaStatus'] = '���';
        //����
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

        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'archiveList', $datas);
    }

    /**
     * �����б�
     */
    function c_saleList() {
        //����
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $datas['rows'] = $conDao->buildContract_d($_REQUEST);
        foreach ($datas['rows'] as $key => $val) {
            switch ($val['state']) {
                case '0' :
                    $datas['rows'][$key]['stateName'] = '����';
                case '1' :
                    $datas['rows'][$key]['stateName'] = '������';
                case '2' :
                    $datas['rows'][$key]['stateName'] = 'ִ����';
                case '3' :
                    $datas['rows'][$key]['stateName'] = '�ѹر�';
                case '4' :
                    $datas['rows'][$key]['stateName'] = '�����';
                case '7' :
                    $datas['rows'][$key]['stateName'] = '�쳣�ر�';
            }
            $datas['rows'][$key]['view'] = $conDao->viewContract_d($val['id']);

            $changeTime = $this->service->_db->getArray("SELECT min(checkDate) as checkDate FROM oa_contract_check where contractId= '" . $val['id'] . "' group by contractId; ");
            $datas['rows'][$key]['checkDate'] = $changeTime[0]['checkDate'];

            $changeTime = $this->service->_db->getArray("SELECT max(changeTime) as changeTime FROM oa_contract_signin where objId= '" . $val['id'] . "' group by objId; ");
            $datas['rows'][$key]['changeTime'] = $changeTime[0]['changeTime'];


        }

        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);

        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'saleList', $datas);
    }

    /**
     * ��ͬ�ر�
     */
    function c_closeContract() {
        $datas = $this->service->getParam($_REQUEST);
        $conDao = new model_contract_contract_contract();
        $conDao->getParam($_REQUEST);
        $conDao->searchArr['states'] = "3,7";
        $conDao->initLimit();
        $conDao->sort = "c.ExaDTOne";
        $datas['rows'] = $conDao->pageBysqlId('select_buildList');
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'closeList', $datas);
    }

    /**
     * ��������鿴�б�
     */
    function c_checkacceptList() {
        $contractId = $_GET['id'];
        $conDao = new model_contract_checkaccept_checkaccept();
        $datas['rows'] = $conDao->findAll(array("contractId" => $contractId));
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'checkacceptList', $datas);
    }

    /**
     * �տ�����鿴�б�
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
        //��ҳ
        $showpage = new includes_class_page ();
        $showpage->show_page(array('total' => $conDao->count));
        $datas['page'] = $showpage->show(9);
        $datas['page2'] = $showpage->show(10);
        $this->show->displayPT($this->objPath . '_' . $this->objName . '-' . 'receiptplanList', $datas);
    }

    /**
     * ��ת����ͬ����Ȩ�޿���ҳ��
     */
    function c_toLimitContract() {
        $html = $this->service->getlimitList_d();
        $this->assign('html', $html);
        $this->view("limitContract");
    }

    /**
     * ��ͬ����Ȩ�޿���
     */
    function c_limitContract() {
        $data = $_POST[$this->objName];
        $result = $this->service->limitContract_d($data);
        if ($result) {
            msgGo('��Ȩ�ɹ���', '?model=contractTool_contractTool_contractTool&action=toLimitContract');
        } else {
            msg('��Ȩʧ�ܣ�');
        }
    }

    /**
     *��ͬ����Ȩ
     */
    function c_toSetauthorizeInfo() {
        $userCode = $_POST['userCode'];
        $userName = $_POST['userName'];
        //�����Ʒ
        if ($userName && $userCode) {
            $list = $this->service->toSetauthorizeInfo_d($userCode, $userName);
            $list = util_jsonUtil :: iconvGB2UTF($list);
            echo $list;
        } else {
            exit();
        }
    }


    /**************************��ͬƽ̨��ص���************************************************/

    /**
     * ���������
     */
    function c_toImportcheckInfo() {
        $this->view('importcheckInfo');
    }

    /**
     * ���������
     */
    function c_toImportPayInfo() {
        $this->view('importPayInfo');
    }

    //���յ���
    function c_upExcelcheckInfo() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $objNameArr = array(
            0 => 'contractCode', //��ͬ��
            1 => 'clause', //��������
            2 => 'dateCode', //���սڵ�
            3 => 'checkDateR', //Ԥ��ʱ��
            4 => 'days', //��������
            5 => 'remark', //��ע
            6 => 'clauseInfo', //
            7 => 'isCon', //
            23 => 'rowsIndex'
        );

        $this->c_addcheckInfo($objNameArr);

    }

    /**
     * �ϴ�EXCEl������������
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
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

                $arrinfo = array(); //������Ϣ
                $conDao = new model_contract_contract_contract();
                $checkDao = new model_contract_checkaccept_checkaccept();
                //����ͻ������ò�������Ϣ
                foreach ($objectArr as $val) {
                    //�жϺ�ͬ�Ƿ����
                    $conArr = $conDao->getContractInfoByCode($val['contractCode']);
                    $conId = $conArr['id'];
                    if (empty($conId)) {
                        $rowIndexStr = $val['rowsIndex'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "����ʧ�ܣ���ͬ��Ϣ������"));
                            }
                        }
                    } else {
                        $id = $checkDao->importAdd_d($val, $conId);
                        if ($id) {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "����ɹ�"));
                                }
                            }
                        } else {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['clause'], "result" => "����ʧ�ܣ�δ֪ԭ��"));
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
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "������", array("�к�", "�����Ϣ", "��������", "���"));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
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


    //���յ���
    function c_upExcelPayInfo() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $objNameArr = array(
            0 => 'contractCode', //��ͬ��
            1 => 'paymentterm', //�ؿ�����
            2 => 'schedulePer', //���Ȱٷֱ�
            3 => 'paymentPer', //�ؿ�ٷֱ�
            4 => 'money', //�ƻ�������
            5 => 'dateCode', //�տ�ڵ�
            6 => 'payDT', //�ƻ���������
            7 => 'dayNum', //��������
            8 => 'remark', //��ע
            9 => 'conType', //
            10 => 'paymenttermInfo', //
            23 => 'rowsIndex'
        );

        $this->c_addPayInfo($objNameArr);

    }

    /**`
     * �ϴ�EXCEl������������
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
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ

            $contractNum = array();
            //ѭ������������
            foreach($excelData as $key => $val){
                $contractNum[$val[0]] += 1;
            }
            //ѭ���жϺ�ͬ�Ļؿ����������Ƿ����15��
            $isCount = true;
            foreach($contractNum as $key => $val){
                $isCount = $this->getContractNum($key,$val);
                if(!$isCount){
                    exit("��ͬ:".$key."�ؿ������Ѵ���15��������������µ���");
                }
            }
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }
                $arrinfo = array(); //������Ϣ
                $conDao = new model_contract_contract_contract();
                $payDao = new model_contract_contract_receiptplan();
                //����ͻ������ò�������Ϣ
                $conIds = "";
                $ctmp = 0;
                foreach ($objectArr as $val) {
                    //�жϺ�ͬ�Ƿ����
                    $conArr = $conDao->getContractInfoByCode($val['contractCode']);
                    $conId = $conArr['id'];

                    $conMoney = $conArr['contractMoney'];
                    if (empty($conId)) {
                        $rowIndexStr = $val['rowsIndex'];
                        $rowsIndexArr = explode(",", $rowIndexStr);
                        foreach ($rowsIndexArr as $rowsIndex) {
                            if (!empty($rowsIndex)) {
                                array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "����ʧ�ܣ���ͬ��Ϣ������"));
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
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "����ɹ�"));
                                }
                            }
                        } else {
                            $rowIndexStr = $val['rowsIndex'];
                            $rowsIndexArr = explode(",", $rowIndexStr);
                            foreach ($rowsIndexArr as $rowsIndex) {
                                if (!empty($rowsIndex)) {
                                    array_push($arrinfo, array("rowsIndex" => $rowsIndex, "orderCode" => $val['contractCode'], "cusName" => $val['paymentterm'], "result" => "����ʧ�ܣ�δ֪ԭ��"));
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
                    echo util_excelUtil::showResultBorrow($this->sysSortArray($arrinfo, "rowsIndex"), "������", array("�к�", "�����Ϣ", "��������", "���"));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }
    }

    //�жϵ��������Ƿ񳬹�15��
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
     *  ����ԭ����д�б� ����ֻ֧�ֵ��
     */
    function c_delayReason() {
        $this->assign("userId", $_SESSION['USER_ID']);
        $this->view("dealyReason");
    }



    /**
     * ���µ����������
     */
    function c_toUpdateIcomeView() {
        $this->view('updateIcomeView');
    }

    /**
     * ���µ����������
     */
   function c_toUpdateIcomeData(){
       echo "������Ҫ��ʱһС��������Ե�......";
       echo "<br/>";
       $incomeDao = new model_finance_income_incomecheck();
       echo "<br/>";
       if($incomeDao->initData_d()){
           echo "������ɣ��������Ͻǹرհ�ť��ˢ���б�����";
       }else{
           echo "����ʧ�ܣ�����ϵϵͳ����Ա";
       }


   }

   /******************************��ͬƽ̨��ص���******************************/
   /**
    * ƥ��excel�ֶ�
    */
   function formatArray_d($titleRow,$datas){
   	// �Ѷ������
   	$definedTitle = array(
   			'��ͬ����ʱ��' => 'ExaDTOne', '��ͬ��' => 'contractCode', '��ͬ����' => 'contractName', '�ͻ�����' => 'customerName',
   			'��ͬ���' => 'contractMoney', '������������' => 'deliveryDate', 'Ԥ��ֽ�ʺ�ͬ��������' => 'returnDate', '��������' => 'checkaccept',
   			'�տ�����' => 'receiptplan','����T��' => 'Tday', '��׼��������' => 'standardDate', 'Ԥ�ƽ�������' => 'shipPlanDate', '�Ƿ񰴼ƻ�����' => 'isExceed', 'ʵ�ʷ�������' => 'outstockDate',
   			'�����������' => 'outstockDate', '���ڷ���ԭ��' => 'delayDetail', 'ʵ�ʷ������ڻ�ʵ����Ŀ��������' => 'realEndDate', 'Ԥ����������' => 'checkDate',
   			'Ԥ����������ȷ��' => 'confirmStatus', 'ʵ����������' => 'realCheckDate', '��������ԭ��' => 'reason', '����' => 'paymentPer', '���' => 'money', 'T��ȷ��' => 'Tday',
   			'���ڿ�Ʊԭ��' => 'invoiceReason', '�����տ�ԭ��' => 'incomeReason', '��Ʊ���' => 'invoiceMoney', '�ѿ�Ʊ���' => 'invoiceMoney', '������' => 'incomMoney', '�ѵ�����' => 'incomeMoney', 'ʣ��δ��Ʊ���' => 'getInvoiceMoney',
   			'Ӧ���˿����' => 'getMoney', '�ۿ�' => 'deductMoney', '���һ�ο�Ʊ����' => 'invoiceDate','���һ���տ�����' => 'receiptDate', 'ֽ�ʺ�ͬ��������' => 'signDate',
   			'ֽ�ʺ�ͬǩ��ʱ��' => 'isAcquiringDate', '�鵵ʱ��' => 'changeTime', 'OA��ͬ��Ϣ��ֽ�ʺ�ͬ��һ��ԭ��' => 'differentReason'
   					);
   					// ���ڴ���ı���
   					$dateTitle = array(
   							'��ͬ����ʱ��' => 'ExaDTOne', '������������' => 'deliveryDate', 'Ԥ��ֽ�ʺ�ͬ��������' => 'returnDate', '��׼��������' => 'standardDate',
   							'Ԥ�ƽ�������' => 'shipPlanDate', 'ʵ�ʷ�������' => 'outstockDate', 'ʵ�ʷ������ڻ�ʵ����Ŀ��������' => 'realEndDate', 'Ԥ����������' => 'checkDate',
   							'ʵ����������' => 'realCheckDate', 'ֽ�ʺ�ͬ��������' => 'signDate', 'ֽ�ʺ�ͬǩ��ʱ��' => 'isAcquiringDate', '�鵵ʱ��' => 'changeTime'
   					);
   					// ����ı���
   					$moneyTitle = array(
   							'��ͬ���' => 'contractMoney', '���' => 'money', '��Ʊ���' => 'invoiceMoney', '�ѿ�Ʊ���' => 'invoiceMoney', '������' => 'incomMoney', '�ѵ�����' => 'incomeMoney',
   							'ʣ��δ��Ʊ���' => 'getInvoiceMoney', 'Ӧ���˿����' => 'getMoney', '�ۿ�' => 'deductMoney'
   					);
   					// �����µ�����
   					$newArr = array();
   					foreach($titleRow as $key => $val){
   						// ��������Ѷ������ݣ�����������
   						if(isset($definedTitle[$val])){
   							foreach ($datas as $k => $v){
   								$value = $v[$definedTitle[$val]];
   								// �������ݴ���
   								if(isset($dateTitle[$val]) && (empty($value) || $value == '0000-00-00')){
   									$value = '-';
   								}
   								// ������ݴ���
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
    * �����ｨ�еĺ�ͬ
    */
   function c_exportBuildContract() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
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
   		//ͳһʵ����
   		$checkaccept = new model_contract_checkaccept_checkaccept();
   		$receiptplan = new model_contract_contract_receiptplan();
   		foreach ($rows as $key => $val) {
   			//Ԥ��ֽ�ʺ�ͬ��������,��ͬ����ʱ���3����
   			$rows[$key]['returnDate'] = date("Y-m-d", strtotime("+3 month", strtotime($val['ExaDTOne'])));
   			$contractId = array('contractId' => $val['id']);
   			//��������
   			$checkacceptRows = $checkaccept->findAll($contractId);
   			foreach ($checkacceptRows as $k => $v){
   				$rows[$key]['checkaccept'].= $v['clause']."\n";
   			}
   			//�տ�����
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
    * ����������ͬ
    */
   function c_exportDeliveryContract() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
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
   		//������ȷ��Ԥ�ƽ�������-��������
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
   		//������ȷ��Ԥ�ƽ�������-������Ŀ����
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
    * ���������պ�ͬ
    */
   function c_exportWaitingAccept() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
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
   		//ʵ����������
   		if ($val['isOutDate'] == 1 && $val['isFinish'] == 0) {
   			$rows[$key]['realCheckDate'] = "����δ����";
   		}
   		//��������
   		$rows[$key]['checkaccept'] = $val['clause'];
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * ������Ʊ/�����ͬ
    */
   function c_exportInvoiceContract() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
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
   			//��ȡ�տ�����
   			$rows[$key]['receiptDate'] = $this->service->receiptDate_d($incomecheckRow);
   			//�տ�����
   			$rows[$key]['receiptplan'] = $val['paymentterm'];
   			//����
   			$rows[$key]['paymentPer'] = $val['paymentPer']."%";
   			//ʣ��δ��Ʊ���
   			$rows[$key]['getInvoiceMoney'] = $val['money'] - $val['invoiceMoney'];
   			//Ӧ���˿����
   			$rows[$key]['getMoney'] = $val['money'] - $val['incomMoney'];
   			//���һ���տ�����
   			$rows[$key]['receiptDate'] = substr($val['receiptDate'], 0, 10);
   		}
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }

   /**
    * �����رպ�ͬ
    */
   function c_exportCloseContract() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
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
    * �������鵵��ͬ
    */
   function c_exportArchiveContract() {
   	//��ȡ��ͷ
   	$colNameArr = array_filter(explode(',', $_GET['colName']));
   	//��ȡ����
   	$conDao = new model_contract_contract_contract();
   	$conDao->getParam(array('states' => '1,2,3,4,5,6,7',
   			'isTemp' => '0',
   			'ExaStatus' => '���',
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
   		//Ԥ��ֽ�ʺ�ͬ��������,��ͬ����ʱ���3����
   		$rows[$key]['returnDate'] = date("Y-m-d", strtotime("+3 month", strtotime($val['ExaDTOne'])));
   	}
   	$rows = $this->formatArray_d($colNameArr,$rows);
   	return model_contractTool_contractTool_contractToolExcelUtil :: export2ExcelUtil($colNameArr,$rows);
   }
}