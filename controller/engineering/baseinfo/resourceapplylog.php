<?php
/**
 * @author show
 * @Date 2014��6��20��
 * @version 1.0
 * @description:��Ŀ�豸���������¼���Ʋ�
 */
class controller_engineering_baseinfo_resourceapplylog extends controller_base_action
{

    function __construct() {
        $this->objName = "resourceapplylog";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * ��ת����Ŀ������¼�б�
     */
    function c_page() {
        // �����켣����
        $objects = $this->service->findAll(array('applyId' => $_GET['applyId']));
        $leftHTML = $this->service->leftCycle_d($objects);
        $this->assign("leftHTML",$leftHTML);
        $this->assign("applyId",$_GET['applyId']);
        $this->view('list');
    }
}