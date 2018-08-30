<?php

/**
 *
 * �˻�ѡ�񱣴���Ʋ�
 * @author chris
 *
 */
class controller_deptuser_user_userselect extends controller_base_action
{

    function __construct() {
        $this->objName = "userselect";
        $this->objPath = "deptuser_user";
        parent:: __construct();
    }

    /**
     * ��ȡ��ǰ�û���ǰģ�鳣��ѡ����
     */
    function c_getCurUserModelSelect() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr['userId'] = $_SESSION['USER_ID'];
        $service->sort = 'c.selectTime';
        $rows = $service->list_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil:: encode($rows);
    }

    /**
     * ���泣��ѡ����
     */
    function c_saveSelectedUser() {
        $selectedUserIds = $_POST['selectedUserIds'];
        $selectedUserNames = util_jsonUtil::iconvUTF2GB($_POST['selectedUserNames']);
        $formCode = $_POST['formCode'];
        $this->service->saveSelectedUser($formCode, $selectedUserIds, $selectedUserNames);
    }

}