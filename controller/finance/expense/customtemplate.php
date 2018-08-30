<?php

/**
 * @author Show
 * @Date 2012��12��21�� ������ 9:45:04
 * @version 1.0
 * @description:���˷���ģ����Ʋ�
 */
class controller_finance_expense_customtemplate extends controller_base_action
{

    function __construct() {
        $this->objName = "customtemplate";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * ��ת�����˷���ģ���б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_myJson() {
        $service = $this->service;
        $_POST['userId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ģ��ά���б��ȡ��ҳ����ת��Json
     */
    function c_myJsonForModify() {
        $service = $this->service;
        $_POST['userId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);

        $backData = array();
        $backData['rows'] = $rows;
        $backData['total'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        echo util_jsonUtil :: encode($backData);
    }

    /**
     * ��ת���������˷���ģ��ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    //�첽����
    function c_ajaxSave() {
        echo $this->service->ajaxSave_d($_POST);
    }

    /**
     * ��ת���༭���˷���ģ��ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴���˷���ģ��ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ȡ�������±���ģ��
     */
    function c_initTemplate() {
        echo util_jsonUtil::encode($this->service->initTemplate_d($_POST['id'], $_POST['isEsm']));
    }

    /**
     * ����ģ��id������Ӧ������Ϣ
     */
    function c_getTemplateCostType() {
        echo util_jsonUtil::encode($this->service->getTemplateCostType_d($_POST['id']));
    }
}