<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_template_rdttask extends controller_base_action{
	function __construct() {
		$this->objName = "rdttask";
		$this->objPath = "rdproject_template";
		parent::__construct ();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assign( 'templateName',$_GET['templateName'] );
		$this->assign( 'templateId',$_GET['templateId'] );
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ));
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ));
		$this->display ( 'add' );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		if(isset($_GET ['perm']) && $_GET ['perm'] == 'view'){
			$rows['taskType'] = $this->getDataNameByCode($rows['taskType']);
			$rows['priority'] = $this->getDataNameByCode($rows['priority']);
		}
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		if (isset($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) ,$rows['taskType']);
			$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ),$rows['priority'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * ȷ��ɾ��
	 */
	function c_toDelete(){
		showmsg('ȷ��ɾ����',"location='?model=rdproject_template_rdttask&action=deleteAction&id=".$_GET['id']."'",'button');
	}
//
//	/**
//	 * ɾ������
//	 */
//	function c_deleteAction(){
//		if($this->service->deletes($_GET['id'])){
//			msg('ɾ���ɹ�');
//		}else{
//			msg('ɾ��ʧ��');
//		}
//	}


}
?>
