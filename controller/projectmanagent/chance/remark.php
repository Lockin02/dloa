<?php
/**
 * @author Administrator
 * @Date 2012-10-24 15:52:02
 * @version 1.0
 * @description:��ͬ��Ϣ��ע���Ʋ�
 */
class controller_projectmanagent_chance_remark  extends controller_base_action {

	function __construct() {
		$this->objName = "remark";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * ��ת����ͬ��Ϣ��ע�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ͬ��Ϣ��עҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ͬ��Ϣ��עҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��ͬ��Ϣ��עҳ��
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