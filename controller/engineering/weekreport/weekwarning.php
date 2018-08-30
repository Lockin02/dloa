<?php

/**
 * @author show
 * @Date 2013��12��6�� 16:31:26
 * @version 1.0
 * @description:��Ŀ�ܱ��澯��¼����Ʋ�
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
     * ��ȡ������Ϣ - �鿴���������༭ҵ���޲���
     */
    function c_getWeek()
    {
        $service = $this->service;
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        $returnType = isset($_REQUEST['returnType']) ? $_REQUEST['returnType'] : 0;
        //��ȡ��������
        $weeklogInfo = $service->getNew_d($projectId, $projectCode, $weekNo);
        //�༭ҳ�棬���±�����
        if (!empty($mainId)) {
            $service->update_d($mainId, $weeklogInfo);
            //��ȡ���º������
            $weeklogInfo = $service->getWeek_d($projectId, $projectCode, $weekNo, $mainId);
        }
        if ($returnType == 0) {
            // ��ѯǰ4���µ�����
            $prevFourWeeks = $this->service->findPrevFourWeeks_d($projectId, $weekNo);

            // �������
            exit(util_jsonUtil::iconvGB2UTF($service->showWeek_d($weeklogInfo, $weekNo, $prevFourWeeks)));
        } else {
            exit(util_jsonUtil::encode($weeklogInfo));
        }
    }

    /**
     * ��ȡ������Ϣ - �鿴���������༭ҵ���޲���
     */
    function c_viewWeek()
    {
        $projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : "";
        $weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
        $mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
        //��ȡ�ܱ�����״̬
        $statusreportDao = new model_engineering_project_statusreport();
        $isNotSubmit = $statusreportDao->getExaStatus_d($mainId);

        // �����û���ύ����ô��Ҫ����Ԥ����ֵ
        if ($isNotSubmit) {
            $newWeek = $this->service->getNew_d($projectId, $projectCode, $weekNo);
            //���±�����
            $this->service->update_d($mainId, $newWeek);
        }
        // ��ȡ�Ժ�Ԥ��
        $weeklogInfo = $this->service->getNow_d($projectId, $projectCode, $weekNo, $mainId);

        // ��ѯǰ4���µ�����
        $prevFourWeeks = $this->service->findPrevFourWeeks_d($projectId, $weekNo);

        // �����ʽ
        exit(util_jsonUtil::iconvGB2UTF($this->service->viewWeek_d($weeklogInfo, $weekNo, $prevFourWeeks)));
    }
}