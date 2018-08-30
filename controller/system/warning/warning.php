<?php

/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能控制层
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
     * 预警功能 被调用的方法
     */
    function c_warningSendEmail()
    {
        return 1;
    }

    /**
     * 跳转到通用预警功能列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 跳转到新增通用预警功能页面
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * 跳转到编辑通用预警功能页面
     */
    function c_toEdit()
    {
        $this->permCheck(); // 安全校验
        $obj = $this->service->get_d($_GET['id']);
        $obj['executeSql'] = stripslashes($obj['executeSql']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * 跳转到查看通用预警功能页面
     */
    function c_toView()
    {
        $this->permCheck(); // 安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('isUsing', $this->service->rtYesNo_d($obj['isUsing']));
        $this->assign('isMailManager', $this->service->rtYesNo_d($obj['isMailManager']));
        $this->view('view');
    }

    /**
     * 执行预警功能
     */
    function c_dealWarning()
    {
        return $this->service->dealWarning_d();
    }

    /**
     * 执行预警功能
     */
    function c_dealWarningAtNoon()
    {
        return $this->service->dealWarning_d(NULL,1);
    }

    /**
     * 手动执行预警功能
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
     * 测试执行预警脚本
     */
    function c_testSql()
    {
        $object = $this->service->testSql_d($_GET['id']);
        $this->assignFunc($object);
        $this->view('testsql');
    }

    /**
     * 获取预警信息
     */
    function c_warningList() {
        $this->service->getParam($_POST);
        echo util_jsonUtil::encode($this->service->list_d());
    }
}