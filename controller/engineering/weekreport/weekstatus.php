<?php

/**
 * @author show
 * @Date 2013��9��22�� 14:46:02
 * @version 1.0
 * @description:��Ŀ�ܽ���״�����Ʋ�
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
     * ��ȡ��״������
     */
    function c_getWeekStatus()
    {
        $service = $this->service;
        $projectId = isset($_POST['projectId']) ? $_POST['projectId'] : "";
        $weekNo = isset($_POST['weekNo']) ? $_POST['weekNo'] : "";
        $mainId = isset($_POST['mainId']) ? $_POST['mainId'] : "";
        $returnType = isset($_POST['returnType']) ? $_POST['returnType'] : 0;
        //��ȡ��������
        $weekStatusInfo = $service->getNewWeekStatus_d($projectId, $weekNo);
        //�༭ҳ�棬���±�����
        if (!empty($mainId)) {
            $service->update_d($mainId, $weekStatusInfo);
            //��ȡ���º������
            $weekStatusInfo = $service->getWeekStatus_d($projectId, $weekNo, $mainId);
        }
        if ($returnType == 0) {
            echo util_jsonUtil::iconvGB2UTF($service->showWeekStatus_d($weekStatusInfo));
        } else {
            echo util_jsonUtil::encode($weekStatusInfo);
        }
    }

    /**
     * �鿴��״������
     */
    function c_viewWeekStatus()
    {
        $projectId = isset($_POST['projectId']) ? $_POST['projectId'] : "";
        $weekNo = isset($_POST['weekNo']) ? $_POST['weekNo'] : "";
        $mainId = isset($_POST['mainId']) ? $_POST['mainId'] : "";
        //��ȡ�ܱ�����״̬
        $statusreportDao = new model_engineering_project_statusreport();
        $statusInfo = $statusreportDao->getExaStatus_d($mainId);
        if ($statusInfo) {
            $weekStatusInfo = $this->service->getNewWeekStatus_d($projectId, $weekNo);
            //���±�����
            $this->service->update_d($mainId, $weekStatusInfo);
        } else {
            $weekStatusInfo = $this->service->getWeekStatus_d($projectId, $weekNo, $mainId);
        }
        echo util_jsonUtil::iconvGB2UTF($this->service->viewWeekStatus_d($weekStatusInfo));
    }
}