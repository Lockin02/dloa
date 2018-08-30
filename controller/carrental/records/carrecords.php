<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 19:07:53
 * @version 1.0
 * @description:�ó���¼(oa_carrental_records)���Ʋ�
 */
class controller_carrental_records_carrecords extends controller_base_action {

	function __construct() {
		$this->objName = "carrecords";
		$this->objPath = "carrental_records";
		parent::__construct ();
	}

	/*
	 * ��ת���ó���¼(oa_carrental_records)�б�
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * ��Ŀ�鿴�⳵��¼
     */
	function c_pageForProject(){
		$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
	}

   /**
	 * ��ת�������ó���¼(oa_carrental_records)ҳ��
	 */
	function c_toAdd() {
     	$this->view ( 'add' );
	}

   /**
	 * ��ת���༭�ó���¼(oa_carrental_records)ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

   /**
	 * ��ת���鿴�ó���¼(oa_carrental_records)ҳ��
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
    * ��ת�鿴������ϢTab
    */
    function c_toViewForCarinfo(){
    	$this->assign ( "carId", $_GET['id'] );
    	$this->view ( 'viewlist' );
    }

    /**
     * ��ת�ó���¼Tab
     */
	function c_viewTab(){
		$this->permCheck (); //��ȫУ��
	 	$this->assign ( "id", $_GET['id'] );
	 	$this->view ( 'viewtab' );
	}
}
?>