<?php
/**
 * @author Administrator
 * @Date 2012��8��27�� ���ڶ� 15:54:13
 * @version 1.0
 * @description:�鼮�鿴
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
			$userAccount = " ";		//������ѯ�������ں�̨ƴװ��ѯ����ʱ��������ȡ����
		}
      	$this->assign('userID',$userAccount);
      	$this->view('list');
    }
 }
?>