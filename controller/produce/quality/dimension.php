<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 9:56:38
 * @version 1.0
 * @description:������Ŀ���Ʋ� 
 */
class controller_produce_quality_dimension extends controller_base_action {

	function __construct() {
		$this->objName = "dimension";
		$this->objPath = "produce_quality";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��������Ŀ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������Ŀҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������Ŀҳ��
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
	 * ��ת���鿴������Ŀҳ��
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