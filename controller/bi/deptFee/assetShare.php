<?php

/**
 * �����۾ɷ�̯
 *
 * Class controller_bi_deptFee_assetShare
 */
class controller_bi_deptFee_assetShare extends controller_base_action
{

    function __construct()
    {
        $this->objName = "assetShare";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * �б�
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
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d();
        //���ݼ��밲ȫ��
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ����
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * �༭
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * �鿴
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }
}