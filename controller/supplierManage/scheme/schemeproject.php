<?php
/**
 * @author Administrator
 * @Date 2012��1��7�� 14:57:01
 * @version 1.0
 * @description:������Ŀ���Ʋ�
 */
class controller_supplierManage_scheme_schemeproject extends controller_base_action {

	function __construct() {
		$this->objName = "schemeproject";
		$this->objPath = "supplierManage_scheme";
		parent::__construct ();
	 }

	/*
	 * ��ת��������Ŀ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������Ŀҳ��
	 */
	function c_toAdd() {
	$this->assign ( 'formManName', $_SESSION ['USERNAME'] ); //������
	$this->assign ( 'formManId', $_SESSION ['USER_ID'] );
     $this->view ( 'add',true);
   }
   /**
    * �����ύ��֤
    */
   function c_add(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		$id = $this->service->add_d($object);
		if($id){
			msg("��ӳɹ���");
		}else{
			msg("���ʧ�ܣ�");
		}
   }

   /**
	 * ��ת���༭������Ŀҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit',true);
   }
/**
    * �����ύ��֤
    */
   function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d($object);
		if($id){
			msg("�༭�ɹ���");
		}else{
			msg("�༭ʧ�ܣ�");
		}
   }
   /**
	 * ��ת���鿴������Ŀҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>