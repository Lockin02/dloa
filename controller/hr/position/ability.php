<?php
/**
 * @author Administrator
 * @Date 2012��7��9�� ����һ 14:16:48
 * @version 1.0
 * @description:ְλ����Ҫ����Ʋ� 
 */
class controller_hr_position_ability extends controller_base_action {

	function __construct() {
		$this->objName = "ability";
		$this->objPath = "hr_position";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��ְλ����Ҫ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������ְλ����Ҫ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭ְλ����Ҫ��ҳ��
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
	 * ��ת���鿴ְλ����Ҫ��ҳ��
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