<?php

/**
 * @author show
 * @Date 2013��8��13�� 16:26:33
 * @version 1.0
 * @description:������¼����Ʋ�
 */
class controller_finance_income_incomecheck extends controller_base_action
{

    function __construct()
    {
        $this->objName = "incomecheck";
        $this->objPath = "finance_income";
        parent:: __construct();
    }

    /**
     * ��ת��������¼���б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson()
    {
        $this->service->getParam($_REQUEST);
        $this->service->asc = false;
        $rows = $this->service->list_d();
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ������¼�鿴�б�
     */
    function c_checkList()
    {
        $this->assign('contractId', isset($_GET['contractId']) ? $_GET['contractId'] : '');
        $this->assign('payConId', isset($_GET['payConId']) ? $_GET['payConId'] : '');
        $this->assign('incomeId', isset($_GET['incomeId']) ? $_GET['incomeId'] : '');
        $this->assign('incomeType', isset($_GET['incomeType']) ? $_GET['incomeType'] : '');
        $this->view('list-check');
    }

    /**
     * ��ȡ��Ҫ��ʼ���ĺ�ͬID��Ϣ
     * @return mixed
     */
    function c_getNeedContractIdList()
    {
        echo util_jsonUtil::encode($this->service->getNeedContractIdList_d());
    }

    /**
     * ��ʼ��������¼
     */
    function c_initData()
    {
        echo util_jsonUtil::encode(array(
            "result" => $this->service->initData_d($_GET['contractIds']),
            "msg" => count(explode(',', $_GET['contractIds']))
        ));
    }

    /**
     * ��ת���鿴������¼��ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('auditStatus', $this->service->rtStatus_d($obj['auditStatus']));
        $this->assign('isRed', $this->service->rtRed_d($obj['isRed']));
        $this->assign('incomeType', $this->service->rtIncomeType($obj['incomeType']));
        $this->view('view');
    }
}