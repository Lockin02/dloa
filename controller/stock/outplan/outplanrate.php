<?php
/**
 * @author Administrator
 * @Date 2012��2��20�� 14:00:37
 * @version 1.0
 * @description:�����ƻ����ȱ�ע���Ʋ�
 */
class controller_stock_outplan_outplanrate extends controller_base_action {

	function __construct() {
		$this->objName = "outplanrate";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ƻ����ȱ�ע
	 */
    function c_page() {
    	$planId = isset($_GET['id']) ? $_GET['id'] : null;
    	$this->assign('planId',$planId);
    	$this->view('list');
    }


	/*
	 * ��ת�������ƻ����ȱ�ע
	 */
    function c_toAdd() {
    	$planId = isset($_GET['id']) ? $_GET['id'] : null;
    	$this->assign('createName',$_SESSION['USERNAME']);
    	$this->assign('planId',$planId);
	  $this->view('add');
    }
	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��ת��¼����Ƚ���
	 */
	 function c_updateRate(){
	 	$planId = $_GET['id'];
		$rateDao->asc=false;
	 	$this->service->searchArr['planId']=$planId;
	 	$row = $this->service->list_d();
	 	if(is_array($row)&&count($row)>0){
	 		foreach ( $row['0'] as $key=>$val){
	 			$this->assign($key,$val);
	 		}
			$this->permCheck (); //��ȫУ��
			$this->view ( 'edit' );
	 	}else{
	 		$this->c_toAdd();
	 	}
	 }

 }
?>