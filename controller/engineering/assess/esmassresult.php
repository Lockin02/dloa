<?php

/**
 * @author show
 * @Date 2013年8月23日 17:19:19
 * @version 1.0
 * @description:日志考核结果设置控制层
 */
class controller_engineering_assess_esmassresult extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmassresult";
        $this->objPath = "engineering_assess";
        parent::__construct();
    }

    /**
     * 跳转到日志考核结果设置列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 跳转到新增日志考核结果设置页面
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * 跳转到编辑日志考核结果设置页面
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
     * 跳转到查看日志考核结果设置页面
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
}