<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:�����ù黹���ϴӱ���Ʋ�
 */
class controller_projectmanagent_borrowreturn_borrowreturnDisequ extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturnDisequ";
		$this->objPath = "projectmanagent_borrowreturn";
		parent::__construct ();
	 }

	/**
	 * ��ת�������ù黹���ϴӱ��б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�����������ù黹���ϴӱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�����ù黹���ϴӱ�ҳ��
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
	 * ��ת���鿴�����ù黹���ϴӱ�ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 
 }
?>