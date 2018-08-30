<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_personfocus_personfocus extends controller_base_action{
	function __construct() {
		$this->objName = "personfocus";
		$this->objPath = "rdproject_personfocus";
		parent::__construct ();
	}

	/**
	 * 重点关注
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->searchArr['isUsing'] = '1';
		$service->searchArr['focuser'] = $_SESSION['USER_ID'];
		$service->searchArr['largeUpdate'] = 1;
		$service->sort = 'w.updateTime';
		$rows = $service->pageBySqlId('focusWeeklog');
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 关注人列表
	 */
	function c_focusPage(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->searchArr['isUsing'] = '1';
		$service->searchArr['focuser'] = $_SESSION['USER_ID'];
		$service->sort = 'w.updateTime';
		$service->groupBy = 'w.createId';
		$rows = $service->pageBySqlId('focus_person');
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showfocuslist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-personlist' );
	}

	/**
	 * 确认添加关注页面
	 */
	function c_makeSure(){
		if($this->service->isFocused($_GET['user_id'],$_SESSION['USER_ID'])){
			showmsg('已存在的关注对象');
		}else
			showmsg ( '确认要关注<<'.$_GET['user_name'].'>>的日志吗？', "location.href='?model=rdproject_personfocus_personfocus&action=addFocus&id=" . $_GET ['id'] . "&user_id=" .$_GET['user_id'] ."&user_name=".$_GET['user_name'] ."'" , 'button' );
	}

	/**
	 * 功能：添加关注
	 */
	function c_addFocus(){
		if($this->service->addFocus($_GET['id'],$_GET['user_id'],$_GET['user_name'])){
			msg('添加成功');
		}else{
			msg('添加失败');
		}
	}

	/**
	 * 确认取消关注页面
	 */
	function c_makeSureCancl(){
		showmsg ( '确认取消关注？', "location.href='?model=rdproject_personfocus_personfocus&action=canclFocus&id=" . $_GET ['id'] . "'", 'button' );
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
