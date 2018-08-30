<?php

/**
 * @author show
 * @Date 2013年12月6日 16:31:26
 * @version 1.0
 * @description:项目周报告警记录表控制层
 */
class controller_engineering_weekreport_weekwarning extends controller_base_action
{

    function __construct()
    {
        $this->objName = "weekwarning";
        $this->objPath = "engineering_weekreport";
        parent :: __construct();
    }

    /**
     * 获取任务信息 - 查看和新增、编辑业务无差异
     */
    function c_getWeek()
    {
        $service = $this->service;
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        $returnType = isset($_REQUEST['returnType']) ? $_REQUEST['returnType'] : 0;
        //获取最新数据
        $weeklogInfo = $service->getNew_d($projectId, $projectCode, $weekNo);
        //编辑页面，更新表数据
        if (!empty($mainId)) {
            $service->update_d($mainId, $weeklogInfo);
            //获取更新后的数据
            $weeklogInfo = $service->getWeek_d($projectId, $projectCode, $weekNo, $mainId);
        }
        if ($returnType == 0) {
            // 查询前4个月的数据
            $prevFourWeeks = $this->service->findPrevFourWeeks_d($projectId, $weekNo);

            // 输出呈现
            exit(util_jsonUtil::iconvGB2UTF($service->showWeek_d($weeklogInfo, $weekNo, $prevFourWeeks)));
        } else {
            exit(util_jsonUtil::encode($weeklogInfo));
        }
    }

    /**
     * 获取任务信息 - 查看和新增、编辑业务无差异
     */
    function c_viewWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        //获取周报审批状态
        $statusreportDao = new model_engineering_project_statusreport();
        $isNotSubmit = $statusreportDao->getExaStatus_d($mainId);

        // 如果表单没有提交，那么需要更新预警数值
        if ($isNotSubmit) {
            $newWeek = $this->service->getNew_d($projectId, $projectCode, $weekNo);
            //更新表数据
            $this->service->update_d($mainId, $newWeek);
        }
        // 获取以后预警
        $weeklogInfo = $this->service->getNow_d($projectId, $projectCode, $weekNo, $mainId);

        // 查询前4个月的数据
        $prevFourWeeks = $this->service->findPrevFourWeeks_d($projectId, $weekNo);

        // 输出格式
        exit(util_jsonUtil::iconvGB2UTF($this->service->viewWeek_d($weeklogInfo, $weekNo, $prevFourWeeks)));
    }
}