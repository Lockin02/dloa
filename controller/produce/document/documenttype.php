<?php
/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_produce_document_documenttype extends controller_base_action {

	function __construct() {
		$this->objName = "documenttype";
		$this->objPath = "produce_document";
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
		$service->sort = 'c.orderNum';
		$service->asc = false;
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
		if(isset ( $_POST ['id'] )){//�����û����չ����
			$service->searchArr ['parentId'] = $_POST ['id'];
		}else{//���ڵ�һ�μ���
			if(isset ( $_GET ['typeId'] ) && !empty($_GET ['typeId'])){//�޶�ĳ������
				$service->searchArr ['id'] = $_GET ['typeId'];
			}else{//���޶�ĳ�����࣬��ʾ����������������������
				$service->searchArr ['parentId'] = -1;
				$service->searchArr ['nid'] = -1;
			}
		}
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ����ҳ��
	 */
	function c_toAdd() {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : "";
		$parentName = isset ( $_GET ['parentId'] ) ? $_GET ['parentName'] : "";
	
		if ($parentId == "") {
			$parentId = - 1;
			$parentName = "�ĵ�����";
		}
	
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentName", $parentName );
	
		$this->display ( "add" );
	}
	
	/**
	 * �޸�ҳ��
	 */
	function c_toEdit() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assign ( "type", $_GET['type'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display ( "edit" );
	}
	
	/**
	 * �鿴ҳ��
	 */
	function c_toView() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('isUse',$obj['isUse'] == 1 ? '��' : '��');
	
		$this->display ( "view" );
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
			msg ( "�޸��ĵ�����ɹ���" );
		} else {
			msg ( "�޸��ĵ�����ʧ�ܣ�" );
		}
	}

	/**
	 * ������������Ƿ��ظ�
	 */
	function c_checktype() {
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("ntype" => $type );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}
}
