<?php

/**
 * @author show
 * @Date 2014��10��15�� 14:15:01
 * @version 1.0
 * @description:���÷�̯������¼���Ʋ�
 */
class controller_finance_cost_costHook extends controller_base_action
{

    function __construct() {
        $this->objName = "costHook";
        $this->objPath = "finance_cost";
        parent::__construct();
    }

    /**
     * ��ת�����÷�̯������¼�б�
     */
    function c_page() {
        $hookId = isset($_GET['hookId']) ? $_GET['hookId'] : exit('û�д��빴�����');
        $this->assign('ids', $this->service->getIds_d($hookId));
        $this->view('list');
    }
}