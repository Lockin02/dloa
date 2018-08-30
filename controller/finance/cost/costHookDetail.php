<?php
/**
 * @author show
 * @Date 2014年10月15日 14:15:01
 * @version 1.0
 * @description:费用分摊核销明细控制层
 */
class controller_finance_cost_costHookDetail extends controller_base_action
{

    function __construct() {
        $this->objName = "costHookDetail";
        $this->objPath = "finance_cost";
        parent::__construct();
    }

    /**
     * 跳转到费用分摊核销明细列表
     */
    function c_page() {
        $this->view('list');
    }
}