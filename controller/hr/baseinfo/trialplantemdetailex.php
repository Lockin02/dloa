<?php

/**
 * @author Show
 * @Date 2012年9月3日 星期一 19:51:29
 * @version 1.0
 * @description:任务模板扩展信息控制层
 */
class controller_hr_baseinfo_trialplantemdetailex extends controller_base_action {

	function __construct() {
		$this->objName = "trialplantemdetailex";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/***************** 列表 *************************/

	/**
	 * 跳转到任务模板扩展信息列表
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
		if(empty($_REQUEST['ids'])){
			return "";
		}
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/***************** 增删改查 **********************/

	/**
	 * 跳转到新增任务模板扩展信息页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑任务模板扩展信息页面
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
	 * 跳转到查看任务模板扩展信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/*************** 业务逻辑 ************************/
	/**
	 * 批量处理方法
	 */
	function c_toSetRule(){
		$this->assignFunc($_GET);
		$this->view('setrule');
	}

	/**
	 * 批量处理
	 */
	function c_setRule(){
		$object = $_POST[$this->objName];
		$rs = $this->service->setRule_d($object);
		if($rs){
			echo "<script>alert('保存成功');if(window.opener){window.opener.returnValue = '$rs';}window.returnValue = '$rs';window.close();</script>";
		}else{
            echo "<script>alert('保存失败');window.close();</script>";
        }
        exit();
	}

	/**
	 * 跳转到查看任务模板扩展信息页面
	 */
	function c_toViewRule() {
		$this->assignFunc($_GET);
		$this->view('viewrule');
	}
}
?>