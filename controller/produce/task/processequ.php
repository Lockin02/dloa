<?php
/**
 * @author ACan
 * @Date 2015��4��13�� 14:25:37
 * @version 1.0
 * @description:������Ϣ-������Ʋ� 
 */
class controller_produce_task_processequ extends controller_base_action {

	function __construct() {
		$this->objName = "processequ";
		$this->objPath = "produce_task";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��������Ϣ-�����б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������Ϣ-����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������Ϣ-����ҳ��
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
	 * ��ת���鿴������Ϣ-����ҳ��
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