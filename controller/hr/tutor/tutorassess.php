<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 16:39:03
 * @version 1.0
 * @description:��ʦ���˱���Ʋ� 
 */
class controller_hr_tutor_tutorassess extends controller_base_action {

	function __construct() {
		$this->objName = "tutorassess";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ʦ���˱��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ʦ���˱�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ʦ���˱�ҳ��
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
	 * ��ת���鿴��ʦ���˱�ҳ��
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