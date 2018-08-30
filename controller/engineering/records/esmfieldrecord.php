<?php

/**
 * @author show
 * @Date 2014年12月23日 15:43:22
 * @version 1.0
 * @description:项目现场决算控制层
 */
class controller_engineering_records_esmfieldrecord extends controller_base_action
{

    function __construct() {
        $this->objName = "esmfieldrecord";
        $this->objPath = "engineering_records";
        parent::__construct();
    }

    /**
     * 更新决算
     * 暂时只用于现场决算和计提补贴
     */
    function c_updateFee() {
        set_time_limit(0);
        $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'field';
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : '';
        try {
            $this->service->businessFeeUpdate_d($category, $_REQUEST['thisYear'], $_REQUEST['thisMonth'], array(
                'projectCode' => $projectCode
            ));
            echo 1;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 取消决算
     * 暂时只用于计提补贴
     */
    function c_cancelFee() {
        set_time_limit(0);
        $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'subsidyProvision';
        $projectCode = isset($_REQUEST['projectCode']) ? $_REQUEST['projectCode'] : '';
        try {
            $this->service->businessFeeCancel_d($category, $_REQUEST['thisYear'], $_REQUEST['thisMonth'], array(
                'projectCode' => $projectCode
            ));
            echo 1;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 决算初始化 - 目前只用于支付决算初始化
     */
    function c_toInit() {
        $otherdatasDao = new model_common_otherdatas();
        $this->assign('value', $otherdatasDao->getConfig("engineering_budget_payables"));
        $this->assign('expense', $otherdatasDao->getConfig("engineering_budget_expense"));
        $this->view('init');
    }

    /**
     * 初始化决算
     */
    function c_init() {
        set_time_limit(0);
        echo $this->service->init_d($_REQUEST['category']);
    }
}