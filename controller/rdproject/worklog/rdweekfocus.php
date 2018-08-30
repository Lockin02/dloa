<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_worklog_rdweekfocus extends controller_base_action{
	function __construct() {
		$this->objName = "rdweekfocus";
		$this->objPath = "rdproject_worklog";
		parent::__construct ();
	}

	/**
	 * 重点关注
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$this->service->searchArr['isUsing'] = '1';
		$this->service->searchArr['focuser'] = $_SESSION['USER_ID'];
		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 确认添加关注页面
	 */
	function c_makeSure(){
		if($this->service->isFocused($_GET['id'],$_SESSION['USER_ID'])){
			showmsg('已存在的关注');
		}else
			showmsg ( '确认添加关注？', "location.href='?model=rdproject_personfocus_personfocus&action=addFocus&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * 功能：添加关注
	 */
	function c_addFocus(){
		if($this->service->addFocus($_GET['id'])){
			msg('添加成功');
		}else{
			msg('添加失败');
		}
	}

	/**
	 * 确认取消关注页面
	 */
	function c_makeSureCancl(){
		showmsg ( '确认取消关注？', "location.href='?model=rdproject_worklog_rdweekfocus&action=canclFocus&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * 功能：取消
	 */
	function c_canclFocus(){
		if($this->service->canclFocus($_GET['id'])){
			msg('取消成功');
		}else{
			msg('取消失败');
		}
	}
}
?>
