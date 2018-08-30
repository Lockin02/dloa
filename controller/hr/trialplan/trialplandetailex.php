<?php

/**
 * @author Show
 * @Date 2012年9月4日 星期二 13:30:45
 * @version 1.0
 * @description:任务扩展信息控制层
 */
class controller_hr_trialplan_trialplandetailex extends controller_base_action {

	function __construct() {
		$this->objName = "trialplandetailex";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * 跳转到任务扩展信息列表
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

	/**
	 * 跳转到新增任务扩展信息页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑任务扩展信息页面
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
	 * 跳转到查看任务扩展信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}


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
            $rs = util_jsonUtil::encode($rs);
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

	//积分计算
	function c_calScore(){
		$ids = $_POST['ids'];
		$score = $_POST['score'];
		$baseScore = $this->service->calScore_d($ids,$score);

		if($baseScore){
			echo $baseScore;
		}else{
			echo 0;
		}
	}
}
?>