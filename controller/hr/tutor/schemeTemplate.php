<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 19:39:28
 * @version 1.0
 * @description:��ʦ���˱�ģ����Ʋ� 
 */
class controller_hr_tutor_schemeTemplate extends controller_base_action {

	function __construct() {
		$this->objName = "schemeTemplate";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����ʦ���˱�ģ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ʦ���˱�ģ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ʦ���˱�ģ��ҳ��
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
	 * ��ת���鿴��ʦ���˱�ģ��ҳ��
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