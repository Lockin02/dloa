<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:22:22
 * @version 1.0
 * @description:Ԥ���ʼ�֪ͨ������Ʋ� 
 */
class controller_system_warningmaillogs_warningmaillogs extends controller_base_action {

	function __construct() {
		$this->objName = "warningmaillogs";
		$this->objPath = "system_warningmaillogs";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��Ԥ���ʼ�֪ͨ����б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������Ԥ���ʼ�֪ͨ���ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭Ԥ���ʼ�֪ͨ���ҳ��
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
	 * ��ת���鿴Ԥ���ʼ�֪ͨ���ҳ��
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