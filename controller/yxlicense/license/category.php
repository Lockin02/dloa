<?php

/**
 * @author Administrator
 * @Date 2012��3��11�� 15:15:40
 * @version 1.0
 * @description:��Ʒ������Ϣ���Ʋ�
 */
class controller_yxlicense_license_category extends controller_base_action
{

    function __construct() {
        $this->objName = "category";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /*
     * ��ת����Ʒ������Ϣ�б�
     */
    function c_page() {
        $this->assign('isUse', $_GET['isUse']);
        $this->assign('id', $_GET['id']);
        $this->assign('name', $_GET['name']);
        $this->view('list');
    }

    /**
     * ��ת������license������Ϣҳ��
     */
    function c_toAdd() {
        //�ж��Ƿ��Ѿ�������д�����ķ��࣬�еĻ���������
        if ($this->service->find(array('licenseId' => $_GET['id'], 'showType' => '5'), null, 'id')) {
            msg('��д���ֻ�ܵ�������,���ܼ�������µķ���');
        } else {
            $this->assign('licenseId', $_GET['id']);
            $this->view('add');
        }
    }

    //����
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName])) {
            msg("��ӳɹ�");
        } else {
            msg("���ʧ��");
        }
    }

    /**
     * ��ת���༭license������Ϣҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('showTypeHidden', $obj['showType']);
        $this->assign('typeHidden', $obj['type']);        //��ѡ/�ı����ͽ��г�ʼ����ʾ
        $this->view('edit');
    }

    /**
     * ��ת���鿴license������Ϣҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        switch ($obj['showType']) {
            case 1 :
                $this->assign('showType', '�б���ʾ');
                break;
            case 2 :
                $this->assign('showType', '������ʾ');
                break;
            case 3 :
                $this->assign('showType', '����ʾ');
                break;
            case 4 :
                $this->assign('showType', 'ֱ������');
                break;
            case 5 :
                $this->assign('showType', '��д���');
                break;
        }

        switch ($obj['isHideTitle']) {
            case 0 :
                $this->assign('isHideTitle', '��');
                break;
            case 1 :
                $this->assign('isHideTitle', '��');
                break;
        }
        //�ı���ʾ���ͣ���ѡ/�ı���
        switch ($obj['type']) {
            case 1 :
                $this->assign('type', '��ѡ');
                break;
            case 2 :
                $this->assign('type', '�ı�');
                break;
        }
        $this->view('view');
    }

    /**
     * ����licenseId��Ʒ������Ϣ
     */
    function c_getTreeData() {
        $service = $this->service;
        $isSale = isset ($_GET ['isSale']) ? $_GET ['isSale'] : 0;
        if ($isSale == '1') {
            $service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.licenseId='11')";
        }
        $service->sort = " c.orderNum";
        $service->asc = false;
        $rows = $service->listBySqlId('select_treeinfo');
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->sort = " c.orderNum";
        $service->asc = false;
        $rows = $service->page_d();

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     *
     * ���µ�����Ʒ�������ҽڵ�
     */
    function c_ajustNode() {
        echo $this->service->createTreeLRValue();
    }

    /**
     * ��ת��Ԥ��ҳ��
     */
    function c_preview() {
        $this->assign('name', $_GET['licenseName']);
        $this->assign('id', $_GET['id']);
        $this->view('preview');
    }
}