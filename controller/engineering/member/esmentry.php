<?php

/**
 * @author tse
 * @Date 2014��3��3�� 15:48:16
 * @version 1.0
 * @description:��Ա�������Ʋ�
 */
class controller_engineering_member_esmentry extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmentry";
        $this->objPath = "engineering_member";
        parent::__construct();
    }

    /**
     * ��ת����������Ŀ��Ա������б�
     */
    function c_page()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * ��ת���鿴������Ŀ��Ա������б�
     */
    function c_viewPage()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-list');
    }

    /**
     * ��ת��������Ա�����ҳ��
     */
    function c_toAdd()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('add');
    }

    /**
     * ��дadd����
     * (non-PHPdoc)
     * @see controller_base_action::c_add()
     */
    function c_add()
    {
        $entryArr = $_POST [$this->objName];
        $personnelDao = new model_hr_personnel_personnel();
        $personnelInfo = $personnelDao->getPersonnelAndLevel_d($entryArr['memberId']);
        $entryArr['personLevel'] = $personnelInfo['personLevel'];
        $result = $this->service->add_d($entryArr);
        if ($result) {
            msg("��ӳɹ�");
        } else {
            msg("���ʧ��");
        }
    }

    /**
     * �������
     */
    function c_addMore()
    {
        if ($this->service->createBatch($_POST[$this->objName])) {
            msg("��ӳɹ�");
        } else {
            msg("���ʧ��");
        }
    }

    /**
     * ��ת���༭��Ա�����ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴��Ա�����ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ת���뿪��Ŀ����Ա�����ҳ��
     */
    function c_toLeaveList()
    {
        $this->assign('memberIds', $_GET['ids']);
        $this->view('leavelist');
    }
}