<?php
/**
 * @author Administrator
 * @Date 2012��10��30�� ���ڶ� 14:49:22
 * @version 1.0
 * @description:��ְ�����嵥��ϸ�����˿��Ʋ� 
 */
class controller_hr_leave_handoverMember extends controller_base_action {

	function __construct() {
		$this->objName = "handoverMember";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ְ�����嵥��ϸ�������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ְ�����嵥��ϸ������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ְ�����嵥��ϸ������ҳ��
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
	 * ��ת���鿴��ְ�����嵥��ϸ������ҳ��
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