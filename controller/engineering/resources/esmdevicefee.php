<?php

/**
 * @author show
 * @Date 2014年9月26日 13:46:51
 * @version 1.0
 * @description:工程设备折旧控制层
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
     * 更新费用
     */
    function c_updateFee()
    {
        echo $this->service->updateFee_d($_POST['thisYear'], $_POST['thisMonth']);
    }

    /**
     * 明细查询列表
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view('searchDetailList');
    }

    /**
     * 明细查询数据
     */
    function c_searchDetailList()
    {
        echo util_jsonUtil::encode($this->service->searchDetailList_d($_POST['projectId']));
    }

    /**
     * 获取区域设备决算
     */
    function c_getOfficeEquFee()
    {
        echo util_jsonUtil::encode($this->service->getOfficeEquFee_d($_POST['officeIds'],
            $_POST['deprMoney'], $_POST['thisYear'], $_POST['thisMonth']));
    }
}