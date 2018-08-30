<?php
/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_stock_productinfo_producttype extends controller_base_action {

	function __construct() {
		$this->objName = "producttype";
		$this->objPath = "stock_productinfo";
		parent::__construct ();
	}

	/**
	 * ���������б�ҳ��
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if (isset ( $service->searchArr ['parentId'] )) {
            $service->searchArr['customCondition'] = "sql: and (c.parentId = '".$service->searchArr ['parentId']."' or id = '".$service->searchArr ['parentId']."')";
			unset ( $service->searchArr ['parentId'] );
		}
        $rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ����parentId��ȡ������������
	 */
	function c_getTreeDataByParentId() {
		$service = $this->service;
		$parentId = isset ( $_POST ['id'] ) ? $_POST ['id'] : - 1;
		$service->searchArr ['parentId'] = $parentId;
		if(isset($_GET['esmCanUse'])){
			$service->searchArr ['esmCanUse'] = 1;
		}
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ������������Ƿ��ظ�
	 */
	function c_checkProType() {
		$proType = isset ( $_GET ['proType'] ) ? $_GET ['proType'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("nproType" => $proType );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName] );
		if ($id) {
			msg ( "�������Ϸ���ɹ���" );
		} else {
			msg ( "�������Ϸ�����Ϣʧ�ܣ�" );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msg ( "�޸����Ϸ���ɹ���" );
		} else {
			msg ( "�޸����Ϸ���ʧ�ܣ�" );
		}
	}

	function c_toAdd() {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : "";
		$parentName = isset ( $_GET ['parentId'] ) ? $_GET ['parentName'] : "";
		$submitDay = isset ( $_GET ['submitDay'] ) ? $_GET ['submitDay'] : "";

		if ($parentId == "") {
			$parentId = - 1;
			$parentName = "���Ϸ���";
		}

		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentName", $parentName );
		$this->show->assign ( "submitDay", $submitDay );

		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ) );
		$this->showDatadicts ( array ('properties' => 'WLSX' ) );
		$this->view ( "add" );
	}

	/**
	 * �޸�ҳ��
	 *
	 */
	function c_init() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $obj ['accessItem'] )) {
			$this->assign ( "itemAccessBody", $this->service->showItemAtEdit ( $obj ['accessItem'] ) );
			$this->assign ( "accessCount", count ( $obj ['accessItem'] ) );
		} else {
			$this->assign ( "itemAccessBody", "" );
			$this->assign ( "accessCount", "0" );
		}

		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ), $obj ['accountingCode'] );
		$this->showDatadicts ( array ('properties' => 'WLSX' ), $obj ['properties'] );
		$this->display ( "edit" );
	}
	/**
	 *
	 * �鿴ҳ��
	 */
	function c_view() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemAccessBody", $this->service->showItemAtView ( $obj ['accessItem'] ) );
		$this->assign ( "accessCount", count ( $obj ['accessItem'] ) );
		$this->show->assign ( "properties", $this->getDataNameByCode ( $obj ['properties'] ) );
		$this->show->assign ( "accountingCode", $this->getDataNameByCode ( $obj ['accountingCode'] ) );

		$esmCanUse = $obj['esmCanUse'] == 1 ? '��':'��';
		$this->assign('esmCanUse',$esmCanUse);

		$this->display ( "view" );
	}

	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}


	/**
	 * �޸�ҳ��
	 *
	 */
	function c_toEdit() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assign ( "type", $_GET['type'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $obj ['accessItem'] )) {
			$this->assign ( "itemAccessBody", $this->service->showItemAtEdit ( $obj ['accessItem'] ,$_GET['type']) );
			$this->assign ( "accessCount", count ( $obj ['accessItem'] ) );
		} else {
			$this->assign ( "itemAccessBody", "" );
			$this->assign ( "accessCount", "0" );
		}

		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ), $obj ['accountingCode'] );
		$this->showDatadicts ( array ('properties' => 'WLSX' ), $obj ['properties'] );
		$this->view ( "edit" );
	}


/**
 * ����嵥����ģʽ
 */
    function c_setEditType() {
    	$this->assign('id',$_GET['id']);
        $this->view("setEditType");
    }

}

?>
