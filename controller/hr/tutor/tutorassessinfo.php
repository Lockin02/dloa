<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 16:39:50
 * @version 1.0
 * @description:��ʦ���˱�----������ϸ���Ʋ� 
 */
class controller_hr_tutor_tutorassessinfo extends controller_base_action {

	function __construct() {
		$this->objName = "tutorassessinfo";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ʦ���˱�----������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ʦ���˱�----������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ʦ���˱�----������ϸҳ��
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
	 * ��ת���鿴��ʦ���˱�----������ϸҳ��
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