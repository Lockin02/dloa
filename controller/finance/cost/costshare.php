<?php

/**
 * @author show
 * @Date 2014��5��6�� 16:12:52
 * @version 1.0
 * @description:���÷��÷�̯���Ʋ�
 */
class controller_finance_cost_costshare extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 ���ù������Ž�ֹѡ��Ĳ���ID����
    function __construct()
    {
        $this->objName = "costshare";
        $this->objPath = "finance_cost";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $subsidyArr = $otherDataDao->getConfig('unSltDeptFilter');
        $this->unSltDeptFilter = $subsidyArr;
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");


        // �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
        $unSelectableIdsArr = $otherDataDao->getConfig('unSelectableIds');
        $this->unSelectableIds = $unSelectableIdsArr;
    }

    /**
     * ��ʾ�����ҳ�б�
     */
    function c_page()
    {
        $this->service->updateDept_d();
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $sql = $this->service->getPageSql_d($_REQUEST);
        $this->service->sort = "c.objId DESC,c.id";
        $rows = $service->pageBySql($sql);
        $arr = array();
        if (!empty($rows)) {
            // ��ȡ������ͬ����Ϣ
            $otherDao = new model_contract_other_other();
            $otherInfo = $otherDao->findAll(null, null, 'id, orderName,fundTypeName');
            $otherHash = array();
            foreach ($otherInfo as $v) {
                $otherHash[$v['id']] = $v;
            }
            $pageCount = array(
                'companyName' => '��ҳС��', 'costMoney' => 0, 'hookMoney' => 0,
                'thisMonthHookMoney' => 0, 'unHookMoney' => 0, 'id' => 'noId'
            );
            foreach ($rows as $k => $v) {
                if ($v['objType'] == 2) {
                    $rows[$k]['objName'] = $otherHash[$v['objId']]['orderName'];
                    $rows[$k]['fundTypeName'] = $otherHash[$v['objId']]['fundTypeName'];
                }
                $pageCount['costMoney'] = bcadd($pageCount['costMoney'], $v['costMoney'], 2);
                $pageCount['hookMoney'] = bcadd($pageCount['hookMoney'], $v['hookMoney'], 2);
                $pageCount['thisMonthHookMoney'] = bcadd($pageCount['thisMonthHookMoney'], $v['thisMonthHookMoney'], 2);
                $pageCount['unHookMoney'] = bcadd($pageCount['unHookMoney'], $v['unHookMoney'], 2);
            }
            $rows[] = $pageCount;

            $sql = $this->service->getPageSql_d($_REQUEST, true, true);
            $list = $service->listBySql($sql);
            $listRow = $list[0];
            $listRow['id'] = 'noId';
            $rows[] = $listRow;
        }

        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��������ϸ-���ڷ��ñ�������ҳ��ķ���ͳ��
     */
    function c_statistictList()
    {
        //    	$this->assign('userId',$_GET['userId']);
//    	$this->assign('areaId',$_GET['areaId']);
//    	$this->assign('year',date('Y'));
//
//    	$this->view('liststatistict');
        $this->assign('userId', (isset($_GET['userId']) ? $_GET['userId'] : ''));
        $this->assign('areaId', (isset($_GET['areaId']) ? $_GET['areaId'] : ''));
        $this->assign('year', (isset($_GET['year']) ? $_GET['year'] : date('Y')));


        if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId'])) && (isset($_GET['view_type']) && !empty($_GET['view_type']))) {
            $this->assign('view_type', (isset($_GET['view_type']) ? $_GET['view_type'] : ''));
            if ($_GET['view_type'] == 'view_all') {
                $this->view('listallstatistict');
            }
        } else if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId']))) {
            $this->view('liststatistict');
        } else {
            $this->view('listallstatistict');
        }
    }

    /**
     * ��ȡ��ҳ����ת��Json - ����ͳ��
     */
    function c_statistictPageJson()
    {
        $service = $this->service;

        // ��ȡ����������۲�����Ϣ
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('SALEDEPT');
        if($matchConfigItem && is_array($matchConfigItem)){
            $CostBelongDeptIds = isset($matchConfigItem[0]['belongDeptIds'])? $matchConfigItem[0]['belongDeptIds'] : '';
            if($CostBelongDeptIds != ''){
                $_REQUEST['belongDeptIds'] = $CostBelongDeptIds;
            }
        }

        $service->getParam($_REQUEST);
        $sql = $this->service->getPageSql_d($_REQUEST, false);
        $this->service->sort = "c.objId DESC,c.id";
        $rows = $service->pageBySql($sql);

        if (isset($_POST['needCountCol']) && $_POST['needCountCol'] == 'true' && $rows) {
            //�ܼ�������
            $total_costMoney = $total_unHookMoney = $total_hookMoney = $total_thisMonthHookMoney = 0;

            foreach ($rows as $k => $v) {
                $total_costMoney = bcadd($total_costMoney, $v['costMoney'], 2);
                $total_hookMoney = bcadd($total_hookMoney, $v['hookMoney'], 2);
                $total_thisMonthHookMoney = bcadd($total_thisMonthHookMoney, $v['thisMonthHookMoney'], 2);
                $total_unHookMoney = bcadd($total_unHookMoney, $v['unHookMoney'], 2);
            }
            $rsArr['objType'] = 'ȫ���ϼ�';
            $rsArr['costMoney'] = $total_costMoney;
            $rsArr['hookMoney'] = $total_hookMoney;
            $rsArr['thisMonthHookMoney'] = $total_thisMonthHookMoney;
            $rsArr['unHookMoney'] = $total_unHookMoney;
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ������÷�̯�б�
     */
    function c_toHistoryForObj()
    {
        $objId = isset($_GET['objId']) && !empty($_GET['objId']) ? $_GET['objId'] : exit('�����������');
        $objType = isset($_GET['objType']) && !empty($_GET['objType']) ? $_GET['objType'] : exit('�����������');
        $this->assign('objId', $objId);
        $this->assign('objType', $objType);
        $this->service->updateDept_d();
        $this->view('list-history');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonHistory()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();

        // �ϼƼ���
        if ($rows) {
            $sum_rows = $this->service->list_d('select_default_sum');
            $sum_rows[0]['id'] = 'nocheck';
            $sum_rows[0]['belongCompanyName'] = '�ϼ���';
            array_push($rows, $sum_rows[0]);
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ������÷�̯�б�
     */
    function c_toHistoryForProject()
    {
        $projectCode = isset($_GET['projectCode']) && !empty($_GET['projectCode']) ? $_GET['projectCode'] : exit('�����������');
        $contractCode = isset($_GET['contractCode']) ? $_GET['contractCode'] : "";
        $this->assign('projectCode', $projectCode);
        $this->assign('contractCode', $contractCode);
        $this->view('list-project');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonProject()
    {
        // ��������
        $searchItem = $_REQUEST;
        if (isset($searchItem['forProject'])) {
            if ($searchItem['contractCode']) {
                $searchItem['mySearchCondition'] = "sql: AND (c.contractCode = '" . $searchItem['contractCode'] .
                    "' OR c.projectCode = '" . $searchItem['projectCode'] . "')";
                unset($searchItem['projectCode']);
                unset($searchItem['contractCode']);
            }
            unset($searchItem['forProject']);
        }

        $service = $this->service;
        $service->getParam($searchItem);
        $rows = $service->page_d();

        // �ϼƼ���
        if ($rows) {
            $sum_rows = $this->service->list_d('select_default_sum');
            $sum_rows[0]['id'] = 'nocheck';
            $sum_rows[0]['belongCompanyName'] = '�ϼ���';
            array_push($rows, $sum_rows[0]);
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ����ҳ�༭�б�
     */
    function c_pageJsonForEdit()
    {
        if (!isset($_POST['objId']) || !$_POST['objId']) exit("{}");
        if (!isset($_POST['objType']) || !$_POST['objType']) exit("{}");
        $listKey = isset($_POST['isChange']) && $_POST['isChange'] ? 'select_change' : 'select_default';
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->page_d($listKey);

        // ��ҳ������
        $pageMoney = 0;
        foreach ($rows as $v) {
            $pageMoney = bcadd($pageMoney, $v['costMoney'], 2);
        }

        // �������һ�·Ǳ�ҳ���
        $list = $service->list_d($listKey);
        $listMoney = 0;
        foreach ($list as $v) {
            $listMoney = bcadd($listMoney, $v['costMoney'], 2);
        }

        // �Ǳ�ҳ���
        $otherPageMoney = bcsub($listMoney, $pageMoney, 2);

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['otherPageMoney'] = $otherPageMoney;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson()
    {
        if (!isset($_POST['objId']) || !$_POST['objId']) exit("{}");
        if (!isset($_POST['objType']) || !$_POST['objType']) exit("{}");
        $listKey = isset($_POST['isChange']) && $_POST['isChange'] ? 'select_change' : 'select_default';
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d($listKey);
        echo util_jsonUtil::encode($rows);
    }

    function c_listjsonForFlight()
    {
        $service = $this->service;
        $projectId = $_POST['projectId'];
        $businessBelong = $_POST['businessBelong'];

        $balanceDao = new model_flights_balance_balance();
        // ��Ʊ��ʱ�򣬽���Ʊ����ת����ñ���
        $rows = $balanceDao->getCostShare_d($projectId);

        $costShare = $rows;
        // ���������һЩ����
        if (is_array($costShare)) {
            $shareObjTypeObj = $service->setShareObjType_d($costShare);
            //���������ֵ��ֶ�
            $datadictDao = new model_system_datadict_datadict ();

            // ���Ż�ȡ
            $deptDao = new model_deptuser_dept_dept();
            $deptArr = $deptDao->getDeptList_d();

            // ��Ա��ȡ
            $userDao = new model_deptuser_user_user();

            // ģ������תת��
            $moduleMap = $datadictDao->getDataDictList_d('HTBK');

            foreach ($costShare as $k => $v) {
                $costShare[$k]['belongCompanyName'] = $datadictDao->getDataNameByCode($businessBelong);
                $costShare[$k]['belongCompany'] = $businessBelong;
                if (isset($v['belongDeptName'])) {
                    $costShare[$k]['belongDeptId'] = $deptArr[$v['belongDeptName']]['DEPT_ID'];
                } else {
                    $costShare[$k]['belongDeptName'] = $deptArr[$v['belongDeptId']]['DEPT_NAME'];
                }
                $costShare[$k]['module'] = $deptArr[$costShare[$k]['belongDeptId']]['module'];
                $costShare[$k]['moduleName'] = $moduleMap[$costShare[$k]['module']];

                // ������ڷ��óе��ˣ�����ֵ��Ϊ�գ���ȥ���Ҷ�Ӧ���˺�
                if (isset($costShare[$k]['feeMan']) && $costShare[$k]['feeMan']) {
                    $userInfo = $userDao->getUserByName($costShare[$k]['feeMan']);
                    $costShare[$k]['feeManId'] = $userInfo['USER_ID'];
                }else{
                    $costShare[$k]['feeMan'] = '';
                    $costShare[$k]['feeManId'] = '';
                }

                $costShare[$k]['shareObjType'] = $shareObjTypeObj[$k]['shareObjType'];
                $costShare[$k]['detailType'] = $shareObjTypeObj[$k]['detailType'];
            }
        }

        echo util_jsonUtil::encode($costShare);
    }

    /**
     * �ṩ��������
     */
    function c_hookJson()
    {
        $rows = $this->service->getHookList_d($_POST['objType'], $_POST['objId'], $_POST['hookId']);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * �ṩ������ѡ����
     */
    function c_hookSelectJson()
    {
        // ���������ҵ��id,ֱ�ӷ������пɹ�����¼
        $rows = $this->service->getHookSelectList_d($_POST['objType'], $_POST['objId'], $_POST['hookId']);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ���ҳ�����
     */
    function c_toAudit()
    {
        // ��֤��ǰҵ���Ƿ����
        $this->checkCanAudit($_GET['objId'], $_GET['objType']);

        // ��������Ĭ��ֵ
        $currency = '�����';

        // ���ݵ�����Ϣ��ȡ�Ѿ����ڵķ�̯���
        $this->assign('objMoney', $this->service->getObjMoney_d($_GET['objId'], $_GET['objType']));
        $this->assign('objTypeCN', $this->service->getObjType($_GET['objType']));

        // ��ȡ��ǰ��������
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');
        $this->assignFunc($periodArr);
        $this->assignFunc($_GET);
        //��ȡ��������۲���id
        $this->assign('saleDeptId', expenseSaleDeptId);
        $this->assign('unSltDeptFilter', $this->unSltDeptFilter);
        $this->assign('unDeptFilter', $this->unDeptExtFilter);

        $unSelectableIds = "";

        //�����˴���
        $principalId = $principalName = $deptId = $deptName = '';
        if ($_GET['objType'] == '2') { //�������ͬ
            $otherDao = new model_contract_other_other();
            $obj = $otherDao->get_d($_GET['objId']);
            if($obj['fundType'] == "KXXZB" && $obj['payForBusiness'] == "FKYWLX-0"){
                $otherDataDao = new model_common_otherdatas();
                // �������ҵ���������޵�ʱ��,��ֹѡ��ķ�����ϸ
                $unSelectableIds = $otherDataDao->getConfig('unSelectableIds');
            };

            if (!empty($obj)) {
                $principalId = $obj['principalId'];
                $principalName = $obj['principalName'];
                $deptId = $obj['deptId'];
                $deptName = $obj['deptName'];
                $currency = $obj['currency'];
            }
        }

        $this->assign('unSelectableIds', $unSelectableIds);
        $this->assign('principalId', $principalId);
        $this->assign('principalName', $principalName);
        $this->assign('deptId', $deptId);
        $this->assign('deptName', $deptName);
        $this->assign('currency', $currency);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        $this->view('audit', true);
    }

    /**
     * �������
     */
    function c_audit()
    {
        $this->checkSubmit();
        if ($this->service->audit_d($_POST[$this->objName])) {
            if ($_POST['goNext']) {
                $nextUrl = $this->c_getWaitInfo('url');
                if ($nextUrl) {
                    msgGo('��˳ɹ�', $nextUrl);
                } else {
                    msgRf('��˳ɹ�����û����Ҫ��˵ĵ��ݡ�');
                }
            } else {
                msgRf('��˳ɹ�');
            }
        } else {
            msgRf('���ʧ��');
        }
    }

    /**
     * �������
     */
    function c_quickAudit()
    {
        echo $this->service->quickAudit_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * ȡ�����
     */
    function c_unAudit()
    {
        echo $this->service->unAudit_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * ����
     */
    function c_back()
    {
        if ($this->service->back_d($_POST['objId'], $_POST['objType'])) {
            $nextUrl = $this->c_getWaitInfo('url');
            echo $nextUrl ? $nextUrl : 1;
        } else {
            echo 0;
        }
    }

    /**
     * ���ٳ���
     */
    function c_quickBack()
    {
        echo $this->service->quickBack_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * �ж�ҵ�񵥾��Ƿ�����
     * @param $objId
     * @param $objType
     */
    function checkCanAudit($objId, $objType)
    {
        $obj = $this->service->find(
            array('auditStatus' => 2, 'isTemp' => 0, 'isDel' => 0, 'objId' => $objId, 'objType' => $objType),
            null, 'objId, objType, objCode, supplierName, company, companyName');
        if (!$obj) {
            $nextUrl = $this->c_getWaitInfo('url');
            if ($nextUrl) {
                succ_show($nextUrl);
            } else {
                msgRf('û����Ҫ��˵ĵ��ݡ�');
            }
        }
    }

    /**
     * ��ȡ����˵ķ�̯��¼ҵ����Ϣ
     * @param string $type ��������:json/url
     * @return string
     */
    function c_getWaitInfo($type = 'json')
    {
        $obj = $this->service->getOneUnAudit_d();
        if ($obj) {
            if ($type == 'json') {
                echo util_jsonUtil::encode($obj);
            } else {
                return "?model=finance_cost_costshare&action=toAudit&objId=" . $obj['objId'] . "&objCode=" .
                $obj['objId'] . "&objCode=" . $obj['objCode'] . "&objType=" . $obj['objType'] . "&supplierName=" .
                $obj['supplierName'] . "&company=" . $obj['company'] . "&companyName=" . $obj['companyName'];
            }
        } else {
            if ($type == 'json') {
                echo 0;
            } else {
                return 0;
            }
        }
    }

    /**
     * excel��������
     */
    function c_importExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->assign('objId', isset($_GET['objId']) ? $_GET['objId'] : "");
            $this->assign('objType', isset($_GET['objType']) ? $_GET['objType'] : "");
            $this->assign('realImport', isset($_GET['realImport']) ? $_GET['realImport'] : "0");
            $this->assign('change', isset($_GET['change']) ? $_GET['change'] : "");
            $this->assign('payForBusiness', isset($_GET['payForBusiness']) ? $_GET['payForBusiness'] : "");
            // ��ʾ
            $this->view('importExcel');
        } else {
            $extArr = array();
            if(isset($_POST['payForBusiness']) && $_POST['payForBusiness'] == 'FKYWLX-0'){// FKYWLX-0 ��Ӧ�ĸ���ҵ�������ǡ��ޡ�
                $extArr['unSelectableIds'] = explode(",",$this->unSelectableIds);
            }

            // �����Լ�����
            $result = $this->service->importExcel_d($this->unSltDeptFilter,$extArr);
            if (is_array($result)) {
                // ����������
                $str = "<link rel='stylesheet' type='text/css' href='css/yxstyle.css'/>" .
                    "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>" .
                    "<script type='text/javascript' src='js/common/businesspage.js'></script>" .
                    "<table class='form_main_table'>" .
                    "<thead><tr class='main_tr_header'><th>����</th><th>��������</th></tr></thead>";
                foreach ($result as $k => $v) {
                    if (isset($v['result'])) {
                        $str .= "<tr class='tr_odd'><td>" . $k . "</td><td>" . $v['result'] . "</td></tr>";
                    }
                }
                $str .= "</table>";

                // ����ȥ��������
                if (isset($_POST['objId']) && $_POST['objId'] && $_POST['realImport']) {

                    // ����
                    $condition = array('objId' => $_POST['objId'], 'objType' => $_POST['objType']);

                    // ɾ��ԭ����
                    $this->service->delete($condition);

                    // ���ݴ���
                    $cleanData = $this->service->mergeArray($condition, $result, 'result');

                    // ��������
                    $this->service->saveDelBatch($cleanData);

                    echo $str . "<script>self.parent.$('#shareGrid').costShareGrid('processData');</script>";
                } else {
                    echo $str . "<script>self.parent.costShareImportExcel('" . util_jsonUtil::encode($result)
                        . "');</script>";
                }
            } else {
                echo $result, "<br/><input type='button' value='���ص���ҳ��' ",
                "onclick='location=\"?model=finance_cost_costshare&action=importExcel\"'/>";
            }
        }
    }

    /**
     * ����������¼
     */
    function c_exportExcel()
    {
        set_time_limit(0);
        $this->service->getParam($_REQUEST);
        $sql = $this->service->getPageSql_d($_REQUEST);
        $this->service->sort = "c.objId DESC,c.id";
        $data = $this->service->listBySql($sql);

        if ($data) {
            // ��ȡ������ͬ����Ϣ
            $otherDao = new model_contract_other_other();
            $otherInfo = $otherDao->findAll(null, null, 'id, orderName,fundTypeName');
            $otherHash = array();
            foreach ($otherInfo as $v) {
                $otherHash[$v['id']] = $v;
            }
            foreach ($data as $k => $v) {
                if ($v['objType'] == 2) {
                    $data[$k]['objName'] = $otherHash[$v['objId']]['orderName'];
                    $data[$k]['fundTypeName'] = $otherHash[$v['objId']]['fundTypeName'];
                }
            }

            model_finance_common_financeExcelUtil::exportCostShare(array(
                'auditStatus' => '���', 'companyName' => '��˾����', 'moduleName' => '�������',
                'feeMan' => '���óе���', 'salesArea' => '��������', 'belongCompanyName' => '������˾',
                'objType' => 'Դ������', 'objCode' => 'Դ�����', 'objName' => 'Դ������', 'fundTypeName' => '��������', 'supplierName' => '��Ӧ��',
                'inPeriod' => '�����ڼ�', 'belongPeriod' => '�����ڼ�', 'detailType' => 'ҵ������',
                'headDeptName' => '��������', 'belongDeptName' => '��������', 'chanceCode' => '�̻����',
                'projectCode' => '��Ŀ���', 'projectName' => '��Ŀ����', 'contractCode' => '��ͬ���',
                'contractName' => '��ͬ����', 'customerName' => '�ͻ�����',
                'customerType' => '�ͻ�����', 'province' => '����ʡ��', 'parentTypeName' => '������ϸ�ϼ�',
                'costTypeName' => '������ϸ', 'currency' => '����', 'costMoney' => '��̯���', 'hookMoney' => '�ۼƹ������',
                'thisMonthHookMoney' => '���¹������', 'unHookMoney' => 'δ�������', 'hookStatus' => '����״̬',
                'auditor' => '�����', 'auditDate' => '�������'
            ), $data, array('costMoney', 'hookMoney', 'thisMonthHookMoney', 'unHookMoney'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ������˲��ֵ�����
     */
    function c_exportAudit()
    {
        set_time_limit(0);
        if (!isset($_GET['objId']) || !$_GET['objId']) exit("{}");
        if (!isset($_GET['objType']) || !$_GET['objType']) exit("{}");
        $listKey = isset($_GET['isChange']) && $_GET['isChange'] ? 'select_change' : 'select_default';
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $orgData = $service->list_d($listKey);
        $data = array();
        if (!empty($orgData)) {
            foreach ($orgData as $v) {
                if ($v['auditStatus'] == 2) {
                    $data[] = $v;
                }
            }
        }
        if ($data) {
            model_finance_common_financeExcelUtil::exportCostShare(array(
                'auditStatus' => '���', 'companyName' => '��˾����', 'moduleName' => '�������',
                'feeMan' => '���óе���', 'salesArea' => '��������', 'belongCompanyName' => '������˾',
                'objType' => 'Դ������', 'objCode' => 'Դ�����', 'supplierName' => '��Ӧ��',
                'inPeriod' => '�����ڼ�', 'belongPeriod' => '�����ڼ�', 'detailType' => 'ҵ������',
                'headDeptName' => '��������', 'belongDeptName' => '��������', 'chanceCode' => '�̻����',
                'projectCode' => '��Ŀ���', 'projectName' => '��Ŀ����', 'contractCode' => '��ͬ���',
                'contractName' => '��ͬ����', 'customerName' => '�ͻ�����',
                'customerType' => '�ͻ�����', 'province' => '����ʡ��', 'parentTypeName' => '������ϸ�ϼ�',
                'costTypeName' => '������ϸ', 'costMoney' => '��̯���', 'hookMoney' => '�ۼƹ������',
                'thisMonthHookMoney' => '���¹������', 'unHookMoney' => 'δ�������', 'hookStatus' => '����״̬',
                'auditor' => '�����', 'auditDate' => '�������'
            ), $data, array('costMoney', 'hookMoney', 'thisMonthHookMoney', 'unHookMoney'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('û�в�ѯ���������');
        }
    }

    /**
     * ���ٱ�������
     */
    function c_ajaxSave()
    {
        echo $this->service->ajaxSave_d($_POST);
    }


    function c_checkHasOverPeriod(){
        $objId = isset($_REQUEST['objId'])? $_REQUEST['objId'] : '';
        if($objId != ''){
            $sql = "select GROUP_CONCAT(inPeriod) as periods from oa_finance_cost where objId = ".$objId;
            $result = $this->service->_db->getArray($sql);
            if($result){
                echo $result[0]['periods'];
            }else{
                echo "no";
            }
        }else{
            echo "no";
        }
    }
}