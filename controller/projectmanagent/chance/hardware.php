<?php
/**
 * @author Administrator
 * @Date 2013��5��29�� 11:33:37
 * @version 1.0
 * @description:�̻�Ӳ���豸����Ʋ� 
 */
class controller_projectmanagent_chance_hardware extends controller_base_action {

	function __construct() {
		$this->objName = "hardware";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���̻�Ӳ���豸���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������̻�Ӳ���豸��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�̻�Ӳ���豸��ҳ��
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
	 * ��ת���鿴�̻�Ӳ���豸��ҳ��
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