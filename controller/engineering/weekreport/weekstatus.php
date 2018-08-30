<?php

/**
 * @author show
 * @Date 2013年9月22日 14:46:02
 * @version 1.0
 * @description:项目周进度状况控制层
 */
class controller_engineering_weekreport_weekstatus extends controller_base_action
{

    function __construct()
    {
        $this->objName = "weekstatus";
        $this->objPath = "engineering_weekreport";
        parent:: __construct();
    }

    /**
     * 获取周状况数据
     */
    function c_getWeekStatus()
    {
        $service = $this->service;
        $projectId = isset($_POST['projectId']) ? $_POST['projectId'] : "";
        $weekNo = isset($_POST['weekNo']) ? $_POST['weekNo'] : "";
        $mainId = isset($_POST['mainId']) ? $_POST['mainId'] : "";
        $returnType = isset($_POST['returnType']) ? $_POST['returnType'] : 0;
        //获取最新数据
        $weekStatusInfo = $service->getNewWeekStatus_d($projectId, $weekNo);
        //编辑页面，更新表数据
        if (!empty($mainId)) {
            $service->update_d($mainId, $weekStatusInfo);
            //获取更新后的数据
            $weekStatusInfo = $service->getWeekStatus_d($projectId, $weekNo, $mainId);
        }
        if ($returnType == 0) {
            echo util_jsonUtil::iconvGB2UTF($service->showWeekStatus_d($weekStatusInfo));
        } else {
            echo util_jsonUtil::encode($weekStatusInfo);
        }
    }

    /**
     * 查看周状况数据
     */
    function c_viewWeekStatus()
    {
        $projectId = isset($_POST['projectId']) ? $_POST['projectId'] : "";
        $weekNo = isset($_POST['weekNo']) ? $_POST['weekNo'] : "";
        $mainId = isset($_POST['mainId']) ? $_POST['mainId'] : "";
        //获取周报审批状态
        $statusreportDao = new model_engineering_project_statusreport();
        $statusInfo = $statusreportDao->getExaStatus_d($mainId);
        if ($statusInfo) {
            $weekStatusInfo = $this->service->getNewWeekStatus_d($projectId, $weekNo);
            //更新表数据
            $this->service->update_d($mainId, $weekStatusInfo);
        } else {
            $weekStatusInfo = $this->service->getWeekStatus_d($projectId, $weekNo, $mainId);
        }
        echo util_jsonUtil::iconvGB2UTF($this->service->viewWeekStatus_d($weekStatusInfo));
    }
}