<?php

/**
 * @author show
 * @Date 2014年10月15日 14:15:01
 * @version 1.0
 * @description:费用分摊核销记录控制层
 */
class controller_finance_cost_costHook extends controller_base_action
{

    function __construct() {
        $this->objName = "costHook";
        $this->objPath = "finance_cost";
        parent::__construct();
    }

    /**
     * 跳转到费用分摊核销记录列表
     */
    function c_page() {
        $hookId = isset($_GET['hookId']) ? $_GET['hookId'] : exit('没有传入勾稽序号');
        $this->assign('ids', $this->service->getIds_d($hookId));
        $this->view('list');
    }
}