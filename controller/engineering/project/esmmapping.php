<?php
/**
 * @author show
 * @Date 2014��7��26�� 14:59:37
 * @version 1.0
 * @description:������Ŀӳ�����Ʋ�
 */
class controller_engineering_project_esmmapping extends controller_base_action
{
    function __construct() {
        $this->objName = "esmmapping";
        $this->objPath = "engineering_project";
        parent::__construct();
    }

    /**
     * ��������PK��Ŀ����ʽ��Ŀ
     */
    function c_remapping(){
        echo util_jsonUtil::iconvGB2UTF($this->service->remapping_d());
    }
}