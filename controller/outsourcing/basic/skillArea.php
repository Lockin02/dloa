<?php
/**
 * @author Administrator
 * @Date 2013��10��24�� ������ 10:06:46
 * @version 1.0
 * @description:��Ӧ�̼���������Ʋ� 
 */
class controller_outsourcing_basic_skillArea extends controller_base_action {

	function __construct() {
		$this->objName = "skillArea";
		$this->objPath = "outsourcing_basic";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ӧ�̼��������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ӧ�̼�������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ӧ�̼�������ҳ��
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
	 * ��ת���鿴��Ӧ�̼�������ҳ��
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