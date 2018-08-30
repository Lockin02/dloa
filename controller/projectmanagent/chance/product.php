<?php

/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:�̻���Ʒ�嵥���Ʋ�
 */
class controller_projectmanagent_chance_product extends controller_base_action
{

    function __construct() {
        $this->objName = "product";
        $this->objPath = "projectmanagent_chance";
        parent::__construct();
    }

    /*
     * ��ת���̻���Ʒ�嵥�б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->list_d();
        // ��ѯ��Ʒ��Ϣ
        $rows = $service->dealProduct_d($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ��ת�������̻���Ʒ�嵥ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭�̻���Ʒ�嵥ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴�̻���Ʒ�嵥ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ʱ�̻�����ӱ� Json
     */
    function c_listPageJson() {
        $service = $this->service;
        $service->getParam($_POST);
        $service->searchArr['isDel'] = 0;
        $service->searchArr['isTemp'] = 0;
        $rows = $service->list_d();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }
}