<?php
/**
 * @author Administrator
 * @Date 2012��2��29�� 19:19:15
 * @version 1.0
 * @description:����������ȱ�ע���Ʋ�
 */
class controller_stock_outplan_contractrate extends controller_base_action {

	function __construct() {
		$this->objName = "contractrate";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
    	$this->assign('relDocId',$_GET['docId']);
    	$this->assign('relDocType',$_GET['docType']);
    	$this->assign('rObjCode',$_GET['objCode']);
    	$this->assign('createName',$_SESSION['USERNAME']);
		$this->view ( 'add' );
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
	 	$docId = $_GET['docId'];
	 	$docType = $_GET['docType'];
	 	$this->service->searchArr['relDocType']=$docType;
		$rateDao->asc=false;
	 	$this->service->searchArr['relDocId']=$docId;
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