<?php
/**
 * @author Show
 * @Date 2012��8��28�� ���ڶ� 11:32:28
 * @version 1.0
 * @description:��ְ�ʸ���֤���۽����������ϸ���Ʋ� 
 */
class controller_hr_certifyapply_certifyresultdetail extends controller_base_action {

	function __construct() {
		$this->objName = "certifyresultdetail";
		$this->objPath = "hr_certifyapply";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ְ�ʸ���֤���۽����������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ְ�ʸ���֤���۽����������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ְ�ʸ���֤���۽����������ϸҳ��
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
	 * ��ת���鿴��ְ�ʸ���֤���۽����������ϸҳ��
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