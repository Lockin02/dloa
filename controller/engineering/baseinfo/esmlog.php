<?php
/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��Ŀ������¼���Ʋ�
 */
class controller_engineering_baseinfo_esmlog extends controller_base_action
{

    function __construct() {
        $this->objName = "esmlog";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * ��ת����Ŀ������¼�б�
     */
    function c_page() {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * �鿴�б�
     */
    function c_grid() {
        $this->assign('projectId', $_GET['projectId'] ? $_GET['projectId'] : '-1');
        $this->view('grid');
    }
}