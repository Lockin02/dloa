<?php

/**
 * @author Show
 * @Date 2012年12月1日 星期六 9:53:08
 * @version 1.0
 * @description:项目考核指标控制层
 */
class controller_engineering_assess_esmasspro extends controller_base_action {

	function __construct() {
		$this->objName = "esmasspro";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * 跳转到项目考核指标列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 编辑项目的考核模板
	 */
	function c_toProjectAssess(){
		$projectId = $_GET['projectId'];
		$projectObj = $this->service->getPorjectInfo_d($projectId);
		$this->assignFunc($projectObj);

		//配置模板获取
		$obj = $this->service->find(array('projectId' => $projectId));
		if($obj){
			$this->assign('indexInfo',$this->service->initEdit_d($obj));
		}else{
			$obj = array(
				'templateId' => '',
				'templateName' => '',
				'score' => '',
				'indexIds' => '',
				'indexNames' => '',
				'needIndexIds' => '',
				'needIndexNames' => '',
				'baseScore' => '',
				'needScore' => '',
				'useScore' => '',
				'useIndexIds' => '',
				'useIndexNames' => '',
				'id' => '',
				'indexInfo' => ''
			);
		}
		$this->assignFunc($obj);
		$this->display('projectassess');
	}

	/**
	 * 设置考核模板
	 */
	function c_projectAssess(){
		$object = $_POST[$this->objName];
		if(empty($object['id'])){
			$rs = $this->service->add_d($object);
		}else{
			$rs = $this->service->edit_d($object);
		}
		if($rs){
			msgGo ( '保存成功','?model=engineering_assess_esmasspro&action=toProjectAssess&projectId='.$object['projectId']);
		}else{
			msgGo ( '保存失败','?model=engineering_assess_esmasspro&action=toProjectAssess&projectId='.$object['projectId']);
		}
	}

	//查看日志审核模版
	function c_toViewProjectAssess(){
		$projectId = $_GET['projectId'];

		//配置模板获取
		$obj = $this->service->find(array('projectId' => $projectId));
		if(!$obj){
			$obj = array(
				'baseScore' => '暂未设置',
				'useIndexNames' => '暂未设置',
				'indexInfo' => '<tr><td colspan="8">暂未设置</td></tr>'
			);
		}else{
			$this->assign('indexInfo',$this->service->initView_d($obj));
		}
		$this->assignFunc($obj);

		$this->display('viewprojectassess');
	}

	/**
	 * 跳转到新增项目考核指标页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目考核指标页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看项目考核指标页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>