<?php
/**
 * @author show
 * @Date 2014年4月29日 16:28:21
 * @version 1.0
 * @description:不合格物料明细控制层
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
     * 获取所有数据返回json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->asc = false;
        $rows = $service->list_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }
}