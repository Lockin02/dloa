<?php

/**
 * @author Show
 * @Date 2011��11��28�� ����һ 15:05:47
 * @version 1.0
 * @description:��Ŀ״̬����(oa_esm_project_statusreport)���Ʋ�
 */
class controller_engineering_project_statusreport extends controller_base_action
{

    function __construct()
    {
        $this->objName = "statusreport";
        $this->objPath = "engineering_project";
        parent::__construct();
    }

    /*
     * ��ת����Ŀ״̬����
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * ��Ŀ״̬����
     */
    function c_pageForProject()
    {
        $this->assign('projectId', $_GET['projectId']);

        //��ȡȨ��
        $otherDataDao = new model_common_otherdatas();
        $thisLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        $this->assign('unauditLimit', isset($thisLimitArr['��־�����Ȩ��']) ? $thisLimitArr['��־�����Ȩ��'] : 0);

        $this->display('listforproject');
    }

    /**
     * �����Ŀ�ܱ�����ҳ��
     */
    function c_toWithdrawReport(){
        $id = isset($_REQUEST['id'])? $_REQUEST['id'] : '';
        $reportRow = $this->service->get_d($id);
        $this->assignFunc($reportRow);
        $this->display('withdrawReport');
    }

    /**
     * �����Ŀ�ܱ�����Ӳ�����¼,ɾ����ص��ܱ���¼��
     */
    function c_withdrawReport(){
        $postData = $_POST[$this->objName];

        if(!empty($postData['id'])){
            $deleteRst = $this->service->_db->query("delete from oa_esm_project_statusreport where id = '{$postData['id']}';");
            $logContent = <<<EOT
��Ŀ���: {$postData['projectCode']}; �ܱ������ܴ�: {$postData['weekNo']}; �ʱ��: {$postData['handupDate']};<br>
���ԭ��: {$postData['withdrawReason']}
EOT;

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($postData['projectId'], '����ܱ�', $logContent);

            msg("��سɹ�!");
        }else{
            msg("���ʧ��!");
        }
    }

    /**
     * ��Ŀ״̬�����б�
     */
    function c_jsonForProject()
    {
        $newData['collection'] = $this->service->getDatasForProject_d($_POST);
        echo util_jsonUtil::encode($newData);
    }

    /**
     * �����б�
     */
    function c_pageManage()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->display('listformanage');
    }

    /**
     * ����ȷ���б� - �����´�Ȩ�޽�Ͻ��б������
     */
    function c_reportList()
    {
        $this->display('listreport');
    }

    /**
     * ����ȷ���б�
     */
    function c_pageJsonReport()
    {
        $service = $this->service;
        $rows = null;

        //��ȡ������ĿϵͳȨ��
        $projectLimitArr = $this->service->getProjectLimits_d();

        //���´�Ȩ�޲���
        $officeArr = array();
        $sysLimit = $projectLimitArr['���´�'];

        //ʡ��Ȩ�޴���
        $proArr = array();
        $proLimit = $projectLimitArr['ʡ��Ȩ��'];

        //���´� �� ȫ�� ����
        if (strstr($sysLimit, ';;') || strstr($proLimit, ';;')) {
            $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
            $rows = $service->pageBySqlId('select_report');
        } else { //���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);

            //���´�����Ȩ��
            $officeIds = $this->service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }

            if (!empty($proLimit)) array_push($proArr, $proLimit);

            //���´�����Ȩ��
            $provinces = $this->service->getProvinces_d();
            if (!empty($provinces)) {
                array_push($proArr, $provinces);
            }

            if (!empty($officeArr) || !empty($proArr)) {
                $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

                $sqlStr = "sql: and (";
                //���´��ű�����
                if ($officeArr) {
                    $sqlStr .= " p.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }

                //ʡ�ݽű�����
                if ($proArr) {
                    if ($officeArr) $sqlStr .= " or "; //���֮ǰ�а��´�Ȩ�ޣ��Ӹ�and
                    //����ʡ��Ȩ��
                    $proNameArr = array_unique(explode(",", implode($proArr, ',')));
                    $sqlStr .= " p.proName in ('" . implode($proNameArr, "','") . "')";
                }

                $sqlStr .= " )";
                $service->searchArr['mySearchCondition'] = $sqlStr;

                $rows = $service->pageBySqlId('select_report');
            }
        }

        if ($rows) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);
        }
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת������ҳ��
     */
    function c_toAdd()
    {
        //��ֵ�ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekNo = isset($_GET['weekNo']) ? $_GET['weekNo'] : $weekDao->getWeekNoByDayTimes();
        $this->assign('weekNo', $weekNo);
        //��ȡ��ʼ���ڽ�������
        $weekDate = $weekDao->findWeekDate($weekNo);
        $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
        $this->assign('beginDate', $beginEndDate['beginDate']);
        $this->assign('endDate', $beginEndDate['endDate']);
        //��ֵ�㱨����
        $this->assign('thisDate', day_date);
        //�㱨��
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $esmprojectDao = new model_engineering_project_esmproject ();
        $esmproject = $esmprojectDao->get_d($_GET['projectId']);
        $this->assignFunc($esmproject);

        $this->display('add', true);
    }

    /**
     * ��д��������
     */
    function c_add()
    {
        $this->checkSubmit();
        // �ܱ�����
        $object = $_POST[$this->objName];
        // ���ش��� - ͬһ����Ŀ��ͬһ���ܴΣ�ֻ����һ���ܱ�
        $existObj = $this->service->find(array('projectId' => $object['projectId'], 'weekNo' => $object['weekNo']));
        // ����ܱ��ظ�������ʾ����
        if ($existObj) {
            msgRf('����Ŀ�Ѿ����ڱ����ܱ��������ظ���д');
        } else {
            $id = $this->service->add_d($object);
            if ($id) {
                if ($_GET['act'] == 'audit') {
                    $rangeId = $this->service->getRangeId_d($object['projectId']);
                    succ_show('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId=' . $id . '&billArea=' . $rangeId);
                } else {
                    msgRf('�����ɹ�');
                }
            }
        }
    }

    /**
     * ��ʼ������
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->display('edit');
    }

    /**
     * ��д��������
     */
    function c_edit()
    {
        //��ȡ����
        $object = isset($_POST[$this->objName]) ? $_POST[$this->objName] : null;
        $rs = $this->service->edit_d($object);

        if ($rs) {
            if ($_GET['act'] == 'audit') {
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msgRf('�༭�ɹ�');
            }
        } else {
            msgRf('�༭ʧ��');
        }
    }

    /**
     * ��ʼ������
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('description', nl2br($obj['description']));
        $this->assign('nextPlan', nl2br($obj['nextPlan']));
        $this->assign('saleChance', nl2br($obj['saleChance']));
        $this->assign('competitorTrends', nl2br($obj['competitorTrends']));
        $this->assign('memberStatus', nl2br($obj['memberStatus']));
        $this->display('view');
    }

    /**
     * ��ʼ������ - ����ʱʹ��
     */
    function c_toAudit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('description', nl2br($obj['description']));
        $this->assign('nextPlan', nl2br($obj['nextPlan']));
        $this->assign('saleChance', nl2br($obj['saleChance']));
        $this->assign('competitorTrends', nl2br($obj['competitorTrends']));
        $this->assign('memberStatus', nl2br($obj['memberStatus']));
        $this->display('audit');
    }

    /**
     * ������Ŀid���ж��Ƿ��Ѿ����ڶ�Ӧ����Ŀ�ܱ�
     */
    function c_hasSubmitedReport()
    {
        if ($rs = $this->service->hasSubmitedReport_d($_POST['projectId'])) {
            echo $rs;
        } else {
            echo 0;
        }
    }

    /**
     *  ���¿��˷���
     */
    function c_updateScore()
    {
        echo $this->service->update(array('id' => $_POST['id']), array('score' => $_POST['score'])) ? 1 : 0;
    }

    /*************************  ������ɺ���ת���� *************************/

    /**
     * ������ɺ���ת����
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * �ܱ�������������
     */
    function c_toAuditList()
    {
        $this->display('listaudit');
    }

    /**
     * �ܱ���ѯ����
     */
    function c_auditJson()
    {
        $_POST['findInName'] = $_SESSION['USER_ID'];
        $this->service->getParam($_POST);
        $this->service->sort = "c.weekNo,c.projectCode";
        $datas = $this->service->page_d('select_audit');
        exit(util_jsonUtil::encode($datas));
    }

    /**
     * ���TAB
     */
    function c_toAuditTab()
    {
        $this->display('tabaudit');
    }

    /**
     * �ܱ�������������
     */
    function c_toAuditedList()
    {
        $this->display('listaudited');
    }

    /**
     * �ܱ���ѯ����
     */
    function c_auditedJson()
    {
        $_POST['findInName'] = $_SESSION['USER_ID'];
        $this->service->getParam($_POST);
        $this->service->sort = "p.Endtime";
        $datas = $this->service->page_d('select_audited');
        exit(util_jsonUtil::encode($datas));
    }

    /**************************  ��Ŀ��ͼ����***************************/
    /**
     * ��Ŀ״̬��ͼ
     */
    function c_toProjectStatus()
    {
        $this->service->searchArr = array('projectId' => $_GET['projectId']);
        $this->service->sort = 'c.weekNo';
        $this->service->asc = false;
        $rows = $this->service->list_d();
        $this->show->assign('result', $this->service->projectStatusChart_d($rows));
        $this->display('projectstatus');
    }
    /*********************�澯��ͼ**************************************/
    /**
     * ��ת���澯��ͼ
     * Enter description here ...
     */
    function c_toWarnView()
    {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
        $beginDate = $weekBEArr['beginDate'];
        $endDate = $weekBEArr['endDate'];
        $this->assign('beginDate', $beginDate);
        $this->assign('endDate', $endDate);

        //����Ĭ��ѡ����
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 't' => '', 'ids' => ''
        ), $_GET));
        $this->view('warnView-list');
    }

    /**
     *
     * �澯��ͼ��������
     */
    function c_warnView()
    {
        echo util_jsonUtil::encode($this->service->warnView_d($_POST));
    }

    /**
     * ��ȡ�澯����
     */
    function c_warnCount() {
        $waringRow = $this->service->warnView_d($_POST);
        echo util_jsonUtil::encode(array_merge(
            $_POST, array(
                'warningNum' => count($waringRow)
            )
        ));
    }

    /**
     * �����ܱ��澯��ͼ
     */
    function c_exportLogEmergencyJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->warnView_d($_GET);
        //�����ͷ
        $thArr = array(
            'officeName' => '��������', 'projectCode' => '��Ŀ���', 'projectName' => '��Ŀ����',
            'managerName' => '��Ŀ����', 'weekNo' => '�ܴ�', 'msg' => '�澯'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '��Ŀ�ܱ��澯');
    }

    /************************ �ֻ��˷��� ****************************/

    /**
     * ��ȡ��Ŀ�ܱ�����
     */
    function c_get() {
        echo util_jsonUtil::encode($this->service->get_d($_REQUEST['id']));
    }
    /**
     * ��ȡ��Ŀ�ܱ����� - ����ҳ
     */
    function c_getAdd(){
    	//��ֵ�ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : $weekDao->getWeekNoByDayTimes();
        //��ȡ��ʼ���ڽ�������
        $weekDate = $weekDao->findWeekDate($weekNo);
        $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
        $esmprojectDao = new model_engineering_project_esmproject ();
        $esmproject = $esmprojectDao->get_d($_REQUEST['projectId']);
        $esmproject['weekNo'] = $weekNo;
        $esmproject['beginDate'] = $beginEndDate['beginDate'];
        $esmproject['endDate'] = $beginEndDate['endDate'];

        $esmproject['thisDate'] = day_date;
        $esmproject['userId'] = $_SESSION['USER_ID'];
        $esmproject['userName'] = $_SESSION['USERNAME'];

        echo util_jsonUtil::encode($esmproject);
    }

    /**
     * ��д��������
     */
    function c_addMobile()
    {
        // �ܱ�����
        $object = $_POST;
        // ���ش��� - ͬһ����Ŀ��ͬһ���ܴΣ�ֻ����һ���ܱ�
        $existObj = $this->service->find(array('projectId' => $object['projectId'], 'weekNo' => $object['weekNo']));
        // ����ܱ��ظ�������ʾ����
        if ($existObj) {
            echo -1;
        } else {
        	$newId = $this->service->add_d(util_jsonUtil::iconvUTF2GBArr($object));
            if ($newId) {
        	   echo $newId;
        	}else{
        	   echo 0;
        	}
        }
    }
    /**
     * ��д�༭����
     */
    function c_editMobile(){
        echo $this->service->edit_d(util_jsonUtil::iconvUTF2GBArr($_POST));
    }

}