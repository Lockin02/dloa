<?php
/**
 * @author Administrator
 * @Date 2012��10��29�� ����һ 15:17:23
 * @version 1.0
 * @description:��ְ--��̸��¼����ϸ���Ʋ� 
 */
class controller_hr_leave_interviewDetail extends controller_base_action {

	function __construct() {
		$this->objName = "interviewDetail";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ְ--��̸��¼����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ְ--��̸��¼����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ְ--��̸��¼����ϸҳ��
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
	 * ��ת���鿴��ְ--��̸��¼����ϸҳ��
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