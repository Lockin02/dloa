<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_template_rdtplan extends controller_base_action{
	function __construct() {
		$this->objName = "rdtplan";
		$this->objPath = "rdproject_template";
		parent::__construct ();
	}

	/**
	 * 模板管理列表
	 */
	function c_showPlanTemplates(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 重写新增模板
	 */
	function c_toAdd() {
		$this->show->assign('pnName',$_GET['pnName']);
		$this->show->assign('pnId',$_GET['pnId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * 模板发布功能
	 */
	function c_issue(){
		if($this->service->issue($_POST['id']))
			echo 1;
		else
			echo 0;
	}

	/**
	 * 删除计划模板
	 */
	function c_toDelete(){
		showmsg('确认删除？',"location.href='?model=rdproject_template_rdtplan&action=sureDelete&id=" . $_GET ['id']."'" , 'button' );
	}

	/**
	 * 删除
	 */
	function c_sureDelete(){
		if ($this->service->deletes( $_POST['id'])) {
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>
