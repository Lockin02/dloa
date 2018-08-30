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
     * �б�ҳ��
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * tabҳ
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
     * δ����б�
     */
    function c_auditingList()
    {
        //������������Ա
        $batchAuditLimit = isset($this->service->this_limit['��������Ȩ��'])
            && $this->service->this_limit['��������Ȩ��'] ? 1 : 0;
        $this->assign('batchAuditLimit', $batchAuditLimit);

        // ����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('common_workflow_workflow', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $canExportLimit = (isset($sysLimit['����Ȩ��']) && $sysLimit['����Ȩ��'] == 1)? 1 : 0;
        $this->assign('canExportLimit', $canExportLimit);

        //��ȡѡ��Ĭ��ֵ
        if (!empty($_GET['selectedCode'])) {
            $this->assign('selectedCode', $_GET['selectedCode']);
        } else {
            // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d());
            $this->assign('selectedCode', '');
        }

        // ��ȡ�û����Զ����б�Ĭ������
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
     * portletδ����б�
     */
    function c_portletList()
    {
        header("Content-type: text/html;charset=gbk");
        // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d());
        $this->assign('selectedCode', '');
        $this->view('portletlist');
    }

    /**
     * δ���pagejson
     */
    function c_auditingPageJson()
    {
        $service = $this->service;

        // �û��Զ�������
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        $userPersonalSetArr = array();

        if(isset($_REQUEST['pageSize']) && $_REQUEST['pageSize'] > 0){
            // �����û����Զ����б�Ĭ����������
            $userPersonalSetArr['defaultPageSize'] = $_REQUEST['pageSize'];
        }
        if(isset($_REQUEST['sortType']) && !empty($_REQUEST['sortType'])){
            $userPersonalSetArr['sortType'] = $_REQUEST['sortType'];
            $_POST['searchMaxDate'] = date("Y-m-d");
            switch($_REQUEST['sortType']){
                case 'proCode':// ����Ŀ
                    unset($_REQUEST['sort']);
                    unset($_REQUEST['dir']);
                    $service->sort = 'c.specialSort,c.projectCode';
                    $service->asc = false;
                    break;
                case 'inputMan':// ���ύ��
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
                case "��":
                    $_REQUEST['isImptSubsidySrch1'] = 1;
                    break;
                case "��":
                    $_REQUEST['isImptSubsidySrch2'] = 1;
                    break;
                default:
                    $_REQUEST['code'] = "none";
            }
        }

        $service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //�������˴���
        $formName = '';
        if (isset($_REQUEST['formName'])) {
            $formName = util_jsonUtil:: iconvUTF2GB($_REQUEST['formName']);
            //���ڴ��ڱ�����͹������Ĵ���
            if (isset ($service->changeFunArr[$formName])) {
                $service->searchArr[$service->changeFunArr[$formName]['seachCode']] = 1;

                //�����Ǳ���������Ĵ���
            } else
                if (isset ($service->urlArr[$formName]['isChange'])) {
                    $service->searchArr[$service->urlArr[$formName]['seachCode']] = 1;
                    $service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
                }
        } else {
            $service->searchArr['inNames'] = $service->rtWorkflowStr_d();
        }

        // �����û���ѡ�е���������
        // $selectedsettingDao = new model_common_workflow_selectedsetting();
        // $selectedsettingDao->updateUserRecord("auditing",$formName);

        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_auditing');
        if ($rows) {
            //��������������
            $rows = $service->rowsDeal_d($rows);
            //���������������
            $rows = $service->auditInfo_d($rows);
        }

        // ����ѯ�ű����뻺��������
        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingSql';");
        $sqlForSession = addslashes($service->listSql);
        if($records){// ���������ɾ��������д��
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
     * ������б�
     */
    function c_auditedList()
    {
        //��ȡѡ��Ĭ��ֵ
        if (!empty($_GET['selectedCode'])) {
            $this->assign('selectedCode', $_GET['selectedCode']);
        } else {
            // $this->assign('selectedCode', $this->service->getPersonSelectedSetting_d('audited'));
            $this->assign('selectedCode', '');
        }

        // ��ȡ�û����Զ����б�Ĭ������
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
     * �����pagejson
     */
    function c_auditedPageJson()
    {
        // �����û����Զ����б�Ĭ����������
        $selectedsettingDao = new model_common_workflow_selectedsetting();
        if(isset($_REQUEST['pageSize']) && $_REQUEST['pageSize'] > 0){
            $selectedsettingDao->updateUserRecordPageSize("audited",$_REQUEST['pageSize']);
        }

        $service = $this->service;
        if(isset($_POST['isImptSubsidySrch'])){
            switch (util_jsonUtil::iconvUTF2GB($_POST['isImptSubsidySrch'])){
                case "":
                    break;
                case "��":
                    $_POST['isImptSubsidySrch1'] = 1;
                    break;
                case "��":
                    $_POST['isImptSubsidySrch2'] = 1;
                    break;
                default:
                    $_REQUEST['code'] = "none";
            }
        }

        // �û��Զ�������
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

        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        if (isset($_POST['last1Year']) && $_POST['last1Year'] == "1") {
            $service->searchArr['last1Year'] = $prev1YearTS = strtotime("-1 year");;
        }

        //�������˴���
        $formName = '';
        if (isset ($_POST['formName'])) {
            $formName = util_jsonUtil:: iconvUTF2GB($_POST['formName']);
            //���ڴ��ڱ�����͹������Ĵ���
            if (isset ($service->changeFunArr[$formName])) {
                $service->searchArr[$service->changeFunArr[$formName]['seachCode']] = 1;

                //�����Ǳ���������Ĵ���
            } else
                if (isset ($service->urlArr[$formName]['isChange'])) {
                    $service->searchArr[$service->urlArr[$formName]['seachCode']] = 1;
                    $service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
                }
        } else {
            $service->searchArr['inNames'] = $service->rtWorkflowStr_d();
        }

        // �����û���ѡ�е���������
        // $selectedsettingDao = new model_common_workflow_selectedsetting();
        // $selectedsettingDao->updateUserRecord("audited",$formName);

        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_audited');
        if ($rows) {
            //��������������
            $rows = $service->rowsDeal_d($rows);
            //���������������
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
     * ����ʱ�鿴�ĵ���
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

        //�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }
        //		die();

        if (!empty ($this->service->urlArr[$obj['formName']]['url'])) {
            $objId = $obj['billId'];

            //�ж���ת·�� - ���û����������·������Ĭ�Ͻ���
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
            echo 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
        }
    }

    /**
     * ����ʱ�鿴�ĵ���
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

        //�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }
        //		die();

        if (!empty ($this->service->urlArr[$obj['formName']]['url'])) {
            $objId = $obj['billId'];

            //�ж���ת·�� - ���û����������·������Ĭ�Ͻ���
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
     * ����ʱ�鿴�ĵ���
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

        //�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }

        if (!empty ($this->service->urlArr[$obj['formName']]['viewUrl'])) {
            $objId = $obj['billId'];
            if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_POST['audited']) { //�ж��Ƿ�Ϊ�������
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
     * �б�鿴����
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

        //�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
        if (empty($obj['formName'])) {
            $obj['formName'] = $tempdb['formName'];
        }

        // ���Ҷ�Ӧ��������
        $fftArr = $this->service->getFlowFormType($obj['formName']);
        if ($fftArr && isset($fftArr[0]['viewUrl']) && $fftArr[0]['viewUrl'] != '') { // �ȴ����ݿ��Ӧ�������ö�ȡUrl
            $formData = $fftArr[0];
            $objId = $obj['billId'];
            if ($formData['isChangeFlow'] && $_GET['audited']) { //�ж��Ƿ�Ϊ�������
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
        } else if (!empty ($this->service->urlArr[$obj['formName']]['viewUrl'])) { // ���û��,���������ļ��������������
            $objId = $obj['billId'];
            if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_GET['audited']) { //�ж��Ƿ�Ϊ�������
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
            echo 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
        }
    }

    /**
     * ������ɺ���תҳ��
     * edit on 2012-03-01
     * edit by kuangzw
     */
    function c_toLoca()
    {
        //��ȡ�����������Ϣ
        $thisFormName = $this->service->getWfInfo_d($_GET['spid']);

        $allStep = isset($this->service->urlArr[$thisFormName['formName']]['allStep']) ? $this->service->urlArr[$thisFormName['formName']]['allStep'] : null;
        if (empty ($allStep) && empty ($thisFormName['examines'])) {
            //����������һ����������û���������в�����õĹ�����,ֱ�ӷ�������ҳ��
            $this->go('?model=common_workflow_workflow&action=auditingList');
        }

        //�����Ӧ���̴��ڱ��������ñ������·��
        if ($url = $this->service->inChange_d($_GET['spid'])) {
            $this->go($url);
        } else
            if (isset ($this->service->urlArr[$thisFormName['formName']]['rtUrl'])) {
                //ֱ�ӵ��������������̷���·��
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
                //��������ڷ���·��,��Ĭ����ת����������ҳ��
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
     * ��ȡ���������� - ������������
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
     * �ж��Ƿ��Ѿ�����������Ϣ
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
     * �ж��Ƿ��Ѿ�����������Ϣ(��ͬ��)
     */
    function c_isAuditedContract()
    {
        $billId = $_POST['billId'];
        $examCode = $_POST['examCode'];
        $rs = $this->service->isAuditedContract_d($billId, $examCode);
        echo util_jsonUtil:: iconvGB2UTF($rs);
        exit();
    }
    /********************* �յ��˵�ϵ�� ********************/
    /**
     * �յ�����
     */
    function c_receiveForm()
    {
        //��ȡ�����������Ϣ
        $wfInfo = $this->service->getWfInfo_d($_POST['spid']);
        //		print_r($wfInfo);
        $rs = $this->service->receiveForm_d($wfInfo['taskId'], $wfInfo['formName'], $wfInfo['objId']);
        echo $rs;
        exit();
    }

    /**
     * �˵�����
     */
    function c_backForm()
    {
        //��ȡ�����������Ϣ
        $wfInfo = $this->service->getWfInfo_d($_POST['spid']);
        $rs = $this->service->backForm_d($wfInfo['taskId'], $wfInfo['formName'], $wfInfo['objId']);
        echo $rs;
        exit();
    }

    /******************** ��������ϵ�� *****************/
    /**
     * ��������ҳ��
     */
    function c_toBatchAudit()
    {
        $this->assignFunc($_GET);

        //���ݻ�ȡ�Լ���Ⱦ
        $auditInfo = $this->service->getAuditInfo_d($_GET['spids']);
        $this->assign('needAudit', $this->service->initAuditInfo_d($auditInfo));

        $this->display('batchaudit');
    }

    /**
     * �������� - һ�ŵ�
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
     * ��ȡ��������������
     */
    function c_getBatchAudit()
    {
        $orgArr = $this->service->getBatchAudit_d();

        $newArr = array(0 => array('text' => '--��������--', 'value' => implode(',', $orgArr)));
        $i = 1;
        foreach ($orgArr as $key => $val) {
            $newArr[$i]['text'] = $val;
            $newArr[$i]['value'] = $val;
            $i++;
        }
        echo util_jsonUtil:: encode($newArr);
    }

    /**
     * ��ȡ��������������
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
            $newArr = array(0 => array('text' => '--��������--', 'value' => implode(',', $orgArr)));
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

    /******************** �����������ݻ�ȡ *****************/
    /**
     * ��ȡ���ݵ������������
     */
    function c_getBoAuditJson()
    {
        echo util_jsonUtil::encode($this->service->getBoAuditList_d($_POST['pid'], $_POST['code']));
    }

    /**
     * ��ȡ�������
     */
    function c_getCategoryList()
    {
        echo util_jsonUtil::encode($this->service->getCategoryList_d(util_jsonUtil::iconvUTF2GB($_REQUEST['formName'])));
    }

    /**
     * ��ȡ�������
     */
    function c_getAuditedCategoryList()
    {
        echo util_jsonUtil::encode($this->service->getAuditedCategoryList_d(util_jsonUtil::iconvUTF2GB($_REQUEST['formName'])));
    }

    /* ======================================== ������ҳ�棨��ʼ�� ======================================== */
    /**
     * ������ҳ��
     * 2016-12-28
     */
    function c_toAudit()
    {
        $hiddenParam = '';
        // ���ݴ���Ĳ�����ȡ��������������
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

        //��ʾҳ��
        $this->assign('hiddenParam', $hiddenParam);
        $this->display('auditpage2');
    }

    /**
     * ��ȡ��������̻�ȡ
     */
    function c_getFlow()
    {
        $obj = util_jsonUtil::iconvUTF2GBArr($_POST);
        $backArr = $this->service->getFlow_d($obj);
        echo util_jsonUtil::encode($backArr);
    }

    /**
     * ��ȡ����������ͼJson����
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
     * �����ύ��������Ϣ
     * 2016-12-24
     */
    function c_saveAudit()
    {
        $auditObj = $this->service->formatParamProcess($_POST);

        // ���ȱ�ټ�¼ID���û�IDֱ�ӱ����˳�
        if (!isset($auditObj['billId']) || $_SESSION['USER_ID'] == "") {
            echo "���ݴ���ʧ��";
            exit();
        }

        // �Ƿ��ʼ���ʾ�ֶ�
        $auditObj['isSendNotify'] = isset($_POST["isSendNotify"]) ? $_POST["isSendNotify"] : 0;

        // ��ȱ��¼ID���û�ID���̨������������
        $auditObj = util_jsonUtil::iconvUTF2GBArr($auditObj);
        $auditObj = $this->service->joinFormData($auditObj);

        $sendToURL = '';
        $auditResult = $this->service->saveAuditApply_d($auditObj, $sendToURL);
        echo util_jsonUtil::encode($auditResult);
    }
    /* ======================================== ������ҳ�棨������ ======================================== */

    /* ======================================== ������������ҳ�棨��ʼ�� ======================================== */
    /**
     * ��ת��������ҳ
     */
    function c_toViewForm()
    {
        $this->view('configForm');
    }

    /**
     * ��ȡ������������
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
     * ���ݱ�ID��ȡ����Ϣ
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
     * ���ݱ�ID��ȡ������Ϣ�б�
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
     * ��������ID��ȡ��������
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
     * ��ת���༭/������������ҳ��
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
        $wfClassArr = $service->getWfClass(); //�����������
        $flowTypeArr = array("1" => "�̶�����"); //array("1"=>"�̶�����","2"=>"��������");//�����������
        $wfClassOptStr = $flowTypeOptStr = '';
        if ($modifyType == 'Edit') {
            $this->assign('title', '�༭����');
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
                    if ($key == 'ClassID') { //�������ѡ��
                        foreach ($wfClassArr as $v) {
                            if ($val == $v['class_id']) {
                                $wfClassOptStr .= "<option value='{$v['class_id']}' selected>{$v['name']}</option>";
                            } else {
                                $wfClassOptStr .= "<option value='{$v['class_id']}'>{$v['name']}</option>";
                            }
                        }
                    }
                    if ($key == 'FLOW_TYPE') { //�������ѡ��
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
            $this->assign('title', '��������');
            $this->assign('modifyType', 'add');
            foreach ($wfClassArr as $v) { //�������ѡ��
                $wfClassOptStr .= "<option value='{$v['class_id']}'>{$v['name']}</option>";
            }
            foreach ($flowTypeArr as $k => $v) { //�������ѡ��
                $flowTypeOptStr .= "<option value='{$k}'>{$v}</option>";
            }
            foreach ($dataArr as $key => $val) {
                if ($key == 'FORM_ID') {
                    $this->assign($val, $formId);
                } else if ($key == 'Idate') { //����ʱ��
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


    /* ============ ���� / ���� ���ݣ���ʼ�� ============ */
    /**
     * �༭��
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
            msg('��ӳɹ���');
            succ_show('index1.php?model=common_workflow_workflow&action=toViewForm');
        } else {
            msg('���ʧ�ܣ�');
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
     * �༭����
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

        // �������̲���
        if ($result['msg'] == 'ok') {
//            echo"<pre>";print_r($stepObj);exit();
            $service->tbl_name = "flow_process";
            $errorNum = 0;
            $newStepIdsArr = array(); // Ϊ����༭ʱɾ��������
            foreach ($stepObj as $stepK => $stepV) {
                if ($stepV['ID'] == '') { //����
                    unset($stepV['rowNum_']);
                    $stepV['FLOW_ID'] = ($modifyType == 'add') ? $newFlowId : $flowObj['FLOW_ID'];
                    foreach ($stepV as $k => $v) {
                        $stepV[$k] = stripslashes($v);
                    }
                    $newStepId = $service->add_d($stepV);
                    $newStepIdsArr[] = $newStepId;
                    $errorNum += ($newStepId) ? 0 : 1;
                } else { // �༭
                    $newStepIdsArr[] = $stepV['ID'];
                    $conditions['ID'] = $stepV['ID'];
                    foreach ($stepV as $k => $v) {
                        $stepV[$k] = stripslashes($v);
                    }
                    $editStep_result = $service->update($conditions, $stepV);
                    $errorNum += ($editStep_result) ? 0 : 1;
                }
            }

            // ����ɾ����
            if ($modifyType == 'edit') {
                //��ȡ��������ԭ���Ĳ���ID
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
     * ɾ������
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
    /* ============ ���� / ���� ���ݣ������� ============ */

    /* ======================================== ������������ҳ�棨������ ======================================== */

    /* ======================================== ҵ�����ҳ�棨��ʼ�� ======================================== */
    function c_toProcessPage()
    {
        if (!WXURL) {
            die('δ���������ת·��');
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

        // ����ҳ������
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
    /* ======================================== ҵ�����ҳ�棨������ ======================================== */

    /* ======================================== ҵ���Ѱ�ҳ�棨��ʼ�� ======================================== */
    function c_toProcessedPage()
    {
        if (!WXURL) {
            die('δ���������ת·��');
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

        // ����ҳ������
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
    /* ======================================== ҵ���Ѱ�ҳ�棨������ ======================================== */

    /* ======================================== �����������������ʼ�� ======================================== */
    /**
     * ҳ�沿��
     */
    function c_toViewMobile()
    {
        $this->assignFunc($_GET);
        $this->display("viewMobile");
    }

    /**
     * ���ݲ���
     */
    function c_viewMobile()
    {
        echo util_jsonUtil::encode($this->service->viewMobile_d($_POST['pid'], $_POST['itemType']));
    }
    /* ======================================== ��������������������� ======================================== */

    /**
     * ���õ�����ͷ���ֶ���Ϣ
     */
    function c_setColInfoToSession(){
        $_REQUEST = util_jsonUtil::iconvUTF2GBArr($_REQUEST);
        $ColId = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $ColName = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : 'workflowAuditingData';

        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        if($records){// ���������ɾ��������д��
            $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        }

        $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColId', svalue = '{$ColId}';");
        $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColName', svalue = '{$ColName}';");
        echo 1;
    }

    /**
     * �����б�����
     */
    function c_exportData(){
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : '';

        // ��ȡ�б���Ľű��Լ������Ӧ���б�����
        $dataSql = '';
        switch($stype){
            case 'workflowAuditingData':// �����б���
                $sqlSessionArr = $this->service->_db->get_one("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'workflowAuditingSql';");
                $dataSql = $sqlSessionArr['svalue'];
                break;
        }
        $data = $this->service->_db->getArray($dataSql);

        if($data){
            // ��ȡ�б�ı�ͷ�ֶ��Լ���ͷ���ƵĻ�������
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

            //��ͷId����
            $colIdArr = explode(',', $colIdStr);
            $colIdArr = array_filter($colIdArr);
            //��ͷName����
            $colNameArr = explode(',', $colNameStr);
            $colNameArr = array_filter($colNameArr);
            //��ͷ����
            $colArr = array_combine($colIdArr, $colNameArr);
            if(!empty($colArr)){
                return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $data);
            }else{
                echo "��ͷ�ֶλ��涪ʧ,������~";
            }
        }
    }
}