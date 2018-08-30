<?php

/**
 * @author show
 * @Date 2014年5月6日 16:12:52
 * @version 1.0
 * @description:公用费用分摊控制层
 */
class controller_finance_cost_costshare extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 费用归属部门禁止选择的部门ID配置
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


        // 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIdsArr = $otherDataDao->getConfig('unSelectableIds');
        $this->unSelectableIds = $unSelectableIdsArr;
    }

    /**
     * 显示对象分页列表
     */
    function c_page()
    {
        $this->service->updateDept_d();
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
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
            // 获取其他合同的信息
            $otherDao = new model_contract_other_other();
            $otherInfo = $otherDao->findAll(null, null, 'id, orderName,fundTypeName');
            $otherHash = array();
            foreach ($otherInfo as $v) {
                $otherHash[$v['id']] = $v;
            }
            $pageCount = array(
                'companyName' => '单页小计', 'costMoney' => 0, 'hookMoney' => 0,
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
     * 报销单明细-用于费用报销审批页面的费用统计
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
     * 获取分页数据转成Json - 费用统计
     */
    function c_statistictPageJson()
    {
        $service = $this->service;

        // 获取配置项的销售部门信息
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
            //总计栏加载
            $total_costMoney = $total_unHookMoney = $total_hookMoney = $total_thisMonthHookMoney = 0;

            foreach ($rows as $k => $v) {
                $total_costMoney = bcadd($total_costMoney, $v['costMoney'], 2);
                $total_hookMoney = bcadd($total_hookMoney, $v['hookMoney'], 2);
                $total_thisMonthHookMoney = bcadd($total_thisMonthHookMoney, $v['thisMonthHookMoney'], 2);
                $total_unHookMoney = bcadd($total_unHookMoney, $v['unHookMoney'], 2);
            }
            $rsArr['objType'] = '全部合计';
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
     * 对象费用分摊列表
     */
    function c_toHistoryForObj()
    {
        $objId = isset($_GET['objId']) && !empty($_GET['objId']) ? $_GET['objId'] : exit('参数传入错误');
        $objType = isset($_GET['objType']) && !empty($_GET['objType']) ? $_GET['objType'] : exit('参数传入错误');
        $this->assign('objId', $objId);
        $this->assign('objType', $objType);
        $this->service->updateDept_d();
        $this->view('list-history');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonHistory()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();

        // 合计加载
        if ($rows) {
            $sum_rows = $this->service->list_d('select_default_sum');
            $sum_rows[0]['id'] = 'nocheck';
            $sum_rows[0]['belongCompanyName'] = '合计列';
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
     * 对象费用分摊列表
     */
    function c_toHistoryForProject()
    {
        $projectCode = isset($_GET['projectCode']) && !empty($_GET['projectCode']) ? $_GET['projectCode'] : exit('参数传入错误');
        $contractCode = isset($_GET['contractCode']) ? $_GET['contractCode'] : "";
        $this->assign('projectCode', $projectCode);
        $this->assign('contractCode', $contractCode);
        $this->view('list-project');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonProject()
    {
        // 参数处理
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

        // 合计加载
        if ($rows) {
            $sum_rows = $this->service->list_d('select_default_sum');
            $sum_rows[0]['id'] = 'nocheck';
            $sum_rows[0]['belongCompanyName'] = '合计列';
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
     * 带分页编辑列表
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

        // 本页金额计算
        $pageMoney = 0;
        foreach ($rows as $v) {
            $pageMoney = bcadd($pageMoney, $v['costMoney'], 2);
        }

        // 这里计算一下非本页金额
        $list = $service->list_d($listKey);
        $listMoney = 0;
        foreach ($list as $v) {
            $listMoney = bcadd($listMoney, $v['costMoney'], 2);
        }

        // 非本页金额
        $otherPageMoney = bcsub($listMoney, $pageMoney, 2);

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['otherPageMoney'] = $otherPageMoney;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 获取所有数据返回json
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
        // 机票的时候，将机票费用转入费用报销
        $rows = $balanceDao->getCostShare_d($projectId);

        $costShare = $rows;
        // 在这里加入一些属性
        if (is_array($costShare)) {
            $shareObjTypeObj = $service->setShareObjType_d($costShare);
            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict ();

            // 部门获取
            $deptDao = new model_deptuser_dept_dept();
            $deptArr = $deptDao->getDeptList_d();

            // 人员获取
            $userDao = new model_deptuser_user_user();

            // 模板中文转转义
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

                // 如果存在费用承担人，并且值不为空，则去查找对应的账号
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
     * 提供勾稽数据
     */
    function c_hookJson()
    {
        $rows = $this->service->getHookList_d($_POST['objType'], $_POST['objId'], $_POST['hookId']);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 提供勾稽可选数据
     */
    function c_hookSelectJson()
    {
        // 如果传入了业务id,直接返回所有可勾稽记录
        $rows = $this->service->getHookSelectList_d($_POST['objType'], $_POST['objId'], $_POST['hookId']);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 审核页面呈现
     */
    function c_toAudit()
    {
        // 验证当前业务是否可审
        $this->checkCanAudit($_GET['objId'], $_GET['objType']);

        // 给个币种默认值
        $currency = '人民币';

        // 根据单据信息获取已经存在的分摊额度
        $this->assign('objMoney', $this->service->getObjMoney_d($_GET['objId'], $_GET['objType']));
        $this->assign('objTypeCN', $this->service->getObjType($_GET['objType']));

        // 获取当前财务周期
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');
        $this->assignFunc($periodArr);
        $this->assignFunc($_GET);
        //获取定义的销售部门id
        $this->assign('saleDeptId', expenseSaleDeptId);
        $this->assign('unSltDeptFilter', $this->unSltDeptFilter);
        $this->assign('unDeptFilter', $this->unDeptExtFilter);

        $unSelectableIds = "";

        //报销人处理
        $principalId = $principalName = $deptId = $deptName = '';
        if ($_GET['objType'] == '2') { //其他类合同
            $otherDao = new model_contract_other_other();
            $obj = $otherDao->get_d($_GET['objId']);
            if($obj['fundType'] == "KXXZB" && $obj['payForBusiness'] == "FKYWLX-0"){
                $otherDataDao = new model_common_otherdatas();
                // 如果付款业务类型是无的时候,禁止选择的费用明细
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
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        $this->view('audit', true);
    }

    /**
     * 审核数据
     */
    function c_audit()
    {
        $this->checkSubmit();
        if ($this->service->audit_d($_POST[$this->objName])) {
            if ($_POST['goNext']) {
                $nextUrl = $this->c_getWaitInfo('url');
                if ($nextUrl) {
                    msgGo('审核成功', $nextUrl);
                } else {
                    msgRf('审核成功，已没有需要审核的单据。');
                }
            } else {
                msgRf('审核成功');
            }
        } else {
            msgRf('审核失败');
        }
    }

    /**
     * 快速审核
     */
    function c_quickAudit()
    {
        echo $this->service->quickAudit_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * 取消审核
     */
    function c_unAudit()
    {
        echo $this->service->unAudit_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * 撤回
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
     * 快速撤回
     */
    function c_quickBack()
    {
        echo $this->service->quickBack_d($_POST['ids']) ? 1 : 0;
    }

    /**
     * 判断业务单据是否可审核
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
                msgRf('没有需要审核的单据。');
            }
        }
    }

    /**
     * 获取待审核的分摊记录业务信息
     * @param string $type 返回类型:json/url
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
     * excel导入数据
     */
    function c_importExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->assign('objId', isset($_GET['objId']) ? $_GET['objId'] : "");
            $this->assign('objType', isset($_GET['objType']) ? $_GET['objType'] : "");
            $this->assign('realImport', isset($_GET['realImport']) ? $_GET['realImport'] : "0");
            $this->assign('change', isset($_GET['change']) ? $_GET['change'] : "");
            $this->assign('payForBusiness', isset($_GET['payForBusiness']) ? $_GET['payForBusiness'] : "");
            // 显示
            $this->view('importExcel');
        } else {
            $extArr = array();
            if(isset($_POST['payForBusiness']) && $_POST['payForBusiness'] == 'FKYWLX-0'){// FKYWLX-0 对应的付款业务类型是【无】
                $extArr['unSelectableIds'] = explode(",",$this->unSelectableIds);
            }

            // 导入以及反馈
            $result = $this->service->importExcel_d($this->unSltDeptFilter,$extArr);
            if (is_array($result)) {
                // 导入结果呈现
                $str = "<link rel='stylesheet' type='text/css' href='css/yxstyle.css'/>" .
                    "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>" .
                    "<script type='text/javascript' src='js/common/businesspage.js'></script>" .
                    "<table class='form_main_table'>" .
                    "<thead><tr class='main_tr_header'><th>行数</th><th>错误内容</th></tr></thead>";
                foreach ($result as $k => $v) {
                    if (isset($v['result'])) {
                        $str .= "<tr class='tr_odd'><td>" . $k . "</td><td>" . $v['result'] . "</td></tr>";
                    }
                }
                $str .= "</table>";

                // 这里去保存数据
                if (isset($_POST['objId']) && $_POST['objId'] && $_POST['realImport']) {

                    // 条件
                    $condition = array('objId' => $_POST['objId'], 'objType' => $_POST['objType']);

                    // 删除原数据
                    $this->service->delete($condition);

                    // 数据处理
                    $cleanData = $this->service->mergeArray($condition, $result, 'result');

                    // 批量新增
                    $this->service->saveDelBatch($cleanData);

                    echo $str . "<script>self.parent.$('#shareGrid').costShareGrid('processData');</script>";
                } else {
                    echo $str . "<script>self.parent.costShareImportExcel('" . util_jsonUtil::encode($result)
                        . "');</script>";
                }
            } else {
                echo $result, "<br/><input type='button' value='返回导入页面' ",
                "onclick='location=\"?model=finance_cost_costshare&action=importExcel\"'/>";
            }
        }
    }

    /**
     * 导出勾稽记录
     */
    function c_exportExcel()
    {
        set_time_limit(0);
        $this->service->getParam($_REQUEST);
        $sql = $this->service->getPageSql_d($_REQUEST);
        $this->service->sort = "c.objId DESC,c.id";
        $data = $this->service->listBySql($sql);

        if ($data) {
            // 获取其他合同的信息
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
                'auditStatus' => '审核', 'companyName' => '公司主体', 'moduleName' => '所属板块',
                'feeMan' => '费用承担人', 'salesArea' => '销售区域', 'belongCompanyName' => '归属公司',
                'objType' => '源单类型', 'objCode' => '源单编号', 'objName' => '源单名称', 'fundTypeName' => '款项类型', 'supplierName' => '供应商',
                'inPeriod' => '入账期间', 'belongPeriod' => '归属期间', 'detailType' => '业务类型',
                'headDeptName' => '二级部门', 'belongDeptName' => '归属部门', 'chanceCode' => '商机编号',
                'projectCode' => '项目编号', 'projectName' => '项目名称', 'contractCode' => '合同编号',
                'contractName' => '合同名称', 'customerName' => '客户名称',
                'customerType' => '客户类型', 'province' => '所属省份', 'parentTypeName' => '费用明细上级',
                'costTypeName' => '费用明细', 'currency' => '币种', 'costMoney' => '分摊金额', 'hookMoney' => '累计勾稽金额',
                'thisMonthHookMoney' => '本月勾稽金额', 'unHookMoney' => '未勾稽金额', 'hookStatus' => '勾稽状态',
                'auditor' => '审核人', 'auditDate' => '审核日期'
            ), $data, array('costMoney', 'hookMoney', 'thisMonthHookMoney', 'unHookMoney'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 导出审核部分的数据
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
                'auditStatus' => '审核', 'companyName' => '公司主体', 'moduleName' => '所属板块',
                'feeMan' => '费用承担人', 'salesArea' => '销售区域', 'belongCompanyName' => '归属公司',
                'objType' => '源单类型', 'objCode' => '源单编号', 'supplierName' => '供应商',
                'inPeriod' => '入账期间', 'belongPeriod' => '归属期间', 'detailType' => '业务类型',
                'headDeptName' => '二级部门', 'belongDeptName' => '归属部门', 'chanceCode' => '商机编号',
                'projectCode' => '项目编号', 'projectName' => '项目名称', 'contractCode' => '合同编号',
                'contractName' => '合同名称', 'customerName' => '客户名称',
                'customerType' => '客户类型', 'province' => '所属省份', 'parentTypeName' => '费用明细上级',
                'costTypeName' => '费用明细', 'costMoney' => '分摊金额', 'hookMoney' => '累计勾稽金额',
                'thisMonthHookMoney' => '本月勾稽金额', 'unHookMoney' => '未勾稽金额', 'hookStatus' => '勾稽状态',
                'auditor' => '审核人', 'auditDate' => '审核日期'
            ), $data, array('costMoney', 'hookMoney', 'thisMonthHookMoney', 'unHookMoney'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

    /**
     * 快速保存数据
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