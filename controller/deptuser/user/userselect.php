<?php

/**
 *
 * 账户选择保存控制层
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
     * 获取当前用户当前模块常用选择人
     */
    function c_getCurUserModelSelect() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr['userId'] = $_SESSION['USER_ID'];
        $service->sort = 'c.selectTime';
        $rows = $service->list_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil:: encode($rows);
    }

    /**
     * 保存常用选择人
     */
    function c_saveSelectedUser() {
        $selectedUserIds = $_POST['selectedUserIds'];
        $selectedUserNames = util_jsonUtil::iconvUTF2GB($_POST['selectedUserNames']);
        $formCode = $_POST['formCode'];
        $this->service->saveSelectedUser($formCode, $selectedUserIds, $selectedUserNames);
    }

}