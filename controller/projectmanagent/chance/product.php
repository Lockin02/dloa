<?php

/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:商机产品清单控制层
 */
class controller_projectmanagent_chance_product extends controller_base_action
{

    function __construct() {
        $this->objName = "product";
        $this->objPath = "projectmanagent_chance";
        parent::__construct();
    }

    /*
     * 跳转到商机产品清单列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->list_d();
        // 查询产品信息
        $rows = $service->dealProduct_d($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 跳转到新增商机产品清单页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 跳转到编辑商机产品清单页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看商机产品清单页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 定时商机报表从表 Json
     */
    function c_listPageJson() {
        $service = $this->service;
        $service->getParam($_POST);
        $service->searchArr['isDel'] = 0;
        $service->searchArr['isTemp'] = 0;
        $rows = $service->list_d();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }
}