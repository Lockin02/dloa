<?php
/**
 * @author show
 * @Date 2014年7月26日 14:59:37
 * @version 1.0
 * @description:试用项目映射表控制层
 */
class controller_engineering_project_esmmapping extends controller_base_action
{
    function __construct() {
        $this->objName = "esmmapping";
        $this->objPath = "engineering_project";
        parent::__construct();
    }

    /**
     * 重新索引PK项目和正式项目
     */
    function c_remapping(){
        echo util_jsonUtil::iconvGB2UTF($this->service->remapping_d());
    }
}