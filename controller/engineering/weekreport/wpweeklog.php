<?php

/**
 * @author show
 * @Date 2013��10��17�� 17:34:30
 * @version 1.0
 * @description:��Ŀ��Ա����־���Ʋ�
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
     * ��ȡ������Ϣ
     */
    function c_getWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        $weeklogInfo = $this->service->getNew_d($projectId, $projectCode, $weekNo);
        //�༭ҳ�棬���±�����
        if (!empty($mainId)) {
            $this->service->update_d($mainId, $weeklogInfo);
        }
        exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
    }

    /**
     * ��ȡ������Ϣ
     */
    function c_viewWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        //��ȡ�ܱ�����״̬
        $statusreportDao = new model_engineering_project_statusreport();
        $statusInfo = $statusreportDao->getExaStatus_d($mainId);
        if ($statusInfo) {
            $weeklogInfo = $this->service->getNew_d($projectId, $projectCode, $weekNo);
            //���±�����
            $this->service->update_d($mainId, $weeklogInfo);
        } else {
            $weeklogInfo = $this->service->getNow_d($mainId);
        }
        exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
    }


    /**
     * ��ȡ��Ա��־��Ϣ_�´���
     */
    function c_getNewWeek()
    {
        $this->assignFunc($_GET);
        $this->view('weeklog');
    }
}