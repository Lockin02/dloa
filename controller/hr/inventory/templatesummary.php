<?php
/**
 * @author Administrator
 * @Date 2012��8��30�� 19:17:07
 * @version 1.0
 * @description:�̵��ܽ����Կ��Ʋ� 
 */
class controller_hr_inventory_templatesummary extends controller_base_action {

	function __construct() {
		$this->objName = "templatesummary";
		$this->objPath = "hr_inventory";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���̵��ܽ������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������̵��ܽ�����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�̵��ܽ�����ҳ��
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
	 * ��ת���鿴�̵��ܽ�����ҳ��
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