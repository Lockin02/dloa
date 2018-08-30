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
	 * �ص��ע
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ��ע���б�
	 */
	function c_focusPage(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ȷ����ӹ�עҳ��
	 */
	function c_makeSure(){
		if($this->service->isFocused($_GET['user_id'],$_SESSION['USER_ID'])){
			showmsg('�Ѵ��ڵĹ�ע����');
		}else
			showmsg ( 'ȷ��Ҫ��ע<<'.$_GET['user_name'].'>>����־��', "location.href='?model=rdproject_personfocus_personfocus&action=addFocus&id=" . $_GET ['id'] . "&user_id=" .$_GET['user_id'] ."&user_name=".$_GET['user_name'] ."'" , 'button' );
	}

	/**
	 * ���ܣ���ӹ�ע
	 */
	function c_addFocus(){
		if($this->service->addFocus($_GET['id'],$_GET['user_id'],$_GET['user_name'])){
			msg('��ӳɹ�');
		}else{
			msg('���ʧ��');
		}
	}

	/**
	 * ȷ��ȡ����עҳ��
	 */
	function c_makeSureCancl(){
		showmsg ( 'ȷ��ȡ����ע��', "location.href='?model=rdproject_personfocus_personfocus&action=canclFocus&id=" . $_GET ['id'] . "'", 'button' );
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
