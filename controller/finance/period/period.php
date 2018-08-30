<?php

/**
 * @author Show
 * @Date 2011年5月21日 星期六 14:47:06
 * @version 1.0
 * @description:财务会计期间表控制层
 */
class controller_finance_period_period extends controller_base_action
{

    function __construct() {
        $this->objName = "period";
        $this->objPath = "finance_period";
        parent::__construct();
    }

    /**
     * 获取权限
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 跳转到财务会计期间表
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * 设置当前会计期
     */
    function c_toCreatePeriod() {
        $this->assign('year', date('Y'));
        $this->assign('sysMonth', date('m') * 1);

        $branchDao = new model_deptuser_branch_branch();
        $branchDao->sort = "ComCard";
        $branchDao->asc = false;
        $branchOptions = $branchDao->list_d('select_for_editgrid');
        $firstBusinessBelongOpt = $businessBelongOpts = '';$num = 0;

        if(!empty($branchOptions) && is_array($branchOptions)){
            foreach ($branchOptions as $branch){
                if($num == 0){
                    $firstBusinessBelongOpt = $branch['name'];
                    $num += 1;
                }
                $businessBelongOpts .= '<option value="'.$branch['value'].'">'.$branch['name'].'</option>';
            }
        }
        $this->assign('firstBusinessBelongOpt', $firstBusinessBelongOpt);
        $this->assign('businessBelongOpts', $businessBelongOpts);
        $this->display('createperiod');
    }

    /**
     * 设置财务会计启用周期
     */
    function c_createPeriod() {
        if ($this->service->createPeriod_d($_POST[$this->objName])) {
            msg('设置成功');
        } else {
            msg('设置失败');
        }
    }

    /**
     * 检测是否已存在本年的财务周期
     */
    function c_isExistPeriod() {
        $year = isset($_REQUEST['year'])? $_REQUEST['year'] : '';
        $businessBelong = isset($_REQUEST['businessBelong'])? $_REQUEST['businessBelong'] : '';
        echo $this->service->isExistPeriod_d($year,$businessBelong) ? 1 : 0;
    }

    /**
     * 检测是否在财务期内
     */
    function c_isFirst() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isFirst_d($_POST['year'], $_POST['month'], $type) ? 1 : 0;
    }

    /**
     * 检测是否在财务期内 传时间
     */
    function c_isFirstDate() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isFirstDate_d($_POST['thisDate'],$type) ? 1 : 0;
    }

    /**
     * 检测时间是否大于当前财务周期 传时间
     */
    function c_isLaterPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo $this->service->isLaterPeriod_d($_POST['thisDate'],$type) ? 0 : 1;
    }

    /**
     * 结帐功能
     */
    function c_checkout() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->checkout_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     * 反结帐功能
     */
    function c_uncheckout() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->uncheckout_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     *  关账功能
     */
    function c_close() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->close_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     *  反关账
     */
    function c_unclose() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->unclose_d($_POST['id'],$type,$businessBelong) ? 1 : 0;
    }

    /**
     * 检测是否处于关账状态下
     */
    function c_isClosed() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        $businessBelong = isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '';
        echo $this->service->isClosed_d($type,$businessBelong) ? 1 : 0;
    }

    /**
     * 获取所有系统中的财务周期
     */
    function c_getAllPeriod() {
        $this->service->_isSetCompany = 0;
        $_POST['businessBelong'] = $_SESSION['USER_COM'];// 根据用户所属公司来获取对应的周期
        $this->service->getParam($_POST);
        echo util_jsonUtil::encode($this->service->list_d('selectSelect'));
    }

    /**
     * 获取最近5周财务期
     */
    function c_getNextOneYearPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo util_jsonUtil::encode($this->service->getNextOneYearPeriod_d($type));
    }

    /**
     * 获取当前财务期
     */
    function c_getNowPeriod() {
    	$type = isset($_POST['type']) ? $_POST['type'] : 'stock';
        echo util_jsonUtil::encode($this->service->rtThisPeriod_d(1,$type));
    }
}