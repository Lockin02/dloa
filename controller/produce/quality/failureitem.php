<?php
/**
 * @author show
 * @Date 2014��4��29�� 16:28:21
 * @version 1.0
 * @description:���ϸ�������ϸ���Ʋ�
 */
class controller_produce_quality_failureitem extends controller_base_action
{

    function __construct()
    {
        $this->objName = "failureitem";
        $this->objPath = "produce_quality";
        parent::__construct();
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->asc = false;
        $rows = $service->list_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }
}