<?php
/**
 * @author Administrator
 * @Date 2012��10��7�� ������ 15:16:42
 * @version 1.0
 * @description:��ʦ����ģ����ϸ���Ʋ� 
 */
class controller_hr_tutor_schemeDetail extends controller_base_action {

	function __construct() {
		$this->objName = "schemeDetail";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ʦ����ģ����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ʦ����ģ����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ʦ����ģ����ϸҳ��
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
	 * ��ת���鿴��ʦ����ģ����ϸҳ��
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