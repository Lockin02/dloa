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
	 * �ص��ע
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->service->searchArr['isUsing'] = '1';
		$this->service->searchArr['focuser'] = $_SESSION['USER_ID'];
		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * ȷ����ӹ�עҳ��
	 */
	function c_makeSure(){
		if($this->service->isFocused($_GET['id'],$_SESSION['USER_ID'])){
			showmsg('�Ѵ��ڵĹ�ע');
		}else
			showmsg ( 'ȷ����ӹ�ע��', "location.href='?model=rdproject_personfocus_personfocus&action=addFocus&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * ���ܣ���ӹ�ע
	 */
	function c_addFocus(){
		if($this->service->addFocus($_GET['id'])){
			msg('��ӳɹ�');
		}else{
			msg('���ʧ��');
		}
	}

	/**
	 * ȷ��ȡ����עҳ��
	 */
	function c_makeSureCancl(){
		showmsg ( 'ȷ��ȡ����ע��', "location.href='?model=rdproject_worklog_rdweekfocus&action=canclFocus&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * ���ܣ�ȡ��
	 */
	function c_canclFocus(){
		if($this->service->canclFocus($_GET['id'])){
			msg('ȡ���ɹ�');
		}else{
			msg('ȡ��ʧ��');
		}
	}
}
?>
