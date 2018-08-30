<?php

/**
 * @author show
 * @Date 2015年2月5日 15:49:55
 * @version 1.0
 * @description:项目关闭规则控制层
 */
class controller_engineering_baseinfo_esmcloserule extends controller_base_action
{

	function __construct() {
		$this->objName = "esmcloserule";
		$this->objPath = "engineering_baseinfo";
		parent::__construct();
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listRuleJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->asc = false;
		$rows = $service->list_d('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 跳转到项目关闭规则列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增项目关闭规则页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目关闭规则页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看项目关闭规则页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}