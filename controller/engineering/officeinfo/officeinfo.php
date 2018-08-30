<?php

/*
 * Created on 2010-11-29
 * 办事处信息
 * author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_engineering_officeinfo_officeinfo extends controller_base_action
{
    function __construct()
    {
        $this->objName = "officeinfo";
        $this->objPath = "engineering_officeinfo";
        parent::__construct();
    }

    /**
     * 办事处信息列表
     */
    function c_officelist()
    {
        $this->view('list');
    }

    /**
     * 新增办事处
     */
    function c_toAdd()
    {
        $this->showDatadicts(array('productLine' => 'GCSCX'));
        $this->showDatadicts(array('module' => 'HTBK'));
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->view('add');
    }

    /**
     * 修改办事处信息
     */
    function c_officeinfoEdit()
    {
        $this->view('edit');
    }

    /**
     * 查看办事处信息
     */
    function c_officeinfoRead()
    {
        $this->view('read');
    }

    /**获取分页数据转成Json
     *author can
     *2010-12-1
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->page_d();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 在用的工程区域
     */
    function c_pageJsonUsing()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['state'] = 0;
        $rows = $service->page_d();
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**初始化对象
     *author can
     *2010-12-1
     */
    function c_init()
    {
        $obj = $this->service->get_d($_GET ['id']);
        $this->assignFunc($obj);
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $datadictDao = new model_system_datadict_datadict();
            $productLine = $datadictDao->getDataNameByCode($obj['productLine']);
            $this->assign('productLine', $productLine);
            $this->assign('module', $datadictDao->getDataNameByCode($obj['module']));
            $this->assign('stateCN', $obj['state'] ? "关闭" : "开启");
            $this->view('read');
        } else {
            $this->showDatadicts(array('productLine' => 'GCSCX'), $obj['productLine']);
            $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);
            $this->view('edit');
        }
    }

    /**
     * 判断办事处是否被项目关联
     */
    function c_isProjected()
    {
        echo $this->service->isProjected_d($_POST['id']) ? 1 : 0;
    }

    //初始化字段值
    function c_initOffice()
    {
        $this->service->initOffice_d();
        echo "更新成功";
    }

    /**
     * ajax获取合同产品执行区域
     */
    function c_ajaxConProExeDept()
    {
        $province = $_POST['province'];
        $module = $_POST['module'];
        $sql = "SELECT
				    *
				FROM
					oa_esm_office_baseinfo
				WHERE
				    module = '" . $module . "'
				AND find_in_set(
					'" . $province . "',
					rangeId
				)";
        $arr = $this->service->_db->getArray($sql);
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ajax 根据执行区域获取办事处的相关信息
     */
    function c_ajaxGetOfficeInfo(){
        $productLine = isset($_POST['productLine'])? $_POST['productLine'] : '';
        $officeinfoDao = new model_engineering_officeinfo_officeinfo();
        $officeInfo = $officeinfoDao->find(array("productLine" => $productLine),null,"id as officeId,officeName,feeDeptId,feeDeptName");
        $backArr = array("officeId"=>"","officeName"=>"","feeDeptId"=>"","feeDeptName"=>"");
        if($officeInfo){
            $feeDeptIdArr = explode(",",$officeInfo['feeDeptId']);
            $feeDeptNameArr = explode(",",$officeInfo['feeDeptName']);
            $backArr['officeId'] = $officeInfo['officeId'];
            $backArr['officeName'] = $officeInfo['officeName'];
            $backArr['feeDeptId'] = !empty($feeDeptIdArr)? $feeDeptIdArr[0] : '';
            $backArr['feeDeptName'] = !empty($feeDeptNameArr)? $feeDeptNameArr[0] : '';
        }
        echo util_jsonUtil::encode($backArr);
    }
}
