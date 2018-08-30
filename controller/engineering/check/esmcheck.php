<?php

/**
 * Created by PhpStorm.
 * User: Kuangzw
 * Date: 2017/8/31
 * Time: 14:42
 */
class controller_engineering_check_esmcheck extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmcheck";
        $this->objPath = "engineering_check";
        parent::__construct();
        // ini_set("display_errors", 1);
    }

    /**
     * 数据检查
     */
    function c_index()
    {
        $this->view("index");
    }

    /**
     * 获取检查类型
     */
    function c_getItems()
    {
        echo util_jsonUtil::encode($this->service->items);
    }

    /**
     * 触发检查
     * 输出结果包含：
     * id：检查类型id
     * checkNum：检查的项目数量
     * correctNum：检查通过的项目数量
     * errorNum：检查不通过的项目数量
     * errorProjectIds: 错误的项目id，逗号分隔
     */
    function c_dealCheck()
    {
        $func = $_POST["id"];
        if (method_exists($this->service, $func)) {
            echo util_jsonUtil::encode($this->service->$func($func, $_POST['status']));
        } else {
            echo util_jsonUtil::encode(array(
                "id" => $func,
                "checkNum" => "规则未完成定义",
                "correctNum" => "规则未完成定义",
                "errorNum" => -1,
                "errorProjectIds" => ''
            ));
        }
    }
}