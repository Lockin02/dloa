<?php

/**
 * @author Show
 * @Date 2012年12月21日 星期五 9:45:04
 * @version 1.0
 * @description:个人费用模板控制层
 */
class controller_finance_expense_customtemplate extends controller_base_action
{

    function __construct() {
        $this->objName = "customtemplate";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * 跳转到个人费用模板列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_myJson() {
        $service = $this->service;
        $_POST['userId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST); //设置前台获取的参数信息

        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 模板维护列表获取分页数据转成Json
     */
    function c_myJsonForModify() {
        $service = $this->service;
        $_POST['userId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST); //设置前台获取的参数信息

        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);

        $backData = array();
        $backData['rows'] = $rows;
        $backData['total'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        echo util_jsonUtil :: encode($backData);
    }

    /**
     * 跳转到新增个人费用模板页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    //异步新增
    function c_ajaxSave() {
        echo $this->service->ajaxSave_d($_POST);
    }

    /**
     * 跳转到编辑个人费用模板页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看个人费用模板页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 获取个人最新保存模板
     */
    function c_initTemplate() {
        echo util_jsonUtil::encode($this->service->initTemplate_d($_POST['id'], $_POST['isEsm']));
    }

    /**
     * 根据模板id返回相应费用信息
     */
    function c_getTemplateCostType() {
        echo util_jsonUtil::encode($this->service->getTemplateCostType_d($_POST['id']));
    }
}