<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 13:59:48
 * @version 1.0
 * @description:��ԴĿ¼(oa_esm_baseinfo_resource)���Ʋ�
 */
class controller_engineering_baseinfo_resource extends controller_base_action {

	function __construct() {
		$this->objName = "resource";
		$this->objPath = "engineering_baseinfo";
		parent::__construct ();
	}

	/*
	 * ��ת����ԴĿ¼(oa_esm_baseinfo_resource)
	 */
    function c_page() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$this->assign('parentId',$parentId);
       	$this->view('list');
    }

    /**
	 * ������
	 */
	function c_getChildren(){
		$service = $this->service;

		$sqlKey = isset($_POST['rtParentType'])? 'select_treelistRtBoolean' : 'select_treelist';

		if(empty($_POST['id'])){
			$rows = array(array('id'=>PARENT_ID,'code' => 'root','name'=> '��ԴĿ¼','isParent'=>'true'));
		}else{
			$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;

			$service->searchArr['parentId'] = $parentId;
			$service->asc = false;

			$rows=$service->listBySqlId($sqlKey);
		}
		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * ����Ƿ���ڸ��ڵ㣬�����������һ��
	 */
	function c_checkParent(){
		if($this->service->checkParent_d()){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId));
        $this->assignFunc($row);
        $this->view('add');
	}

		/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign ( 'resourceNature', $this->getDataNameByCode ( $obj ['resourceNature'] ) );
			$this->view ( 'view' );
		} else {
			$this->showDatadicts ( array ('resourceNature' => 'GCXMZYXZ' ), $obj ['resourceNature'] );
			$this->view ( 'edit' );
		}
	}

	/**
	 * �ı�����״̬
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => $_POST['status']))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ɾ��ʱ�ж��Ƿ�����
	 */
	 function c_deleteCheck(){
		if($this->service->deleteCheck_d($_POST['rowData']) == 1){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	 }
}
?>