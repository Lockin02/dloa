<?php
/**
 * @author Administrator
 * @Date 2012��8��28�� ������ 15:54:13
 * @version 1.0
 * @description:���鿴
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
			$userAccount = " ";		//������ѯ�������ں�̨ƴװ��ѯ����ʱ��������ȡ����
		}
      	$this->assign('debtor',$userAccount);
      	$this->view('list');
    }
    //��ת��������Ŀ��Ա����¼��
    function c_toEsmmemberList() {
    	$this->assign('debtor',$_GET['userAccount']);
    	$this->assign('projectNo',$_GET['projectCode']);
    	$this->view('esmmember-list');
    }
 }
?>