<?php
/**
 * @author Administrator
 * @Date 2012-08-02 15:58:33
 * @version 1.0
 * @description:�������ֿ��Ʋ�
 */
class controller_projectmanagent_chance_competitor extends controller_base_action {

	function __construct() {
		$this->objName = "competitor";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * ��ת�����������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������������ҳ��
	 */
	function c_toAdd() {
	   $this->assign("chanceId",$_GET['chanceId']);
       $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>