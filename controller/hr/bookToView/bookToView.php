<?php
/**
 * @author Administrator
 * @Date 2012年8月27日 星期二 15:54:13
 * @version 1.0
 * @description:书籍查看
 */
class controller_hr_bookToView_bookToView extends controller_base_action {

	function __construct() {
		$this->objName = "bookToView";
		$this->objPath = "hr_bookToView";
		parent::__construct ();
	 }
	function c_toPersonList() {
		if($_GET['userAccount']){
			$userAccount = $_GET['userAccount'];
		}
		else{
			$userAccount = " ";		//条件查询，避免在后台拼装查询条件时该条件被取消掉
		}
      	$this->assign('userID',$userAccount);
      	$this->view('list');
    }
 }
?>