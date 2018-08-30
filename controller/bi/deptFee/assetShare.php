<?php

/**
 * 部门折旧分摊
 *
 * Class controller_bi_deptFee_assetShare
 */
class controller_bi_deptFee_assetShare extends controller_base_action
{

    function __construct()
    {
        $this->objName = "assetShare";
        $this->objPath = "bi_deptFee";
        parent::__construct();
    }

    /**
     * 列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d();
        //数据加入安全码
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 新增
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * 编辑
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 查看
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }
}