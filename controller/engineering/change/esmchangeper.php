<?php
/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:37
 * @version 1.0
 * @description:��Ŀ�������Ԥ����Ʋ� 
 */
class controller_engineering_change_esmchangeper extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeper";
		$this->objPath = "engineering_change";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ŀ�������Ԥ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ŀ�������Ԥ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ŀ�������Ԥ��ҳ��
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
	 * ��ת���鿴��Ŀ�������Ԥ��ҳ��
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