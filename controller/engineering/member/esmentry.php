<?php

/**
 * @author tse
 * @Date 2014年3月3日 15:48:16
 * @version 1.0
 * @description:人员出入表控制层
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
     * 跳转到管理工程项目人员出入表列表
     */
    function c_page()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * 跳转到查看工程项目人员出入表列表
     */
    function c_viewPage()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-list');
    }

    /**
     * 跳转到新增人员出入表页面
     */
    function c_toAdd()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('add');
    }

    /**
     * 重写add方法
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
            msg("添加成功");
        } else {
            msg("添加失败");
        }
    }

    /**
     * 批量添加
     */
    function c_addMore()
    {
        if ($this->service->createBatch($_POST[$this->objName])) {
            msg("添加成功");
        } else {
            msg("添加失败");
        }
    }

    /**
     * 跳转到编辑人员出入表页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看人员出入表页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 跳转到离开项目的人员出入表页面
     */
    function c_toLeaveList()
    {
        $this->assign('memberIds', $_GET['ids']);
        $this->view('leavelist');
    }
}