<?php
/**
 * @author show
 * @Date 2014��10��15�� 14:15:01
 * @version 1.0
 * @description:���÷�̯������ϸ���Ʋ�
 */
class controller_finance_cost_costHookDetail extends controller_base_action
{

    function __construct() {
        $this->objName = "costHookDetail";
        $this->objPath = "finance_cost";
        parent::__construct();
    }

    /**
     * ��ת�����÷�̯������ϸ�б�
     */
    function c_page() {
        $this->view('list');
    }
}