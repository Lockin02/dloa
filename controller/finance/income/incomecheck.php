<?php

/**
 * @author show
 * @Date 2013年8月13日 16:26:33
 * @version 1.0
 * @description:核销记录表控制层
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
     * 跳转到核销记录表列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson()
    {
        $this->service->getParam($_REQUEST);
        $this->service->asc = false;
        $rows = $this->service->list_d();
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 核销记录查看列表
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
     * 获取需要初始化的合同ID信息
     * @return mixed
     */
    function c_getNeedContractIdList()
    {
        echo util_jsonUtil::encode($this->service->getNeedContractIdList_d());
    }

    /**
     * 初始化核销记录
     */
    function c_initData()
    {
        echo util_jsonUtil::encode(array(
            "result" => $this->service->initData_d($_GET['contractIds']),
            "msg" => count(explode(',', $_GET['contractIds']))
        ));
    }

    /**
     * 跳转到查看核销记录表页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('auditStatus', $this->service->rtStatus_d($obj['auditStatus']));
        $this->assign('isRed', $this->service->rtRed_d($obj['isRed']));
        $this->assign('incomeType', $this->service->rtIncomeType($obj['incomeType']));
        $this->view('view');
    }
}