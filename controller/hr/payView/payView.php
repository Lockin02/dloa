<?php
/**
 * @author Administrator
 * @Date 2012年8月28日 星期三 15:54:13
 * @version 1.0
 * @description:借款查看
 */
class controller_hr_payView_payView extends controller_base_action {

	function __construct() {
		$this->objName = "payView";
		$this->objPath = "hr_payView";
		parent::__construct ();
	 }
	function c_toPersonList() {
		if($_GET['userAccount']){
			$userAccount = $_GET['userAccount'];
		}
		else{
			$userAccount = " ";		//条件查询，避免在后台拼装查询条件时该条件被取消掉
		}
      	$this->assign('debtor',$userAccount);
      	$this->view('list');
    }
    //跳转到工程项目成员借款记录表
    function c_toEsmmemberList() {
    	$this->assign('debtor',$_GET['userAccount']);
    	$this->assign('projectNo',$_GET['projectCode']);
    	$this->view('esmmember-list');
    }
 }
?>