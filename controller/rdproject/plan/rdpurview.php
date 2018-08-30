<?php
/*
 * Created on 2010-10-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_plan_rdpurview extends controller_base_action{

	function __construct() {
		$this->objName = "rdpurview";
		$this->objPath = "rdproject_plan";
		parent::__construct ();
	}


	/**
	 * 显示计划中的权限
	 */
	function c_purviewPlan(){
		$isGetPurview = $this->service->checkPurview($_GET['pnId']);
		if($isGetPurview){
			$this->show->assign('disabled_save',"");
			$this->show->assign('disabled_add',"");
		}else{
			$this->show->assign('disabled_save',"disabled='disabled'");
			$this->show->assign('disabled_add',"disabled='disabled'");
		}
		$rows = $this->service->getListInPlan($_GET['pnId']);
		$this->show->assign('pnId',$_GET['pnId']);
		$this->show->assign('list',$this->service->showlist($rows,$isGetPurview));
		$this->show->display($this->objPath.'_'.$this->objName.'-list');
	}

	/**
	 * 保存计划
	 */
	function c_savePlanPurview(){
		if($this->service->savePlanPurview($_POST[$this->objName],$_POST['planId'])){
			msgGo('保存成功');
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->show->assign('pnId',$_GET['pnId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}


	/**
	 * AJAX验证是否在计划中已存在该对象的权限
	 */
	function c_ajaxPurview() {
		$userName = isset ( $_GET ['userName'] ) ? $_GET ['userName'] : false;
		$planId = isset ( $_GET ['planId'] ) ? $_GET ['planId'] : false;
		$searchArr = array ("planId" => $planId,"userName" => $userName);
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
}
?>
