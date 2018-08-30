<?php

/**
 * 账户控制层
 * @author chris
 */
class controller_deptuser_branch_branch extends controller_base_action
{

    function __construct() {
        $this->objName = "branch";
        $this->objPath = "deptuser_branch";
        parent::__construct();
    }

    /**
     * 根据用户ID，获取用户名称
     * author zengzx
     * 2012.06.29
     */
    function c_getUserName() {
        $userId = isset($_POST['NamePT']) ? $_POST['NamePT'] : "";
        $NameCN = $this->service->getBranchName_d($userId);
        echo $NameCN['NameCN'];
    }

    /**
     * 根据公司类型获取下拉模板
     */
    function c_getBranchStr() {
        $type = $_POST['type'];
        $str = $this->service->getBranchStr_d($type);
        echo util_jsonUtil::iconvGB2UTF($str);
    }

    /**
     * 据用户ID，获取公司信息
     */
    function c_getBrachInfo() {
        $userId = isset($_POST['userId']) ? $_POST['userId'] : "";
        $brach = $this->service->getBrachByUserNo($userId);
        echo util_jsonUtil::encode($brach);
    }

    /**
     * 获取公司信息 - editgrid select 格式
     */
    function c_listForSelect() {
        $this->service->getParam($_REQUEST);
        $this->service->sort = "ComCard";
        $this->service->asc = false;
        echo util_jsonUtil::encode($this->service->list_d('select_for_editgrid'));
    }
}
