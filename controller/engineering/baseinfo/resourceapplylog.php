<?php
/**
 * @author show
 * @Date 2014年6月20日
 * @version 1.0
 * @description:项目设备申请操作记录控制层
 */
class controller_engineering_baseinfo_resourceapplylog extends controller_base_action
{

    function __construct() {
        $this->objName = "resourceapplylog";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * 跳转到项目操作记录列表
     */
    function c_page() {
        // 操作轨迹呈现
        $objects = $this->service->findAll(array('applyId' => $_GET['applyId']));
        $leftHTML = $this->service->leftCycle_d($objects);
        $this->assign("leftHTML",$leftHTML);
        $this->assign("applyId",$_GET['applyId']);
        $this->view('list');
    }
}