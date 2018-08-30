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
     * ���ݼ��
     */
    function c_index()
    {
        $this->view("index");
    }

    /**
     * ��ȡ�������
     */
    function c_getItems()
    {
        echo util_jsonUtil::encode($this->service->items);
    }

    /**
     * �������
     * ������������
     * id���������id
     * checkNum��������Ŀ����
     * correctNum�����ͨ������Ŀ����
     * errorNum����鲻ͨ������Ŀ����
     * errorProjectIds: �������Ŀid�����ŷָ�
     */
    function c_dealCheck()
    {
        $func = $_POST["id"];
        if (method_exists($this->service, $func)) {
            echo util_jsonUtil::encode($this->service->$func($func, $_POST['status']));
        } else {
            echo util_jsonUtil::encode(array(
                "id" => $func,
                "checkNum" => "����δ��ɶ���",
                "correctNum" => "����δ��ɶ���",
                "errorNum" => -1,
                "errorProjectIds" => ''
            ));
        }
    }
}