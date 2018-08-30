<?php
/**
 * @author Administrator
 * @Date 2012-10-25 14:53:11
 * @version 1.0
 * @description:�豸������Ϣ���Ʋ�
 */
class controller_equipment_budget_budgetType extends controller_base_action {

	function __construct() {
		$this->objName = "budgetType";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * ��ת���豸������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������豸������Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�豸������Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�豸������Ϣҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   /******************�豸������************************************/
	/**
	 * ����parentId��Ʒ������Ϣ
	 */
	function c_getTreeData() {
		$service = $this->service;
		$parentId = isset ( $_POST ['id'] ) ? $_POST ['id'] : - 1;

		$service->searchArr ['parentId'] = $parentId;
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
		if (isset ( $service->searchArr ['parentId'] )) {
			$node = $service->get_d ( $service->searchArr ['parentId'] );
			unset ( $service->searchArr ['parentId'] );
			$service->searchArr ['dlft'] = $node ['lft'];
			$service->searchArr ['xrgt'] = $node ['rgt'];
			$rows = $service->page_d ( "select_default" );
		} else {
			$rows = $service->page_d ();
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>