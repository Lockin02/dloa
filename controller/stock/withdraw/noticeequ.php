<?php
/**
 * @author Administrator
 * @Date 2012��11��20�� 10:22:14
 * @version 1.0
 * @description:���֪ͨ���嵥���Ʋ� 
 */
class controller_stock_withdraw_noticeequ extends controller_base_action {

	function __construct() {
		$this->objName = "noticeequ";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	 }
    
	/*
	 * ��ת�����֪ͨ���嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������֪ͨ���嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���֪ͨ���嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴���֪ͨ���嵥ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   
   /**
    * �����ƻ������ʱ����ȡʵ�ʿ�������������
    */
   function c_getNumAtInStock(){
   	$rs = $this->service->find(array('mainId' => $_POST['mainId'],'productId' => $_POST['productId']),null,'number,executedNum');
   	echo $rs['number'] - $rs['executedNum'];
   }
 }