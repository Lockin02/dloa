<?php

/**
 * Created on 2011-8-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_common_workflow_workflow extends controller_base_action
{

    function __construct()
    {
        $this->objName = "workflow";
        $this->objPath = "common_workflow";
        parent:: __construct();
    }

    /**
     * 列表页面
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * tab页
     */
    function c_auditTab()
    {
        $selectedCode = isset($_GET['selectedCode']) ? $_GET['selectedCode'] : "";
        if (util_jsonUtil::is_utf8($selectedCode)) {
            $selectedCode = util_jsonUtil::iconvUTF2GB($selectedCode);
        }
        $this->assign('selectedCode', $selectedCode);
        $this->assign('sessionId', $_GET['sessionId']);
        $this->display('audittab');
    }

    /**
     * 未审核列表
     */
    function c_auditingList()
    {
        //可批量审批人员
        $batchAuditLimit = isset($this->service->this_limit['批量审批权限'])
            && $this->service->this_limit['批量审批权限'] ? 1 : 0;
        $this->assign('batchAuditLimit', $batchAuditLimit);

        // 导出权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('common_workflow_workflow', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $canExportLimit = (isset($sysLimit['导出权限']) && $sysLimit['导出权限'] == 1)? 1 : 0;
        $this->assign('canExportLimit', $canExportLimit);

        //获取选择默认值
        if (!empty($_GET['selectedCode'])) {
            $this->assign('selectedCode', $_GET['selectedCode']);
        } else {
            // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d());
            $this->assign('selectedCode', '');
        }

        // 获取用户的自定义列表默认配置
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        $result = $selectedsettingDao->getUserDefaultPersonalSet("auditing");
        unset($result['id']);
        unset($result['userId']);
        unset($result['selectedCode']);
        unset($result['gridId']);
        $this->assignFunc($result);
        $defaultPageSize = (isset($result['defaultPageSize']) && $result['defaultPageSize'] > 0)? $result['defaultPageSize'] : 20;
        $this->assign('defaultPageSize', $defaultPageSize);

        $this->display('auditinglist');
    }

    /**
     * portlet未审核列表
     */
    function c_portletList()
    {
        header("Content-type: text/html;charset=gbk");
        // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d());
        $this->assign('selectedCode', '');
        $this->view('portletlist');
    }

    /**
     * 未审核pagejson
     */
    function c_auditingPageJson()
    {
        $service = $this->service;

        // 用户自定义设置
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        $userPersonalSetArr = array();

        if(isset($_REQUEST['pageSize']) && $_REQUEST['pageSize'] > 0){
            // 更新用户的自定义列表默认数据条数
            $userPersonalSetArr['defaultPageSize'] = $_REQUEST['pageSize'];
        }
        if(isset($_REQUEST['sortType']) && !empty($_REQUEST['sortType'])){
            $userPersonalSetArr['sortType'] = $_REQUEST['sortType'];
            $_POST['searchMaxDate'] = date("Y-m-d");
            switch($_REQUEST['sortType']){
                case 'proCode':// 按项目
                    unset($_REQUEST['sort']);
                    unset($_REQUEST['dir']);
                    $service->sort = 'c.specialSort,c.projectCode';
                    $service->asc = false;
                    break;
                case 'inputMan':// 按提交人
                    unset($_REQUEST['sort']);
                    unset($_REQUEST['dir']);
                    $service->sort = 'u.USER_NAME';
                    $service->asc = false;
                    break;
                default:
                    $service->sort = 'c.start';
                    break;
            }
        }else{
            $userPersonalSetArr['auditedTimeRange'] = '';
        }

        if(!empty($userPersonalSetArr)){
            $selectedsettingDao->updateUserPersonalSet("auditing",$userPersonalSetArr);
        }

        if(isset($_REQUEST['isImptSubsidySrch'])){
            switch (util_jsonUtil::iconvUTF2GB($_REQUEST['isImptSubsidySrch'])){
                case "":
                    break;
                case "是":
                    $_REQUEST['isImptSubsidySrch1'] = 1;
                    break;
                case "否":
                    $_REQUEST['isImptSubsidySrch2'] = 1;
                    break;
                default:
                    $_REQUEST['code'] = "none";
            }
        }

        $service->getParam($_REQUEST); //设置前台获取的参数信息

        //下拉过滤处理
        $formName = '';
        if (isset($_REQUEST['formName'])) {
            $formName = util_jsonUtil:: iconvUTF2GB($_REQUEST['formName']);
            //对于存在变更类型工作流的处理
            if (isset ($service->changeFunArr[$formName])) {
                $service->searchArr[$service->changeFunArr[$formName]['seachCode']] = 1;

                //对于是变更工作流的处理
            } else
                if (isset ($service->urlArr[$formName]['isChange'])) {
                    $service->searchArr[$service->urlArr[$formName]['seachCode']] = 1;
                    $service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
                }
        } else {
            $service->searchArr['inNames'] = $service->rtWorkflowStr_d();
        }

        // 更新用户所选中的审批类型
        // $selectedsettingDao = new model_common_workflow_selectedsetting();
        // $selectedsettingDao->updateUserRecord("auditing",$formName);

        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_auditing');
        if ($rows) {
            //处理变更部分数据
            $rows = $service->rowsDeal_d($rows);
            //处理审批意见部分
            $rows = $service->auditInfo_d($rows);
        }

        // 将查询脚本存入缓存数据中
        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingSql';");
        $sqlForSession = addslashes($service->listSql);
        if($records){// 如果存在则删除并重新写入
            $this->service->_db->query("UPDATE oa_system_session_records SET svalue = '{$sqlForSession}' where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingSql' AND skey = 'ColId';");
        }else{
            $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = 'workflowAuditingSql', skey = 'ColId', svalue = '{$sqlForSession}';");
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 已审核列表
     */
    function c_auditedList()
    {
        //获取选择默认值
        if (!empty($_GET['selectedCode'])) {
            $this->assign('selectedCode', $_GET['selectedCode']);
        } else {
            // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d('audited'));
            $this->assign('selectedCode', '');
        }

        // 获取用户的自定义列表默认配置
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        $result = $selectedsettingDao->getUserDefaultPersonalSet("audited");
        unset($result['id']);
        unset($result['userId']);
        unset($result['selectedCode']);
        unset($result['gridId']);
        $this->assignFunc($result);
        $defaultPageSize = (isset($result['defaultPageSize']) && $result['defaultPageSize'] > 0)? $result['defaultPageSize'] : 20;
        $this->assign('defaultPageSize', $defaultPageSize);

        $this->display('auditedlist');
    }

    /**
     * 已审核pagejson
     */
    function c_auditedPageJson()
    {
        // 更新用户的自定义列表默认数据条数
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        if(isset($_REQUEST['pageSize']) && $_REQUEST['pageSize'] > 0){
            $selectedsettingDao->updateUserRecordPageSize("audited",$_REQUEST['pageSize']);
        }

        $service = $this->service;
        if(isset($_POST['isImptSubsidySrch'])){
            switch (util_jsonUtil::iconvUTF2GB($_POST['isImptSubsidySrch'])){
                case "":
                    break;
                case "是":
                    $_POST['isImptSubsidySrch1'] = 1;
                    break;
                case "否":
                    $_POST['isImptSubsidySrch2'] = 1;
                    break;
                default:
                    $_REQUEST['code'] = "none";
            }
        }

        // 用户自定义设置
        $userPersonalSetArr = array();
        if(isset($_REQUEST['auditedTimeRange']) && !empty($_REQUEST['auditedTimeRange'])){
            $userPersonalSetArr['auditedTimeRange'] = $_REQUEST['auditedTimeRange'];
            $_POST['searchMaxDate'] = date("Y-m-d");
            switch($_REQUEST['auditedTimeRange']){
                case '1':
                    $_POST['searchMinDate'] = date("Y-m-d",strtotime("-1 month"));
                    break;
                case '3':
                    $_POST['searchMinDate'] = date("Y-m-d",strtotime("-3 month"));
                    break;
                case '6':
                    $_POST['searchMinDate'] = date("Y-m-d",strtotime("-6 month"));
                    break;
                default:
                    $_POST['searchMinDate'] = date("Y-m-d",strtotime("-3 month"));
                    $userPersonalSetArr['auditedTimeRange'] = '3';
                    break;
            }
        }else{
            $userPersonalSetArr['auditedTimeRange'] = '';
        }

        if(!empty($userPersonalSetArr)){
            $selectedsettingDao->updateUserPersonalSet("audited",$userPersonalSetArr);
        }

        $service->getParam($_POST); //设置前台获取的参数信息
        if (isset($_POST['last1Year']) && $_POST['last1Year'] == "1") {
            $service->searchArr['last1Year'] = $prev1YearTS = strtotime("-1 year");;
        }

        //下拉过滤处理
        $formName = '';
        if (isset ($_POST['formName'])) {
            $formName = util_jsonUtil:: iconvUTF2GB($_POST['formName']);
            //对于存在变更类型工作流的处理
            if (isset ($service->changeFunArr[$formName])) {
                $service->searchArr[$service->changeFunArr[$formName]['seachCode']] = 1;

                //对于是变更工作流的处理
            } else
                if (isset ($service->urlArr[$formName]['isChange'])) {
                    $service->searchArr[$service->urlArr[$formName]['seachCode']] = 1;
                    $service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
                }
        } else {
            $service->searchArr['inNames'] = $service->rtWorkflowStr_d();
        }

        // 更新用户所选中的审批类型
        // $selectedsettingDao = new model_common_workflow_selectedsetting();
        // $selectedsettingDao->updateUserRecord("audited",$formName);

        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_audited');
        if ($rows) {
            //处理变更部分数据
            $rows = $service->rowsDeal_d($rows);
            //处理审批意见部分
            $rows = $service->auditInfo_d($rows);
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 审批时查看的单据
     */
    function c_toObjInfo()
    {
        $obj = $_GET;
        unset ($obj['model']);
        unset ($obj['action']);
        $tempdb = $this->service->getWfInfo_d($obj['spid']);
        if (!empty ($tempdb['DBTable'])) {
            $dburlstr = '&gdbtable=' . $tempdb['DBTable'];
        } else {
            $dburlstr = '';
        }

        //判断是否有表单名称，没有则直接进行复制
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }
        //		die();

        if (!empty ($this->service->urlArr[$obj['formName']]['url'])) {
            $objId = $obj['billId'];

            //判断跳转路径 - 如果没有特殊配置路径，则默认进入
            $speUrl = $this->service->getSpeUrl_d($obj['formName']);
            if ($speUrl && $tempdb['isEditPage'] == 1) {
                $url = $speUrl . $objId;
            } else {
                $url = $this->service->urlArr[$obj['formName']]['url'] . $objId;
            }

            if ($this->service->urlArr[$obj['formName']]['isSkey']) {
                $url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj'], null);
            }
            $url .= $dburlstr;
            succ_show($url);
        } else {
            echo '未配置完成的审批对象，请联系管理员完成对应配置';
        }
    }

    /**
     * 审批时查看的单据
     */
    function c_ajaxGetUrl()
    {
        $obj = $_POST;
        unset ($obj['model']);
        unset ($obj['action']);
        $tempdb = $this->service->getWfInfo_d($obj['spid']);
        if (!empty ($tempdb['DBTable'])) {
            $dburlstr = '&gdbtable=' . $tempdb['DBTable'];
        } else {
            $dburlstr = '';
        }

        //判断是否有表单名称，没有则直接进行复制
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }
        //		die();

        if (!empty ($this->service->urlArr[$obj['formName']]['url'])) {
            $objId = $obj['billId'];

            //判断跳转路径 - 如果没有特殊配置路径，则默认进入
            $speUrl = $this->service->getSpeUrl_d($obj['formName']);
            if ($speUrl) {
                $url = $speUrl . $objId;
            } else {
                $url = $this->service->urlArr[$obj['formName']]['url'] . $objId;
            }

            if ($this->service->urlArr[$obj['formName']]['isSkey']) {
                $url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj'], null);
            }
            $url .= $dburlstr;
            echo util_jsonUtil::encode(array('url' => $url));
        } else {
            echo -1;
        }
    }

    /**
     * 审批时查看的单据
     */
    function c_ajaxGetViewUrl()
    {
        $obj = $_POST;
        unset ($obj['model']);
        unset ($obj['action']);
        $tempdb = $this->service->getWfInfo_d($obj['spid']);
        if (!empty ($tempdb['DBTable'])) {
            $dburlstr = '&gdbtable=' . $tempdb['DBTable'];
        } else {
            $dburlstr = '&gdbtable=';
        }

        //判断是否有表单名称，没有则直接进行复制
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }

        if (!empty ($this->service->urlArr[$obj['formName']]['viewUrl'])) {
            $objId = $obj['billId'];
            if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_POST['audited']) { //判断是否为变更审批
                $objId = $this->service->getObjIdByTempId_d($objId, $this->service->urlArr[$obj['formName']]['changeCode']);
                $url = $this->service->urlArr[$obj['formName']]['auditedViewUrl'] . $objId;
            } else {
                $url = $this->service->urlArr[$obj['formName']]['viewUrl'] . $objId;
            }

            if ($this->service->urlArr[$obj['formName']]['isSkey']) {
                $url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj'], null);
            }
            $url .= $dburlstr;
            echo util_jsonUtil::encode(array('url' => $url));
        } else {
            echo -1;
        }
    }

    /**
     * 列表查看单据
     */
    function c_toViweObjInfo()
    {
        $obj = $_GET;
        unset ($obj['model']);
        unset ($obj['action']);
        $tempdb = $this->service->getWfInfo_d($obj['spid']);
        if (!empty ($tempdb['DBTable'])) {
            $dburlstr = '&gdbtable=' . $tempdb['DBTable'];
        } else {
            $dburlstr = '&gdbtable=';
        }

        //判断是否有表单名称，没有则直接进行复制
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }

        // 查找对应表单的数据
        $fftArr = $this->service->getFlowFormType($obj['formName']);
        if ($fftArr && isset($fftArr[0]['viewUrl']) && $fftArr[0]['viewUrl'] != '') { // 先从数据库对应表单内配置读取Url
            $formData = $fftArr[0];
            $objId = $obj['billId'];
            if ($formData['isChangeFlow'] && $_GET['audited']) { //判断是否为变更审批
                $objId = $this->service->getObjIdByTempId_d($objId, $formData['changeCode']);
                $url = $formData['viewUrl'] . $objId;
            } else {
                $url = $formData['viewUrl'] . $objId;
            }

            if ($formData['encryptKey'] && $formData['encryptKey'] != '') {
                $url .= '&skey=' . $this->md5Row($objId, $formData['encryptKey'], null);
            }
            $url .= $dburlstr;
            succ_show($url);
        } else if (!empty ($this->service->urlArr[$obj['formName']]['viewUrl'])) { // 如果没有,则用配置文件里面的配置链接
            $objId = $obj['billId'];
            if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_GET['audited']) { //判断是否为变更审批
                $objId = $this->service->getObjIdByTempId_d($objId, $this->service->urlArr[$obj['formName']]['changeCode']);
                $url = $this->service->urlArr[$obj['formName']]['auditedViewUrl'] . $objId;
            } else {
                $url = $this->service->urlArr[$obj['formName']]['viewUrl'] . $objId;
            }

            if ($this->service->urlArr[$obj['formName']]['isSkey']) {
                $url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj'], null);
            }
            $url .= $dburlstr;
            succ_show($url);
        } else {
            echo '未配置完成的审批对象，请联系管理员完成对应配置';
        }
    }

    /**
     * 审批完成后跳转页面
     * edit on 2012-03-01
     * edit by kuangzw
     */
    function c_toLoca()
    {
        //获取工作流相关信息
        $thisFormName = $this->service->getWfInfo_d($_GET['spid']);

        $allStep = isset($this->service->urlArr[$thisFormName['formName']]['allStep']) ? $this->service->urlArr[$thisFormName['formName']]['allStep'] : null;
        if (empty ($allStep) && empty ($thisFormName['examines'])) {
            //如果不是最后一步审批，且没有配置所有步骤调用的工作流,直接返回审批页面
            $this->go('?model=common_workflow_workflow&action=auditingList');
        }

        //如果对应流程存在变更，则调用变更返回路径
        if ($url = $this->service->inChange_d($_GET['spid'])) {
            $this->go($url);
        } else
            if (isset ($this->service->urlArr[$thisFormName['formName']]['rtUrl'])) {
                //直接调用正常审批流程返回路径
                $addStr = null;
                if (isset ($_GET['row'])) {
                    $rows = $_GET['row'];
                    if (is_array($rows)) {
                        foreach ($rows as $key => $val) {
                            $addStr .= '&rows[' . $key . ']=' . $val;
                        }
                    }
                }
                if (!empty ($_GET['gdbtable'])) {
                    $addStr .= '&gdbtable=' . $_GET['gdbtable'];
                }
                $url = $this->service->urlArr[$thisFormName['formName']]['rtUrl'] . $_GET['spid'] . $addStr;
                $this->go($url);
            } else {
                //如果不存在返回路径,则默认跳转到所有审批页面
                $this->go('?model=common_workflow_workflow&action=auditingList');
            }
    }

    /**
     * header go
     * @param $url string
     */
    function go($url)
    {
        $baseUrl = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $baseUrl .= '://' . $_SERVER['HTTP_HOST'];
        if (substr($url, 0, 1) != "?") {
            $projectUrl = str_replace('index1.php', '', $_SERVER['PHP_SELF']);
        } else {
            $projectUrl = $_SERVER['PHP_SELF'];
        }
        header("Location: " . $baseUrl . $projectUrl . $url, TRUE, 302);
    }

    /**
     * 获取工作流类型 - 用于下拉过滤
     */
    function c_getFormType()
    {
        $orgArr = $this->service->rtWorkflowArr_d();
        $newArr = array();
        foreach ($orgArr as $key => $val) {
            $newArr[$key]['text'] = $val;
            $newArr[$key]['value'] = $val;
        }
        echo util_jsonUtil:: encode($newArr);
    }

    /**
     * 判断是否已经存在审批信息
     */
    function c_isAudited()
    {
        $billId = $_POST['billId'];
        $examCode = $_POST['examCode'];
        $rs = $this->service->isAudited_d($billId, $examCode);
        echo $rs;
        exit();
    }

    /**
     * 判断是否已经存在审批信息(合同用)
     */
    function c_isAuditedContract()
    {
        $billId = $_POST['billId'];
        $examCode = $_POST['examCode'];
        $rs = $this->service->isAuditedContract_d($billId, $examCode);
        echo util_jsonUtil:: iconvGB2UTF($rs);
        exit();
    }
    /********************* 收单退单系列 ********************/
    /**
     * 收单操作
     */
    function c_receiveForm()
    {
        //获取工作流相关信息
        $wfInfo = $this->service->getWfInfo_d($_POST['spid']);
        //		print_r($wfInfo);
        $rs = $this->service->receiveForm_d($wfInfo['taskId'], $wfInfo['formName'], $wfInfo['objId']);
        echo $rs;
        exit();
    }

    /**
     * 退单操作
     */
    function c_backForm()
    {
        //获取工作流相关信息
        $wfInfo = $this->service->getWfInfo_d($_POST['spid']);
        $rs = $this->service->backForm_d($wfInfo['taskId'], $wfInfo['formName'], $wfInfo['objId']);
        echo $rs;
        exit();
    }

    /******************** 批量审批系列 *****************/
    /**
     * 批量审批页面
     */
    function c_toBatchAudit()
    {
        $this->assignFunc($_GET);

        //数据获取以及渲染
        $auditInfo = $this->service->getAuditInfo_d($_GET['spids']);
        $this->assign('needAudit', $this->service->initAuditInfo_d($auditInfo));

        $this->display('batchaudit');
    }

    /**
     * 批量审批 - 一张单
     */
    function c_ajaxAudit()
    {
        $content = util_jsonUtil:: iconvUTF2GB($_REQUEST['content']);
        $rs = $this->service->ajaxAudit_d($_REQUEST['spid'], $_REQUEST['result'], $content,
            $_REQUEST['isSend'], $_REQUEST['isSendNext']);
        if ($rs === true) {
            echo 1;
        } else {
            echo util_jsonUtil:: iconvGB2UTF($rs);
        }
    }

    /**
     * 获取可批量审批内容
     */
    function c_getBatchAudit()
    {
        $orgArr = $this->service->getBatchAudit_d();

        $newArr = array(0 => array('text' => '--批审类型--', 'value' => implode(',', $orgArr)));
        $i = 1;
        foreach ($orgArr as $key => $val) {
            $newArr[$i]['text'] = $val;
            $newArr[$i]['value'] = $val;
            $i++;
        }
        echo util_jsonUtil:: encode($newArr);
    }

    /**
     * 获取可批量审批内容
     */
    function c_getIncludesFormName()
    {
        $type = isset($_REQUEST['listType'])? $_REQUEST['listType'] : "auditing";
        if($type == "auditing"){
            $orgArr = $this->service->getAuditingIncludesFormName();
        }else{
            $orgArr = $this->service->getAuditedIncludesFormName();
        }

        if(isset($_REQUEST['needAll']) && $_REQUEST['needAll'] == 1){
            $newArr = array(0 => array('text' => '--批审类型--', 'value' => implode(',', $orgArr)));
            $i = 1;
        }else{
            $i = 0;
        }

        foreach ($orgArr as $key => $val) {
            $newArr[$i]['text'] = $val;
            $newArr[$i]['value'] = $val;
            $i++;
        }
        $backArr = array(
            "data" => $newArr
        );
        echo util_jsonUtil:: encode($backArr);
    }

    /******************** 单据审批数据获取 *****************/
    /**
     * 获取单据的审批情况数据
     */
    function c_getBoAuditJson()
    {
        echo util_jsonUtil::encode($this->service->getBoAuditList_d($_POST['pid'], $_POST['code']));
    }

    /**
     * 获取分类办理
     */
    function c_getCategoryList()
    {
        echo util_jsonUtil::encode($this->service->getCategoryList_d(util_jsonUtil::iconvUTF2GB($_REQUEST['formName'])));
    }

    /**
     * 获取分类办理
     */
    function c_getAuditedCategoryList()
    {
        echo util_jsonUtil::encode($this->service->getAuditedCategoryList_d(util_jsonUtil::iconvUTF2GB($_REQUEST['formName'])));
    }

    /* ======================================== 新审批页面（开始） ======================================== */
    /**
     * 新审批页面
     * 2016-12-28
     */
    function c_toAudit()
    {
        $hiddenParam = '';
        // 根据传入的参数获取审批流所需数据
        $obj = $this->service->formatParamProcess($_GET);
        foreach ($obj as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $subk => $subv) {
                    $hiddenParam .= '<input type="hidden" class="dftDataArr" name="' . $k . '[' . $subk . ']" id="' . $k . '_' . $subk . '" value="' . $subv . '">';
                }
            } else {
                $hiddenParam .= '<input type="hidden" class="dftData" name="' . $k . '" id="' . $k . '" value="' . $v . '">';
            }
        }

        //显示页面
        $this->assign('hiddenParam', $hiddenParam);
        $this->display('auditpage2');
    }

    /**
     * 获取可审核流程获取
     */
    function c_getFlow()
    {
        $obj = util_jsonUtil::iconvUTF2GBArr($_POST);
        $backArr = $this->service->getFlow_d($obj);
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * 获取审批流程视图Json数据
     * 2016-12-28
     */
    function c_getProcessViewJson()
    {
        $obj = isset($_REQUEST['process']) ? $_REQUEST['process'] : array();
        $backArr['msg'] = '';
        $backArr['data'] = array();
        $processViewData = $this->service->getProcess_d($obj);
        if (!empty($processViewData)) {
            $backArr['msg'] = 'ok';
            $backArr['data'] = $processViewData;
        }
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * 处理提交的审批信息
     * 2016-12-24
     */
    function c_saveAudit()
    {
        $auditObj = $this->service->formatParamProcess($_POST);

        // 如果缺少记录ID或用户ID直接报错退出
        if (!isset($auditObj['billId']) || $_SESSION['USER_ID'] == "") {
            echo "数据传送失败";
            exit();
        }

        // 是否发邮件标示字段
        $auditObj['isSendNotify'] = isset($_POST["isSendNotify"]) ? $_POST["isSendNotify"] : 0;

        // 不缺记录ID或用户ID则后台处理审批流表单
        $auditObj = util_jsonUtil::iconvUTF2GBArr($auditObj);
        $auditObj = $this->service->joinFormData($auditObj);

        $sendToURL = '';
        $auditResult = $this->service->saveAuditApply_d($auditObj, $sendToURL);
        echo util_jsonUtil::encode($auditResult);
    }
    /* ======================================== 新审批页面（结束） ======================================== */

    /* ======================================== 新审批流配置页面（开始） ======================================== */
    /**
     * 跳转到表单配置页
     */
    function c_toViewForm()
    {
        $this->view('configForm');
    }

    /**
     * 获取审批流表单数据
     */
    function c_listForms()
    {
        $service = $this->service;
        $sql = "select FORM_ID AS id,FORM_NAME AS text from flow_form_type ORDER BY FORM_NAME;";
        $datas = $service->_db->getArray($sql);
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }

    /**
     * 根据表单ID获取表单信息
     */
    function c_getFormById()
    {
        $service = $this->service;
        $id = $_POST['id'];
        $sql = "select * from flow_form_type where FORM_ID ='{$id}';";
        $datas = $service->_db->getArray($sql);
        $backData['msg'] = '';
        $backData['data'] = array();
        if ($datas && !empty($datas)) {
            $backData['msg'] = 'ok';
            $backData['data'] = $datas[0];
        }
        echo util_jsonUtil:: encode($backData);
    }

    /**
     * 根据表单ID获取流程信息列表
     */
    function c_getWfByFormId()
    {
        $service = $this->service;
        $obj = $_REQUEST;
        $pageNum = isset($obj['page']) ? $obj['page'] : 0;
        $pageSize = isset($obj['rows']) ? $obj['rows'] : 0;
        $formId = isset($obj['formId']) ? $obj['formId'] : '';
        $pageStart = ($pageNum - 1) * $pageSize;
        $pageEnd = $pageSize;
        $limit = " LIMIT {$pageStart},{$pageEnd}";
        $rows = $service->getWfByFormId_d($formId, '', '', '', $limit);
        $total = $service->countWfByFormId_d($formId);
        $backData = array();
        if (!empty($rows)) {
            foreach ($rows as $k => $v) {
                $rows[$k]['CreatorName'] = $service->getUserByUid($v['Creator']);
                $rows[$k]['EnterUserName'] = $service->getUserByUid($v['Enter_user']);
            }
        }
        $backData['rows'] = $rows;
        $backData['total'] = $total;
        echo util_jsonUtil:: encode($backData);
    }

    /**
     * 根据流程ID获取流程数据
     */
    function c_getFlowById()
    {
        $service = $this->service;
        $obj = $_REQUEST;
        $flowId = isset($obj['flowId']) ? $obj['flowId'] : '';
        $rows = $service->getWfByFormId_d('', "FLOW_ID = '{$flowId}'");
        if (!empty($rows)) {
            foreach ($rows as $k => $v) {
                $rows[$k]['CreatorName'] = $service->getUserByUid($v['Creator']);
                $rows[$k]['EnterUserName'] = $service->getUserByUid($v['Enter_user']);
            }
        }
        echo util_jsonUtil:: encode($rows);
    }

    /**
     * 跳转【编辑/新增】表单流程页面
     */
    function c_toModifyFlow()
    {
        $service = $this->service;
        $obj = $_REQUEST;
        $flowId = isset($obj['flowId']) ? $obj['flowId'] : '';
        $modifyType = isset($obj['modifyType']) ? $obj['modifyType'] : '';
        $dataArr = array(
            "FLOW_NAME" => "flowName",
            "FLOW_ID" => "flowId",
            "FORM_ID" => "flow_formId",
            "Idate" => "createTime",
            "Enter_user" => "enterUserId",
            "EnterUserName" => "enterUser",
            "Creator" => "creatorId",
            "CreatorName" => "creator",
            "MinMoney" => "minMoney",
            "MaxMoney" => "maxMoney",
            "filtingSql" => "filtingSql",
            "filtingClass" => "filtingClass",
            "filtingFun" => "filtingFun"
        );
        $wfClassArr = $service->getWfClass(); //公文类别数组
        $flowTypeArr = array("1" => "固定流程"); //array("1"=>"固定流程","2"=>"自由流程");//流程类别数组
        $wfClassOptStr = $flowTypeOptStr = '';
        if ($modifyType == 'Edit') {
            $this->assign('title', '编辑流程');
            $this->assign('modifyType', 'edit');
            $rows = $service->getWfByFormId_d('', "FLOW_ID = '{$flowId}'");
            if (!empty($rows)) {
                foreach ($rows as $k => $v) {
                    $rows[$k]['CreatorName'] = $service->getUserByUid($v['Creator']);
                    $rows[$k]['EnterUserName'] = $service->getUserByUid($v['Enter_user']);
                }
            }
            if (!empty($rows)) {
                $row = $rows[0];
                foreach ($row as $key => $val) {
                    if (isset($dataArr[$key])) {
                        $this->assign($dataArr[$key], $val);
                    }
                    if ($key == 'ClassID') { //公文类别选项
                        foreach ($wfClassArr as $v) {
                            if ($val == $v['class_id']) {
                                $wfClassOptStr .= "<option value='{$v['class_id']}' selected>{$v['name']}</option>";
                            } else {
                                $wfClassOptStr .= "<option value='{$v['class_id']}'>{$v['name']}</option>";
                            }
                        }
                    }
                    if ($key == 'FLOW_TYPE') { //流程类别选项
                        foreach ($flowTypeArr as $k => $v) {
                            if ($val == $k) {
                                $flowTypeOptStr .= "<option value='{$k}' selected>{$v}</option>";
                            } else {
                                $flowTypeOptStr .= "<option value='{$k}'>{$v}</option>";
                            }
                        }
                    }
                }
            }
        } else if ($modifyType == 'Add') {
            $formId = isset($obj['formId']) ? $obj['formId'] : '';
            $this->assign('title', '新增流程');
            $this->assign('modifyType', 'add');
            foreach ($wfClassArr as $v) { //公文类别选项
                $wfClassOptStr .= "<option value='{$v['class_id']}'>{$v['name']}</option>";
            }
            foreach ($flowTypeArr as $k => $v) { //流程类别选项
                $flowTypeOptStr .= "<option value='{$k}'>{$v}</option>";
            }
            foreach ($dataArr as $key => $val) {
                if ($key == 'FORM_ID') {
                    $this->assign($val, $formId);
                } else if ($key == 'Idate') { //创建时间
                    $this->assign($val, date('Y-m-d'));
                } else {
                    $this->assign($val, '');
                }
            }
            $this->assign('enterUserId', $_SESSION['USER_ID']);
            $this->assign('enterUser', $_SESSION['USER_NAME']);
            $this->assign('creatorId', $_SESSION['USER_ID']);
            $this->assign('creator', $_SESSION['USER_NAME']);
        }

        $this->assign('wfClass', $wfClassOptStr);
        $this->assign('flowType', $flowTypeOptStr);
        $this->view('toModifyFlow');
    }

    function c_listSteps()
    {
        $service = $this->service;
        $flowId = isset($_REQUEST['flowId']) ? $_REQUEST['flowId'] : '';
        $sql = "select * from flow_process where flow_id = '{$flowId}';";

        $arr = $this->service->_db->getArray($sql);
        if (!empty($arr)) {
            foreach ($arr as $k => $v) {
                $arr[$k]['show_ProcessUserName'] = $service->getUserByUid($v['PRCS_USER']);
                $arr[$k]['show_SpecName'] = $service->getUserByUid($v['PRCS_SPEC']);
            }
        }
        $rows = $this->sconfig->md5Rows($arr);
        echo util_jsonUtil::encode($rows);
    }


    /* ============ 接受 / 处理 数据（开始） ============ */
    /**
     * 编辑表单
     */
    function c_editForm()
    {
        $service = $this->service;
        $obj = $_POST[$this->objName];
        foreach ($obj as $k => $v) {
            $obj[$k] = stripslashes($v);
        }
        $formId = isset($obj['FORM_ID']) ? $obj['FORM_ID'] : '';
        $condition = array("FORM_ID" => $formId);
        $service->tbl_name = "flow_form_type";
        $result = $service->update($condition, $obj);
        if ($result) {
            msg('添加成功！');
            succ_show('index1.php?model=common_workflow_workflow&action=toViewForm');
        } else {
            msg('添加失败！');
            succ_show('index1.php?model=common_workflow_workflow&action=toViewForm');
        }
    }

    function c_getLogicOpts()
    {
        $service = $this->service;
        $result = $service->getLogicOpts();

        $backData['msg'] = ($result) ? 'ok' : 'fail';
        $backData['data'] = $result;

        echo util_jsonUtil::encode($backData);
    }

    /**
     * 编辑流程
     */
    function c_modifyFlow()
    {
        $service = $this->service;
        $flowObj = $_POST['flow'];
        $stepObj = $_POST['step'];
        $flowObj = util_jsonUtil::iconvUTF2GBArr($flowObj);
        $stepObj = util_jsonUtil::iconvUTF2GBArr($stepObj);
        $modifyType = $_POST['modifyType'];
        $result['msg'] = '';
        $result['postData'] = $_POST;
        $newFlowId = '';
        $service->tbl_name = "flow_type";
        switch ($modifyType) {
            case 'add':
                foreach ($flowObj as $k => $v) {
                    $flowObj[$k] = stripslashes($v);
                }
                $newFlowId = $service->add_d($flowObj);
                if ($newFlowId) {
                    $result['msg'] = 'ok';
                }
                break;
            case 'edit':
                if ($flowObj['FLOW_ID'] != '') {
                    $conditions['FLOW_ID'] = $flowObj['FLOW_ID'];
                    foreach ($flowObj as $k => $v) {
                        $flowObj[$k] = stripslashes($v);
                    }
                    $edit_result = $service->update($conditions, $flowObj);
                    if ($edit_result) {
                        $result['msg'] = 'ok';
                    }
                }
                break;
        }

        // 处理流程步骤
        if ($result['msg'] == 'ok') {
//            echo"<pre>";print_r($stepObj);exit();
            $service->tbl_name = "flow_process";
            $errorNum = 0;
            $newStepIdsArr = array(); // 为处理编辑时删除步骤用
            foreach ($stepObj as $stepK => $stepV) {
                if ($stepV['ID'] == '') { //新增
                    unset($stepV['rowNum_']);
                    $stepV['FLOW_ID'] = ($modifyType == 'add') ? $newFlowId : $flowObj['FLOW_ID'];
                    foreach ($stepV as $k => $v) {
                        $stepV[$k] = stripslashes($v);
                    }
                    $newStepId = $service->add_d($stepV);
                    $newStepIdsArr[] = $newStepId;
                    $errorNum += ($newStepId) ? 0 : 1;
                } else { // 编辑
                    $newStepIdsArr[] = $stepV['ID'];
                    $conditions['ID'] = $stepV['ID'];
                    foreach ($stepV as $k => $v) {
                        $stepV[$k] = stripslashes($v);
                    }
                    $editStep_result = $service->update($conditions, $stepV);
                    $errorNum += ($editStep_result) ? 0 : 1;
                }
            }

            // 处理删除列
            if ($modifyType == 'edit') {
                //获取该流程下原来的步骤ID
                $sql = "select ID from flow_process where flow_id = '{$flowObj['FLOW_ID']}';";
                $arr = $this->service->_db->getArray($sql);
                if ($arr) {
                    foreach ($arr as $k => $v) {
                        if (!in_array($v['ID'], $newStepIdsArr)) {
                            $conditions['ID'] = $v['ID'];
                            $delresult = $service->delete($conditions);
                            $errorNum += ($delresult) ? 0 : 1;
                        }
                    }
                }
            }
            $result['msg'] = ($errorNum > 0) ? '' : 'ok';
        }
        echo util_jsonUtil:: encode($result);
    }

    /**
     * 删除流程
     */
    function c_delFlowById()
    {
        $service = $this->service;
        $flowId = isset($_POST['flowId']) ? $_POST['flowId'] : '';
        if ($flowId != '') {
            $service->tbl_name = "flow_type";
            $conditions['FLOW_ID'] = $flowId;
            $delresult = $service->delete($conditions);
            echo $delresult ? 'ok' : '';
        } else {
            echo '';
        }
    }
    /* ============ 接受 / 处理 数据（结束） ============ */

    /* ======================================== 新审批流配置页面（结束） ======================================== */

    /* ======================================== 业务办理页面（开始） ======================================== */
    function c_toProcessPage()
    {
        if (!WXURL) {
            die('未设置相关跳转路径');
        }
        $sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
        $taskId = isset($_REQUEST['taskId']) ? $_REQUEST['taskId'] : '';
        $spid = isset($_REQUEST['spid']) ? $_REQUEST['spid'] : '';
        $examCode = isset($_REQUEST['examCode']) ? $_REQUEST['examCode'] : '';
        $billId = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : '';
        $formName = isset($_REQUEST['formName']) ? $_REQUEST['formName'] : '';
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $isTemp = isset($_REQUEST['isTemp']) ? $_REQUEST['isTemp'] : '';
        $reCmd = isset($_REQUEST['reCmd']) ? $_REQUEST['reCmd'] : '';

        // 返回页面链接
        $backUrl = WXURL . 'r/w?sid=' . $sid .
            '&cmd=com.youngheart.apps.mobile.workflow_page&formName=' . util_jsonUtil::iconvGB2UTF($formName);

        $this->assign('backUrl', $backUrl);
        $this->assign('sid', $sid);
        $this->assign('taskId', $taskId);
        $this->assign('spid', $spid);
        $this->assign('examCode', $examCode);
        $this->assign('billId', $billId);
        $this->assign('formName', $formName);
        $this->assign('code', $code);
        $this->assign('isTemp', $isTemp);
        $this->assign('reCmd', $reCmd);
        $this->display("processPage");
    }
    /* ======================================== 业务办理页面（结束） ======================================== */

    /* ======================================== 业务已办页面（开始） ======================================== */
    function c_toProcessedPage()
    {
        if (!WXURL) {
            die('未设置相关跳转路径');
        }
        $sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
        $taskId = isset($_REQUEST['taskId']) ? $_REQUEST['taskId'] : '';
        $spid = isset($_REQUEST['spid']) ? $_REQUEST['spid'] : '';
        $examCode = isset($_REQUEST['examCode']) ? $_REQUEST['examCode'] : '';
        $billId = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : '';
        $formName = isset($_REQUEST['formName']) ? $_REQUEST['formName'] : '';
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $isTemp = isset($_REQUEST['isTemp']) ? $_REQUEST['isTemp'] : '';
        $reCmd = isset($_REQUEST['reCmd']) ? $_REQUEST['reCmd'] : '';

        // 返回页面链接
        $backUrl = WXURL . 'r/w?sid=' . $sid .
            '&cmd=com.youngheart.apps.mobile.workflow_audited&formName=' . util_jsonUtil::iconvGB2UTF($formName);

        $this->assign('backUrl', $backUrl);
        $this->assign('sid', $sid);
        $this->assign('taskId', $taskId);
        $this->assign('spid', $spid);
        $this->assign('examCode', $examCode);
        $this->assign('billId', $billId);
        $this->assign('formName', $formName);
        $this->assign('code', $code);
        $this->assign('isTemp', $isTemp);
        $this->assign('reCmd', $reCmd);
        $this->display("processedPage");
    }
    /* ======================================== 业务已办页面（结束） ======================================== */

    /* ======================================== 审批流办理情况（开始） ======================================== */
    /**
     * 页面部分
     */
    function c_toViewMobile()
    {
        $this->assignFunc($_GET);
        $this->display("viewMobile");
    }

    /**
     * 数据部分
     */
    function c_viewMobile()
    {
        echo util_jsonUtil::encode($this->service->viewMobile_d($_POST['pid'], $_POST['itemType']));
    }
    /* ======================================== 审批流办理情况（结束） ======================================== */

    /**
     * 设置导出表头的字段信息
     */
    function c_setColInfoToSession(){
        $_REQUEST = util_jsonUtil::iconvUTF2GBArr($_REQUEST);
        $ColId = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $ColName = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : 'workflowAuditingData';

        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        if($records){// 如果存在则删除并重新写入
            $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        }

        $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColId', svalue = '{$ColId}';");
        $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColName', svalue = '{$ColName}';");
        echo 1;
    }

    /**
     * 导出列表数据
     */
    function c_exportData(){
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : '';

        // 获取列表缓存的脚本以及查出对应的列表数据
        $dataSql = '';
        switch($stype){
            case 'workflowAuditingData':// 待办列表导出
                $sqlSessionArr = $this->service->_db->get_one("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingSql';");
                $dataSql = $sqlSessionArr['svalue'];
                break;
        }
        $data = $this->service->_db->getArray($dataSql);

        if($data){
            // 获取列表的表头字段以及表头名称的缓存数据
            $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingData';");
            if($records){
                foreach ($records as $record){
                    if(isset($record['skey']) && $record['skey'] == 'ColId'){
                        $colIdStr = $record['svalue'];
                    }else if(isset($record['skey']) && $record['skey'] == 'ColName'){
                        $colNameStr = $record['svalue'];
                    }
                }
                $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingData';");
            }else{
                $colIdStr = '';
                $colNameStr = '';
            }

            //表头Id数组
            $colIdArr = explode(',', $colIdStr);
            $colIdArr = array_filter($colIdArr);
            //表头Name数组
            $colNameArr = explode(',', $colNameStr);
            $colNameArr = array_filter($colNameArr);
            //表头数组
            $colArr = array_combine($colIdArr, $colNameArr);
            if(!empty($colArr)){
                return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $data);
            }else{
                echo "表头字段缓存丢失,请重试~";
            }
        }
    }
}