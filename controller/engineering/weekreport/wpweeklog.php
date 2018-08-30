<?php

/**
 * @author show
 * @Date 2013年10月17日 17:34:30
 * @version 1.0
 * @description:项目成员周日志控制层
 */
class controller_engineering_weekreport_wpweeklog extends controller_base_action
{

    function __construct()
    {
        $this->objName = "wpweeklog";
        $this->objPath = "engineering_weekreport";
        parent :: __construct();
    }

    /**
     * 获取任务信息
     */
    function c_getWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        $weeklogInfo = $this->service->getNew_d($projectId, $projectCode, $weekNo);
        //编辑页面，更新表数据
        if (!empty($mainId)) {
            $this->service->update_d($mainId, $weeklogInfo);
        }
        exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
    }

    /**
     * 获取任务信息
     */
    function c_viewWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        //获取周报审批状态
        $statusreportDao = new model_engineering_project_statusreport();
        $statusInfo = $statusreportDao->getExaStatus_d($mainId);
        if ($statusInfo) {
            $weeklogInfo = $this->service->getNew_d($projectId, $projectCode, $weekNo);
            //更新表数据
            $this->service->update_d($mainId, $weeklogInfo);
        } else {
            $weeklogInfo = $this->service->getNow_d($mainId);
        }
        exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
    }


    /**
     * 获取成员日志信息_新窗口
     */
    function c_getNewWeek()
    {
        $this->assignFunc($_GET);
        $this->view('weeklog');
    }
}