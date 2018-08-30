<?php
/**
 * @author Administrator
 * @Date 2012��3��11�� 15:15:40
 * @version 1.0
 * @description:��Ʒ������Ϣ���Ʋ�
 */
class controller_yxlicense_license_categoryform extends controller_base_action {

	function __construct() {
		$this->objName = "categoryform";
		$this->objPath = "yxlicense_license";
		parent::__construct ();
	}

	/*
	 * ��ת����������Ϣ�б�
	 */
	function c_page() {
//		$this->assign('isUse',$_GET['isUse']);
    	$this->assign('itemId',$_GET['id']);
 //   	$this->assign('name',$_GET['name']);
		$this->view ( 'list' );
	}

	/**
	 * ��ת����������ҳ��
	 */
	function c_toAdd() {
		$this->assign('itemId',$_GET['id']);
		$this->view ( 'add' );
	}
	
	//����
	function c_add() {
		$obj = $_POST[$this->objName];
		$id = $this->service->add_d ($obj);
		if ($id) {
			msg ( "��ӳɹ�" );
		} else {
			msg ( "���ʧ��" );
		}
	}
	
	/**
	 * ��ת���༭������Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	/**
	 * ��ת����ϸ������Ϣҳ��
	 */
	function c_toDetailEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'detailedit' );
	}
	

	/**
	 * ��ת���鿴������Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		
		$this->view ( 'view' );
	}

	/**
	 * ����licenseId��Ʒ������Ϣ
	 */
	function c_getTreeData() {
		$service = $this->service;
		$isSale = isset ( $_GET ['isSale'] ) ? $_GET ['isSale'] : 0;
		if($isSale == '1'){
			$service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.licenseId='11')";
		}
//		$licenseId = isset ( $_POST ['id'] ) ? $_POST ['id'] : 1;
//		$service->searchArr ['licenseId'] = $licenseId;
		$service->sort = " c.orderNum";
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = array ();
		$rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * ���µ�����Ʒ�������ҽڵ�
	 */
	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}
	
	/**
	 * ��ת��Ԥ��ҳ��
	 */
	function c_preview(){
		$this->assign('name',$_GET['licenseName']);
		$this->assign('id',$_GET['id']);
		$this->view('preview');
	}
	
// 	function c_returnHtml(){
// 		$id = $_REQUEST['id'] ? $_REQUEST['id'] : null;
		
		
// 	}
}
?>