<?php
/**
 * @author Administrator
 * @Date 2013��10��9�� 9:41:37
 * @version 1.0
 * @description:������������ϸ���Ʋ� 
 */
class controller_report_report_produceinfo extends controller_base_action {

	function __construct() {
		$this->objName = "produceinfo";
		$this->objPath = "report_report";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��������������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������������ϸҳ��
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
	 * ��ת���鿴������������ϸҳ��
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