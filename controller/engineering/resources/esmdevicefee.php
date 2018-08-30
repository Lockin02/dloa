<?php

/**
 * @author show
 * @Date 2014��9��26�� 13:46:51
 * @version 1.0
 * @description:�����豸�۾ɿ��Ʋ�
 */
class controller_engineering_resources_esmdevicefee extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmdevicefee";
        $this->objPath = "engineering_resources";
        parent::__construct();
    }

    /**
     * ���·���
     */
    function c_updateFee()
    {
        echo $this->service->updateFee_d($_POST['thisYear'], $_POST['thisMonth']);
    }

    /**
     * ��ϸ��ѯ�б�
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view('searchDetailList');
    }

    /**
     * ��ϸ��ѯ����
     */
    function c_searchDetailList()
    {
        echo util_jsonUtil::encode($this->service->searchDetailList_d($_POST['projectId']));
    }

    /**
     * ��ȡ�����豸����
     */
    function c_getOfficeEquFee()
    {
        echo util_jsonUtil::encode($this->service->getOfficeEquFee_d($_POST['officeIds'],
            $_POST['deprMoney'], $_POST['thisYear'], $_POST['thisMonth']));
    }
}