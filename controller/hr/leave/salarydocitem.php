<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 16:32:54
 * @version 1.0
 * @description:���ʽ��ӵ��嵥���Ʋ� 
 */
class controller_hr_leave_salarydocitem extends controller_base_action {

	function __construct() {
		$this->objName = "salarydocitem";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����ʽ��ӵ��嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������ʽ��ӵ��嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���ʽ��ӵ��嵥ҳ��
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
	 * ��ת���鿴���ʽ��ӵ��嵥ҳ��
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