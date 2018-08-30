<?php

/**
 * @author Show
 * @Date 2011年11月28日 星期一 15:05:47
 * @version 1.0
 * @description:项目状态报告(oa_esm_project_statusreport)控制层
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
     * 跳转到项目状态报告
     */
    function c_page()
    {
        $this->display('list');
    }

    /**
     * 项目状态报告
     */
    function c_pageForProject()
    {
        $this->assign('projectId', $_GET['projectId']);

        //获取权限
        $otherDataDao = new model_common_otherdatas();
        $thisLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        $this->assign('unauditLimit', isset($thisLimitArr['日志反审核权限']) ? $thisLimitArr['日志反审核权限'] : 0);

        $this->display('listforproject');
    }

    /**
     * 打回项目周报操作页面
     */
    function c_toWithdrawReport(){
        $id = isset($_REQUEST['id'])? $_REQUEST['id'] : '';
        $reportRow = $this->service->get_d($id);
        $this->assignFunc($reportRow);
        $this->display('withdrawReport');
    }

    /**
     * 打回项目周报（添加操作记录,删除打回的周报记录）
     */
    function c_withdrawReport(){
        $postData = $_POST[$this->objName];

        if(!empty($postData['id'])){
            $deleteRst = $this->service->_db->query("delete from oa_esm_project_statusreport where id = '{$postData['id']}';");
            $logContent = <<<EOT
项目编号: {$postData['projectCode']}; 周报所属周次: {$postData['weekNo']}; 填报时间: {$postData['handupDate']};<br>
打回原因: {$postData['withdrawReason']}
EOT;

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($postData['projectId'], '打回周报', $logContent);

            msg("打回成功!");
        }else{
            msg("打回失败!");
        }
    }

    /**
     * 项目状态报告列表
     */
    function c_jsonForProject()
    {
        $newData['collection'] = $this->service->getDatasForProject_d($_POST);
        echo util_jsonUtil::encode($newData);
    }

    /**
     * 管理列表
     */
    function c_pageManage()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->display('listformanage');
    }

    /**
     * 报告确认列表 - 跟办事处权限结合进行报告过滤
     */
    function c_reportList()
    {
        $this->display('listreport');
    }

    /**
     * 报告确认列表
     */
    function c_pageJsonReport()
    {
        $service = $this->service;
        $rows = null;

        //获取工程项目系统权限
        $projectLimitArr = $this->service->getProjectLimits_d();

        //办事处权限部分
        $officeArr = array();
        $sysLimit = $projectLimitArr['办事处'];

        //省份权限处理
        $proArr = array();
        $proLimit = $projectLimitArr['省份权限'];

        //办事处 － 全部 处理
        if (strstr($sysLimit, ';;') || strstr($proLimit, ';;')) {
            $service->getParam($_POST); //设置前台获取的参数信息
            $rows = $service->pageBySqlId('select_report');
        } else { //如果没有选择全部，则进行权限查询并赋值
            if (!empty($sysLimit)) array_push($officeArr, $sysLimit);

            //办事处经理权限
            $officeIds = $this->service->getOfficeIds_d();
            if (!empty($officeIds)) {
                array_push($officeArr, $officeIds);
            }

            if (!empty($proLimit)) array_push($proArr, $proLimit);

            //办事处经理权限
            $provinces = $this->service->getProvinces_d();
            if (!empty($provinces)) {
                array_push($proArr, $provinces);
            }

            if (!empty($officeArr) || !empty($proArr)) {
                $service->getParam($_POST); //设置前台获取的参数信息

                $sqlStr = "sql: and (";
                //办事处脚本构建
                if ($officeArr) {
                    $sqlStr .= " p.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
                }

                //省份脚本构建
                if ($proArr) {
                    if ($officeArr) $sqlStr .= " or "; //如果之前有办事处权限，加个and
                    //配置省份权限
                    $proNameArr = array_unique(explode(",", implode($proArr, ',')));
                    $sqlStr .= " p.proName in ('" . implode($proNameArr, "','") . "')";
                }

                $sqlStr .= " )";
                $service->searchArr['mySearchCondition'] = $sqlStr;

                $rows = $service->pageBySqlId('select_report');
            }
        }

        if ($rows) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);
        }
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到新增页面
     */
    function c_toAdd()
    {
        //设值周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekNo = isset($_GET['weekNo']) ? $_GET['weekNo'] : $weekDao->getWeekNoByDayTimes();
        $this->assign('weekNo', $weekNo);
        //获取开始日期结束日期
        $weekDate = $weekDao->findWeekDate($weekNo);
        $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
        $this->assign('beginDate', $beginEndDate['beginDate']);
        $this->assign('endDate', $beginEndDate['endDate']);
        //设值汇报日期
        $this->assign('thisDate', day_date);
        //汇报人
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $esmprojectDao = new model_engineering_project_esmproject ();
        $esmproject = $esmprojectDao->get_d($_GET['projectId']);
        $this->assignFunc($esmproject);

        $this->display('add', true);
    }

    /**
     * 重写新增方法
     */
    function c_add()
    {
        $this->checkSubmit();
        // 周报数据
        $object = $_POST[$this->objName];
        // 查重处理 - 同一个项目，同一个周次，只能有一个周报
        $existObj = $this->service->find(array('projectId' => $object['projectId'], 'weekNo' => $object['weekNo']));
        // 如果周报重复，则提示出错
        if ($existObj) {
            msgRf('此项目已经存在本周周报，不能重复填写');
        } else {
            $id = $this->service->add_d($object);
            if ($id) {
                if ($_GET['act'] == 'audit') {
                    $rangeId = $this->service->getRangeId_d($object['projectId']);
                    succ_show('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId=' . $id . '&billArea=' . $rangeId);
                } else {
                    msgRf('新增成功');
                }
            }
        }
    }

    /**
     * 初始化对象
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->display('edit');
    }

    /**
     * 重写新增方法
     */
    function c_edit()
    {
        //获取动作
        $object = isset($_POST[$this->objName]) ? $_POST[$this->objName] : null;
        $rs = $this->service->edit_d($object);

        if ($rs) {
            if ($_GET['act'] == 'audit') {
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msgRf('编辑成功');
            }
        } else {
            msgRf('编辑失败');
        }
    }

    /**
     * 初始化对象
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
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
     * 初始化对象 - 审批时使用
     */
    function c_toAudit()
    {
        $this->permCheck(); //安全校验
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
     * 传入项目id，判断是否已经存在对应的项目周报
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
     *  更新考核分数
     */
    function c_updateScore()
    {
        echo $this->service->update(array('id' => $_POST['id']), array('score' => $_POST['score'])) ? 1 : 0;
    }

    /*************************  审批完成后跳转处理 *************************/

    /**
     * 审批完成后跳转处理
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 周报独立审批界面
     */
    function c_toAuditList()
    {
        $this->display('listaudit');
    }

    /**
     * 周报查询数据
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
     * 审核TAB
     */
    function c_toAuditTab()
    {
        $this->display('tabaudit');
    }

    /**
     * 周报独立审批界面
     */
    function c_toAuditedList()
    {
        $this->display('listaudited');
    }

    /**
     * 周报查询数据
     */
    function c_auditedJson()
    {
        $_POST['findInName'] = $_SESSION['USER_ID'];
        $this->service->getParam($_POST);
        $this->service->sort = "p.Endtime";
        $datas = $this->service->page_d('select_audited');
        exit(util_jsonUtil::encode($datas));
    }

    /**************************  项目视图部分***************************/
    /**
     * 项目状态视图
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
    /*********************告警视图**************************************/
    /**
     * 跳转到告警视图
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

        //设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 't' => '', 'ids' => ''
        ), $_GET));
        $this->view('warnView-list');
    }

    /**
     *
     * 告警视图搜索数据
     */
    function c_warnView()
    {
        echo util_jsonUtil::encode($this->service->warnView_d($_POST));
    }

    /**
     * 获取告警数量
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
     * 导出周报告警视图
     */
    function c_exportLogEmergencyJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->warnView_d($_GET);
        //定义表头
        $thArr = array(
            'officeName' => '归属区域', 'projectCode' => '项目编号', 'projectName' => '项目名称',
            'managerName' => '项目经理', 'weekNo' => '周次', 'msg' => '告警'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '项目周报告警');
    }

    /************************ 手机端方法 ****************************/

    /**
     * 获取项目周报属性
     */
    function c_get() {
        echo util_jsonUtil::encode($this->service->get_d($_REQUEST['id']));
    }
    /**
     * 获取项目周报属性 - 新增页
     */
    function c_getAdd(){
    	//设值周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : $weekDao->getWeekNoByDayTimes();
        //获取开始日期结束日期
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
     * 重写新增方法
     */
    function c_addMobile()
    {
        // 周报数据
        $object = $_POST;
        // 查重处理 - 同一个项目，同一个周次，只能有一个周报
        $existObj = $this->service->find(array('projectId' => $object['projectId'], 'weekNo' => $object['weekNo']));
        // 如果周报重复，则提示出错
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
     * 重写编辑方法
     */
    function c_editMobile(){
        echo $this->service->edit_d(util_jsonUtil::iconvUTF2GBArr($_POST));
    }

}