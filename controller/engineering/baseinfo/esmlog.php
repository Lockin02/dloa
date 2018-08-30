<?php
/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:项目操作记录控制层
 */
class controller_engineering_baseinfo_esmlog extends controller_base_action
{

    function __construct() {
        $this->objName = "esmlog";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * 跳转到项目操作记录列表
     */
    function c_page() {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * 查看列表
     */
    function c_grid() {
        $this->assign('projectId', $_GET['projectId'] ? $_GET['projectId'] : '-1');
        $this->view('grid');
    }
}