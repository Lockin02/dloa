<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:23
 * @version 1.0
 * @description:项目费用预算变更表控制层
 */
class controller_engineering_change_esmchangebud extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangebud";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * 跳转到项目费用预算变更表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort = 'c.budgetType ,c.parentName';
		$service->asc = false;
		$rows = $service->list_d ();
		//加入权限
		$rows = $this->gridDateFilter($rows);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 列表数据权限过滤
	 */
	function gridDateFilter($rows){
		//人力预算单价权限点 2013-07-08
		$otherDataDao = new model_common_otherdatas();
		$esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		if(!$esmLimitArr['人力预算单价']){
			foreach($rows as $key => $val){
				if($val['budgetType'] == 'budgetPerson' && empty($val['customPrice'])){
					$rows[$key]['price'] = '******';
				}
			}
		}
		return $rows;
	}

	/********************* 增删改查 *************************/
	/**
	 * 跳转到新增项目费用预算变更表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目费用预算变更表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
		$this->view('edit');
	}

	/**
	 * 跳转到查看项目费用预算变更表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
		$this->view('view');
	}
}