<?php

/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:21
 * @version 1.0
 * @description:ͨ��Ԥ�����ܿ��Ʋ�
 */
class controller_system_warning_warning extends controller_base_action
{
    function __construct()
    {
        $this->objName = "warning";
        $this->objPath = "system_warning";
        parent::__construct();
    }

    /**
     * Ԥ������ �����õķ���
     */
    function c_warningSendEmail()
    {
        return 1;
    }

    /**
     * ��ת��ͨ��Ԥ�������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ת������ͨ��Ԥ������ҳ��
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * ��ת���༭ͨ��Ԥ������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $obj['executeSql'] = stripslashes($obj['executeSql']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * ��ת���鿴ͨ��Ԥ������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); // ��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('isUsing', $this->service->rtYesNo_d($obj['isUsing']));
        $this->assign('isMailManager', $this->service->rtYesNo_d($obj['isMailManager']));
        $this->view('view');
    }

    /**
     * ִ��Ԥ������
     */
    function c_dealWarning()
    {
        return $this->service->dealWarning_d();
    }

    /**
     * ִ��Ԥ������
     */
    function c_dealWarningAtNoon()
    {
        return $this->service->dealWarning_d(NULL,1);
    }

    /**
     * �ֶ�ִ��Ԥ������
     */
    function c_dealWarningByMan()
    {
        if ($this->service->dealWarning_d($_POST['id'])) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ����ִ��Ԥ���ű�
     */
    function c_testSql()
    {
        $object = $this->service->testSql_d($_GET['id']);
        $this->assignFunc($object);
        $this->view('testsql');
    }

    /**
     * ��ȡԤ����Ϣ
     */
    function c_warningList() {
        $this->service->getParam($_POST);
        echo util_jsonUtil::encode($this->service->list_d());
    }
}