<?php
/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 14:18:58
 * @version 1.0
 * @description:�����豸������Ϣ���Ʋ� 
 */
class controller_stock_extra_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "stock_extra";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������豸������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������豸������Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����豸������Ϣҳ��
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
	 * ��ת���鿴�����豸������Ϣҳ��
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